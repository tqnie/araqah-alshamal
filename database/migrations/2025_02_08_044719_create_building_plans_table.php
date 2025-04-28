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
        Schema::create('building_plans', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('excerpt');
            $table->string('slug')->unique(); 
            $table->unsignedInteger('product_id');
            $table->text('image');
            $table->string('plan_image')->nullable()->default(null);
            $table->longText('body'); 
            $table->boolean('active')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('building_plans');
    }
};
// The command `php artisan make:migration create_projects_table` generates a new migration file.
// No additional code is needed in the current file for this command to work.