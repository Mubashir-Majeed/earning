<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('videos', function (Blueprint $table) {
            $table->decimal('dollar_value_starter', 10, 2)->nullable()->after('dollar_value');
            $table->decimal('dollar_value_growth', 10, 2)->nullable()->after('dollar_value_starter');
            $table->decimal('dollar_value_pro', 10, 2)->nullable()->after('dollar_value_growth');
        });

        if (Schema::hasColumn('videos', 'dollar_value')) {
            DB::table('videos')->update([
                'dollar_value_starter' => DB::raw('dollar_value'),
                'dollar_value_growth' => DB::raw('dollar_value'),
                'dollar_value_pro' => DB::raw('dollar_value'),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('videos', function (Blueprint $table) {
            $table->dropColumn([
                'dollar_value_starter',
                'dollar_value_growth',
                'dollar_value_pro',
            ]);
        });
    }
};

