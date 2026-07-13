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
        Schema::table('daily_quests', function (Blueprint $table) {
            // Add unique constraint on date to prevent duplicate daily quests for same day
            $table->unique('date', 'unique_daily_quest_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('daily_quests', function (Blueprint $table) {
            $table->dropUnique('unique_daily_quest_date');
        });
    }
};
