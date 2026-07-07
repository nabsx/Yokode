<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('achievements', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description');
            $table->string('icon')->default('🏆');
            $table->integer('required_exp')->nullable();
            $table->integer('required_streak')->nullable();
            $table->integer('required_lessons')->nullable();
            $table->integer('required_quizzes')->nullable();
            $table->integer('reward_exp')->default(0);
            $table->integer('reward_coins')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('achievements');
    }
};