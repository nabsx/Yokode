<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_answers', function (Blueprint $table) {
            $table->boolean('is_viewed_reason')->default(false)->after('is_correct');
            $table->integer('attempt_number')->default(1)->after('is_viewed_reason');
            $table->timestamp('locked_until')->nullable()->after('attempt_number');
        });
    }

    public function down(): void
    {
        Schema::table('user_answers', function (Blueprint $table) {
            $table->dropColumn(['is_viewed_reason', 'attempt_number', 'locked_until']);
        });
    }
};
