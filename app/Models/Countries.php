<?php

namespace App\Models;

use App\Models\Hotels;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Countries extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'season'
    ];
}