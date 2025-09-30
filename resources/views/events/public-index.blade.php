<x-app-layout>
    <x-front-navbar />
    
    <div class="container py-5">
        <!-- Hero Section -->
        <div class="row mb-5">
            <div class="col-12 text-center">
                <h1 class="display-4 fw-bold text-dark mb-3">Tous nos Événements 🌟</h1>
                <p class="lead text-muted">Découvrez tous nos événements à venir</p>
            </div>
        </div>

        <!-- Events Count and Filter -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">{{ $events->total() }} événement(s) trouvé(s)</h5>
                    </div>
                    <div>
                        <select class="form-select form-select-sm" style="width: auto;">
                            <option>Trier par: Date</option>
                            <option>Trier par: Prix</option>
                            <option>Trier par: Popularité</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Events Grid -->
        <div class="row">
            @forelse($events as $event)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card shadow-xs border h-100 hover-scale">
                    @if($event->images && count($event->images) > 0)
                        <img src="{{ $event->images[0] }}" class="card-img-top" alt="{{ $event->title }}" style="height: 200px; object-fit: cover;">
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
                                    ${{ number_format($event->price, 2) }}
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-xs text-muted">
                                    <i class="fas fa-users me-1"></i>
                                    {{ $event->registrations->count() }}/{{ $event->capacity_max }} inscrits
                                </span>
                                <div class="btn-group">
                                    @php
                                        $isRegistered = auth()->check() && $event->registrations()->where('user_id', auth()->id())->exists();
                                        $isFull = $event->registrations->count() >= $event->capacity_max;
                                    @endphp
                                    
                                    @if($event->status->value === 'UPCOMING' && !$isRegistered && !$isFull)
                                        <a href="{{ route('registrations.create', ['event_id' => $event->id]) }}" class="btn btn-sm btn-dark">
                                            <i class="fas fa-ticket-alt me-1"></i>Participer
                                        </a>
                                    @elseif($isRegistered)
                                        <button class="btn btn-sm btn-success" disabled>
                                            <i class="fas fa-check me-1"></i>Inscrit
                                        </button>
                                    @endif
                                    <a href="{{ route('events.public.show', $event->id) }}" class="btn btn-sm btn-outline-dark">
                                        Détails
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5">
                <i class="fas fa-calendar-times text-muted fa-4x mb-3"></i>
                <h4 class="text-muted">Aucun événement à venir</h4>
                <p class="text-muted">Revenez plus tard pour découvrir nos prochains événements.</p>
                <a href="/" class="btn btn-dark mt-3">
                    <i class="fas fa-home me-2"></i>Retour à l'accueil
                </a>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($events->hasPages())
        <div class="row mt-5">
            <div class="col-12 d-flex justify-content-center">
                {{ $events->links() }}
            </div>
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
</x-app-layout>