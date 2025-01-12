<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Post extends Model
{
    use HasFactory;


    protected $fillable = [
        'user_id',
        'image',
        'title',
        'body',
    ];


    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'post_id');
    }

    public function userLikes()
    {
        return $this->hasMany(Like::class)->where('user_id', Auth::id());
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
