<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Crequest extends Model
{
    use HasFactory;
    protected $fillable = [
        'consumer_id',
        'outlet_id',
        'quantity',
        'panel',
        'payment',
        'edelivery',
        'sdelivery',
        'cylinder',
        'deliverystatus',
        'requested_at'
    ];
}
