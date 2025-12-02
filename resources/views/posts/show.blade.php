@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto">
        
        <a href="{{ route('home') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 mb-6 transition">
            &larr; Voltar para a lista
        </a>

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

                <div class="flex gap-6 pt-6 border-t">
                    <div class="flex items-center gap-2 text-green-600 font-bold">
                        👍 {{ $post->reactions_likes }} Likes
                    </div>
                    <div class="flex items-center gap-2 text-red-500 font-bold">
                        👎 {{ $post->reactions_dislikes }} Dislikes
                    </div>
                </div>
            </div>
        </article>

        <div class="mb-12">
            <h3 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                Comentários 
                <span class="bg-gray-200 text-gray-700 text-sm px-2 py-1 rounded-full">{{ $post->comments->count() }}</span>
            </h3>

            <div class="space-y-4">
                @forelse($post->comments as $comment)
                    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
                        <div class="flex justify-between items-start mb-2">
                            <h4 class="font-bold text-gray-900">{{ $comment->user->username ?? 'Usuário' }}</h4>
                            <span class="text-xs text-gray-500">{{ $comment->likes }} likes</span>
                        </div>
                        <p class="text-gray-700">{{ $comment->body }}</p>
                    </div>
                @empty
                    <p class="text-gray-500 italic">Ainda não há comentários neste post.</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection