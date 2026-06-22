<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'conversation_id', 'user_id', 'body', 'attachments',
    ];

    protected $casts = [
        'attachments' => 'array',
    ];

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function hasAttachments(): bool
    {
        return ! empty($this->attachments);
    }

    /** Normalised attachment list for the frontend (no filesystem paths leaked). */
    public function attachmentsForApi(): array
    {
        return collect($this->attachments ?? [])->values()->map(function ($a, $index) {
            $mime = $a['mime'] ?? 'application/octet-stream';

            return [
                'index' => $index,
                'name' => $a['name'] ?? 'file',
                'mime' => $mime,
                'size' => $a['size'] ?? 0,
                'is_image' => str_starts_with($mime, 'image/'),
                'url' => route('chat.attachment', ['message' => $this->id, 'index' => $index]),
                'download_url' => route('chat.attachment', ['message' => $this->id, 'index' => $index, 'download' => 1]),
            ];
        })->all();
    }
}
