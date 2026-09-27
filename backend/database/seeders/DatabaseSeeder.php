<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Models\Voucher;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::firstOrCreate(['email' => 'test@example.com'], [
            'name' => 'Test User',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $categories = collect([
            ['name' => 'Điện thoại', 'slug' => 'dien-thoai', 'description' => 'Điện thoại thông minh chính hãng'],
            ['name' => 'Laptop', 'slug' => 'laptop', 'description' => 'Laptop học tập và làm việc'],
            ['name' => 'Âm thanh', 'slug' => 'am-thanh', 'description' => 'Tai nghe và thiết bị âm thanh'],
            ['name' => 'Phụ kiện', 'slug' => 'phu-kien', 'description' => 'Phụ kiện công nghệ thiết yếu'],
        ])->mapWithKeys(fn ($category) => [$category['slug'] => Category::firstOrCreate(['slug' => $category['slug']], $category)]);

        $brands = collect([
            ['name' => 'Apple', 'slug' => 'apple', 'description' => 'Thiết bị Apple'],
            ['name' => 'Samsung', 'slug' => 'samsung', 'description' => 'Thiết bị Samsung'],
            ['name' => 'ASUS', 'slug' => 'asus', 'description' => 'Thiết bị ASUS'],
            ['name' => 'Sony', 'slug' => 'sony', 'description' => 'Thiết bị Sony'],
            ['name' => 'Logitech', 'slug' => 'logitech', 'description' => 'Phụ kiện Logitech'],
        ])->mapWithKeys(fn ($brand) => [$brand['slug'] => Brand::firstOrCreate(['slug' => $brand['slug']], $brand)]);

        $products = [
            ['name' => 'iPhone 15 128GB', 'slug' => 'iphone-15-128gb', 'sku' => 'IP15-128-BLK', 'category' => 'dien-thoai', 'brand' => 'apple', 'price' => 21990000, 'sale_price' => 19990000, 'quantity' => 12, 'description' => 'iPhone 15 chính hãng, màn hình Super Retina XDR 6.1 inch.'],
            ['name' => 'Samsung Galaxy S24 5G', 'slug' => 'samsung-galaxy-s24-5g', 'sku' => 'SS-S24-5G', 'category' => 'dien-thoai', 'brand' => 'samsung', 'price' => 19990000, 'sale_price' => 18490000, 'quantity' => 10, 'description' => 'Galaxy S24 5G hiệu năng mạnh mẽ, camera AI thông minh.'],
            ['name' => 'MacBook Air M3 13 inch', 'slug' => 'macbook-air-m3-13', 'sku' => 'MBA-M3-13-8', 'category' => 'laptop', 'brand' => 'apple', 'price' => 27990000, 'sale_price' => 26490000, 'quantity' => 8, 'description' => 'MacBook Air chip M3 mỏng nhẹ, pin bền bỉ cho công việc.'],
            ['name' => 'ASUS Vivobook 15 OLED', 'slug' => 'asus-vivobook-15-oled', 'sku' => 'AS-VV15-OLED', 'category' => 'laptop', 'brand' => 'asus', 'price' => 16990000, 'sale_price' => null, 'quantity' => 7, 'description' => 'Laptop màn hình OLED sắc nét, phù hợp học tập và văn phòng.'],
            ['name' => 'Sony WH-1000XM5', 'slug' => 'sony-wh-1000xm5', 'sku' => 'SNY-XM5-BLK', 'category' => 'am-thanh', 'brand' => 'sony', 'price' => 8990000, 'sale_price' => 7990000, 'quantity' => 15, 'description' => 'Tai nghe chống ồn cao cấp Sony WH-1000XM5.'],
            ['name' => 'AirPods Pro 2 USB-C', 'slug' => 'airpods-pro-2-usb-c', 'sku' => 'APP2-USBC', 'category' => 'am-thanh', 'brand' => 'apple', 'price' => 6490000, 'sale_price' => 5790000, 'quantity' => 18, 'description' => 'Tai nghe AirPods Pro thế hệ 2 với cổng USB-C.'],
            ['name' => 'Logitech MX Master 3S', 'slug' => 'logitech-mx-master-3s', 'sku' => 'LOG-MX3S', 'category' => 'phu-kien', 'brand' => 'logitech', 'price' => 2690000, 'sale_price' => 2290000, 'quantity' => 20, 'description' => 'Chuột không dây công thái học cho công việc chuyên nghiệp.'],
            ['name' => 'Samsung T7 Shield 1TB', 'slug' => 'samsung-t7-shield-1tb', 'sku' => 'SS-T7S-1TB', 'category' => 'phu-kien', 'brand' => 'samsung', 'price' => 2590000, 'sale_price' => null, 'quantity' => 14, 'description' => 'Ổ cứng SSD di động bền bỉ, tốc độ truyền dữ liệu cao.'],
            ['name' => 'Apple Magic Keyboard', 'slug' => 'apple-magic-keyboard', 'sku' => 'APP-MK-2024', 'category' => 'phu-kien', 'brand' => 'apple', 'price' => 2690000, 'sale_price' => 2390000, 'quantity' => 16, 'description' => 'Bàn phím không dây Apple Magic Keyboard gọn nhẹ.'],
            ['name' => 'ASUS ROG Strix G16', 'slug' => 'asus-rog-strix-g16', 'sku' => 'AS-ROG-G16', 'category' => 'laptop', 'brand' => 'asus', 'price' => 32990000, 'sale_price' => 30990000, 'quantity' => 5, 'description' => 'Laptop gaming hiệu năng cao cho game thủ và sáng tạo nội dung.'],
            ['name' => 'Samsung Galaxy Tab S9 FE', 'slug' => 'samsung-galaxy-tab-s9-fe', 'sku' => 'SS-TABS9FE', 'category' => 'dien-thoai', 'brand' => 'samsung', 'price' => 11990000, 'sale_price' => 9990000, 'quantity' => 11, 'description' => 'Máy tính bảng Samsung Galaxy Tab S9 FE, phù hợp học tập và giải trí.'],
            ['name' => 'Sony SRS-XB100', 'slug' => 'sony-srs-xb100', 'sku' => 'SNY-XB100', 'category' => 'am-thanh', 'brand' => 'sony', 'price' => 1490000, 'sale_price' => null, 'quantity' => 24, 'description' => 'Loa Bluetooth nhỏ gọn Sony SRS-XB100, âm thanh mạnh mẽ.'],
            ['name' => 'iPhone 14 128GB', 'slug' => 'iphone-14-128gb', 'sku' => 'IP14-128-BLU', 'category' => 'dien-thoai', 'brand' => 'apple', 'price' => 17990000, 'sale_price' => 15990000, 'quantity' => 13, 'description' => 'iPhone 14 128GB chính hãng, hiệu năng ổn định.'],
            ['name' => 'Samsung Galaxy A55 5G', 'slug' => 'samsung-galaxy-a55-5g', 'sku' => 'SS-A55-5G', 'category' => 'dien-thoai', 'brand' => 'samsung', 'price' => 9990000, 'sale_price' => 8990000, 'quantity' => 20, 'description' => 'Galaxy A55 5G thiết kế hiện đại, camera chất lượng.'],
            ['name' => 'iPad Gen 10 WiFi 64GB', 'slug' => 'ipad-gen-10-wifi-64gb', 'sku' => 'IPAD10-64', 'category' => 'dien-thoai', 'brand' => 'apple', 'price' => 10990000, 'sale_price' => null, 'quantity' => 9, 'description' => 'iPad Gen 10 phục vụ học tập, làm việc và giải trí.'],
            ['name' => 'Samsung Galaxy Z Flip6', 'slug' => 'samsung-galaxy-z-flip6', 'sku' => 'SS-ZFLIP6', 'category' => 'dien-thoai', 'brand' => 'samsung', 'price' => 26990000, 'sale_price' => 23990000, 'quantity' => 6, 'description' => 'Điện thoại gập Galaxy Z Flip6 thời trang và linh hoạt.'],
            ['name' => 'MacBook Pro M3 14 inch', 'slug' => 'macbook-pro-m3-14', 'sku' => 'MBP-M3-14', 'category' => 'laptop', 'brand' => 'apple', 'price' => 39990000, 'sale_price' => 37990000, 'quantity' => 7, 'description' => 'MacBook Pro M3 14 inch cho công việc chuyên nghiệp.'],
            ['name' => 'ASUS Zenbook 14 OLED', 'slug' => 'asus-zenbook-14-oled', 'sku' => 'AS-ZB14-OLED', 'category' => 'laptop', 'brand' => 'asus', 'price' => 23990000, 'sale_price' => 21990000, 'quantity' => 10, 'description' => 'ASUS Zenbook 14 OLED mỏng nhẹ, màn hình rực rỡ.'],
            ['name' => 'ASUS TUF Gaming F15', 'slug' => 'asus-tuf-gaming-f15', 'sku' => 'AS-TUF-F15', 'category' => 'laptop', 'brand' => 'asus', 'price' => 24990000, 'sale_price' => null, 'quantity' => 8, 'description' => 'Laptop gaming ASUS TUF Gaming F15 hiệu năng bền bỉ.'],
            ['name' => 'MacBook Air M2 13 inch', 'slug' => 'macbook-air-m2-13', 'sku' => 'MBA-M2-13', 'category' => 'laptop', 'brand' => 'apple', 'price' => 22990000, 'sale_price' => 20490000, 'quantity' => 11, 'description' => 'MacBook Air M2 gọn nhẹ cho học tập và văn phòng.'],
            ['name' => 'Sony WF-1000XM5', 'slug' => 'sony-wf-1000xm5', 'sku' => 'SNY-WF-XM5', 'category' => 'am-thanh', 'brand' => 'sony', 'price' => 7490000, 'sale_price' => 6790000, 'quantity' => 17, 'description' => 'Tai nghe true wireless Sony chống ồn cao cấp.'],
            ['name' => 'Sony ULT Field 1', 'slug' => 'sony-ult-field-1', 'sku' => 'SNY-ULT-1', 'category' => 'am-thanh', 'brand' => 'sony', 'price' => 2990000, 'sale_price' => null, 'quantity' => 18, 'description' => 'Loa di động Sony với âm bass mạnh mẽ.'],
            ['name' => 'AirPods 4', 'slug' => 'airpods-4', 'sku' => 'APP-AIRPODS4', 'category' => 'am-thanh', 'brand' => 'apple', 'price' => 4490000, 'sale_price' => 3990000, 'quantity' => 22, 'description' => 'Tai nghe AirPods 4 kết nối nhanh và âm thanh sống động.'],
            ['name' => 'Logitech G Pro X 2 Lightspeed', 'slug' => 'logitech-g-pro-x-2', 'sku' => 'LOG-GPX2', 'category' => 'am-thanh', 'brand' => 'logitech', 'price' => 4290000, 'sale_price' => 3790000, 'quantity' => 12, 'description' => 'Tai nghe gaming không dây Logitech G Pro X 2.'],
            ['name' => 'Logitech K380S Keyboard', 'slug' => 'logitech-k380s-keyboard', 'sku' => 'LOG-K380S', 'category' => 'phu-kien', 'brand' => 'logitech', 'price' => 1090000, 'sale_price' => null, 'quantity' => 28, 'description' => 'Bàn phím Bluetooth nhỏ gọn kết nối đa thiết bị.'],
            ['name' => 'Logitech Brio 300 Webcam', 'slug' => 'logitech-brio-300-webcam', 'sku' => 'LOG-BRIO300', 'category' => 'phu-kien', 'brand' => 'logitech', 'price' => 1590000, 'sale_price' => 1390000, 'quantity' => 19, 'description' => 'Webcam Full HD cho học và họp trực tuyến.'],
            ['name' => 'Samsung SmartTag2', 'slug' => 'samsung-smarttag2', 'sku' => 'SS-SMARTTAG2', 'category' => 'phu-kien', 'brand' => 'samsung', 'price' => 690000, 'sale_price' => null, 'quantity' => 30, 'description' => 'Thiết bị định vị đồ dùng Samsung SmartTag2.'],
            ['name' => 'Apple 20W USB-C Adapter', 'slug' => 'apple-20w-usb-c-adapter', 'sku' => 'APP-ADP-20W', 'category' => 'phu-kien', 'brand' => 'apple', 'price' => 590000, 'sale_price' => null, 'quantity' => 35, 'description' => 'Củ sạc nhanh Apple 20W USB-C chính hãng.'],
            ['name' => 'ASUS ROG Chakram X', 'slug' => 'asus-rog-chakram-x', 'sku' => 'AS-CHAKRAM-X', 'category' => 'phu-kien', 'brand' => 'asus', 'price' => 2990000, 'sale_price' => 2590000, 'quantity' => 9, 'description' => 'Chuột gaming ASUS ROG Chakram X đa năng.'],
            ['name' => 'Sony LinkBuds S', 'slug' => 'sony-linkbuds-s', 'sku' => 'SNY-LINKBUDS-S', 'category' => 'am-thanh', 'brand' => 'sony', 'price' => 4290000, 'sale_price' => 3690000, 'quantity' => 14, 'description' => 'Tai nghe Sony LinkBuds S nhỏ nhẹ, chống ồn thông minh.'],
            ['name' => 'Samsung 45W Power Adapter', 'slug' => 'samsung-45w-power-adapter', 'sku' => 'SS-ADP-45W', 'category' => 'phu-kien', 'brand' => 'samsung', 'price' => 1090000, 'sale_price' => 890000, 'quantity' => 21, 'description' => 'Củ sạc nhanh Samsung 45W USB-C chính hãng.'],
        ];

        foreach ($products as $item) {
            Product::updateOrCreate(['sku' => $item['sku']], [
                'name' => $item['name'], 'slug' => $item['slug'], 'description' => $item['description'],
                'price' => $item['price'], 'sale_price' => $item['sale_price'], 'quantity' => $item['quantity'],
                'status' => 'active', 'category_id' => $categories[$item['category']]->id, 'brand_id' => $brands[$item['brand']]->id,
            ]);
        }

        return;

        Voucher::firstOrCreate(['code' => 'LUC10'], ['name' => 'Giảm 10% tối đa 100.000 ₫', 'type' => 'percent', 'value' => 10, 'min_order_amount' => 500000, 'max_discount' => 100000, 'usage_limit' => 100, 'used_count' => 0, 'is_active' => true, 'starts_at' => now(), 'ends_at' => now()->addYear()]);
        Voucher::firstOrCreate(['code' => 'LUC50K'], ['name' => 'Giảm ngay 50.000 ₫', 'type' => 'fixed', 'value' => 50000, 'min_order_amount' => 1000000, 'max_discount' => null, 'usage_limit' => 100, 'used_count' => 0, 'is_active' => true, 'starts_at' => now(), 'ends_at' => now()->addYear()]);
        foreach ([
            ['code' => 'FREESHIP300', 'name' => 'Miễn phí vận chuyển cho đơn từ 300.000 ₫', 'min_order_amount' => 300000],
            ['code' => 'FREESHIP500', 'name' => 'Miễn phí vận chuyển cho đơn từ 500.000 ₫', 'min_order_amount' => 500000],
            ['code' => 'FREESHIP1M', 'name' => 'Miễn phí vận chuyển cho đơn từ 1.000.000 ₫', 'min_order_amount' => 1000000],
            ['code' => 'FREESHIP0', 'name' => 'Miễn phí vận chuyển không cần giá trị tối thiểu', 'min_order_amount' => 0],
            ['code' => 'SHIPFREE0', 'name' => 'Freeship toàn đơn từ 0 ₫', 'min_order_amount' => 0],
            ['code' => 'SALE50', 'name' => 'Giảm 50% tối đa 200.000 ₫', 'min_order_amount' => 500000, 'type' => 'percent', 'value' => 50, 'is_free_shipping' => false, 'max_discount' => 200000],
            ['code' => 'MEGA50', 'name' => 'Giảm 50% tối đa 500.000 ₫', 'min_order_amount' => 2000000, 'type' => 'percent', 'value' => 50, 'is_free_shipping' => false, 'max_discount' => 500000],
        ] as $voucher) {
            Voucher::updateOrCreate(['code' => $voucher['code']], array_merge([
                'claim_method' => 'claim', 'type' => 'fixed', 'value' => 0, 'is_free_shipping' => true,
                'max_discount' => null, 'usage_limit' => 100, 'used_count' => 0, 'is_active' => true,
                'starts_at' => now(), 'ends_at' => now()->addYear(),
            ], $voucher));
        }
    }
}
