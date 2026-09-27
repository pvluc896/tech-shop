<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->enum('type', ['fixed', 'percent']);
            $table->decimal('value', 15, 2);
            $table->decimal('min_order_amount', 15, 2)->default(0);
            $table->decimal('max_discount', 15, 2)->nullable();
            $table->unsignedInteger('usage_limit')->nullable();
            $table->unsignedInteger('used_count')->default(0);
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('voucher_id')->nullable()->after('user_id')->constrained()->nullOnDelete();
            $table->string('voucher_code')->nullable()->after('payment_method');
            $table->decimal('discount_amount', 15, 2)->default(0)->after('shipping_fee');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['voucher_id']);
            $table->dropColumn(['voucher_id', 'voucher_code', 'discount_amount']);
        });
        Schema::dropIfExists('vouchers');
    }
};
