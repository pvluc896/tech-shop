# Luc Tech Shop

## Cấu trúc

- `backend/`: Laravel — controller, model, service, route, migration, test và cấu hình.
- `frontend/`: Blade views, CSS, JavaScript, Vite và toàn bộ tài nguyên public.
- `storage/`: log, cache, session và file upload lúc chạy. Thư mục này tách riêng để PHP trên Windows có quyền ghi ổn định.

## Chạy dự án

1. Trỏ DocumentRoot của Apache/XAMPP đến `frontend/public`.
2. Mở terminal tại `backend/`, sau đó chạy `php artisan serve` hoặc `composer run dev`.
3. Chạy frontend độc lập bằng `cd frontend && npm run dev`.

## Kiểm thử và build

```powershell
cd backend
php artisan test

cd ../frontend
npm run build
```
