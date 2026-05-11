<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Activity extends Model
{
    use HasFactory;

    public const TYPES = ['call', 'email', 'meeting', 'note'];

    protected $fillable = [
        'type',
        'description',
        'customer_id',
        'lead_id',
        'user_id',
    ];

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
