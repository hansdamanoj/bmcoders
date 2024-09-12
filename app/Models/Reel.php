<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reel extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'video_path',
        'thumbnail_path',
    ];

    // Reel belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
