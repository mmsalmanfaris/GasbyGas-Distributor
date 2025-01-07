<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consumer extends Model
{
    use HasFactory;
    protected $fillable = [
        'fname',
        'nic',
        'contact',
        'email',
        'district',
        'outlet_id',
        'category',
        'rnumber',
        'password',
    ];

    public function crequests()
    {
        return $this->hasMany(Crequest::class);
    }

    public function cancallations()
    {
        return $this->hasMany(Cancallation::class);
    }
}
