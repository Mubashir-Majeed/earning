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
        // Add dollar_value to videos table
        Schema::table('videos', function (Blueprint $table) {
            $table->decimal('dollar_value', 10, 2)->default(0.10)->after('points_value');
        });

        // Convert existing points_value to dollar_value (750 points = $80, so 1 point = $0.1067)
        DB::statement('UPDATE videos SET dollar_value = ROUND(points_value * 0.1067, 2)');

        // Remove points_value column
        Schema::table('videos', function (Blueprint $table) {
            $table->dropColumn('points_value');
        });

        // Remove points_earned from video_tasks
        Schema::table('video_tasks', function (Blueprint $table) {
            $table->dropColumn('points_earned');
        });

        // Remove points_earned from user_earnings
        Schema::table('user_earnings', function (Blueprint $table) {
            $table->dropColumn('points_earned');
        });

        // Remove points from users
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('points');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Add points back to users
        Schema::table('users', function (Blueprint $table) {
            $table->integer('points')->default(0)->after('balance');
        });

        // Add points_earned back to user_earnings
        Schema::table('user_earnings', function (Blueprint $table) {
            $table->integer('points_earned')->default(0)->after('video_task_id');
        });

        // Add points_earned back to video_tasks
        Schema::table('video_tasks', function (Blueprint $table) {
            $table->integer('points_earned')->default(0)->after('completed_at');
        });

        // Add points_value back to videos
        Schema::table('videos', function (Blueprint $table) {
            $table->integer('points_value')->default(10)->after('duration');
        });

        // Convert dollar_value back to points_value
        DB::statement('UPDATE videos SET points_value = ROUND(dollar_value / 0.1067)');

        // Remove dollar_value
        Schema::table('videos', function (Blueprint $table) {
            $table->dropColumn('dollar_value');
        });
    }
};

