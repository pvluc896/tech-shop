<!DOCTYPE html>
<html lang="vi" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Luc Tech Shop')</title>
    <script>document.documentElement.dataset.bsTheme = localStorage.getItem('luc-tech-theme') || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');</script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-body-tertiary d-flex flex-column min-vh-100">
    <div class="shop-announcement text-white-50 small py-2">
        <div class="container d-flex justify-content-between align-items-center gap-3"><span><span class="text-white">✓</span> Hàng chính hãng · Giao hàng toàn quốc</span><a class="link-light text-decoration-none" href="tel:19000000">Hỗ trợ: 1900 0000</a></div>
    </div>
    <header class="shop-header navbar navbar-expand-xl bg-white border-bottom sticky-top py-3">
        <div class="container gap-2">
            <a href="/" class="navbar-brand d-flex align-items-center gap-2 me-xl-4">
                <span class="bg-primary text-white rounded-4 d-inline-flex align-items-center justify-content-center fw-bold fs-4 px-3 py-2 shadow">L</span>
                <span class="lh-sm"><span class="d-block fw-bold text-dark fs-5">LUC TECH</span><small class="d-block text-primary fw-semibold">SMART LIVING</small></span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#shopNavigation" aria-controls="shopNavigation" aria-expanded="false" aria-label="Mở điều hướng"><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="shopNavigation">
                <form action="{{ route('products.index') }}" method="GET" class="order-xl-2 flex-grow-1 mx-xl-4 my-3 my-xl-0">
                    <div class="position-relative rounded-pill bg-body-tertiary border"><input type="search" name="search" value="{{ request('search') }}" class="form-control rounded-pill bg-transparent border-0 ps-4 pe-5 shadow-none" placeholder="Tìm sản phẩm, SKU..." aria-label="Tìm sản phẩm"><button type="submit" class="btn btn-link text-primary d-inline-flex align-items-center justify-content-center position-absolute top-50 end-0 translate-middle-y me-2 p-2 text-decoration-none" aria-label="Tìm kiếm"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/></svg></button></div>
                </form>
                <nav class="navbar-nav order-xl-1 gap-xl-1">
                    <a class="nav-link fw-semibold px-3 {{ request()->is('/') ? 'text-primary' : '' }}" href="/">Trang chủ</a>
                    <a class="nav-link fw-semibold px-3 {{ request()->is('products*') ? 'text-primary' : '' }}" href="/products">Sản phẩm</a>
                    <a class="nav-link fw-semibold px-3 {{ request()->is('categories*') ? 'text-primary' : '' }}" href="/categories">Danh mục</a>
                    <a class="nav-link fw-semibold px-3 {{ request()->is('brands*') ? 'text-primary' : '' }}" href="/brands">Thương hiệu</a>
                    @auth<a class="nav-link fw-semibold px-3 {{ request()->is('vouchers*') ? 'text-primary' : '' }}" href="{{ route('vouchers.index') }}">Voucher</a>@endauth
                </nav>
                <div class="d-flex align-items-center gap-2 order-xl-3 pt-3 pt-xl-0">
                    <button class="theme-toggle btn btn-light border rounded-circle d-inline-flex align-items-center justify-content-center p-2" type="button" data-theme-toggle aria-label="Chuyển sang giao diện tối" title="Chuyển sang giao diện tối"><span aria-hidden="true">☾</span></button>
                    <a class="btn btn-light border-0 d-none d-xl-inline-flex align-items-center gap-2 text-secondary" href="tel:19000000"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1 1 .4 2 .7 2.9a2 2 0 0 1-.5 2.1L8 10a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.5c.9.3 1.9.6 2.9.7A2 2 0 0 1 22 16.9Z"/></svg><span class="small fw-semibold">Hỗ trợ</span></a>
                    <a class="btn btn-light border position-relative rounded-circle d-inline-flex align-items-center justify-content-center p-2" href="{{ route('cart.index') }}" aria-label="Giỏ hàng" title="Giỏ hàng" data-cart-link><svg width="21" height="21" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 3h2l2.4 11.4a2 2 0 0 0 2 1.6h7.9a2 2 0 0 0 1.9-1.4L21 7H6"/><circle cx="10" cy="20" r="1"/><circle cx="18" cy="20" r="1"/></svg>@if (count(session('cart', [])))<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill text-bg-danger" data-cart-count>{{ count(session('cart', [])) }}</span>@endif</a>
                    @auth
                        <div class="dropdown">
                            <button class="btn btn-light border rounded-circle d-inline-flex align-items-center justify-content-center p-2" type="button" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Mở menu tài khoản" title="Tài khoản">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="8" r="4"/><path d="M4 21a8 8 0 0 1 16 0"/></svg>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow">
                                <li><h6 class="dropdown-header">{{ auth()->user()->email }}</h6></li>
                                @if (auth()->user()->role === 'admin')<li><a class="dropdown-item account-menu-item d-flex align-items-center gap-2" href="/admin"><svg class="account-menu-icon icon-admin" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>Quản trị</a></li>@endif
                                <li><a class="dropdown-item account-menu-item d-flex align-items-center gap-2" href="{{ route('orders.index') }}"><svg class="account-menu-icon icon-orders" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 3h12a2 2 0 0 1 2 2v16l-3-2-3 2-3-2-3 2-2-1V5a2 2 0 0 1 2-2Z"/><path d="M9 8h6M9 12h6"/></svg>Đơn hàng của tôi</a></li>
                                <li><a class="dropdown-item account-menu-item d-flex align-items-center gap-2" href="{{ route('favorites.index') }}"><svg class="account-menu-icon icon-favorites" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.7l-1.1-1.1a5.5 5.5 0 0 0-7.8 7.8L12 21l8.9-8.6a5.5 5.5 0 0 0-.1-7.8Z"/></svg>Sản phẩm yêu thích</a></li>
                                <li><a class="dropdown-item account-menu-item d-flex align-items-center gap-2" href="{{ route('profile') }}"><svg class="account-menu-icon icon-profile" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="8" r="4"/><path d="M4 21a8 8 0 0 1 16 0"/></svg>Thông tin cá nhân</a></li>
                                <li><a class="dropdown-item account-menu-item d-flex align-items-center gap-2" href="{{ route('password.change') }}"><svg class="account-menu-icon icon-password" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect width="16" height="11" x="4" y="11" rx="2"/><path d="M8 11V7a4 4 0 0 1 8 0v4"/></svg>Đổi mật khẩu</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="dropdown-item account-menu-item d-flex align-items-center gap-2"><svg class="account-menu-icon icon-logout" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M10 17l5-5-5-5"/><path d="M15 12H3"/><path d="M21 3v18"/></svg>Đăng xuất</button></form></li>
                            </ul>
                        </div>
                    @else
                        <a class="btn btn-outline-primary rounded-circle d-inline-flex align-items-center justify-content-center p-2" href="{{ route('login') }}" aria-label="Đăng nhập" title="Đăng nhập"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="8" r="4"/><path d="M4 21a8 8 0 0 1 16 0"/></svg></a>
                    @endauth
                </div>
            </div>
        </div>
    </header>
    <main class="flex-grow-1">
        @if (session('success'))<div class="container pt-4"><div class="alert alert-success alert-dismissible fade show mb-0" role="alert" data-auto-dismiss>{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button></div></div>@endif
        @if ($errors->any())<div class="container pt-4"><div class="alert alert-danger alert-dismissible fade show mb-0" role="alert">{{ $errors->first() }}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button></div></div>@endif
        @yield('content')
    </main>
    <footer class="shop-footer text-white-50 mt-5 pt-5">
        <div class="container pb-4"><div class="row g-4">
            <div class="col-12 col-lg-4 pe-lg-5"><a href="/" class="h5 fw-bold text-white text-decoration-none">LUC TECH SHOP</a><p class="mt-3 mb-0 lh-lg">Điểm đến đáng tin cậy cho thiết bị công nghệ chính hãng, tư vấn tận tâm và dịch vụ hậu mãi chu đáo.</p></div>
            <div class="col-6 col-lg-2"><h2 class="h6 text-white">Về chúng tôi</h2><ul class="list-unstyled"><li><a class="footer-link" href="#">Giới thiệu</a></li><li><a class="footer-link" href="#">Tin công nghệ</a></li><li><a class="footer-link" href="#">Tuyển dụng</a></li></ul></div>
            <div class="col-6 col-lg-2"><h2 class="h6 text-white">Hỗ trợ khách hàng</h2><ul class="list-unstyled"><li><a class="footer-link" href="#">Hướng dẫn mua hàng</a></li><li><a class="footer-link" href="#">Chính sách bảo hành</a></li><li><a class="footer-link" href="#">Đổi trả & hoàn tiền</a></li></ul></div>
            <div class="col-12 col-lg-4"><h2 class="h6 text-white">Liên hệ</h2><p class="mb-2">Điện thoại: <a class="footer-link" href="tel:0787899863">0787899863</a></p><p class="mb-2">Gmail: <a class="footer-link" href="mailto:phanvanluc8650@gmail.com">phanvanluc8650@gmail.com</a></p><p class="mb-0">Địa chỉ: <button type="button" class="btn btn-link footer-link p-0 align-baseline" data-bs-toggle="modal" data-bs-target="#schoolMapModal">Trường Cao đẳng Công Thương TP.HCM</button></p></div>
        </div></div>
        <div class="border-top border-secondary"><div class="container py-3 small d-flex flex-wrap justify-content-between gap-2"><span>© {{ date('Y') }} Luc Tech Shop.</span><span>Điều khoản sử dụng · Chính sách bảo mật</span></div></div>
    </footer>

    <aside class="shop-assistant" data-shop-assistant>
        <section class="shop-assistant-panel shadow-lg" aria-label="Luc AI" hidden>
            <header><div class="shop-assistant-avatar">✦</div><div><strong>Luc AI</strong><small>Trợ lý mua sắm</small></div><button type="button" class="btn-close" data-assistant-close aria-label="Đóng chat"></button></header>
            <div class="shop-assistant-messages" data-assistant-messages><div class="assistant-message">Chào bạn! Mình là Luc AI. Bạn cần tư vấn sản phẩm hay hỗ trợ mua sắm không?</div></div>
            <form class="shop-assistant-form" data-assistant-form><input type="text" name="message" maxlength="1000" autocomplete="off" placeholder="Nhắn cho Luc AI..." aria-label="Tin nhắn cho Luc AI"><button type="submit" aria-label="Gửi tin nhắn">↑</button></form>
        </section>
        <button class="shop-assistant-trigger shadow" type="button" data-assistant-trigger aria-label="Mở Luc AI" title="Hỏi Luc AI"><span>✦</span><small>AI</small></button>
    </aside>

    <div class="modal fade" id="schoolMapModal" tabindex="-1" aria-labelledby="schoolMapModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header">
                    <div>
                        <h2 class="modal-title fs-5 fw-bold" id="schoolMapModalLabel">Trường Cao đẳng Công Thương TP.HCM</h2>
                        <p class="small text-secondary mb-0">20 Tăng Nhơn Phú, phường Phước Long, TP. Thủ Đức, TP.HCM</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                </div>
                <div class="ratio ratio-16x9">
                    <iframe src="https://www.google.com/maps?q=Trường%20Cao%20đẳng%20Công%20Thương%20TP.HCM&output=embed" title="Bản đồ Trường Cao đẳng Công Thương TP.HCM" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
                <div class="modal-footer">
                    <a class="btn btn-primary" href="https://www.google.com/maps/search/?api=1&query=Trường%20Cao%20đẳng%20Công%20Thương%20TP.HCM" target="_blank" rel="noopener">Mở Google Maps</a>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="cancelOrderModal" tabindex="-1" aria-labelledby="cancelOrderModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form id="cancelOrderForm" method="POST" class="modal-content border-0 shadow">
                @csrf
                @method('PATCH')
                <div class="modal-header border-0 pb-0">
                    <div><h2 class="modal-title fs-5 fw-bold" id="cancelOrderModalLabel">Hủy đơn hàng</h2><p class="small text-secondary mb-0 mt-1" id="cancelOrderDescription"></p></div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                </div>
                <div class="modal-body pt-3">
                    <label for="cancelOrderReason" class="form-label fw-semibold">Lý do hủy đơn <span class="text-danger">*</span></label>
                    <select id="cancelOrderReason" class="form-select" name="cancel_reason" required>
                        <option value="" selected disabled>Chọn lý do hủy</option>
                        <option value="ordered_by_mistake">Đặt nhầm sản phẩm</option>
                        <option value="change_order_info">Muốn thay đổi thông tin đơn hàng</option>
                        <option value="delivery_time">Thời gian giao hàng không phù hợp</option>
                        <option value="better_price">Tìm được mức giá tốt hơn</option>
                        <option value="other">Lý do khác</option>
                    </select>
                    <p class="small text-secondary mb-0 mt-3">Đơn chỉ có thể hủy khi đang chờ xác nhận.</p>
                </div>
                <div class="modal-footer border-0 pt-0"><button type="button" class="btn btn-light border" data-bs-dismiss="modal">Quay lại</button><button type="submit" class="btn btn-danger">Xác nhận hủy đơn</button></div>
            </form>
        </div>
    </div>
    <script>
        document.getElementById('cancelOrderModal')?.addEventListener('show.bs.modal', (event) => {
            const trigger = event.relatedTarget;
            const form = document.getElementById('cancelOrderForm');
            const description = document.getElementById('cancelOrderDescription');
            const reason = document.getElementById('cancelOrderReason');
            if (!trigger || !form || !description || !reason) return;

            form.action = trigger.dataset.cancelAction;
            description.textContent = `Đơn hàng ${trigger.dataset.orderNumber} sẽ được hủy sau khi bạn xác nhận.`;
            reason.value = '';
        });

        document.querySelectorAll('[data-password-toggle]').forEach((button) => {
            button.addEventListener('click', () => {
                const input = document.getElementById(button.dataset.passwordToggle);
                const hidden = input.type === 'password';
                input.type = hidden ? 'text' : 'password';
                button.setAttribute('aria-label', hidden ? 'Ẩn mật khẩu' : 'Hiện mật khẩu');
                button.setAttribute('title', hidden ? 'Ẩn mật khẩu' : 'Hiện mật khẩu');
                button.setAttribute('aria-pressed', String(hidden));
                button.querySelector('svg').innerHTML = hidden
                    ? '<path d="m3 3 18 18"/><path d="M10.6 10.6a2 2 0 0 0 2.8 2.8"/><path d="M9.9 4.2A10.8 10.8 0 0 1 12 4c6.5 0 10 8 10 8a18.5 18.5 0 0 1-3 3.8M6.6 6.6C3.7 8.5 2 12 2 12s3.5 8 10 8a9.8 9.8 0 0 0 3.4-.6"/>'
                    : '<path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/>';
            });
        });
        document.querySelectorAll('[data-auto-dismiss]').forEach((alert) => {
            window.setTimeout(() => {
                alert.classList.remove('show');
                window.setTimeout(() => alert.remove(), 200);
            }, 3000);
        });
        if (window.location.pathname === '/products') {
            const scrollKey = 'product-pagination-scroll-position';
            const savedPosition = sessionStorage.getItem(scrollKey);
            if (savedPosition !== null) {
                window.requestAnimationFrame(() => window.scrollTo(0, Number(savedPosition)));
                sessionStorage.removeItem(scrollKey);
            }
            document.querySelectorAll('.pagination a').forEach((link) => {
                link.addEventListener('click', () => sessionStorage.setItem(scrollKey, String(window.scrollY)));
            });
        }

        const showCartMessage = (message, type = 'success') => {
            const alert = document.createElement('div');
            alert.className = `alert alert-${type} shadow-sm position-fixed top-0 end-0 m-3 mb-0`;
            alert.style.zIndex = '1100';
            alert.textContent = message;
            document.body.appendChild(alert);
            window.setTimeout(() => alert.remove(), type === 'success' ? 1000 : 2500);
        };

        document.addEventListener('submit', async (event) => {
            const form = event.target;
            if (!form.matches('[data-cart-add]')) return;

            event.preventDefault();
            const button = form.querySelector('button[type="submit"]');
            button.disabled = true;

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: new FormData(form),
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                });
                const result = await response.json();
                if (!response.ok) throw new Error(result.message || 'Không thể thêm sản phẩm vào giỏ.');

                let badge = document.querySelector('[data-cart-count]');
                if (!badge) {
                    badge = document.createElement('span');
                    badge.className = 'position-absolute top-0 start-100 translate-middle badge rounded-pill text-bg-danger';
                    badge.dataset.cartCount = '';
                    document.querySelector('[data-cart-link]').appendChild(badge);
                }
                badge.textContent = result.cartCount;
                showCartMessage(result.message);
            } catch (error) {
                showCartMessage(error.message, 'danger');
            } finally {
                button.disabled = false;
            }
        });

    </script>
</body>
</html>
