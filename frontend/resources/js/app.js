import './bootstrap';
import 'bootstrap';

const applyTheme = (theme) => {
    document.documentElement.dataset.bsTheme = theme;
    localStorage.setItem('luc-tech-theme', theme);
    document.querySelectorAll('[data-theme-toggle]').forEach((button) => {
        const isDark = theme === 'dark';
        button.querySelector('span').textContent = isDark ? '☀' : '☾';
        button.setAttribute('aria-label', isDark ? 'Chuyển sang giao diện sáng' : 'Chuyển sang giao diện tối');
        button.setAttribute('title', isDark ? 'Chuyển sang giao diện sáng' : 'Chuyển sang giao diện tối');
    });
};

applyTheme(document.documentElement.dataset.bsTheme || 'light');
document.addEventListener('click', (event) => {
    if (!event.target.closest('[data-theme-toggle]')) return;
    applyTheme(document.documentElement.dataset.bsTheme === 'dark' ? 'light' : 'dark');
});

const assistant = document.querySelector('[data-shop-assistant]');
if (assistant) {
    const panel = assistant.querySelector('.shop-assistant-panel');
    const trigger = assistant.querySelector('[data-assistant-trigger]');
    const close = assistant.querySelector('[data-assistant-close]');
    const form = assistant.querySelector('[data-assistant-form]');
    const messages = assistant.querySelector('[data-assistant-messages]');
    const appendMessage = (text, type = '') => {
        const message = document.createElement('div');
        message.className = `assistant-message ${type}`.trim();
        message.textContent = text;
        messages.appendChild(message);
        messages.scrollTop = messages.scrollHeight;
        return message;
    };
    const toggleAssistant = (show) => {
        panel.hidden = !show;
        trigger.setAttribute('aria-expanded', String(show));
        if (show) form.querySelector('input').focus();
    };
    trigger.addEventListener('click', () => toggleAssistant(panel.hidden));
    close.addEventListener('click', () => toggleAssistant(false));
    form.addEventListener('submit', async (event) => {
        event.preventDefault();
        const input = form.querySelector('input');
        const button = form.querySelector('button');
        const message = input.value.trim();
        if (!message) return;
        appendMessage(message, 'user');
        input.value = '';
        button.disabled = true;
        const loading = appendMessage('Luc AI đang suy nghĩ...', 'loading');
        try {
            const response = await fetch('/assistant/chat', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', Accept: 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                body: JSON.stringify({ message }),
            });
            const data = await response.json();
            loading.remove();
            if (!response.ok) throw new Error(data.message || 'Không thể kết nối Luc AI.');
            appendMessage(data.reply);
        } catch (error) {
            loading.remove();
            appendMessage(error.message, 'loading');
        } finally {
            button.disabled = false;
            input.focus();
        }
    });
}

const updatePaginationContent = async (url) => {
    const currentMain = document.querySelector('main');
    if (!currentMain) return window.location.assign(url);
    const scrollPosition = window.scrollY;
    const currentHeight = currentMain.offsetHeight;

    try {
        const response = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
        if (!response.ok) throw new Error('Không thể tải dữ liệu.');

        const page = new DOMParser().parseFromString(await response.text(), 'text/html');
        const nextMain = page.querySelector('main');
        if (!nextMain) throw new Error('Không tìm thấy nội dung trang.');

        currentMain.style.minHeight = `${currentHeight}px`;
        currentMain.innerHTML = nextMain.innerHTML;
        document.title = page.title;
        window.history.pushState({}, '', url);
        window.requestAnimationFrame(() => {
            window.scrollTo(0, scrollPosition);
            window.requestAnimationFrame(() => currentMain.style.removeProperty('min-height'));
        });
    } catch (error) {
        window.location.assign(url);
    }
};

document.addEventListener('click', (event) => {
    const paginationLink = event.target.closest('.pagination a');
    if (!paginationLink || paginationLink.closest('#product-catalog-content') || event.defaultPrevented || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) return;

    event.preventDefault();
    updatePaginationContent(paginationLink.href);
});

const showFavoriteMessage = (message, type = 'success') => {
    const alert = document.createElement('div');
    alert.className = `alert alert-${type} shadow-sm position-fixed top-0 end-0 m-3 mb-0`;
    alert.style.zIndex = '1200';
    alert.textContent = message;
    document.body.appendChild(alert);
    window.setTimeout(() => alert.remove(), 2200);
};

document.addEventListener('submit', async (event) => {
    const form = event.target;
    if (!form.matches('[data-favorite-toggle]')) return;

    event.preventDefault();
    const button = form.querySelector('button[type="submit"]');
    button.disabled = true;

    try {
        const response = await fetch(form.action, {
            method: 'POST',
            body: new FormData(form),
            headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        });
        const result = await response.json();
        if (!response.ok) throw new Error(result.message || 'Không thể cập nhật sản phẩm yêu thích.');

        const isFavorited = result.isFavorited;
        const methodInput = form.querySelector('input[name="_method"]');
        if (isFavorited && !methodInput) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = '_method';
            input.value = 'DELETE';
            form.appendChild(input);
        } else if (!isFavorited && methodInput) {
            methodInput.remove();
        }

        button.classList.toggle('is-favorite', isFavorited);
        button.setAttribute('aria-label', isFavorited ? 'Bỏ yêu thích' : 'Thêm vào yêu thích');
        button.setAttribute('title', isFavorited ? 'Bỏ yêu thích' : 'Thêm vào yêu thích');
        button.querySelector('svg').setAttribute('fill', isFavorited ? 'currentColor' : 'none');
        showFavoriteMessage(result.message);

        if (!isFavorited && window.location.pathname === '/favorites') {
            form.closest('.col')?.remove();
        }
    } catch (error) {
        showFavoriteMessage(error.message, 'danger');
    } finally {
        button.disabled = false;
    }
});
