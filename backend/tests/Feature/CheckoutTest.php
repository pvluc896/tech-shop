<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Voucher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_checkout_with_a_valid_voucher(): void
    {
        $category = Category::create(['name' => 'Laptop', 'slug' => 'laptop']);
        $brand = Brand::create(['name' => 'Apple', 'slug' => 'apple']);
        $product = Product::create(['category_id' => $category->id, 'brand_id' => $brand->id, 'name' => 'MacBook Test', 'slug' => 'macbook-test', 'sku' => 'TEST-MBP-01', 'price' => 1_000_000, 'sale_price' => 900_000, 'quantity' => 5, 'status' => 'active']);
        $voucher = Voucher::create(['code' => 'LUC10', 'name' => 'Giảm 10%', 'type' => 'percent', 'value' => 10, 'min_order_amount' => 500_000, 'max_discount' => 100_000, 'usage_limit' => 10, 'is_active' => true, 'starts_at' => now()->subDay(), 'ends_at' => now()->addDay()]);
        $user = User::factory()->create();
        $user->vouchers()->attach($voucher->id, ['claimed_at' => now()]);

        $this->withSession(['cart' => [$product->id => 2]])->get('/cart')->assertOk();
        $this->withSession(['cart' => [$product->id => 2]])->get('/checkout')->assertOk();

        $response = $this->actingAs($user)->withSession(['cart' => [$product->id => 2]])->post('/checkout', [
            'customer_name' => 'Nguyễn Văn A', 'email' => 'buyer@example.com', 'phone' => '0901234567',
            'province' => 'Thành phố Hồ Chí Minh', 'district' => 'Quận 1', 'ward' => 'Phường Bến Nghé', 'address_detail' => '1 Nguyễn Huệ',
            'payment_method' => 'cod', 'voucher_code' => 'LUC10',
        ]);

        $response->assertRedirectToRoute('checkout.success', ['order' => 1]);
        $this->assertDatabaseHas('orders', ['voucher_code' => 'LUC10', 'subtotal' => 1_800_000, 'shipping_fee' => 25_000, 'discount_amount' => 100_000, 'total' => 1_725_000]);
        $this->assertDatabaseHas('order_items', ['product_id' => $product->id, 'quantity' => 2]);
        $this->assertDatabaseHas('vouchers', ['code' => 'LUC10', 'used_count' => 1]);
        $this->assertDatabaseHas('user_vouchers', ['user_id' => $user->id, 'voucher_id' => $voucher->id, 'order_id' => 1]);
        $this->assertDatabaseHas('products', ['id' => $product->id, 'quantity' => 3]);
    }

    public function test_customer_cannot_use_a_voucher_after_one_day(): void
    {
        $category = Category::create(['name' => 'Laptop', 'slug' => 'laptop']);
        $brand = Brand::create(['name' => 'Apple', 'slug' => 'apple']);
        $product = Product::create(['category_id' => $category->id, 'brand_id' => $brand->id, 'name' => 'MacBook Test', 'slug' => 'macbook-test', 'sku' => 'TEST-MBP-01', 'price' => 1_000_000, 'quantity' => 5, 'status' => 'active']);
        $voucher = Voucher::create(['code' => 'EXPIRED', 'name' => 'Voucher hết hạn', 'type' => 'fixed', 'value' => 50_000, 'min_order_amount' => 100_000, 'is_active' => true]);
        $user = User::factory()->create();
        $user->vouchers()->attach($voucher->id, ['claimed_at' => now()->subDay()->subMinute()]);

        $response = $this->actingAs($user)->withSession(['cart' => [$product->id => 1]])->post('/checkout', [
            'customer_name' => 'Nguyễn Văn A', 'email' => 'buyer@example.com', 'phone' => '0901234567',
            'province' => 'Thành phố Hồ Chí Minh', 'district' => 'Quận 1', 'ward' => 'Phường Bến Nghé', 'address_detail' => '1 Nguyễn Huệ',
            'payment_method' => 'cod', 'voucher_code' => 'EXPIRED',
        ]);

        $response->assertSessionHasErrors('voucher_code');
    }
}
