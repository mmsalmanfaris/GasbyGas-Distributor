<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Outlet extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
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
        return $this->hasMany(Orequest::class);
    }
}
