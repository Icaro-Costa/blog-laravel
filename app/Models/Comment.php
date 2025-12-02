<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'body',
        'likes',
        'postId',
        'userId'
    ];

    // --- RELAÇÕES ---

    // O comentário pertence a um Post
    public function post()
    {
        return $this->belongsTo(Post::class, 'postId');
    }

    // O comentário foi escrito por um User
    public function user()
    {
        return $this->belongsTo(User::class, 'userId');
    }
}