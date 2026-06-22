<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Conversation extends Model
{
    use HasFactory;

    public const TYPE_PROJECT = 'project';
    public const TYPE_DIRECT = 'direct';
    public const TYPE_ANNOUNCEMENT = 'announcement';

    protected $fillable = [
        'type', 'organization_id', 'project_id', 'title', 'created_by', 'last_message_at',
    ];

    protected $casts = [
        'last_message_at' => 'datetime',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function participants(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'conversation_user')
            ->withPivot('last_read_at')
            ->withTimestamps();
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function latestMessage(): HasMany
    {
        return $this->messages()->latest('id')->limit(1);
    }

    /** Can the user read this conversation? */
    public function canBeAccessedBy(User $user): bool
    {
        return match ($this->type) {
            self::TYPE_PROJECT => $this->project && $this->project->canBeViewedBy($user),
            self::TYPE_ANNOUNCEMENT => $this->organization_id === $user->organization_id,
            default => $this->participants()->whereKey($user->id)->exists(),
        };
    }

    /** Can the user post into this conversation? */
    public function canBePostedBy(User $user): bool
    {
        if ($this->type === self::TYPE_ANNOUNCEMENT) {
            return $user->isAdmin() || $user->isManager();
        }

        return $this->canBeAccessedBy($user);
    }

    /** Display label from the viewer's perspective. */
    public function displayTitle(User $viewer): string
    {
        return match ($this->type) {
            self::TYPE_PROJECT => $this->project->title ?? __('app.chat.project_chat'),
            self::TYPE_ANNOUNCEMENT => $this->title ?: __('app.chat.announcement'),
            default => optional($this->participants->firstWhere('id', '!=', $viewer->id))->name
                ?? __('app.chat.direct_message'),
        };
    }
}
