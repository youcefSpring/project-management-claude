<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Organization extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'website',
        'phone',
        'email',
        'address',
        'logo',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function owner()
    {
        return $this->hasOne(User::class)->where('role', User::ROLE_ADMIN);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getTotalUsersAttribute()
    {
        return $this->users()->count();
    }

    public function getTotalProjectsAttribute()
    {
        return $this->projects()->count();
    }

    public function getActiveProjectsAttribute()
    {
        return $this->projects()->where('status', '!=', 'completed')->count();
    }
}
