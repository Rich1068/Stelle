<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->integer('order')->default(0)->after('type_id'); // Add the order column with a default value
        });
    }
    
    public function down()
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropColumn('order'); // Remove the order column if the migration is rolled back
        });
    }
};
