<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CommentSecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_cannot_update_others_comment()
    {
        // 1. Cenário: Eu sou o User 1, mas o comentário é do User 99
        $me = User::factory()->create(['id' => 1]);
        $otherUser = User::factory()->create(['id' => 99]);
        
        $post = Post::create(['title' => 'Post', 'body' => '...', 'userId' => $me->id]);
        
        $comment = Comment::create([
            'body' => 'Texto Original',
            'postId' => $post->id,
            'userId' => 99 // Pertence ao outro
        ]);

        // 2. Ação: Tentar enviar um pedido PUT para alterar o texto
        // Como não temos login, o sistema assume que somos o ID 1 (hardcoded no controller)
        $response = $this->put(route('comments.update', $comment->id), [
            'body' => 'Texto Hackeado'
        ]);

        // 3. Verificação:
        // O controller deve apenas fazer "return back()" sem salvar
        // Verificamos se o texto no banco CONTINUA o original
        $this->assertDatabaseHas('comments', [
            'id' => $comment->id,
            'body' => 'Texto Original'
        ]);
        
        // Garante que o texto novo NÃO entrou
        $this->assertDatabaseMissing('comments', [
            'body' => 'Texto Hackeado'
        ]);
    }

    public function test_comment_body_is_required_on_update()
    {
        $user = User::factory()->create(['id' => 1]);
        $post = Post::create(['title' => 'Post', 'body' => '...', 'userId' => $user->id]);
        
        $comment = Comment::create([
            'body' => 'Texto válido',
            'postId' => $post->id,
            'userId' => 1
        ]);

        // Tentar atualizar com texto vazio
        $response = $this->put(route('comments.update', $comment->id), [
            'body' => ''
        ]);

        // Deve dar erro de validação
        $response->assertSessionHasErrors('body');
    }
}