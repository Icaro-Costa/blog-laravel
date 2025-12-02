<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Http\Request;

class PostController extends Controller
{
    // --- FUNÇÕES AUXILIARES ---
    private function applyFilters($query, Request $request)
    {
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('tag')) {
            $query->whereJsonContains('tags', $request->tag);
        }
        switch ($request->sort) {
            case 'likes': $query->orderBy('reactions_likes', 'desc'); break;
            case 'views': $query->orderBy('views', 'desc'); break;
            case 'oldest': $query->orderBy('created_at', 'asc'); break;
            default: $query->orderBy('created_at', 'desc'); break;
        }
        return $query;
    }

    // --- PÁGINAS PRINCIPAIS ---
    public function index(Request $request)
    {
        $query = Post::with('author');
        $this->applyFilters($query, $request);
        $posts = $query->paginate(30)->withQueryString();
        return view('posts.index', compact('posts'));
    }

    public function show($id)
    {
        $post = Post::with(['author', 'comments.user'])->findOrFail($id);

        // --- LÓGICA DE NAVEGAÇÃO INTELIGENTE ---
        $previous = url()->previous();
        $current = route('posts.show', $id);

        // Verifica se viemos da Home (exato)
        $isHome = ($previous == route('home')) || ($previous == route('home') . '/');
        
        // Verifica se viemos de uma lista de usuário (tem '/posts' mas não é '/post/')
        $isUserList = str_contains($previous, '/posts') && !str_contains($previous, '/post/');

        // REGRA DE OURO: Só atualiza a memória se viemos de uma LISTAGEM.
        // Se viemos do próprio post (comentário/edição), NÃO MEXE na sessão.
        if ($isHome || $isUserList) {
            session()->put('back_url', $previous);
        }

        // Segurança: Se a sessão estiver vazia, define Home como padrão
        if (!session()->has('back_url')) {
            session()->put('back_url', route('home'));
        }

        return view('posts.show', compact('post'));
    }

    public function postsByUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $query = $user->posts()->with('author');
        $this->applyFilters($query, $request);
        $posts = $query->paginate(30)->withQueryString();
        return view('posts.user_posts', compact('user', 'posts'));
    }

    // --- INTERAÇÕES POSTS (LIKES/DISLIKES) ---
    public function like($id)
    {
        $post = Post::findOrFail($id);
        $likedPosts = session()->get('liked_posts', []);
        $dislikedPosts = session()->get('disliked_posts', []);
        $isLiked = false;

        if (in_array($id, $dislikedPosts)) {
            $post->decrement('reactions_dislikes');
            $dislikedPosts = array_diff($dislikedPosts, [$id]);
            session()->put('disliked_posts', $dislikedPosts);
        }

        if (in_array($id, $likedPosts)) {
            $post->decrement('reactions_likes');
            $likedPosts = array_diff($likedPosts, [$id]);
            $isLiked = false;
        } else {
            $post->increment('reactions_likes');
            $likedPosts[] = $id;
            $isLiked = true;
        }
        session()->put('liked_posts', $likedPosts);

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true, 'liked' => $isLiked, 'disliked' => false,
                'likes_count' => $post->reactions_likes, 'dislikes_count' => $post->reactions_dislikes
            ]);
        }
        return back();
    }

    public function dislike($id)
    {
        $post = Post::findOrFail($id);
        $likedPosts = session()->get('liked_posts', []);
        $dislikedPosts = session()->get('disliked_posts', []);
        $isDisliked = false;

        if (in_array($id, $likedPosts)) {
            $post->decrement('reactions_likes');
            $likedPosts = array_diff($likedPosts, [$id]);
            session()->put('liked_posts', $likedPosts);
        }

        if (in_array($id, $dislikedPosts)) {
            $post->decrement('reactions_dislikes');
            $dislikedPosts = array_diff($dislikedPosts, [$id]);
            $isDisliked = false;
        } else {
            $post->increment('reactions_dislikes');
            $dislikedPosts[] = $id;
            $isDisliked = true;
        }
        session()->put('disliked_posts', $dislikedPosts);

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true, 'liked' => false, 'disliked' => $isDisliked,
                'likes_count' => $post->reactions_likes, 'dislikes_count' => $post->reactions_dislikes
            ]);
        }
        return back();
    }

    // --- INTERAÇÕES COMENTÁRIOS ---
    public function likeComment($id)
    {
        $comment = Comment::findOrFail($id);
        $liked = session()->get('liked_comments', []);
        $disliked = session()->get('disliked_comments', []);
        $isLiked = false;

        if (in_array($id, $disliked)) {
            $comment->decrement('dislikes');
            $disliked = array_diff($disliked, [$id]);
            session()->put('disliked_comments', $disliked);
        }

        if (in_array($id, $liked)) {
            $comment->decrement('likes');
            $liked = array_diff($liked, [$id]);
            $isLiked = false;
        } else {
            $comment->increment('likes');
            $liked[] = $id;
            $isLiked = true;
        }
        session()->put('liked_comments', $liked);

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true, 'liked' => $isLiked, 'disliked' => false,
                'likes_count' => $comment->likes, 'dislikes_count' => $comment->dislikes
            ]);
        }
        return back();
    }

    public function dislikeComment($id)
    {
        $comment = Comment::findOrFail($id);
        $liked = session()->get('liked_comments', []);
        $disliked = session()->get('disliked_comments', []);
        $isDisliked = false;

        if (in_array($id, $liked)) {
            $comment->decrement('likes');
            $liked = array_diff($liked, [$id]);
            session()->put('liked_comments', $liked);
        }

        if (in_array($id, $disliked)) {
            $comment->decrement('dislikes');
            $disliked = array_diff($disliked, [$id]);
            $isDisliked = false;
        } else {
            $comment->increment('dislikes');
            $disliked[] = $id;
            $isDisliked = true;
        }
        session()->put('disliked_comments', $disliked);

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true, 'liked' => false, 'disliked' => $isDisliked,
                'likes_count' => $comment->likes, 'dislikes_count' => $comment->dislikes
            ]);
        }
        return back();
    }

    // --- CRUD COMENTÁRIOS ---
    public function storeComment(Request $request, $id)
    {
        $request->validate(['body' => 'required|min:2|max:500']);
        Comment::create([
            'body' => $request->body, 'postId' => $id, 'userId' => 1, 'likes' => 0, 'dislikes' => 0
        ]);
        return redirect()->route('posts.show', $id)->withFragment('comments-section')->with('success', 'Comentário enviado!');
    }

    public function editComment($id)
    {
        $comment = Comment::findOrFail($id);
        if ($comment->userId != 1) return back()->with('error', 'Sem permissão.');
        return view('comments.edit', compact('comment'));
    }

    public function updateComment(Request $request, $id)
    {
        $request->validate(['body' => 'required|min:2|max:500']);
        $comment = Comment::findOrFail($id);
        if ($comment->userId != 1) return back();
        $comment->update(['body' => $request->body]);
        return redirect()->route('posts.show', $comment->postId)->withFragment('comments-section')->with('success', 'Atualizado!');
    }

    public function deleteComment($id)
    {
        $comment = Comment::findOrFail($id);
        if ($comment->userId != 1) return back()->with('error', 'Sem permissão.');
        $comment->delete();
        return back()->with('success', 'Removido.');
    }
}