<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('daily_quest_templates', function (Blueprint $table) {
            $table->id();
            $table->integer('day_of_week'); // 0 = Sunday, 1 = Monday, ..., 6 = Saturday
            $table->string('title');
            $table->text('description');
            $table->enum('type', ['complete_lesson', 'answer_quiz', 'gain_exp', 'login', 'perfect_quiz']);
            $table->integer('target');
            $table->integer('reward_exp');
            $table->integer('reward_coins')->default(0);
            $table->timestamps();
            
            // Unique constraint to ensure one template per day of week per type
            $table->unique(['day_of_week']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_quest_templates');
    }
};
