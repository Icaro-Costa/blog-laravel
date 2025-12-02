<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CommentReactionTest extends TestCase
{
    use RefreshDatabase;

    public function test_comment_like_increments_count()
    {
        $user = User::factory()->create(['id' => 1]);
        $post = Post::create(['title' => '...', 'body' => '...', 'userId' => $user->id]);
        
        $comment = Comment::create([
            'body' => 'Bom post!',
            'postId' => $post->id,
            'userId' => $user->id,
            'likes' => 0,
            'dislikes' => 0
        ]);

        // Dar Like no comentário
        $response = $this->postJson(route('comments.like', $comment->id));

        $response->assertStatus(200)
                 ->assertJson(['success' => true, 'liked' => true]);

        $this->assertDatabaseHas('comments', [
            'id' => $comment->id,
            'likes' => 1
        ]);
    }

    public function test_comment_like_removes_previous_dislike()
    {
        $user = User::factory()->create(['id' => 1]);
        $post = Post::create(['title' => '...', 'body' => '...', 'userId' => $user->id]);
        
        $comment = Comment::create([
            'body' => 'Comentário polémico',
            'postId' => $post->id,
            'userId' => $user->id,
            'likes' => 0,
            'dislikes' => 1 // Começa já com 1 dislike (simulado)
        ]);

        // Simular que já temos esse dislike na sessão
        $sessionData = ['disliked_comments' => [$comment->id]];

        // Ação: Dar LIKE
        $response = $this->withSession($sessionData)
                         ->postJson(route('comments.like', $comment->id));

        $comment->refresh();

        // Verificação:
        // Likes deve subir para 1
        $this->assertEquals(1, $comment->likes);
        // Dislikes deve descer para 0 (a lógica de anulação)
        $this->assertEquals(0, $comment->dislikes);
        
        // Sessão deve ter atualizado
        $response->assertSessionHas('liked_comments', [$comment->id]);
        $response->assertSessionMissing('disliked_comments', [$comment->id]);
    }
}