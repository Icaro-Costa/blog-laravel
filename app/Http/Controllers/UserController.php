<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // Mostrar o perfil de um usuário
    public function show($id)
    {
        $user = User::findOrFail($id);
        
        return view('users.show', compact('user'));
    }
}