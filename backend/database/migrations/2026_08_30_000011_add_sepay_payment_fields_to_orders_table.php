<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('sepay_transaction_id')->nullable()->unique()->after('status');
            $table->timestamp('paid_at')->nullable()->after('sepay_transaction_id');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropUnique(['sepay_transaction_id']);
            $table->dropColumn(['sepay_transaction_id', 'paid_at']);
        });
    }
};
