<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Films extends Model
{
    use HasFactory;

    protected $table = 'films';

    protected $fillable = [
        'year',
        'title',
        'studios',
        'producers',
        'winner',
    ];
}
