@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto">
        
        <div class="flex items-center gap-6 mb-6 text-sm font-medium">
            
            <a href="{{ session('back_url', route('home')) }}" class="flex items-center gap-2 text-gray-500 hover:text-blue-600 transition">
                <span>&larr;</span> Voltar
            </a>

            <span class="text-gray-300">|</span>

            <a href="{{ route('home') }}" class="flex items-center gap-2 text-gray-500 hover:text-blue-600 transition">
                <span>🏠</span> Ir para o Início
            </a>
        </div>

        <article class="bg-white rounded-lg shadow-lg overflow-hidden mb-8 border border-gray-100">
            <div class="p-8">
                <div class="flex flex-wrap gap-2 mb-4">
                    @if($post->tags)
                        @foreach($post->tags as $tag)
                            <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full font-semibold">
                                #{{ $tag }}
                            </span>
                        @endforeach
                    @endif
                </div>

                <h1 class="text-4xl font-bold text-gray-900 mb-6">{{ $post->title }}</h1>

                <div class="flex items-center gap-3 mb-8 text-gray-600 border-b pb-6">
                    <div class="w-10 h-10 rounded-full bg-gray-200 overflow-hidden">
                        @if($post->author && $post->author->image)
                            <img src="{{ $post->author->image }}" alt="Autor" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center font-bold text-gray-500">?</div>
                        @endif
                    </div>
                    <div>
                        <a href="{{ route('users.posts', $post->author->id) }}" class="font-medium text-gray-900 hover:text-blue-600 hover:underline transition cursor-pointer">
                            {{ $post->author->firstName ?? 'Desconhecido' }} {{ $post->author->lastName ?? '' }}
                        </a>
                        <p class="text-sm">Publicado em {{ $post->created_at->format('d/m/Y') }} • {{ $post->views }} visualizações</p>
                    </div>
                </div>

                <div class="prose max-w-none text-gray-800 text-lg leading-relaxed mb-8">
                    {{ $post->body }}
                </div>

                <div class="flex gap-8 pt-6 border-t">
                    
                    <form action="{{ route('posts.like', $post->id) }}" method="POST" class="like-form">
                        @csrf
                        @php $userHasLiked = in_array($post->id, session('liked_posts', [])); @endphp
                        <button type="submit" class="flex items-center gap-2 text-lg transition {{ $userHasLiked ? 'text-green-600 font-bold' : 'text-gray-500 hover:text-green-600' }}" title="Curtir">
                            <span class="group-hover:scale-125 transition-transform duration-200">👍</span> 
                            <span class="count-display">{{ $post->reactions_likes }}</span> Likes
                        </button>
                    </form>

                    <form action="{{ route('posts.dislike', $post->id) }}" method="POST" class="dislike-form">
                        @csrf
                        @php $userHasDisliked = in_array($post->id, session('disliked_posts', [])); @endphp
                        <button type="submit" class="flex items-center gap-2 text-lg transition {{ $userHasDisliked ? 'text-red-500 font-bold' : 'text-gray-500 hover:text-red-500' }}" title="Não curtir">
                            <span class="group-hover:scale-125 transition-transform duration-200">👎</span> 
                            <span class="count-display">{{ $post->reactions_dislikes }}</span> Dislikes
                        </button>
                    </form>

                </div>
            </div>
        </article>

        <div class="mb-12" id="comments-section">
            <h3 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                Comentários 
                <span class="bg-gray-200 text-gray-700 text-sm px-2 py-1 rounded-full">{{ $post->comments->count() }}</span>
            </h3>

            <div class="bg-gray-50 p-6 rounded-lg border border-gray-200 mb-8 shadow-sm">
                <form action="{{ route('comments.store', $post->id) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="body" class="block text-gray-700 font-bold mb-2">Participe da discussão:</label>
                        <textarea name="body" id="body" rows="3" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white" placeholder="Escreva o seu comentário aqui..." required></textarea>
                    </div>
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition font-bold text-sm shadow flex items-center gap-2">
                        <span>💬</span> Enviar Comentário
                    </button>
                </form>
            </div>

            <div class="space-y-4">
                @forelse($post->comments as $comment)
                    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100 group relative" id="comment-{{ $comment->id }}">
                        
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex items-center gap-2">
                                <h4 class="font-bold text-gray-900">{{ $comment->user->username ?? 'Usuário' }}</h4>
                                @if($comment->userId == 1)
                                    <span class="bg-blue-100 text-blue-800 text-xs px-2 py-0.5 rounded-full font-bold">Você</span>
                                @endif
                            </div>

                            @if($comment->userId == 1)
                                <div class="relative">
                                    <details class="group/menu">
                                        <summary class="list-none cursor-pointer text-gray-400 hover:text-gray-600 p-1 rounded hover:bg-gray-100 transition">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z"></path></svg>
                                        </summary>
                                        
                                        <div class="absolute right-0 mt-1 w-32 bg-white border border-gray-200 rounded-lg shadow-xl z-10 hidden group-open/menu:block overflow-hidden">
                                            <button onclick="toggleEdit({{ $comment->id }})" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 flex items-center gap-2 transition">
                                                ✏️ Editar
                                            </button>
                                            <form action="{{ route('comments.destroy', $comment->id) }}" method="POST" onsubmit="return confirm('Excluir comentário?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 flex items-center gap-2 transition">
                                                    🗑️ Excluir
                                                </button>
                                            </form>
                                        </div>
                                        
                                        <div class="fixed inset-0 z-0 hidden group-open/menu:block" onclick="this.parentElement.removeAttribute('open')"></div>
                                    </details>
                                </div>
                            @endif
                        </div>

                        <div class="comment-view">
                            <p class="text-gray-700 mb-3">{{ $comment->body }}</p>

                            <div class="flex items-center gap-4 border-t border-gray-50 pt-2">
                                
                                <form action="{{ route('comments.like', $comment->id) }}" method="POST" class="like-form">
                                    @csrf
                                    @php $commentLiked = in_array($comment->id, session('liked_comments', [])); @endphp
                                    <button type="submit" class="flex items-center gap-1 text-sm transition {{ $commentLiked ? 'text-green-600 font-bold' : 'text-gray-500 hover:text-green-600' }}" title="Gostei">
                                        <span>👍</span> <span class="count-display">{{ $comment->likes }}</span>
                                    </button>
                                </form>

                                <form action="{{ route('comments.dislike', $comment->id) }}" method="POST" class="dislike-form">
                                    @csrf
                                    @php $commentDisliked = in_array($comment->id, session('disliked_comments', [])); @endphp
                                    <button type="submit" class="flex items-center gap-1 text-sm transition {{ $commentDisliked ? 'text-red-500 font-bold' : 'text-gray-500 hover:text-red-500' }}" title="Não gostei">
                                        <span>👎</span> <span class="count-display">{{ $comment->dislikes }}</span>
                                    </button>
                                </form>

                            </div>
                        </div>

                        @if($comment->userId == 1)
                            <div class="comment-edit hidden mt-2">
                                <form action="{{ route('comments.update', $comment->id) }}" method="POST">
                                    @csrf @method('PUT')
                                    <textarea name="body" rows="3" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-50 mb-2" required>{{ $comment->body }}</textarea>
                                    <div class="flex items-center gap-2">
                                        <button type="submit" class="bg-blue-600 text-white px-4 py-1.5 rounded text-sm font-bold hover:bg-blue-700 transition">Salvar</button>
                                        <button type="button" onclick="toggleEdit({{ $comment->id }})" class="bg-gray-200 text-gray-700 px-4 py-1.5 rounded text-sm font-bold hover:bg-gray-300 transition">Cancelar</button>
                                    </div>
                                </form>
                            </div>
                        @endif

                    </div>
                @empty
                    <div class="text-center py-8 bg-white rounded-lg border border-gray-100">
                        <p class="text-gray-500 italic mb-2">Ainda não há comentários neste post.</p>
                        <p class="text-sm text-blue-500">Seja o primeiro a comentar!</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <script>
        function toggleEdit(commentId) {
            const container = document.getElementById('comment-' + commentId);
            const viewDiv = container.querySelector('.comment-view');
            const editDiv = container.querySelector('.comment-edit');

            if (viewDiv && editDiv) {
                viewDiv.classList.toggle('hidden');
                editDiv.classList.toggle('hidden');
            }
            
            // Fecha o menu dropdown
            const details = container.querySelector('details');
            if(details) details.removeAttribute('open');
        }
    </script>
@endsection