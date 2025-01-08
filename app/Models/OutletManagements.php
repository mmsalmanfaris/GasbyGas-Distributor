<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutletManagements extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'user_id',
        'district',
        'town',
        'email',
        'stock',
        'contact',
        'password',
    ];

    public function consumers()
    {
        return $this->hasMany(Consumer::class);
    }

    public function crequests()
    {
        return $this->hasMany(Crequest::class);
    }

    public function orequest()
    {
        return $this->hasMany(DispatchSchedules::class);
    }
}
