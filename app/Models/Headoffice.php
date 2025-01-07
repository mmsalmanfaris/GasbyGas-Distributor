<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Headoffice extends Model
{
    use HasFactory;
    protected $fillable = [
        'rdate',
        'stock',
        'cdate',
    ];
}
