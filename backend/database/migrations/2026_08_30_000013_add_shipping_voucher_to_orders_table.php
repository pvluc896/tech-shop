<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('shipping_voucher_id')->nullable()->after('voucher_id')->constrained('vouchers')->nullOnDelete();
            $table->string('shipping_voucher_code')->nullable()->after('voucher_code');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['shipping_voucher_id']);
            $table->dropColumn(['shipping_voucher_id', 'shipping_voucher_code']);
        });
    }
};
