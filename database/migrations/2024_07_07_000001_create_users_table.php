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
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('role_name');
        });

        DB::table('roles')->insert([
            ['role_name' => 'Super Admin'],
            ['role_name' => 'Admin'],
            ['role_name' => 'User'],
        ]);

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->foreignId('role_id')->constrained('roles');
            $table->string('profile_picture')->nullable();
            $table->enum('gender', ['male', 'female']);
            $table->string('description')->nullable();
            $table->string('contact_number')->nullable();
            $table->foreignId('country_id')->nullable()->constrained('country');
            $table->timestamps();
        });
        DB::table('users')->insert([
            'name' => 'Super Admin',
            'email' => 'rs106848@gmail.com',
            'password' => Hash::make('12345678'),
            'role_id' => DB::table('roles')->where('role_name', 'Super Admin')->first()->id,
        ]);
        DB::table('users')->insert([
            'name' => 'Admin',
            'email' => 'rich106848@gmail.com',
            'password' => Hash::make('12345678'),
            'role_id' => DB::table('roles')->where('role_name', 'Admin')->first()->id,
        ]);
        DB::table('users')->insert([
            'name' => 'User',
            'email' => 'sy.richarddarwin@auf.edu.ph',
            'password' => Hash::make('12345678'),
            'role_id' => DB::table('roles')->where('role_name', 'User')->first()->id,
        ]);

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
        Schema::dropIfExists('roles');
    }
};
