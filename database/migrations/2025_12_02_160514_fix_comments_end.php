<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = OFF');
            
            Schema::create('comments_fixed', function (Blueprint $table) {
                $table->id();
                $table->string('text');
                $table->foreignId('article_id')->constrained()->onDelete('cascade');
                $table->foreignId('user_id')->default(1)->constrained()->onDelete('cascade');
                $table->boolean('accept')->default(false);
                $table->timestamps();
            });
            
            DB::insert('INSERT INTO comments_fixed 
                (id, text, article_id, user_id, accept, created_at, updated_at)
                SELECT 
                    id, 
                    text,
                    article_id,
                    created_at as user_id,
                    updated_at as accept,
                    user_id as created_at,
                    accept as updated_at
                FROM comments');
            
            Schema::dropIfExists('comments');
            
            Schema::rename('comments_fixed', 'comments');
            
            DB::statement('PRAGMA foreign_keys = ON');
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = OFF');
            
            Schema::create('comments_original', function (Blueprint $table) {
                $table->id();
                $table->string('text');
                $table->foreignId('article_id')->constrained()->onDelete('cascade');
                $table->foreignId('user_id')->default(1)->constrained()->onDelete('cascade');
                $table->boolean('accept')->default(false);
                $table->timestamps();
            });
            
            DB::insert('INSERT INTO comments_original 
                (id, text, article_id, user_id, accept, created_at, updated_at)
                SELECT 
                    id, 
                    text,
                    article_id,
                    updated_at as user_id,
                    created_at as accept,
                    accept as created_at,
                    user_id as updated_at
                FROM comments');
            
            Schema::dropIfExists('comments');
            Schema::rename('comments_original', 'comments');
            
            DB::statement('PRAGMA foreign_keys = ON');
        }
    }
};