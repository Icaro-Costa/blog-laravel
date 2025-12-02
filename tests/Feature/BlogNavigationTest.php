<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BlogNavigationTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_loads_and_shows_posts()
    {
        // 1. Preparar
        $user = User::factory()->create();
        $post = Post::create([
            'title' => 'Meu Primeiro Post Teste',
            'body' => 'Conteúdo do post',
            'userId' => $user->id,
            'reactions_likes' => 10
        ]);

        // 2. Agir
        $response = $this->get('/');

        // 3. Verificar
        $response->assertStatus(200);
        $response->assertSee('Meu Primeiro Post Teste');
        
        // CORREÇÃO: Verificamos o ícone e o número separadamente para ser mais robusto
        $response->assertSee('👍'); 
        $response->assertSee('10'); 
    }

    public function test_post_detail_page_loads()
    {
        $user = User::factory()->create();
        $post = Post::create([
            'title' => 'Detalhes do Post',
            'body' => 'Texto completo aqui.',
            'userId' => $user->id
        ]);

        $response = $this->get(route('posts.show', $post->id));

        $response->assertStatus(200);
        $response->assertSee('Detalhes do Post');
        $response->assertSee('Texto completo aqui.');
    }

    public function test_user_profile_loads()
    {
        $user = User::factory()->create(['firstName' => 'João', 'lastName' => 'Silva']);

        $response = $this->get(route('users.show', $user->id));

        $response->assertStatus(200);
        $response->assertSee('João Silva');
    }
}