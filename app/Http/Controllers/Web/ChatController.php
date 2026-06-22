<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use App\Services\ChatService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ChatController extends Controller
{
    public function __construct(protected ChatService $chat) {}

    /** Chat page shell. */
    public function index(Request $request)
    {
        $user = $request->user();
        $open = $request->integer('conversation') ?: null;

        $users = User::where('organization_id', $user->organization_id)
            ->where('id', '!=', $user->id)
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('chat.index', [
            'openConversationId' => $open,
            'orgUsers' => $users,
            'canAnnounce' => $user->isAdmin() || $user->isManager(),
        ]);
    }

    /** Open (creating if needed) a project's chat channel. */
    public function projectChat(Request $request, \App\Models\Project $project)
    {
        abort_unless($project->canBeViewedBy($request->user()), 403);
        $conversation = $this->chat->projectConversation($project);

        return redirect()->route('chat.index', ['conversation' => $conversation->id]);
    }

    /** List the user's conversations + unread counts. */
    public function conversations(Request $request): JsonResponse
    {
        return response()->json(['data' => $this->chat->conversationsFor($request->user())]);
    }

    /** Messages of a conversation (optionally only after an id). Marks the thread read. */
    public function messages(Request $request, Conversation $conversation): JsonResponse
    {
        $this->authorizeAccess($conversation, $request->user());

        $after = $request->integer('after') ?: null;
        $messages = $this->chat->messages($conversation, $after);
        $this->chat->markRead($conversation, $request->user());

        return response()->json([
            'data' => $messages,
            'can_post' => $conversation->canBePostedBy($request->user()),
            'title' => $conversation->displayTitle($request->user()),
            'type' => $conversation->type,
        ]);
    }

    /** Send a message (body and/or files). */
    public function send(Request $request, Conversation $conversation): JsonResponse
    {
        $user = $request->user();
        $this->authorizeAccess($conversation, $user);

        if (! $conversation->canBePostedBy($user)) {
            return $this->ajaxError(__('app.chat.cannot_post'), 403);
        }

        $request->validate([
            'body' => 'nullable|string|max:5000',
            'attachments' => 'nullable|array|max:5',
            'attachments.*' => 'file|max:10240',
        ]);

        $files = $request->file('attachments', []);

        try {
            $this->chat->validateFiles($files);
            $message = $this->chat->postMessage($conversation, $user, $request->input('body'), $files);
        } catch (\InvalidArgumentException $e) {
            return $this->ajaxError($e->getMessage());
        }

        return response()->json(['data' => $this->chat->presentMessage($message->load('user:id,name'))]);
    }

    /** Open (or create) a direct conversation with another user. */
    public function startDirect(Request $request): JsonResponse
    {
        $request->validate(['user_id' => 'required|exists:users,id']);
        $me = $request->user();
        $other = User::findOrFail($request->integer('user_id'));

        if ($other->id === $me->id || $other->organization_id !== $me->organization_id) {
            return $this->ajaxError(__('app.chat.invalid_recipient'));
        }

        $conversation = $this->chat->directConversation($me, $other);

        return response()->json(['conversation_id' => $conversation->id]);
    }

    /** Create an announcement (admin/manager only) and post its first message. */
    public function storeAnnouncement(Request $request): JsonResponse
    {
        $user = $request->user();
        if (! $user->isAdmin() && ! $user->isManager()) {
            return $this->ajaxError(__('app.chat.cannot_post'), 403);
        }

        $request->validate([
            'title' => 'required|string|max:160',
            'body' => 'required|string|max:5000',
        ]);

        $conversation = $this->chat->createAnnouncement($user, $request->input('title'));
        $this->chat->postMessage($conversation, $user, $request->input('body'));

        return $this->ajaxSuccess(__('app.chat.announcement_posted'), null, [
            'conversation_id' => $conversation->id,
        ]);
    }

    /** The 3-second poller: new messages for the open thread + global unread + recent notifications. */
    public function poll(Request $request): JsonResponse
    {
        $user = $request->user();
        $payload = [
            'chat_unread' => $this->chat->totalUnread($user),
            'notifications_unread' => \App\Models\Notification::where('user_id', $user->id)->whereNull('read_at')->count(),
            'messages' => [],
        ];

        if ($request->filled('conversation')) {
            $conversation = Conversation::find($request->integer('conversation'));
            if ($conversation && $conversation->canBeAccessedBy($user)) {
                $payload['messages'] = $this->chat->messages($conversation, $request->integer('after') ?: null);
                $this->chat->markRead($conversation, $user);
            }
        }

        return response()->json($payload);
    }

    /** Authorized attachment serving (inline preview or forced download). */
    public function attachment(Request $request, Message $message, int $index): StreamedResponse
    {
        $user = $request->user();
        abort_unless($message->conversation->canBeAccessedBy($user), 403);

        $attachments = $message->attachments ?? [];
        abort_unless(isset($attachments[$index]), 404);
        $att = $attachments[$index];

        $disk = Storage::disk(ChatService::DISK);
        abort_unless($disk->exists($att['path']), 404);

        $disposition = $request->boolean('download') ? 'attachment' : 'inline';
        $mime = $att['mime'] ?? 'application/octet-stream';
        // Never serve untrusted files as active content.
        $safeMime = str_starts_with($mime, 'image/') ? $mime : ($disposition === 'inline' && $mime === 'application/pdf' ? $mime : 'application/octet-stream');

        return $disk->download($att['path'], $att['name'] ?? 'file', [
            'Content-Type' => $safeMime,
            'Content-Disposition' => $disposition.'; filename="'.addslashes($att['name'] ?? 'file').'"',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    private function authorizeAccess(Conversation $conversation, User $user): void
    {
        abort_unless($conversation->canBeAccessedBy($user), 403);
    }
}
