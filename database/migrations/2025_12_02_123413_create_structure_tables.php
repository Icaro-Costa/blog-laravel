<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Tabela de Usuários (Baseado em users.json)
        Schema::create('users', function (Blueprint $table) {
            $table->id(); 
            $table->string('firstName');
            $table->string('lastName');
            $table->string('email')->unique();
            $table->string('phone');
            $table->string('username')->unique(); 
            $table->string('password'); 
            $table->string('image')->nullable();
            $table->date('birthDate')->nullable();
            
            // Campos de endereço
            $table->string('address_address')->nullable();
            $table->string('address_city')->nullable();
            $table->string('address_state')->nullable();
            $table->string('address_postalCode')->nullable();

            $table->timestamps();
        });

        // 2. Tabela de Posts (Baseado em posts.json)
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('body');
            $table->json('tags')->nullable(); 
            
            // Reações
            $table->integer('reactions_likes')->default(0);
            $table->integer('reactions_dislikes')->default(0);
            
            $table->integer('views')->default(0);
            
            // Chave estrangeira para o usuário
            $table->foreignId('userId')->constrained('users')->onDelete('cascade');
            
            $table->timestamps();
        });

        // 3. Tabela de Comentários (Baseado em comments.json)
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->text('body');
            $table->integer('likes')->default(0);
            
            // Relações
            $table->foreignId('postId')->constrained('posts')->onDelete('cascade');
            $table->foreignId('userId')->constrained('users')->onDelete('cascade');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
        Schema::dropIfExists('posts');
        Schema::dropIfExists('users');
    }
};