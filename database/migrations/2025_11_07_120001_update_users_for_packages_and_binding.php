<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('investment_package')->nullable()->after('initial_deposit_amount');
            $table->decimal('pending_deposit_amount', 10, 2)->nullable()->after('investment_package');
            $table->string('pending_package_code')->nullable()->after('pending_deposit_amount');
            $table->string('bep20_address')->nullable()->after('payment_details');
            $table->timestamp('wallet_bound_at')->nullable()->after('bep20_address');
            $table->timestamp('channel_subscribed_at')->nullable()->after('wallet_bound_at');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'investment_package',
                'pending_deposit_amount',
                'pending_package_code',
                'bep20_address',
                'wallet_bound_at',
                'channel_subscribed_at',
            ]);
        });
    }
};

