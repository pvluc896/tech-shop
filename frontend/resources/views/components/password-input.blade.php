@props(['name', 'label', 'autocomplete' => 'current-password', 'autofocus' => false])

<div class="mb-3">
    <label for="{{ $name }}" class="form-label fw-semibold">{{ $label }}</label>
    <div class="position-relative">
        <input id="{{ $name }}" name="{{ $name }}" type="password" autocomplete="{{ $autocomplete }}"
            @if ($autofocus) autofocus @endif
            class="form-control pe-5 @error($name) is-invalid @enderror" required>
        <button class="btn position-absolute top-50 end-0 translate-middle-y me-2 p-1 border-0 bg-transparent text-secondary" type="button" data-password-toggle="{{ $name }}"
            aria-label="Hiện mật khẩu" aria-pressed="false" title="Hiện mật khẩu">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z" />
                <circle cx="12" cy="12" r="3" />
            </svg>
        </button>
    </div>
    @error($name)<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
</div>
