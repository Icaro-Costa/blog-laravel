<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. IMPORTAR USUÁRIOS
        $this->command->info('A importar usuários da DummyJSON...');
        
        // Vamos buscar 100 usuários (limit=0 traz todos, mas vamos garantir um bom número)
        $usersResponse = Http::get('https://dummyjson.com/users?limit=100');
        $users = $usersResponse->json()['users'];

        foreach ($users as $user) {
            // Verificar se o usuário já existe para evitar duplicados
            if (DB::table('users')->where('id', $user['id'])->exists()) {
                continue;
            }

            DB::table('users')->insert([
                'id' => $user['id'],
                'firstName' => $user['firstName'],
                'lastName' => $user['lastName'],
                'email' => $user['email'],
                'phone' => $user['phone'],
                'username' => $user['username'],
                'password' => Hash::make($user['password']), // Criptografar a senha
                'image' => $user['image'],
                'birthDate' => $user['birthDate'],
                // Mapear o endereço (que vem como objeto na API) para as nossas colunas
                'address_address' => $user['address']['address'] ?? null,
                'address_city' => $user['address']['city'] ?? null,
                'address_state' => $user['address']['state'] ?? null,
                'address_postalCode' => $user['address']['postalCode'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        $this->command->info('Usuários importados!');


        // 2. IMPORTAR POSTS
        $this->command->info('A importar posts...');
        
        $postsResponse = Http::get('https://dummyjson.com/posts?limit=150'); // Buscar 150 posts
        $posts = $postsResponse->json()['posts'];

        foreach ($posts as $post) {
            // Só importamos o post se o usuário dono dele existir no nosso banco
            if (!DB::table('users')->where('id', $post['userId'])->exists()) {
                continue;
            }

            DB::table('posts')->insert([
                'id' => $post['id'],
                'title' => $post['title'],
                'body' => $post['body'],
                'tags' => json_encode($post['tags']), // Converter array de tags para JSON string
                'reactions_likes' => $post['reactions']['likes'] ?? 0,
                'reactions_dislikes' => $post['reactions']['dislikes'] ?? 0,
                'views' => $post['views'],
                'userId' => $post['userId'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        $this->command->info('Posts importados!');


        // 3. IMPORTAR COMENTÁRIOS
        $this->command->info('A importar comentários...');
        
        $commentsResponse = Http::get('https://dummyjson.com/comments?limit=300'); // Buscar bastantes comentários
        $comments = $commentsResponse->json()['comments'];

        foreach ($comments as $comment) {
            // O user no comentário vem dentro de um objeto "user": { "id": 1, ... }
            $userId = $comment['user']['id'];

            // Só inserimos se o Post e o User existirem
            $postExists = DB::table('posts')->where('id', $comment['postId'])->exists();
            $userExists = DB::table('users')->where('id', $userId)->exists();

            if ($postExists && $userExists) {
                DB::table('comments')->insert([
                    'id' => $comment['id'],
                    'body' => $comment['body'],
                    'likes' => $comment['likes'],
                    'postId' => $comment['postId'],
                    'userId' => $userId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
        $this->command->info('Tudo pronto! Banco de dados preenchido.');
    }
}