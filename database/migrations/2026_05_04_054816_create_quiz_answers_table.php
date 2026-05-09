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
     // داخل ملف الـ Migration لجدول quiz_answers
 
    Schema::create('quiz_answers', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        // أضيفي هذا السطر ليربط مع الـ UUID الخاص بالـ Session
        $table->foreignUuid('quiz_session_id')->constrained('quiz_sessions')->onDelete('cascade');
        $table->foreignId('question_id')->constrained()->onDelete('cascade');
        $table->text('answer');
        $table->timestamps();
    });
}


    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_answers');
    }
};
