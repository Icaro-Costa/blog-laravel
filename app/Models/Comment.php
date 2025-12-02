<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // 1. Importar a classe SoftDeletes

class Comment extends Model
{
    // 2. Adicionar a trait SoftDeletes aqui
    use HasFactory, SoftDeletes; 

    protected $fillable = [
        'body',
        'likes',
        'dislikes',
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