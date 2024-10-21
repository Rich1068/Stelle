<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->id()->primary();
            $table->char('countrycode', 3);
            $table->string('countryname', 200);
            $table->char('code', 2)->nullable();
        });
        Schema::create('regions', function (Blueprint $table) {
            $table->id(); 
            $table->string('psgcCode')->nullable();
            $table->text('regDesc')->nullable();
            $table->string('regCode')->nullable();
            $table->timestamps(); 
        });

        Schema::create('provinces', function (Blueprint $table) {
            $table->id(); 
            $table->string('psgcCode')->nullable(); 
            $table->text('provDesc')->nullable(); 
            $table->string('regCode')->nullable(); 
            $table->string('provCode')->nullable(); 
            $table->timestamps(); 
        });

    }
    public function down(): void
    {
        Schema::dropIfExists('countries');
        Schema::dropIfExists('regions');
        Schema::dropIfExists('provinces');
    }
};    
