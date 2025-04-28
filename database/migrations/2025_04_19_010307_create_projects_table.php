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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique(); 
            $table->string('name'); 
            $table->text('body')->nullable()->default(null);
            $table->string('image')->nullable()->default(null);
            $table->string('location')->nullable()->default(null);
            $table->string('type')->nullable()->default(null);
            $table->boolean('active')->default(1);
            $table->timestamps();
      
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
