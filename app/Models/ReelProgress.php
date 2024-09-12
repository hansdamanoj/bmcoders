<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReelProgress extends Model
{
    use HasFactory;

    protected $table = 'reel_progress';

    protected $fillable = [
        'user_id',
        'reel_id',
        'last_second',
    ];
}
