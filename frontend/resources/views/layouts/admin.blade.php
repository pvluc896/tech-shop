<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        @yield('title', 'Admin')
    </title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="d-flex min-vh-100">
        <!-- SIDEBAR -->
        <aside class="admin-sidebar bg-dark text-white p-3">
            <h4 class="text-center mb-4">
                LUC TECH SHOP
            </h4>
            <hr>
            <ul class="nav nav-pills flex-column gap-2">
                <!-- Dashboard -->
                <li class="nav-item">
                    <a
                        href="/admin"
                        class="nav-link text-white">
                        🏠 Dashboard
                    </a>
                </li>
                <!-- Sản phẩm -->
                <li class="nav-item">
                    <a
                        href="/admin/products"
                        class="nav-link text-white">
                        📦 Sản phẩm
                    </a>
                </li>
                <!-- Danh mục -->
                <li class="nav-item">
                    <a
                        href="/admin/categories"
                        class="nav-link text-white">
                        📁 Danh mục
                    </a>
                </li>
                <!-- Thương hiệu -->
                <li class="nav-item">
                    <a
                        href="/admin/brands"
                        class="nav-link text-white">
                        🏷️ Thương hiệu
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/admin/vouchers" class="nav-link text-white">🎟️ Voucher</a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.users.index') }}" class="nav-link text-white">👥 Khách hàng</a>
                </li>
                <!-- Đơn hàng -->
                <li class="nav-item">
                    <a
                        href="{{ route('admin.orders.index') }}"
                        class="nav-link text-white">
                        🛒 Đơn hàng
                    </a>
                </li>
            </ul>
        </aside>
        <!-- MAIN -->
        <div class="flex-grow-1 bg-light">
            <!-- TOPBAR -->
            <nav class="admin-topbar navbar px-4 px-lg-5">
                <div class="d-flex align-items-center gap-3">
                    <span class="admin-topbar-mark"><svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="4"/><path d="M8 8h8M8 12h5M8 16h3"/></svg></span>
                    <div><span class="admin-topbar-eyebrow">Quản trị hệ thống</span><h1 class="admin-page-title mb-0">@yield('page-title', 'Dashboard')</h1></div>
                </div>
                <a href="/" class="btn admin-view-site d-inline-flex align-items-center gap-2"><span>Xem trang người dùng</span><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M15 3h6v6"/><path d="M10 14 21 3"/><path d="M21 14v6a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h6"/></svg></a>
            </nav>
            <!-- CONTENT -->
            <main class="p-4">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>
    <div class="modal fade" id="adminLockUserModal" tabindex="-1" aria-labelledby="adminLockUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form id="adminLockUserForm" method="POST" class="modal-content border-0 shadow">
                @csrf
                @method('PATCH')
                <div class="modal-header border-0 pb-0">
                    <div><h5 class="modal-title" id="adminLockUserModalLabel">Khóa tài khoản</h5><p class="text-secondary small mb-0 mt-1" id="adminLockUserDescription"></p></div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                </div>
                <div class="modal-body pt-3">
                    <label for="adminLockReason" class="form-label fw-semibold">Lý do khóa <span class="text-danger">*</span></label>
                    <select id="adminLockReason" class="form-select" name="lock_reason" required>
                        <option value="" selected disabled>Chọn lý do khóa</option>
                        <option value="suspicious_activity">Hoạt động đăng nhập bất thường</option>
                        <option value="policy_violation">Vi phạm chính sách cửa hàng</option>
                        <option value="fraudulent_order">Nghi ngờ đơn hàng gian lận</option>
                        <option value="customer_request">Theo yêu cầu của khách hàng</option>
                        <option value="other">Lý do khác</option>
                    </select>
                </div>
                <div class="modal-footer border-0 pt-0"><button type="button" class="btn btn-light border" data-bs-dismiss="modal">Hủy</button><button type="submit" class="btn btn-warning">Xác nhận khóa</button></div>
            </form>
        </div>
    </div>
    <script>
        const autoDismissAdminAlerts = () => {
            document.querySelectorAll('main .alert.alert-success, main .alert.alert-danger').forEach((alert) => {
                if (alert.dataset.dismissScheduled) return;
                alert.dataset.dismissScheduled = 'true';
                alert.classList.add('fade', 'show');
                window.setTimeout(() => {
                    alert.classList.remove('show');
                    window.setTimeout(() => alert.remove(), 200);
                }, 2000);
            });
        };

        autoDismissAdminAlerts();

        document.getElementById('adminLockUserModal')?.addEventListener('show.bs.modal', (event) => {
            const trigger = event.relatedTarget;
            const form = document.getElementById('adminLockUserForm');
            const description = document.getElementById('adminLockUserDescription');
            const reason = document.getElementById('adminLockReason');
            if (!trigger || !form || !description || !reason) return;

            form.action = trigger.dataset.lockAction;
            description.textContent = `Chọn lý do trước khi khóa tài khoản ${trigger.dataset.userName}.`;
            reason.value = '';
        });

        document.addEventListener('click', async (event) => {
            const link = event.target.closest('.admin-sidebar a[href]');
            if (!link || link.getAttribute('href') === '#' || event.defaultPrevented || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) return;

            event.preventDefault();
            const main = document.querySelector('main');
            if (!main) return window.location.assign(link.href);

            try {
                const response = await fetch(link.href, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                if (!response.ok) throw new Error('Không thể tải trang.');

                const page = new DOMParser().parseFromString(await response.text(), 'text/html');
                const nextMain = page.querySelector('main');
                if (!nextMain) throw new Error('Không tìm thấy nội dung trang.');

                main.innerHTML = nextMain.innerHTML;
                document.title = page.title;
                window.history.pushState({}, '', link.href);
                window.scrollTo(0, 0);
                autoDismissAdminAlerts();
            } catch (error) {
                window.location.assign(link.href);
            }
        });
    </script>
</body>
</html>
