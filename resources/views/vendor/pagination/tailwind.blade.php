@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="pagination-container">
        <div class="pagination-info">
            <p class="text-sm text-muted">
                {!! __('Showing') !!}
                <span class="font-medium">{{ $paginator->firstItem() }}</span>
                {!! __('to') !!}
                <span class="font-medium">{{ $paginator->lastItem() }}</span>
                {!! __('of') !!}
                <span class="font-medium">{{ $paginator->total() }}</span>
                {!! __('results') !!}
            </p>
        </div>

        <div class="pagination-links">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span class="pagination-btn disabled" aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                    <span aria-hidden="true"><i class="fas fa-chevron-left"></i></span>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="pagination-btn" rel="prev" aria-label="{{ __('pagination.previous') }}">
                    <i class="fas fa-chevron-left"></i>
                </a>
            @endif

            {{-- Pagination Elements --}}
            <div class="pagination-pages">
                @foreach ($elements as $element)
                    {{-- "Three Dots" Separator --}}
                    @if (is_string($element))
                        <span class="pagination-ellipsis" aria-disabled="true">{{ $element }}</span>
                    @endif

                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span class="pagination-btn active" aria-current="page">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="pagination-btn" aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach
                    @endif
                @endforeach
            </div>

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="pagination-btn" rel="next" aria-label="{{ __('pagination.next') }}">
                    <i class="fas fa-chevron-right"></i>
                </a>
            @else
                <span class="pagination-btn disabled" aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                    <span aria-hidden="true"><i class="fas fa-chevron-right"></i></span>
                </span>
            @endif
        </div>
    </nav>
@endif

<style>
.pagination-container {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    align-items: center;
    margin: 1rem 0;
    gap: 1rem;
}

.pagination-info {
    color: var(--color-text-secondary, #6c757d);
}

.pagination-links {
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

.pagination-pages {
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

.pagination-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    min-width: 36px;
    height: 36px;
    padding: 0 0.5rem;
    border-radius: 0.375rem;
    font-weight: 500;
    color: var(--color-text-primary, #495057);
    background-color: var(--color-bg-light, #fff);
    border: 1px solid var(--color-border, #dee2e6);
    transition: all 0.2s ease;
    text-decoration: none;
    cursor: pointer;
}

.pagination-btn:hover {
    background-color: var(--color-success-light, #e8f5e9);
    color: var(--color-success, #4caf50);
    border-color: var(--color-success, #4caf50);
    z-index: 1;
}

.pagination-btn.active {
    background: linear-gradient(310deg, var(--color-success-dark, #2e7d32) 0%, var(--color-success, #4caf50) 100%);
    color: white;
    border-color: transparent;
    box-shadow: 0 3px 5px rgba(0, 0, 0, 0.1);
}

.pagination-btn.disabled {
    opacity: 0.5;
    cursor: not-allowed;
    pointer-events: none;
}

.pagination-ellipsis {
    display: flex;
    align-items: center;
    justify-content: center;
    min-width: 36px;
    height: 36px;
    color: var(--color-text-secondary, #6c757d);
}

/* Dark mode support */
.bg-gray-900 .pagination-btn, 
.dark-mode .pagination-btn,
[data-bs-theme="dark"] .pagination-btn {
    background-color: var(--color-section-dark, #1a2035);
    color: var(--color-text-light, #e9ecef);
    border-color: var(--color-border-dark, #2a3042);
}

.bg-gray-900 .pagination-btn:hover,
.dark-mode .pagination-btn:hover,
[data-bs-theme="dark"] .pagination-btn:hover {
    background-color: rgba(76, 175, 80, 0.2);
    color: var(--color-success-bright, #69f0ae);
    border-color: var(--color-success-bright, #69f0ae);
}

.bg-gray-900 .pagination-info,
.dark-mode .pagination-info,
[data-bs-theme="dark"] .pagination-info {
    color: var(--color-text-secondary-dark, #adb5bd);
}

.bg-gray-900 .pagination-ellipsis,
.dark-mode .pagination-ellipsis,
[data-bs-theme="dark"] .pagination-ellipsis {
    color: var(--color-text-secondary-dark, #adb5bd);
}

/* Responsive adjustments */
@media (max-width: 576px) {
    .pagination-container {
        flex-direction: column;
        align-items: center;
    }
    
    .pagination-info {
        text-align: center;
        margin-bottom: 0.5rem;
    }
    
    .pagination-btn {
        min-width: 32px;
        height: 32px;
        font-size: 0.875rem;
    }
}
</style>
