<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            $table->foreignId('category_id')
                ->constrained('categories')
                ->restrictOnDelete();

            $table->foreignId('brand_id')
                ->constrained('brands')
                ->restrictOnDelete();

            $table->string('name');
            $table->string('slug')->unique();

            $table->string('sku')->unique();

            $table->text('description')->nullable();

            $table->decimal('price', 15, 2);
            $table->decimal('sale_price', 15, 2)->nullable();

            $table->integer('quantity')->default(0);

            $table->string('status')->default('active');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
