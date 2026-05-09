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
         Schema::create('quiz_sessions', function (Blueprint $table) {
    $table->uuid('id')->primary();
    $table->foreignId('user_id')->nullable()->constrained();
    $table->integer('score')->default(0); // <--- أضيفي هذا السطر
    $table->string('predicted_performance')->nullable();
    $table->text('model_analysis')->nullable();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_sessions');
    }
};
