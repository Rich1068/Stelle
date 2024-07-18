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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->date('date');
            $table->enum('mode', ['onsite', 'virtual']);
            $table->string('address');
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('capacity');
            $table->string('event_banner')->nullable();
            $table->timestamps();
        });
        Schema::create('user_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('event_id')->constrained('event')->onDelete('cascade');
            $table->timestamps();
        });
        Schema::create('participant_status', function (Blueprint $table) {
            $table->id();
            $table->string('status');
        });
        DB::table('participant_status')->insert([
            ['status' => 'Accepted'],
            ['status' => 'Declined'],
            ['status' => 'Pending'],
        ]);
        Schema::create('event_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('event')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('id')->constrained('participant_status')->onDelete('cascade'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event');
        Schema::dropIfExists('user_events');
    }
};
