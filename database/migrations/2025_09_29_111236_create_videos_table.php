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
        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('youtube_url');
            $table->string('youtube_id');
            $table->string('category'); // Heroism, Nation Builders, Histories, Mysteries, etc.
            $table->string('thumbnail_url')->nullable();
            $table->integer('duration')->nullable(); // in seconds
            $table->integer('points_value')->default(10);
            $table->boolean('is_active')->default(true);
            $table->date('assigned_date')->nullable();
            $table->integer('max_watches_per_day')->default(100);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('videos');
    }
};
