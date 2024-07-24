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
        Schema::create('evaluation_forms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            $table->timestamps();
        });
        Schema::create('question_types', function (Blueprint $table) {
            $table->id();
            $table->string('type');
        });
        DB::table('question_types')->insert([
            ['type' => 'Essay'],
            ['type' => 'Radio'],
        ]);
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('form_id')->constrained('evaluation_forms')->onDelete('cascade');
            $table->text('question');
            $table->foreignId('type_id')->constrained('question_types')->onDelete('cascade'); 
            $table->timestamps();
        });
        Schema::create('answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')->constrained('questions')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->text('answer');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluation_forms');
        Schema::dropIfExists('questions');
        Schema::dropIfExists('answers');
    }
};
