<x-app-layout>
    <x-front-navbar />
    
    <div class="container py-5">
        <!-- Hero Section -->
        <div class="row">
            <div class="col-12 text-center py-5">
                <h1 class="display-4 fw-bold text-dark mb-3">Bienvenue sur EcoEvents 🎉</h1>
                <p class="lead text-muted mb-4">Découvrez et participez à des événements exceptionnels</p>
                <a href="{{ route('events.public') }}" class="btn btn-dark btn-lg">
                    <i class="fas fa-calendar me-2"></i>Voir tous les événements
                </a>
            </div>
        </div>

        <!-- Featured Events Section -->
        <div class="row mt-5">
            <div class="col-12">
                <h2 class="text-center mb-4">Événements à venir</h2>
                <p class="text-center text-muted mb-5">Découvrez nos prochains événements passionnants</p>
            </div>
            
            @php
                $featuredEvents = \App\Models\Event::with('category')
                    ->where('is_public', true)
                    ->where('status', '!=', \App\EventStatus::CANCELLED)
                    ->where('start_date', '>', now())
                    ->orderBy('start_date')
                    ->take(6)
                    ->get();
            @endphp
            
            @foreach($featuredEvents as $event)
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

        <!-- Features Section -->
        <div class="row mt-5 py-5">
            <div class="col-12 text-center mb-5">
                <h2>Pourquoi choisir EcoEvents ?</h2>
            </div>
            <div class="col-lg-4 col-md-6 text-center mb-4">
                <div class="icon icon-shape icon-lg bg-gradient-primary text-white rounded-circle shadow mb-3">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <h5>Événements variés</h5>
                <p class="text-muted">Découvrez une large gamme d'événements adaptés à tous les goûts</p>
            </div>
            <div class="col-lg-4 col-md-6 text-center mb-4">
                <div class="icon icon-shape icon-lg bg-gradient-success text-white rounded-circle shadow mb-3">
                    <i class="fas fa-ticket-alt"></i>
                </div>
                <h5>Réservation facile</h5>
                <p class="text-muted">Réservez vos places en quelques clics seulement</p>
            </div>
            <div class="col-lg-4 col-md-6 text-center mb-4">
                <div class="icon icon-shape icon-lg bg-gradient-info text-white rounded-circle shadow mb-3">
                    <i class="fas fa-users"></i>
                </div>
                <h5>Communauté active</h5>
                <p class="text-muted">Rejoignez une communauté passionnée par les événements</p>
            </div>
        </div>
    </div>

    <style>
        .hover-scale {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .hover-scale:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
        }
        .icon-shape {
            width: 80px;
            height: 80px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
    </style>
    
    @auth
        <x-eco-chatbot title="EcoAssistant" placeholder="Posez une question sur l'écologie..." />
    @endauth
</x-app-layout>