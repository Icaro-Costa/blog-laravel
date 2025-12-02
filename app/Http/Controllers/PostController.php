<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class PostController extends Controller
{
    // Função auxiliar para aplicar filtros (para não repetir código)
    private function applyFilters($query, Request $request)
    {
        // 1. Filtro por Busca (Título)
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // 2. Filtro por Tag (JSON)
        if ($request->filled('tag')) {
            $query->whereJsonContains('tags', $request->tag);
        }

        // 3. Ordenação
        switch ($request->sort) {
            case 'likes':
                $query->orderBy('reactions_likes', 'desc');
                break;
            case 'views':
                $query->orderBy('views', 'desc');
                break;
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            default: // 'latest' é o padrão
                $query->orderBy('created_at', 'desc');
                break;
        }

        return $query;
    }

    public function index(Request $request)
    {
        $query = Post::with('author');

        // Aplica os filtros definidos acima
        $this->applyFilters($query, $request);

        // Mantém os parâmetros da URL na paginação (ex: página 2 da busca "amor")
        $posts = $query->paginate(30)->withQueryString();

        return view('posts.index', compact('posts'));
    }

    public function show($id)
    {
        $post = Post::with(['author', 'comments.user'])->findOrFail($id);
        return view('posts.show', compact('post'));
    }

    public function postsByUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Começa a query apenas nos posts deste usuário
        $query = $user->posts()->with('author');

        // Aplica os mesmos filtros
        $this->applyFilters($query, $request);

        $posts = $query->paginate(30)->withQueryString();

        return view('posts.user_posts', compact('user', 'posts'));
    }
}