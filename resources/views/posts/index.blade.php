@extends('layouts.app')

@section('content')
    <div class="flex justify-between items-end mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Últimas Publicações</h1>
        <span class="text-gray-500 text-sm">Página {{ $posts->currentPage() }}</span>
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
                        <div class="flex items-center gap-2">
                             <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center overflow-hidden">
                                @if($post->author && $post->author->image)
                                    <img src="{{ $post->author->image }}" alt="User" class="w-full h-full object-cover">
                                @else
                                    <span>{{ substr($post->author->firstName ?? 'U', 0, 1) }}</span>
                                @endif
                             </div>
                             <span class="font-medium truncate max-w-[100px]">{{ $post->author->firstName ?? 'Anônimo' }}</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="flex items-center gap-1 text-green-600">
                                👍 {{ $post->reactions_likes }}
                            </span>
                            <span class="flex items-center gap-1 text-blue-600">
                                💬 {{ $post->comments->count() }}
                            </span>
                        </div>
                    </div>
                </div>
            </article>
        @empty
            <div class="col-span-3 text-center py-12 text-gray-500">
                Nenhum post encontrado com estes filtros.
            </div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $posts->links() }}
    </div>
@endsection