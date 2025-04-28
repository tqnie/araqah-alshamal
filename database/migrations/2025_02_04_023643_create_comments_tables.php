<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{ 
    public function up(): void
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('post_id'); 
            $table->unsignedBigInteger('user_id'); 
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->text('content');
            $table->boolean('approved')->nullable();
            $table->timestamps();

            $table->index(['approved', 'post_id']);
            $table->index('parent_id');

            $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });
    }
 
    public function down(): void
    {
        Schema::dropIfExists('comments'); 
    }
};