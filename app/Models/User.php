<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Os atributos que podem ser preenchidos em massa.
     */
    protected $fillable = [
        'firstName',
        'lastName',
        'email',
        'phone',
        'username',
        'password',
        'image',
        'birthDate',
        'address_address',
        'address_city',
        'address_state',
        'address_postalCode',
    ];

    /**
     * Os atributos que devem ficar escondidos (ex: password).
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Os atributos que devem ser convertidos.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // --- RELAÇÕES ---

    // Um usuário pode ter muitos posts
    public function posts()
    {
        // 'userId' é o nome da coluna na tabela de posts que liga a este usuário
        return $this->hasMany(Post::class, 'userId');
    }

    // Um usuário pode ter muitos comentários
    public function comments()
    {
        return $this->hasMany(Comment::class, 'userId');
    }
}