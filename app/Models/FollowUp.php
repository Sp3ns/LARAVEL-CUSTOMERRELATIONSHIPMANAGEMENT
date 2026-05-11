<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FollowUp extends Model
{
    use HasFactory;

    public const STATUSES = ['Pending', 'In Progress', 'Completed'];

    protected $fillable = [
        'title',
        'description',
        'due_date',
        'status',
        'customer_id',
        'lead_id',
        'user_id',
    ];

    protected function casts(): array
    {
        return [
            'due_date' => 'date',
        ];
    }

    /* ─── Helpers ──────────────────────────────────── */

    public function isCompleted(): bool
    {
        return $this->status === 'Completed';
    }

    public function isOverdue(): bool
    {
        return !$this->isCompleted() && $this->due_date->isPast();
    }

    /* ─── Relationships ────────────────────────────── */

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
