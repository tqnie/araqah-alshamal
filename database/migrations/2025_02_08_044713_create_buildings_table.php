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
        Schema::create('buildings', function (Blueprint $table) {
            $table->id();
            
            $table->unsignedInteger('building_plan_id');
            $table->string('building_number'); 
            $table->string('sale');
            $table->decimal('price', 10, 2);
            $table->string('area');
            $table->string('block_number');
            $table->string('street_view');
            $table->string('direction');
            $table->string('type');
            $table->integer('x');
            $table->integer('y');
            $table->integer('width');
            $table->integer('height');
            $table->boolean('active')->default(1);
            $table->timestamps();
        });
    } 

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buildings');
    }
};
