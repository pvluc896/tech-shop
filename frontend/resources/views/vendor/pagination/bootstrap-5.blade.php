@if ($paginator->hasPages())
    <nav aria-label="Phân trang">
        @php
            $lastPage = $paginator->lastPage();
            $currentPage = $paginator->currentPage();
            $pages = [1, 2, $currentPage - 1, $currentPage, $currentPage + 1, $lastPage - 1, $lastPage];
            $pages = array_values(array_unique(array_filter($pages, fn ($page) => $page >= 1 && $page <= $lastPage)));
            sort($pages);
        @endphp
        <ul class="pagination mb-0">
            <li class="page-item {{ $paginator->onFirstPage() ? 'disabled' : '' }}">
                @if ($paginator->onFirstPage())<span class="page-link" aria-label="Trang đầu">«</span>@else<a class="page-link" href="{{ $paginator->url(1) }}" aria-label="Trang đầu">«</a>@endif
            </li>
            @if ($paginator->onFirstPage())
                <li class="page-item disabled"><span class="page-link">‹</span></li>
            @else
                <li class="page-item"><a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Trang trước">‹</a></li>
            @endif
            @foreach ($pages as $index => $page)
                @if ($index > 0 && $page > $pages[$index - 1] + 1)
                    <li class="page-item disabled"><span class="page-link">…</span></li>
                @endif
                @if ($page === $currentPage)
                    <li class="page-item active" aria-current="page"><span class="page-link">{{ $page }}</span></li>
                @else
                    <li class="page-item"><a class="page-link" href="{{ $paginator->url($page) }}">{{ $page }}</a></li>
                @endif
            @endforeach
            @if ($paginator->hasMorePages())
                <li class="page-item"><a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Trang sau">›</a></li>
            @else
                <li class="page-item disabled"><span class="page-link">›</span></li>
            @endif
            <li class="page-item {{ $paginator->hasMorePages() ? '' : 'disabled' }}">
                @if ($paginator->hasMorePages())<a class="page-link" href="{{ $paginator->url($lastPage) }}" aria-label="Trang cuối">»</a>@else<span class="page-link" aria-label="Trang cuối">»</span>@endif
            </li>
        </ul>
    </nav>
@endif
