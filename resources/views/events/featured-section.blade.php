<!-- resources/views/components/events-section.blade.php -->
@php
    $featuredEvents = \App\Models\Event::with('category')
        ->where('is_public', true)
        ->where('status', '!=', \App\EventStatus::CANCELLED)
       <!-- ->where('start_date', '>', now())-->
        ->orderBy('start_date')
        ->take(6)
        ->get();
@endphp

<!-- Featured Events Section -->
<div class="row mt-5">
    <div class="col-12">
        <h2 class="text-center mb-4">Événements à venir</h2>
        <p class="text-center text-muted mb-5">Découvrez nos prochains événements passionnants</p>
    </div>
    
    @foreach($featuredEvents as $event)
    <div class="col-lg-4 col-md-6 mb-4">
        <div class="card shadow-xs border h-100 hover-scale">
            @if($event->images && count($event->images) > 0)
                <img src="{{ str_starts_with($event->images[0], 'http') ? $event->images[0] : asset('storage/' . $event->images[0]) }}" 
                    class="card-img-top" 
                    alt="{{ $event->title }}" 
                    style="height: 250px; object-fit: cover; transition: transform 0.3s ease;">
                               
            @else
                <div class="card-img-top bg-gradient-dark d-flex align-items-center justify-content-center" style="height: 200px;">
                    <i class="fas fa-calendar-alt text-white fa-3x"></i>
                </div>
            @endif
            
            <div class="card-body d-flex flex-column">
                <div class="mb-2">
                    @php
                        $statusColors = [
                            'UPCOMING' => 'bg-gradient-warning',
                            'ONGOING' => 'bg-gradient-success',
                            'COMPLETED' => 'bg-gradient-info',
                            'CANCELLED' => 'bg-gradient-danger'
                        ];
                    @endphp
                    <span class="badge {{ $statusColors[$event->status->value] ?? 'bg-gradient-secondary' }} text-xs">
                        {{ $event->status->value }}
                    </span>
                    <span class="badge bg-gradient-primary text-xs ms-1">
                        {{ $event->category->name }}
                    </span>
                </div>
                
                <h5 class="card-title font-weight-bold">{{ Str::limit($event->title, 50) }}</h5>
                <p class="card-text text-muted flex-grow-1">{{ Str::limit($event->description, 100) }}</p>
                
                <div class="mt-auto">
                    <div class="d-flex justify-content-between align-items-center text-sm text-muted mb-2">
                        <div>
                            <i class="fas fa-calendar me-1"></i>
                            {{ $event->start_date->format('M d, Y') }}
                        </div>
                        <div>
                            <i class="fas fa-clock me-1"></i>
                            {{ $event->start_date->format('H:i') }}
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center text-sm text-muted mb-3">
                        <div>
                            <i class="fas fa-map-marker-alt me-1"></i>
                            {{ Str::limit($event->location, 20) }}
                        </div>
                        <div class="fw-bold text-dark">
                            {{ number_format($event->price, 2) }} TND
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-xs text-muted">
                            <i class="fas fa-users me-1"></i>
                            {{ $event->registrations->count() }}/{{ $event->capacity_max }} inscrits
                        </span>
                        <a href="{{ route('events.public.show', $event->id) }}" class="btn btn-sm btn-dark">
                            Voir détails
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
    
    @if($featuredEvents->count() === 0)
    <div class="col-12 text-center py-5">
        <i class="fas fa-calendar-times text-muted fa-4x mb-3"></i>
        <h4 class="text-muted">Aucun événement à venir</h4>
        <p class="text-muted">Revenez plus tard pour découvrir nos prochains événements.</p>
    </div>
    @endif
    
    @if($featuredEvents->count() > 0)
    <div class="col-12 text-center mt-4">
        <a href="{{ route('events.public') }}" class="btn btn-outline-dark btn-lg">
            Voir tous les événements
        </a>
    </div>
    @endif
</div>

<style>
    .hover-scale {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .hover-scale:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
    }
</style>