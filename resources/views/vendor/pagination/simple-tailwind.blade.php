@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="simple-pagination-container">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="pagination-btn disabled">
                <span class="pagination-text">« Previous</span>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="pagination-btn">
                <span class="pagination-text">« Previous</span>
            </a>
        @endif

        <span class="pagination-info">
            Showing {{ $paginator->firstItem() }} to {{ $paginator->lastItem() }} of {{ $paginator->total() }} results
        </span>

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="pagination-btn">
                <span class="pagination-text">Next »</span>
            </a>
        @else
            <span class="pagination-btn disabled">
                <span class="pagination-text">Next »</span>
            </span>
        @endif
    </nav>
@endif

<style>
.simple-pagination-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin: 1rem 0;
    gap: 1rem;
}

.pagination-info {
    color: var(--color-text-secondary, #6c757d);
    font-size: 0.875rem;
}

.pagination-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0.5rem 1rem;
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
}

.pagination-btn.disabled {
    opacity: 0.5;
    cursor: not-allowed;
    pointer-events: none;
}

.pagination-text {
    font-size: 0.875rem;
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

/* Responsive adjustments */
@media (max-width: 576px) {
    .simple-pagination-container {
        flex-direction: column;
        align-items: center;
    }
    
    .pagination-info {
        text-align: center;
        margin: 0.5rem 0;
        order: -1;
    }
}
</style>
