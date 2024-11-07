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
        // Step 1: Rename `date` to `start_date` and add `end_date` as nullable
        Schema::table('events', function (Blueprint $table) {
            $table->renameColumn('date', 'start_date');
            $table->date('end_date')->nullable()->after('start_date');
        });

        // Step 2: Set `end_date` to `start_date` for existing rows
        DB::table('events')->whereNull('end_date')->update([
            'end_date' => DB::raw('start_date')
        ]);

        // Step 3: Make `end_date` required by removing the `nullable` attribute
        Schema::table('events', function (Blueprint $table) {
            $table->date('end_date')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('end_date');
            $table->renameColumn('start_date', 'date');
        });
    }
};
