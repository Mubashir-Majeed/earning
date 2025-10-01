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
        Schema::create('user_earnings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('video_task_id')->nullable()->constrained()->onDelete('cascade');
            $table->integer('points_earned');
            $table->decimal('dollar_value', 10, 2); // 750 points = $80, so this calculates the dollar value
            $table->enum('type', ['video_watch', 'bonus', 'referral', 'adjustment']);
            $table->text('description')->nullable();
            $table->date('earned_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_earnings');
    }
};
