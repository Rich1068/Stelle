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
            $table->string('first_name');
            $table->string('last_name');
            $table->string('middle_name')->nullable();
            $table->string('email')->unique();
            $table->string('google_id')->nullable()->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->foreignId('role_id')->constrained('roles')->onDelete('cascade');;
            $table->string('profile_picture')->nullable();
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->date('birthdate')->nullable();
            $table->string('description')->nullable();
            $table->string('contact_number')->nullable();
            $table->foreignId('country_id')->nullable()->constrained('countries')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
        DB::table('users')->insert([
            'first_name' => 'Super1',
            'last_name' => 'Admin',
            'email' => 'superadmin1@gmail.com',
            'password' => Hash::make('12345678'),
            'email_verified_at' => now(),
            'birthdate' => '2003-10-13',
            'gender' => 'male',
            'role_id' => DB::table('roles')->where('role_name', 'Super Admin')->first()->id,
        ]);
        DB::table('users')->insert([
            'first_name' => 'Super2',
            'last_name' => 'Admin',
            'email' => 'superadmin2@gmail.com',
            'password' => Hash::make('12345678'),
            'email_verified_at' => now(),
            'birthdate' => '1988-02-22',
            'gender' => 'female',
            'role_id' => DB::table('roles')->where('role_name', 'Super Admin')->first()->id,
        ]);
        DB::table('users')->insert([
            'first_name' => 'Admin1',
            'last_name' => 'Admin',
            'email' => 'admin1@gmail.com',
            'password' => Hash::make('12345678'),
            'email_verified_at' => now(),
            'birthdate' => '1998-03-12',
            'gender' => 'male',
            'role_id' => DB::table('roles')->where('role_name', 'Admin')->first()->id,
        ]);
        DB::table('users')->insert([
            'first_name' => 'Admin2',
            'last_name' => 'Admin',
            'email' => 'admin2@gmail.com',
            'password' => Hash::make('12345678'),
            'email_verified_at' => now(),
            'birthdate' => '1978-05-03',
            'gender' => 'female',
            'role_id' => DB::table('roles')->where('role_name', 'Admin')->first()->id,
        ]);
        DB::table('users')->insert([
            'first_name' => 'Admin3',
            'last_name' => 'Admin',
            'email' => 'admin3@gmail.com',
            'password' => Hash::make('12345678'),
            'email_verified_at' => now(),
            'birthdate' => '1990-07-21',
            'role_id' => DB::table('roles')->where('role_name', 'Admin')->first()->id,
        ]);
        DB::table('users')->insert([
            'first_name' => 'John',
            'last_name' => 'Smith',
            'email' => 'user@gmail.com',
            'password' => Hash::make('12345678'),
            'email_verified_at' => now(),
            'birthdate' => '2001-12-12',
            'gender' => 'male',
            'role_id' => DB::table('roles')->where('role_name', 'User')->first()->id,
        ]);
        DB::table('users')->insert([
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'email' => 'user2@gmail.com',
            'password' => Hash::make('12345678'),
            'email_verified_at' => now(),
            'birthdate' => '2000-08-09',
            'gender' => 'female',
            'role_id' => DB::table('roles')->where('role_name', 'User')->first()->id,
        ]);
        DB::table('users')->insert([
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'email' => 'user3@gmail.com',
            'password' => Hash::make('12345678'),
            'email_verified_at' => now(),
            'birthdate' => '2000-08-09',
            'role_id' => DB::table('roles')->where('role_name', 'User')->first()->id,
        ]);

        Schema::create('participant_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('status');
        });
        DB::table('participant_statuses')->insert([
            ['status' => 'Accepted'],
            ['status' => 'Declined'],
            ['status' => 'Pending'],
        ]);
        Schema::create('register_admins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->onstrained('users')->onDelete('cascade');
            $table->foreignId('status_id')->constrained('participant_statuses')->onDelete('cascade'); 
            $table->timestamps();
        });

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
        Schema::dropIfExists('register_admins');
        Schema::dropIfExists('participant_statuses');
    }
};
