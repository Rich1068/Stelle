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
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->text('design');
            $table->string('cert_path');
            $table->foreignId('status_id')->constrained('statuses')->onDelete('cascade');
            $table->timestamps();
        });
        Schema::create('cert_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->string('template_name');
            $table->text('design')->nullable();
            $table->string('path');
            $table->foreignId('status_id')->constrained('statuses')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('event_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            $table->foreignId('template_id')->constrained('cert_templates')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('cert_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cert_id')->constrained('certificates')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('cert_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cert_users');
        Schema::dropIfExists('event_templates');
        Schema::dropIfExists('cert_templates');
        Schema::dropIfExists('certificates');

    }
};
