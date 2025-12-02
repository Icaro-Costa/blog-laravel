<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    // Campos que podem ser preenchidos
    protected $fillable = [
        'title',
        'body',
        'tags',
        'reactions_likes',
        'reactions_dislikes',
        'views',
        'userId'
    ];

    // Conversão automática de dados
    protected $casts = [
        'tags' => 'array', // Transforma o JSON do banco em Array do PHP automaticamente
    ];

    // --- RELAÇÕES ---

    // Um post pertence a um Autor (User)
    public function author()
    {
        return $this->belongsTo(User::class, 'userId');
    }

    // Um post tem muitos comentários
    public function comments()
    {
        return $this->hasMany(Comment::class, 'postId');
    }
}