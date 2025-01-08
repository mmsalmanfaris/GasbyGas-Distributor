<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DispatchSchedules extends Model
{
    use HasFactory;
    protected $fillable = [
        'outlet_id',
        'quantity',
        'request',
        'edelivery',
        'sdelivery',
        'status'
    ];
}
