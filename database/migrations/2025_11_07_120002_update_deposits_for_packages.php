<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('deposits', function (Blueprint $table) {
            $table->string('package_code')->nullable()->after('currency');
            $table->decimal('expected_withdrawal_cap', 10, 2)->nullable()->after('amount');
        });
    }

    public function down(): void
    {
        Schema::table('deposits', function (Blueprint $table) {
            $table->dropColumn(['package_code', 'expected_withdrawal_cap']);
        });
    }
};

