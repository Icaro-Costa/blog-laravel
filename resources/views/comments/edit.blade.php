@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto mt-10">
    <div class="bg-white p-8 rounded-lg shadow-md border border-gray-100">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Editar Comentário</h2>

        <form action="{{ route('comments.update', $comment->id) }}" method="POST">
            @csrf
            @method('PUT') <div class="mb-4">
                <label for="body" class="block text-gray-700 font-bold mb-2">Seu texto:</label>
                <textarea name="body" id="body" rows="4" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">{{ $comment->body }}</textarea>
            </div>

            <div class="flex items-center gap-4">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition font-bold">
                    Salvar Alterações
                </button>
                <a href="{{ route('posts.show', $comment->postId) }}" class="text-gray-500 hover:text-gray-700 hover:underline">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection