<?php

namespace App\Notifications;

use App\Models\ProjectNote;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewProjectComment extends Notification implements ShouldQueue
{
    use Queueable;

    public $note;

    /**
     * Create a new notification instance.
     */
    public function __construct(ProjectNote $note)
    {
        $this->note = $note;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject(__('app.notifications.new_project_comment_subject', ['project' => $this->note->project->title]))
                    ->line(__('app.notifications.new_project_comment_body', ['user' => $this->note->user->name]))
                    ->action(__('app.notifications.view_project'), route('projects.show', $this->note->project))
                    ->line(__('app.notifications.thank_you'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'project_comment',
            'note_id' => $this->note->id,
            'project_id' => $this->note->project_id,
            'user_id' => $this->note->user_id,
            'user_name' => $this->note->user->name,
            'content' => \Str::limit($this->note->content, 50),
            'title' => __('app.notifications.new_comment_on_project', ['project' => $this->note->project->title]),
            'url' => route('projects.show', $this->note->project),
        ];
    }
}
