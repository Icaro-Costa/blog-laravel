<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ModelsTest extends TestCase
{
    use RefreshDatabase; // Reseta o banco a cada teste

    public function test_user_has_many_posts()
    {
        $user = User::factory()->create();
        $post = Post::create([
            'title' => 'Teste', 'body' => 'Corpo', 'userId' => $user->id
        ]);

        // Verifica se conseguimos acessar os posts através do usuário
        $this->assertTrue($user->posts->contains($post));
    }

    public function test_post_belongs_to_user()
    {
        $user = User::factory()->create();
        $post = Post::create([
            'title' => 'Teste', 'body' => 'Corpo', 'userId' => $user->id
        ]);

        // Verifica se o autor do post é o usuário correto
        $this->assertEquals($user->id, $post->author->id);
    }

    public function test_post_has_many_comments()
    {
        $user = User::factory()->create();
        $post = Post::create([
            'title' => 'Teste', 'body' => 'Corpo', 'userId' => $user->id
        ]);
        
        $comment = Comment::create([
            'body' => 'Comentário', 'postId' => $post->id, 'userId' => $user->id
        ]);

        $this->assertTrue($post->comments->contains($comment));
    }
}