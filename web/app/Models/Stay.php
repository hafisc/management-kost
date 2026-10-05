<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stay extends Model
{
    protected $guarded = [];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
