<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tenant extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'full_name',
        'whatsapp_number',
        'notes',
        'status',
    ];

    public function stays(): HasMany
    {
        return $this->hasMany(Stay::class);
    }

    public function activeStay()
    {
        return $this->hasOne(Stay::class)
            ->where('status', 'ACTIVE')
            ->latest('start_at');
    }
}
