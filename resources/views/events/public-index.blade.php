<x-app-layout>
    <x-front-navbar />
    
    <div class="container py-5">

        <!-- Search and Filter Bar -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-xs border">
                    <div class="card-body py-3">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <span class="badge bg-primary rounded-pill">{{ $events->total() }}</span>
                                        <span class="ms-2 text-dark">événement(s) disponible(s)</span>
                                    </div>
                                    <div class="vr mx-3"></div>
                                    <div class="text-muted">
                                        <small>Dernière mise à jour: {{ now()->format('d/m/Y') }}</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 text-end">
                                <div class="dropdown">
                                    <button class="btn btn-outline-dark dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        <i class="fas fa-sort me-2"></i>Trier par
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#"><i class="fas fa-calendar me-2"></i>Date croissante</a></li>
                                        <li><a class="dropdown-item" href="#"><i class="fas fa-calendar-alt me-2"></i>Date décroissante</a></li>
                                        <li><a class="dropdown-item" href="#"><i class="fas fa-dollar-sign me-2"></i>Prix croissant</a></li>
                                        <li><a class="dropdown-item" href="#"><i class="fas fa-dollar-sign me-2"></i>Prix décroissant</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Events Grid -->
        <div class="row">
            @forelse($events as $event)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card event-card shadow-xs border-0 h-100 overflow-hidden">
                    <!-- Image Section with Overlay -->
                    <div class="card-image position-relative">
                        @if($event->images && count($event->images) > 0)
                            <img src="{{ asset('storage/' . $event->images[0]) }}" 
                                 class="card-img-top" 
                                 alt="{{ $event->title }}" 
                                 style="height: 250px; object-fit: cover; transition: transform 0.3s ease;">
                        @else
                            <div class="card-img-top bg-gradient-dark d-flex align-items-center justify-content-center position-relative" 
                                 style="height: 250px;">
                                <div class="position-absolute top-0 start-0 w-100 h-100 opacity-20" 
                                     style="background: linear-gradient(45deg, #667eea 0%, #764ba2 100%);"></div>
                                <div class="position-relative z-1 text-center text-white">
                                    <i class="fas fa-calendar-alt fa-4x mb-3"></i>
                                    <h6 class="mb-0">{{ $event->title }}</h6>
                                </div>
                            </div>
                        @endif
                        
                        <!-- Status Badge Overlay -->
                        <div class="position-absolute top-3 start-3">
                            @php
                                $statusColors = [
                                    'UPCOMING' => 'bg-gradient-warning',
                                    'ONGOING' => 'bg-gradient-success',
                                    'COMPLETED' => 'bg-gradient-info',
                                    'CANCELLED' => 'bg-gradient-danger'
                                ];
                            @endphp
                            <span class="badge {{ $statusColors[$event->status->value] ?? 'bg-gradient-secondary' }} text-xs shadow-sm">
                                {{ $event->status->value }}
                            </span>
                        </div>
                        
                        <!-- Category Badge Overlay -->
                        <div class="position-absolute top-3 end-3">
                            <span class="badge bg-gradient-primary text-xs shadow-sm">
                                {{ $event->category->name }}
                            </span>
                        </div>
                        
                        <!-- Price Overlay -->
                        <div class="position-absolute bottom-3 end-3">
                            <span class="badge bg-dark text-white px-3 py-2 shadow">
                                @if($event->price > 0)
                                    ${{ number_format($event->price, 2) }}
                                @else
                                    GRATUIT
                                @endif
                            </span>
                        </div>
                    </div>
                    
                    <!-- Card Body -->
                    <div class="card-body d-flex flex-column p-4">
                        <h5 class="card-title font-weight-bold text-dark mb-2 line-clamp-2" style="min-height: 3rem;">
                            {{ $event->title }}
                        </h5>
                        
                        <p class="card-text text-muted mb-3 flex-grow-1 line-clamp-3" style="min-height: 4.5rem;">
                            {{ Str::limit($event->description, 120) }}
                        </p>
                        
                        <!-- Event Meta Information -->
                        <div class="event-meta mb-3">
                            <div class="d-flex align-items-center text-sm text-muted mb-2">
                                <i class="fas fa-calendar text-primary me-2"></i>
                                <span>{{ $event->start_date->format('d M Y') }}</span>
                                <span class="mx-2">•</span>
                                <i class="fas fa-clock text-primary me-2"></i>
                                <span>{{ $event->start_date->format('H:i') }}</span>
                            </div>
                            
                            <div class="d-flex align-items-center text-sm text-muted mb-2">
                                <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                <span class="flex-grow-1">{{ Str::limit($event->location, 25) }}</span>
                            </div>
                            
                            <div class="d-flex align-items-center text-sm text-muted">
                                <i class="fas fa-users text-primary me-2"></i>
                                <span>{{ $event->registrations->count() }}/{{ $event->capacity_max }} inscrits</span>
                                <div class="progress flex-grow-1 ms-2" style="height: 4px;">
                                    @php
                                        $progress = $event->capacity_max > 0 ? ($event->registrations->count() / $event->capacity_max) * 100 : 0;
                                    @endphp
                                    <div class="progress-bar bg-primary" style="width: {{ $progress }}%"></div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="mt-auto">
                            @php
                                $isRegistered = auth()->check() && $event->registrations()->where('user_id', auth()->id())->exists();
                                $isFull = $event->registrations->count() >= $event->capacity_max;
                            @endphp
                            
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="{{ route('events.public.show', $event->id) }}" 
                                   class="btn btn-outline-dark btn-sm flex-grow-1 me-2">
                                    <i class="fas fa-eye me-1"></i>Détails
                                </a>
                                
                                @if($event->status->value === 'UPCOMING' && !$isRegistered && !$isFull)
                                    <a href="{{ route('registrations.create', ['event_id' => $event->id]) }}" 
                                       class="btn btn-dark btn-sm px-3">
                                        <i class="fas fa-ticket-alt me-1"></i>Participer
                                    </a>
                                @elseif($isRegistered)
                                    <button class="btn btn-success btn-sm px-3" disabled>
                                        <i class="fas fa-check me-1"></i>Inscrit
                                    </button>
                                @elseif($isFull)
                                    <button class="btn btn-danger btn-sm px-3" disabled>
                                        <i class="fas fa-times me-1"></i>Complet
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <!-- Empty State -->
            <div class="col-12">
                <div class="card shadow-xs border-0">
                    <div class="card-body text-center py-5">
                        <div class="empty-state-icon mb-4">
                            <i class="fas fa-calendar-times text-muted fa-5x"></i>
                        </div>
                        <h3 class="text-muted mb-3">Aucun événement à venir</h3>
                        <p class="text-muted mb-4">Nous préparons de nouvelles expériences passionnantes pour vous.</p>
                        <div class="d-flex justify-content-center gap-3">
                            <a href="/" class="btn btn-dark">
                                <i class="fas fa-home me-2"></i>Retour à l'accueil
                            </a>
                            <button class="btn btn-outline-dark">
                                <i class="fas fa-bell me-2"></i>Me notifier
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($events->hasPages())
        <div class="row mt-5">
            <div class="col-12">
                <div class="card shadow-xs border-0">
                    <div class="card-body py-3">
                        <div class="d-flex justify-content-center">
                            {{ $events->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    <style>
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .event-card {
            transition: all 0.3s ease;
            border-radius: 12px;
        }
        
        .event-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 35px rgba(0,0,0,0.15) !important;
        }
        
        .event-card:hover .card-img-top {
            transform: scale(1.05);
        }
        
        .card-image {
            overflow: hidden;
        }
        
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .line-clamp-3 {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .empty-state-icon {
            opacity: 0.7;
        }
        
        .progress {
            background-color: #e9ecef;
            border-radius: 2px;
        }
        
    </style>
</x-app-layout>