<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('province')->nullable()->after('address');
            $table->string('district')->nullable()->after('province');
            $table->string('ward')->nullable()->after('district');
            $table->string('address_detail')->nullable()->after('ward');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->string('province')->nullable()->after('address');
            $table->string('district')->nullable()->after('province');
            $table->string('ward')->nullable()->after('district');
            $table->string('address_detail')->nullable()->after('ward');
        });
    }

    public function down(): void
    {
        Schema::table('users', fn (Blueprint $table) => $table->dropColumn(['province', 'district', 'ward', 'address_detail']));
        Schema::table('orders', fn (Blueprint $table) => $table->dropColumn(['province', 'district', 'ward', 'address_detail']));
    }
};
