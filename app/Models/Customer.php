<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'company',
        'address',
        'status',
        'assigned_user_id',
    ];

    /* ─── Accessors ────────────────────────────────── */

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /* ─── Relationships ────────────────────────────── */

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_user_id');
    }

    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class);
    }

    public function followUps(): HasMany
    {
        return $this->hasMany(FollowUp::class);
    }
}
