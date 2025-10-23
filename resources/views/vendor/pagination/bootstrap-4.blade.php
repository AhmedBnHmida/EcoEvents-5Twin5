@if ($paginator->hasPages())
    <nav class="modern-pagination">
        <ul class="pagination pagination-success">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
                    <span class="page-link" aria-hidden="true">
                        <i class="fas fa-chevron-left"></i>
                        <span class="sr-only">Previous</span>
                    </span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')">
                        <i class="fas fa-chevron-left"></i>
                        <span class="sr-only">Previous</span>
                    </a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="page-item disabled" aria-disabled="true"><span class="page-link">{{ $element }}</span></li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active" aria-current="page"><span class="page-link">{{ $page }}</span></li>
                        @else
                            <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">
                        <i class="fas fa-chevron-right"></i>
                        <span class="sr-only">Next</span>
                    </a>
                </li>
            @else
                <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
                    <span class="page-link" aria-hidden="true">
                        <i class="fas fa-chevron-right"></i>
                        <span class="sr-only">Next</span>
                    </span>
                </li>
            @endif
        </ul>
    </nav>

    <div class="pagination-info text-center text-sm text-muted mt-2">
        Showing {{ $paginator->firstItem() }} to {{ $paginator->lastItem() }} of {{ $paginator->total() }} results
    </div>
@endif

<style>
.modern-pagination {
    display: flex;
    justify-content: center;
    margin: 1rem 0;
}

.pagination-success {
    --bs-pagination-color: var(--color-text-primary, #495057);
    --bs-pagination-bg: var(--color-bg-light, #fff);
    --bs-pagination-border-color: var(--color-border, #dee2e6);
    --bs-pagination-hover-color: var(--color-success, #4caf50);
    --bs-pagination-hover-bg: var(--color-success-light, #e8f5e9);
    --bs-pagination-hover-border-color: var(--color-success, #4caf50);
    --bs-pagination-focus-color: var(--color-success, #4caf50);
    --bs-pagination-focus-bg: var(--color-success-light, #e8f5e9);
    --bs-pagination-active-bg: var(--color-success, #4caf50);
    --bs-pagination-active-border-color: var(--color-success, #4caf50);
    --bs-pagination-disabled-color: var(--color-text-secondary, #6c757d);
    --bs-pagination-disabled-bg: var(--color-bg-light, #f8f9fa);
    --bs-pagination-disabled-border-color: var(--color-border, #dee2e6);
}

.pagination-success .page-item.active .page-link {
    background: linear-gradient(310deg, var(--color-success-dark, #2e7d32) 0%, var(--color-success, #4caf50) 100%);
    border-color: transparent;
    color: white;
    box-shadow: 0 3px 5px rgba(0, 0, 0, 0.1);
}

.pagination-success .page-link {
    display: flex;
    align-items: center;
    justify-content: center;
    min-width: 36px;
    height: 36px;
    padding: 0 0.5rem;
    margin: 0 0.125rem;
    border-radius: 0.375rem !important;
    font-weight: 500;
    transition: all 0.2s ease;
}

.pagination-success .page-link:hover {
    z-index: 3;
}

.pagination-success .page-item:first-child .page-link,
.pagination-success .page-item:last-child .page-link {
    border-radius: 0.375rem !important;
}

.pagination-info {
    color: var(--color-text-secondary, #6c757d);
    font-size: 0.875rem;
}

/* Dark mode support */
.bg-gray-900 .pagination-success, 
.dark-mode .pagination-success,
[data-bs-theme="dark"] .pagination-success {
    --bs-pagination-bg: var(--color-section-dark, #1a2035);
    --bs-pagination-color: var(--color-text-light, #e9ecef);
    --bs-pagination-border-color: var(--color-border-dark, #2a3042);
    --bs-pagination-hover-bg: rgba(76, 175, 80, 0.2);
    --bs-pagination-hover-color: var(--color-success-bright, #69f0ae);
    --bs-pagination-hover-border-color: var(--color-success-bright, #69f0ae);
    --bs-pagination-disabled-bg: var(--color-section-dark, #1a2035);
    --bs-pagination-disabled-color: var(--color-text-secondary-dark, #adb5bd);
    --bs-pagination-disabled-border-color: var(--color-border-dark, #2a3042);
}

.bg-gray-900 .pagination-info,
.dark-mode .pagination-info,
[data-bs-theme="dark"] .pagination-info {
    color: var(--color-text-secondary-dark, #adb5bd);
}

/* Responsive adjustments */
@media (max-width: 576px) {
    .pagination-success .page-link {
        min-width: 32px;
        height: 32px;
        font-size: 0.875rem;
    }
}
</style>
