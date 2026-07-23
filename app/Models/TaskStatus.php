<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class TaskStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'slug',
        'name',
        'color',
        'sort_order',
        'is_default',
        'is_final',
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'is_final' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Default statuses seeded for a new organization.
     */
    public static function defaults(): array
    {
        return [
            ['slug' => 'pending', 'name' => 'Pending', 'color' => '#ffc107', 'sort_order' => 1, 'is_default' => true, 'is_final' => false],
            ['slug' => 'in_progress', 'name' => 'In Progress', 'color' => '#0d6efd', 'sort_order' => 2, 'is_default' => false, 'is_final' => false],
            ['slug' => 'completed', 'name' => 'Completed', 'color' => '#198754', 'sort_order' => 3, 'is_default' => false, 'is_final' => true],
            ['slug' => 'cancelled', 'name' => 'Cancelled', 'color' => '#6c757d', 'sort_order' => 4, 'is_default' => false, 'is_final' => true],
        ];
    }

    /**
     * Create the default status set for an organization (idempotent).
     */
    public static function seedDefaultsFor(int $organizationId): void
    {
        foreach (static::defaults() as $default) {
            static::firstOrCreate(
                ['organization_id' => $organizationId, 'slug' => $default['slug']],
                $default
            );
        }
    }

    /**
     * All statuses of an organization, ordered.
     */
    public static function forOrganization(?int $organizationId)
    {
        if (! $organizationId) {
            return static::query()->whereRaw('1 = 0')->get();
        }

        return static::where('organization_id', $organizationId)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();
    }

    /**
     * Default status slug of an organization.
     */
    public static function defaultSlugFor(?int $organizationId): string
    {
        $statuses = static::forOrganization($organizationId);

        return optional($statuses->firstWhere('is_default', true) ?? $statuses->first())->slug ?? 'pending';
    }

    /**
     * Build a unique slug within the organization.
     */
    public static function makeSlug(string $name, int $organizationId, ?int $ignoreId = null): string
    {
        $base = Str::slug($name, '_') ?: 'status';
        $slug = $base;
        $i = 2;

        while (static::where('organization_id', $organizationId)
            ->where('slug', $slug)
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->exists()) {
            $slug = $base.'_'.$i++;
        }

        return $slug;
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Tasks currently using this status inside the organization.
     */
    public function tasksQuery()
    {
        return Task::where('status', $this->slug)
            ->whereHas('project', fn ($q) => $q->where('organization_id', $this->organization_id));
    }

    public function tasksCount(): int
    {
        return $this->tasksQuery()->count();
    }

    /**
     * A status can be deleted only when unused and not the last one.
     */
    public function canBeDeleted(): bool
    {
        if ($this->tasksCount() > 0) {
            return false;
        }

        return static::where('organization_id', $this->organization_id)->count() > 1;
    }

    public function scopeForOrg($query, ?int $organizationId)
    {
        return $query->where('organization_id', $organizationId);
    }
}
