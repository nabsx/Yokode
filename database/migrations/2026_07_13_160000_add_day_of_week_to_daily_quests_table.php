<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('daily_quests', function (Blueprint $table) {
            $table->tinyInteger('day_of_week')->nullable()->comment('0=Monday, 1=Tuesday, 2=Wednesday, 3=Thursday, 4=Friday, 5=Saturday, 6=Sunday');
        });
    }

    public function down(): void
    {
        Schema::table('daily_quests', function (Blueprint $table) {
            $table->dropColumn('day_of_week');
        });
    }
};
