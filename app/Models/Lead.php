<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lead extends Model
{
    use HasFactory;

    public const STATUSES = [
        'New', 'Contacted', 'Qualified',
        'Proposal Sent', 'Negotiation', 'Won', 'Lost',
    ];

    public const PRIORITIES = ['Low', 'Medium', 'High'];

    public const SOURCES = [
        'Website', 'Referral', 'Social Media',
        'Email Campaign', 'Cold Call', 'Advertisement', 'Other',
    ];

    protected $fillable = [
        'name',
        'email',
        'phone',
        'source',
        'status',
        'priority',
        'expected_value',
        'notes',
        'assigned_user_id',
    ];

    protected function casts(): array
    {
        return [
            'expected_value' => 'decimal:2',
        ];
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
