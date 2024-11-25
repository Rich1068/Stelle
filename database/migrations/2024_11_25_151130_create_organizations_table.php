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
        Schema::create('organizations', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('name'); // Organization name
            $table->text('description')->nullable(); // Description of the organization
            $table->string('icon')->nullable(); // Path to the organization logo
            $table->string('contact_email')->nullable(); // Organization email
            $table->string('contact_phone')->nullable(); // Organization phone number
            $table->foreignId('owner')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps(); // Created and updated timestamps
        });

        Schema::create('organization_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('organization_id')->constrained('organizations')->onDelete('cascade');
            $table->foreignId('status_id')->constrained('participant_statuses')->onDelete('cascade'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organizations');
    }
};
