<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, delete duplicate daily_quests entries, keeping only the latest one per date
        // This ensures we can add the unique constraint
        DB::statement('
            DELETE FROM daily_quests
            WHERE id NOT IN (
                SELECT id FROM (
                    SELECT MAX(id) as id
                    FROM daily_quests
                    GROUP BY DATE(date)
                ) as subquery
            )
        ');

        // Clean up orphaned user_quests that point to deleted daily_quests
        DB::statement('
            DELETE FROM user_quests
            WHERE daily_quest_id NOT IN (
                SELECT id FROM daily_quests
            )
        ');

        // Now add the unique constraint
        Schema::table('daily_quests', function (Blueprint $table) {
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
