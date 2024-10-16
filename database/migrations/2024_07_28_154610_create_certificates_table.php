<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;  // Add DB facade
use Illuminate\Support\Facades\File;  // Add File facade for file handling


return new class extends Migration
{
    /**
     * Run the migrations.
     */


    public function up(): void
    {
        $jsonFilePath1 = storage_path('json_files/template1.json');
        $jsonContent1 = file_get_contents($jsonFilePath1);
        $jsonFilePath2 = storage_path('json_files/template2.json');
        $jsonContent2 = file_get_contents($jsonFilePath2);
        $jsonFilePath3 = storage_path('json_files/template3.json');
        $jsonContent3 = file_get_contents($jsonFilePath3);

        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->longText('design');
            $table->string('cert_path');
            $table->foreignId('status_id')->constrained('statuses')->onDelete('cascade');
            $table->timestamps();
        });
        Schema::create('cert_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('template_name');
            $table->longText('design')->nullable();
            $table->string('path');
            $table->foreignId('status_id')->constrained('statuses')->onDelete('cascade');
            $table->timestamps();
        });
        DB::table('cert_templates')->insert([
            'template_name' => 'sample_1',
            'design' => $jsonContent1,
            'path' => 'storage/images/certificates/cert_templates/template1.png',
            'status_id' => '1',
            'created_at'=> now(),
        ]);
        DB::table('cert_templates')->insert([
            'template_name' => 'sample_2',
            'design' => $jsonContent2,
            'path' => 'storage/images/certificates/cert_templates/template2.png',
            'status_id' => '1',
            'created_at'=> now(),
        ]);
        DB::table('cert_templates')->insert([
            'template_name' => 'sample_3',
            'design' => $jsonContent3,
            'path' => 'storage/images/certificates/cert_templates/template3.png',
            'status_id' => '1',
            'created_at'=> now(),
        ]);
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
