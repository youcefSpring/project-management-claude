<?php

namespace App\Notifications;

use App\Models\TaskNote;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewTaskComment extends Notification implements ShouldQueue
{
    use Queueable;

    public $note;

    /**
     * Create a new notification instance.
     */
    public function __construct(TaskNote $note)
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
                    ->subject(__('app.notifications.new_task_comment_subject', ['task' => $this->note->task->title]))
                    ->line(__('app.notifications.new_task_comment_body', ['user' => $this->note->user->name]))
                    ->action(__('app.notifications.view_task'), route('tasks.show', $this->note->task))
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
            'type' => 'task_comment',
            'note_id' => $this->note->id,
            'task_id' => $this->note->task_id,
            'project_id' => $this->note->task->project_id,
            'user_id' => $this->note->user_id,
            'user_name' => $this->note->user->name,
            'content' => \Str::limit($this->note->content, 50),
            'title' => __('app.notifications.new_comment_on_task', ['task' => $this->note->task->title]),
            'url' => route('tasks.show', $this->note->task),
        ];
    }
}
