<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectNote extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'user_id',
        'content',
        'attachments',
        'is_internal',
        'type',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'attachments' => 'array',
            'metadata' => 'array',
            'is_internal' => 'boolean',
        ];
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getFormattedCreatedAtAttribute(): string
    {
        return $this->created_at->format('d/m/Y H:i');
    }

    public function canBeEditedBy(User $user): bool
    {
        return $this->user_id === $user->id && $this->created_at->diffInHours(now()) <= 24;
    }

    public function canBeDeletedBy(User $user): bool
    {
        if ($user->isAdmin()) {
            return true;
        }
        return $this->user_id === $user->id && $this->created_at->diffInHours(now()) <= 24;
    }

    public function hasAttachments(): bool
    {
        return !empty($this->attachments) && is_array($this->attachments);
    }

    public function getImageAttachmentsAttribute(): array
    {
        if (!$this->hasAttachments()) {
            return [];
        }

        return array_filter($this->attachments, function ($attachment) {
            return isset($attachment['mime_type']) && str_starts_with($attachment['mime_type'], 'image/');
        });
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($note) {
            // Send notifications to project members
            $project = $note->project;
            $members = $project->members()->where('users.id', '!=', $note->user_id)->get();
            
            // Add manager if not in members list and not the note creator
            if ($project->manager_id && $project->manager_id !== $note->user_id && !$members->contains('id', $project->manager_id)) {
                $members->push($project->manager);
            }

            foreach ($members as $member) {
                $member->notify(new \App\Notifications\NewProjectComment($note));
            }
        });
    }
}
