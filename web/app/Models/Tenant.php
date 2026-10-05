<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    protected $guarded = [];

    public function stays()
    {
        return $this->hasMany(Stay::class);
    }
}
