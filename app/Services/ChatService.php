<?php

namespace App\Services;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\Notification;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ChatService
{
    /** Private disk used for chat attachments (NOT publicly accessible). */
    public const DISK = 'local';

    /** Allowed upload mime types. */
    public const ALLOWED_MIMES = [
        'image/jpeg', 'image/png', 'image/gif', 'image/webp',
        'application/pdf', 'text/plain', 'text/csv',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'application/zip', 'application/x-zip-compressed',
    ];

    public const MAX_BYTES = 10 * 1024 * 1024; // 10MB

    /** Get (or create) the chat channel for a project. */
    public function projectConversation(Project $project): Conversation
    {
        return Conversation::firstOrCreate(
            ['type' => Conversation::TYPE_PROJECT, 'project_id' => $project->id],
            ['organization_id' => $project->organization_id, 'created_by' => $project->manager_id]
        );
    }

    /** Get (or create) the 1-to-1 conversation between two users. */
    public function directConversation(User $a, User $b): Conversation
    {
        $existing = Conversation::where('type', Conversation::TYPE_DIRECT)
            ->whereHas('participants', fn ($q) => $q->whereKey($a->id))
            ->whereHas('participants', fn ($q) => $q->whereKey($b->id))
            ->first();

        if ($existing) {
            return $existing;
        }

        return DB::transaction(function () use ($a, $b) {
            $conversation = Conversation::create([
                'type' => Conversation::TYPE_DIRECT,
                'organization_id' => $a->organization_id,
                'created_by' => $a->id,
            ]);
            $conversation->participants()->attach([$a->id, $b->id]);

            return $conversation;
        });
    }

    /** Create an organization-wide announcement channel. */
    public function createAnnouncement(User $author, string $title): Conversation
    {
        return Conversation::create([
            'type' => Conversation::TYPE_ANNOUNCEMENT,
            'organization_id' => $author->organization_id,
            'title' => $title,
            'created_by' => $author->id,
        ]);
    }

    /**
     * Post a message. $files are already-validated UploadedFile instances.
     *
     * @throws \InvalidArgumentException on rejected file
     */
    public function postMessage(Conversation $conversation, User $user, ?string $body, array $files = []): Message
    {
        $attachments = [];
        foreach ($files as $file) {
            $attachments[] = $this->storeAttachment($conversation, $file);
        }

        if (($body === null || trim($body) === '') && empty($attachments)) {
            throw new \InvalidArgumentException(__('app.chat.message_required'));
        }

        $message = $conversation->messages()->create([
            'user_id' => $user->id,
            'body' => $body ? trim($body) : null,
            'attachments' => $attachments ?: null,
        ]);

        $conversation->forceFill(['last_message_at' => $message->created_at])->save();

        // Sender has read their own message.
        $this->markRead($conversation, $user);

        $this->notifyParticipants($conversation, $message, $user);

        return $message;
    }

    /** Securely store one attachment on the private disk; returns metadata only. */
    protected function storeAttachment(Conversation $conversation, UploadedFile $file): array
    {
        $mime = $file->getMimeType();
        if (! in_array($mime, self::ALLOWED_MIMES, true)) {
            throw new \InvalidArgumentException(__('app.chat.file_type_not_allowed'));
        }
        if ($file->getSize() > self::MAX_BYTES) {
            throw new \InvalidArgumentException(__('app.chat.file_too_large'));
        }

        $ext = $file->extension() ?: $file->getClientOriginalExtension();
        $storedName = Str::uuid().($ext ? '.'.$ext : '');
        $path = $file->storeAs("chat/{$conversation->id}", $storedName, self::DISK);

        return [
            'name' => mb_substr($file->getClientOriginalName(), 0, 180),
            'path' => $path,
            'mime' => $mime,
            'size' => $file->getSize(),
        ];
    }

    /** Validate request files before they reach postMessage. */
    public function validateFiles(array $files): void
    {
        foreach ($files as $file) {
            if (! $file instanceof UploadedFile || ! $file->isValid()) {
                throw new \InvalidArgumentException(__('app.chat.invalid_file'));
            }
            if (! in_array($file->getMimeType(), self::ALLOWED_MIMES, true)) {
                throw new \InvalidArgumentException(__('app.chat.file_type_not_allowed'));
            }
            if ($file->getSize() > self::MAX_BYTES) {
                throw new \InvalidArgumentException(__('app.chat.file_too_large'));
            }
        }
    }

    public function markRead(Conversation $conversation, User $user): void
    {
        // Ensure a pivot row exists (announcements add members lazily on read).
        $conversation->participants()->syncWithoutDetaching([
            $user->id => ['last_read_at' => now()],
        ]);
    }

    /** Messages for a conversation, optionally only those after an id. */
    public function messages(Conversation $conversation, ?int $afterId = null, int $limit = 50): array
    {
        $query = $conversation->messages()->with('user:id,name')->orderBy('id');
        if ($afterId) {
            $query->where('id', '>', $afterId);
        } else {
            // Latest N, returned ascending.
            $ids = $conversation->messages()->orderByDesc('id')->limit($limit)->pluck('id');
            $query->whereIn('id', $ids);
        }

        return $query->get()->map(fn (Message $m) => $this->presentMessage($m))->all();
    }

    public function presentMessage(Message $m): array
    {
        return [
            'id' => $m->id,
            'user_id' => (int) $m->user_id,
            'user_name' => $m->user->name ?? '—',
            'body' => $m->body,
            'attachments' => $m->attachmentsForApi(),
            'created_at' => optional($m->created_at)->toIso8601String(),
            'time' => optional($m->created_at)->format('M d, H:i'),
        ];
    }

    /** Conversations visible to the user, with unread counts. */
    public function conversationsFor(User $user): array
    {
        // Direct + project channels the user is a participant of, plus org announcements.
        $member = Conversation::whereHas('participants', fn ($q) => $q->whereKey($user->id))
            ->with(['participants:id,name', 'project:id,title'])
            ->get();

        $announcements = Conversation::where('type', Conversation::TYPE_ANNOUNCEMENT)
            ->where('organization_id', $user->organization_id)
            ->get();

        return $member->merge($announcements)
            ->unique('id')
            ->filter(fn (Conversation $c) => $c->canBeAccessedBy($user))
            ->sortByDesc(fn (Conversation $c) => optional($c->last_message_at)->timestamp ?? 0)
            ->map(fn (Conversation $c) => [
                'id' => $c->id,
                'type' => $c->type,
                'title' => $c->displayTitle($user),
                'last_message_at' => optional($c->last_message_at)->diffForHumans(),
                'unread' => $this->unreadCount($c, $user),
            ])->values()->all();
    }

    public function unreadCount(Conversation $conversation, User $user): int
    {
        $pivot = $conversation->participants()->whereKey($user->id)->first();
        $lastRead = $pivot?->pivot?->last_read_at;

        $q = $conversation->messages()->where('user_id', '!=', $user->id);
        if ($lastRead) {
            $q->where('created_at', '>', $lastRead);
        }

        return $q->count();
    }

    public function totalUnread(User $user): int
    {
        return collect($this->conversationsFor($user))->sum('unread');
    }

    protected function notifyParticipants(Conversation $conversation, Message $message, User $sender): void
    {
        $recipientIds = match ($conversation->type) {
            Conversation::TYPE_ANNOUNCEMENT => User::where('organization_id', $conversation->organization_id)
                ->where('id', '!=', $sender->id)->pluck('id'),
            default => $conversation->participants()->where('users.id', '!=', $sender->id)->pluck('users.id'),
        };

        $preview = $message->body
            ? Str::limit($message->body, 80)
            : __('app.chat.sent_attachment');

        foreach ($recipientIds as $uid) {
            Notification::create([
                'user_id' => $uid,
                'type' => 'message',
                'title' => $conversation->displayTitle(User::find($uid) ?? $sender),
                'message' => $sender->name.': '.$preview,
                'data' => ['conversation_id' => $conversation->id, 'message_id' => $message->id],
            ]);
        }
    }
}
