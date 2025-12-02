<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CommentValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_comment_requires_body()
    {
        $user = User::factory()->create(['id' => 1]);
        $post = Post::create(['title' => 'Teste', 'body' => '...', 'userId' => $user->id]);

        // Tentar enviar vazio
        $response = $this->post(route('comments.store', $post->id), [
            'body' => '' 
        ]);

        // Deve dar erro de validação na chave 'body'
        $response->assertSessionHasErrors('body');
    }

    public function test_comment_cannot_be_too_short()
    {
        $user = User::factory()->create(['id' => 1]);
        $post = Post::create(['title' => 'Teste', 'body' => '...', 'userId' => $user->id]);

        // Enviar só "A" (definimos min:2 ou min:3 no controller)
        $response = $this->post(route('comments.store', $post->id), [
            'body' => 'A' 
        ]);

        $response->assertSessionHasErrors('body');
    }
}