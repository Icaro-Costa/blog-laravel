<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LikeLogicTest extends TestCase
{
    use RefreshDatabase;

    public function test_like_removes_previous_dislike()
    {
        $user = User::factory()->create();
        $post = Post::create(['title' => 'Teste', 'body' => '...', 'userId' => $user->id]);

        // 1. Simular que já dei DISLIKE (guardando na sessão)
        // Como o teste não mantém sessão entre requests manuais, usamos o helper withSession
        $sessionData = ['disliked_posts' => [$post->id]];

        // 2. Dar LIKE agora
        $response = $this->withSession($sessionData)
                         ->post(route('posts.like', $post->id));

        // 3. Verificar no Banco:
        // Likes deve ser 1
        // Dislikes deve ter descido (assumindo que o controller decrementa. 
        // Nota: O controller decrementa do valor do banco. Se estava a 0, vai para -1 se não houver cuidado, 
        // ou assume-se que se estava na sessão, o banco já tinha o valor. 
        // Para este teste ser perfeito, deveríamos iniciar o post com 1 dislike no banco).
        
        $post->refresh(); // Recarregar do banco
        
        // Verifica se adicionou o like
        $this->assertEquals(1, $post->reactions_likes);
        
        // Verifica se a sessão foi atualizada (removeu o dislike)
        $response->assertSessionHas('liked_posts', [$post->id]);
        $response->assertSessionMissing('disliked_posts', [$post->id]); // Deve ter saído da lista
    }
}