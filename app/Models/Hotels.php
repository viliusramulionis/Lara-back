<?php

namespace App\Models;

use App\Models\Orders;
use App\Models\Countries;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Hotels extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'price', 'photo', 'travel_duration'
    ];
}