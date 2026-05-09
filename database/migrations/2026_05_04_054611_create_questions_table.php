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
        Schema::create('questions', function (Blueprint $table) {
    $table->id();
    $table->text('question_text');
    $table->string('input_type'); // number or select
    $table->json('options')->nullable(); // هنخزن الـ Array اللي في الـ JSON هنا
    $table->integer('batch_number');
    $table->float('min')->nullable();
    $table->float('max')->nullable();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
