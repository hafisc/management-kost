<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'paid_at' => 'datetime',
        'due_date' => 'date',
        'period_start' => 'date',
        'period_end' => 'date',
    ];

    public function stay()
    {
        return $this->belongsTo(Stay::class);
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }
}
