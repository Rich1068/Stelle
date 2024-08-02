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
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            $table->text('design')->nullable();
            $table->string('cert_path')->nullable();
            $table->timestamps();
        });
        Schema::create('cert_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('path');
            $table->timestamps();
        });
        DB::table('cert_templates')->insert([
            'user_id' => '1',
            'path' => 'storage/images/certificates/cert_templates/template1.jpg'
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cert_templates');
        Schema::dropIfExists('certificates');
    }
};
