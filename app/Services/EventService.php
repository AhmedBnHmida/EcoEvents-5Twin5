{{-- resources/views/components/events/featured-section.blade.php --}}
@props([
    'title' => 'Featured Events',
    'subtitle' => 'Discover our upcoming exciting events',
    'limit' => 6,
    'showViewAll' => true,
    'events' => null,
    'columns' => 'col-lg-4 col-md-6',
    'cardClass' => '',
])

@php
    use App\Services\EventService;
    
    $eventService = app(EventService::class);
    $featuredEvents = $events ?? $eventService->getFeaturedEvents($limit);
    
    // Column classes based on count
    $columnClasses = match(true) {
        $featuredEvents->count() === 1 => 'col-12 col-md-8 col-lg-6 mx-auto',
        $featuredEvents->count() === 2 => 'col-12 col-md-6',
        default => $columns
    };
@endphp

<section class="events-section {{ $attributes->get('class') }}" {{ $attributes->except('class') }}>
    <div class="container">
        <!-- Section Header -->
        <div class="row justify-content-center">
            <div class="col-12 col-md-10 col-lg-8 text-center">
                <h2 class="display-5 fw-bold text-dark mb-3">{{ $title }}</h2>
                @if($subtitle)
                    <p class="lead text-muted mb-4">{{ $subtitle }}</p>
                @endif
            </div>
        </div>

        <!-- Events Grid -->
        <div class="row g-4 justify-content-center">
            @forelse($featuredEvents as $event)
                <div class="{{ $columnClasses }}">
                    <x-events.card :event="$event" :class="$cardClass" />
                </div>
            @empty
                <!-- Empty State -->
                <div class="col-12 col-md-8 col-lg-6 text-center py-5">
                    <div class="empty-state">
                        <i class="fas fa-calendar-times text-muted fa-4x mb-4"></i>
                        <h4 class="text-muted mb-3">No Upcoming Events</h4>
                        <p class="text-muted mb-4">Check back later for our upcoming events schedule.</p>
                        @if($showViewAll)
                            <a href="{{ route('events.public') }}" class="btn btn-outline-primary">
                                Browse All Events
                            </a>
                        @endif
                    </div>
                </div>
            @endforelse
        </div>

        <!-- View All Button -->
        @if($showViewAll && $featuredEvents->count() > 0)
            <div class="row mt-4">
                <div class="col-12 text-center">
                    <a href="{{ route('events.public') }}" class="btn btn-outline-primary btn-lg">
                        View All Events
                        <i class="fas fa-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>
        @endif
    </div>
</section>

<style>
    .events-section {
        padding: 5rem 0;
    }
    
    .empty-state {
        padding: 3rem 2rem;
        border-radius: 1rem;
        background: var(--bs-light);
    }
</style>