<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;

class InteractionTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_comment()
    {
        // Precisamos criar o User ID 1 porque o Controller usa ele hardcoded
        $user = User::factory()->create(['id' => 1]);
        $post = Post::create([
            'title' => 'Post para Comentar', 'body' => '...', 'userId' => $user->id
        ]);

        $response = $this->post(route('comments.store', $post->id), [
            'body' => 'Este é um comentário de teste.'
        ]);

        // Verifica redirecionamento e se gravou no banco
        $response->assertRedirect();
        $this->assertDatabaseHas('comments', [
            'body' => 'Este é um comentário de teste.',
            'postId' => $post->id,
            'userId' => 1 // Verifica se atribuiu ao admin
        ]);
    }

    public function test_can_delete_own_comment()
    {
        $user = User::factory()->create(['id' => 1]); // Nós somos o ID 1
        $post = Post::create(['title' => '...', 'body' => '...', 'userId' => $user->id]);
        
        $comment = Comment::create([
            'body' => 'Vou apagar isto',
            'postId' => $post->id,
            'userId' => 1 
        ]);

        // Tentar apagar
        $response = $this->delete(route('comments.destroy', $comment->id));

        $response->assertRedirect();
        // Verifica Soft Delete (a linha ainda existe, mas deleted_at não é null)
        $this->assertSoftDeleted('comments', ['id' => $comment->id]);
    }

    public function test_cannot_delete_others_comment()
    {
        $me = User::factory()->create(['id' => 1]);
        $otherUser = User::factory()->create(['id' => 99]);
        
        $post = Post::create(['title' => '...', 'body' => '...', 'userId' => $me->id]);
        
        // Comentário feito por OUTRA pessoa
        $comment = Comment::create([
            'body' => 'Não toque aqui',
            'postId' => $post->id,
            'userId' => 99 
        ]);

        // Tentar apagar (o Controller verifica se userId == 1)
        // O nosso código atual permite que o ID 1 apague se ele for o dono.
        // Espera, a lógica do controller diz: if ($comment->userId != 1) return back()->with('error'...)
        // Então se o comentário é do ID 99, eu (ID 1) não posso apagar?
        // Sim, o código diz: só posso apagar se O COMENTÁRIO for do ID 1.
        
        $response = $this->delete(route('comments.destroy', $comment->id));
        
        // Deve falhar (não apagar)
        $this->assertDatabaseHas('comments', ['id' => $comment->id, 'deleted_at' => null]);
    }

    public function test_post_like_increment()
    {
        $user = User::factory()->create();
        $post = Post::create(['title' => 'Like Me', 'body' => '...', 'userId' => $user->id, 'reactions_likes' => 0]);

        // Dar Like via AJAX
        $response = $this->postJson(route('posts.like', $post->id));

        $response->assertStatus(200)
                 ->assertJson(['success' => true, 'liked' => true]);
        
        $this->assertDatabaseHas('posts', ['id' => $post->id, 'reactions_likes' => 1]);
    }
}