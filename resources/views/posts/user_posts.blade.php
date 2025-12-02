@extends('layouts.app')

@section('content')
    <div class="mb-8 border-b pb-4">
        <a href="{{ route('home') }}" class="text-blue-600 hover:underline mb-4 inline-block">&larr; Voltar ao Início</a>

        <div class="flex items-center justify-between flex-wrap gap-4 mb-2">
            <h1 class="text-3xl font-bold text-gray-800">
                Publicações de <span class="text-blue-600">{{ $user->firstName }} {{ $user->lastName }}</span>
            </h1>
            <a href="{{ route('users.show', $user->id) }}" class="text-sm bg-gray-100 text-gray-700 px-4 py-2 rounded hover:bg-gray-200 transition border shadow-sm">
                Ver Perfil Completo
            </a>
        </div>
        <p class="text-gray-600 mt-2">Total de {{ $posts->total() }} posts encontrados.</p>
    </div>

    @include('posts._filters')

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($posts as $post)
            <article class="bg-white rounded-lg shadow-md hover:shadow-xl transition duration-300 flex flex-col overflow-hidden border border-gray-100">
                <div class="p-6 flex-1 flex flex-col">
                    
                    <div class="flex flex-wrap gap-2 mb-3">
                        @if($post->tags)
                            @foreach($post->tags as $tag)
                                <a href="{{ request()->fullUrlWithQuery(['tag' => $tag]) }}" class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full font-semibold hover:bg-blue-200 transition">
                                    #{{ $tag }}
                                </a>
                            @endforeach
                        @endif
                    </div>

                    <h2 class="text-xl font-bold mb-2 text-gray-900 leading-tight">
                        <a href="{{ route('posts.show', $post->id) }}" class="hover:text-blue-600 hover:underline">
                            {{ $post->title }}
                        </a>
                    </h2>

                    <p class="text-gray-600 mb-4 text-sm line-clamp-3">
                        {{ Str::limit($post->body, 120) }}
                    </p>

                    <div class="mt-auto border-t pt-4 flex items-center justify-between text-sm text-gray-500">
                        <span>{{ $post->created_at->format('d/m/Y') }}</span>
                        <div class="flex items-center gap-3">
                             <span class="flex items-center gap-1 text-green-600">
                                👍 {{ $post->reactions_likes }}
                            </span>
                        </div>
                    </div>
                </div>
            </article>
        @empty
            <div class="col-span-3 text-center py-12 text-gray-500">
                Nenhum post encontrado para este usuário com estes filtros.
            </div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $posts->links() }}
    </div>
@endsection