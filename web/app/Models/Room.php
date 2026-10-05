<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $guarded = [];

    public function stays()
    {
        return $this->hasMany(Stay::class);
    }

    public function activeStay()
    {
        return $this->hasOne(Stay::class)->whereIn('status', ['Aktif', 'Akan Checkout'])->latestOfMany();
    }
}
