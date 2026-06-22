<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

/**
 * Soft deletes that also record WHO deleted the record (deleted_by),
 * so each user can find and restore the data they removed.
 */
trait SoftDeletesWithUser
{
    use SoftDeletes;

    public static function bootSoftDeletesWithUser(): void
    {
        static::deleting(function ($model) {
            // Only stamp on a soft delete, not a force delete.
            if (method_exists($model, 'isForceDeleting') && $model->isForceDeleting()) {
                return;
            }
            if (Auth::check()) {
                $model->deleted_by = Auth::id();
                $model->saveQuietly();
            }
        });

        static::restoring(function ($model) {
            $model->deleted_by = null;
        });
    }

    public function deleter()
    {
        return $this->belongsTo(\App\Models\User::class, 'deleted_by');
    }
}
