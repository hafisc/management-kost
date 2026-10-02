<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Stay extends Model
{
    protected $fillable = [
        'tenant_id',
        'room_id',
        'start_at',
        'end_at',
        'duration_type',
        'duration_value',
        'occupancy_type',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'start_at' => 'date',
            'end_at' => 'date',
            'duration_value' => 'integer',
        ];
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function scopeActive(Builder $query): void
    {
        $query->where('status', 'ACTIVE');
    }

    public function scopeExpiring(Builder $query, int $days = 7): void
    {
        $query->where('status', 'ACTIVE')
            ->whereBetween('end_at', [now(), now()->addDays($days)]);
    }

    public function scopeExpired(Builder $query): void
    {
        $query->where('status', 'ACTIVE')
            ->where('end_at', '<', now());
    }
}
