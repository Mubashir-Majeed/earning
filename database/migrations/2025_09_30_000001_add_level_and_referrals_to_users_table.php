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
        Schema::table('users', function (Blueprint $table) {
            $table->enum('level', ['level_1', 'level_2', 'level_3'])->default('level_1')->after('is_active');
            $table->unsignedBigInteger('referrer_id')->nullable()->after('level');
            $table->decimal('initial_deposit_amount', 10, 2)->nullable()->after('referrer_id');
            $table->integer('referrals_count')->default(0)->after('initial_deposit_amount');
            $table->integer('monthly_withdrawals_count')->default(0)->after('referrals_count');
            $table->date('monthly_withdrawals_period')->nullable()->after('monthly_withdrawals_count');
            $table->decimal('unwithdrawable_balance_min', 10, 2)->default(50.00)->after('monthly_withdrawals_period');

            $table->foreign('referrer_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['referrer_id']);
            $table->dropColumn([
                'level',
                'referrer_id',
                'initial_deposit_amount',
                'referrals_count',
                'monthly_withdrawals_count',
                'monthly_withdrawals_period',
                'unwithdrawable_balance_min',
            ]);
        });
    }
};


