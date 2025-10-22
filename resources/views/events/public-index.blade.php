<x-app-layout>
    <canvas id="fullScreenCanvas" class="fixed-canvas"></canvas>
    
    <x-front-navbar />
    
    <div class="container py-5 main-content-wrapper">

        <!-- Page Header -->
        <div class="row mb-5">
            <div class="col-12 text-center">
                <span class="badge bg-success-gradient text-uppercase py-2 px-3 mb-3 badge-pill">Événements Écologiques</span>
                <h1 class="display-5 fw-bold text-bright-white mb-3">Découvrez nos Événements</h1>
                <p class="lead text-muted">Participez à des actions concrètes pour l'environnement et rejoignez notre communauté engagée</p>
            </div>
        </div>

        <!-- Search and Filter Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-lg border-0 section-dark-bg">
                    <div class="card-header bg-transparent border-bottom py-4">
                        <h4 class="mb-0 text-bright-white fw-semibold">
                            <i class="fas fa-filter text-success me-2"></i>
                            Filtres et Recherche
                        </h4>
                    </div>
                    <div class="card-body">
                        <form method="GET" action="{{ route('events.public') }}" id="filter-form">
                            <div class="row g-4 align-items-end">
                                <!-- Search Input -->
                                <div class="col-md-3">
                                    <label class="form-label text-bright-white fw-semibold mb-2">
                                        <i class="fas fa-search me-1"></i>Recherche
                                    </label>
                                    <div class="input-group input-group-eco">
                                        <span class="input-group-text bg-dark-input border-end-0">
                                            <i class="fas fa-search text-muted"></i>
                                        </span>
                                        <input type="text" 
                                               name="search" 
                                               class="form-control bg-dark-input text-white border-start-0" 
                                               value="{{ request('search') }}"
                                               placeholder="Titre, description..."
                                               id="search-input">
                                        @if(request('search'))
                                        <button type="button" class="btn btn-outline-secondary clear-filter" data-filter="search">
                                            <i class="fas fa-times"></i>
                                        </button>
                                        @endif
                                    </div>
                                </div>

                                <!-- Location Filter -->
                                <div class="col-md-3">
                                    <label class="form-label text-bright-white fw-semibold mb-2">
                                        <i class="fas fa-map-marker-alt me-1"></i>Lieu
                                    </label>
                                    <div class="input-group input-group-eco">
                                        <span class="input-group-text bg-dark-input border-end-0">
                                            <i class="fas fa-location-dot text-muted"></i>
                                        </span>
                                        <input type="text" 
                                               name="location" 
                                               class="form-control bg-dark-input text-white border-start-0" 
                                               value="{{ request('location') }}"
                                               placeholder="Ville, adresse..."
                                               id="location-input">
                                        @if(request('location'))
                                        <button type="button" class="btn btn-outline-secondary clear-filter" data-filter="location">
                                            <i class="fas fa-times"></i>
                                        </button>
                                        @endif
                                    </div>
                                </div>
                                
                                <!-- Category Filter -->
                                <div class="col-md-2">
                                    <label class="form-label text-bright-white fw-semibold mb-2">
                                        <i class="fas fa-tag me-1"></i>Catégorie
                                    </label>
                                    <div class="input-group input-group-eco">
                                        <select name="category" class="form-control form-select bg-dark-input text-white" id="category-select">
                                            <option value="">Toutes les catégories</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}" 
                                                    {{ request('category') == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @if(request('category'))
                                        <button type="button" class="btn btn-outline-secondary clear-filter" data-filter="category">
                                            <i class="fas fa-times"></i>
                                        </button>
                                        @endif
                                    </div>
                                </div>

                                <!-- Price Range -->
                                <div class="col-md-2">
                                    <label class="form-label text-bright-white fw-semibold mb-2">
                                        <i class="fas fa-ticket me-1"></i>Prix
                                    </label>
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <div class="input-group input-group-eco">
                                                <input type="number" 
                                                       name="min_price" 
                                                       class="form-control bg-dark-input text-white text-center" 
                                                       value="{{ request('min_price') }}"
                                                       placeholder="Min" 
                                                       min="0"
                                                       id="min-price">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="input-group input-group-eco">
                                                <input type="number" 
                                                       name="max_price" 
                                                       class="form-control bg-dark-input text-white text-center" 
                                                       value="{{ request('max_price') }}"
                                                       placeholder="Max" 
                                                       min="0"
                                                       id="max-price">
                                                @if(request('min_price') || request('max_price'))
                                                <button type="button" class="btn btn-outline-secondary clear-filter" data-filter="price">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Date Range -->
                                <div class="col-md-2">
                                    <label class="form-label text-bright-white fw-semibold mb-2">
                                        <i class="fas fa-calendar me-1"></i>Date
                                    </label>
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <input type="date" 
                                                   name="start_date" 
                                                   class="form-control bg-dark-input text-white text-center" 
                                                   value="{{ request('start_date') }}"
                                                   id="start-date"
                                                   placeholder="Début">
                                        </div>
                                        <div class="col-6">
                                            <div class="input-group input-group-eco">
                                                <input type="date" 
                                                       name="end_date" 
                                                       class="form-control bg-dark-input text-white text-center" 
                                                       value="{{ request('end_date') }}"
                                                       id="end-date"
                                                       placeholder="Fin">
                                                @if(request('start_date') || request('end_date'))
                                                <button type="button" class="btn btn-outline-secondary clear-filter" data-filter="date">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Active Filters Display -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div id="active-filters" class="d-flex flex-wrap gap-2 align-items-center">
                                            @if(request()->anyFilled(['search', 'location', 'category', 'min_price', 'max_price', 'start_date', 'end_date']))
                                                <small class="text-success fw-semibold me-2">Filtres actifs:</small>
                                                
                                                @if(request('search'))
                                                <span class="badge bg-primary d-flex align-items-center py-2">
                                                    <i class="fas fa-search me-1"></i>"{{ request('search') }}"
                                                    <button type="button" class="btn-close btn-close-white ms-2 clear-filter" data-filter="search" style="font-size: 0.7rem;"></button>
                                                </span>
                                                @endif

                                                @if(request('location'))
                                                <span class="badge bg-secondary d-flex align-items-center py-2">
                                                    <i class="fas fa-map-marker-alt me-1"></i>"{{ request('location') }}"
                                                    <button type="button" class="btn-close btn-close-white ms-2 clear-filter" data-filter="location" style="font-size: 0.7rem;"></button>
                                                </span>
                                                @endif
                                                
                                                @if(request('category'))
                                                    @php
                                                        $selectedCategory = $categories->firstWhere('id', request('category'));
                                                    @endphp
                                                    @if($selectedCategory)
                                                    <span class="badge bg-info d-flex align-items-center py-2">
                                                        <i class="fas fa-tag me-1"></i>{{ $selectedCategory->name }}
                                                        <button type="button" class="btn-close btn-close-white ms-2 clear-filter" data-filter="category" style="font-size: 0.7rem;"></button>
                                                    </span>
                                                    @endif
                                                @endif
                                                
                                                @if(request('min_price') || request('max_price'))
                                                <span class="badge bg-warning text-dark d-flex align-items-center py-2">
                                                    <i class="fas fa-dollar-sign me-1"></i>
                                                    {{ request('min_price', 0) }} - {{ request('max_price', '∞') }}
                                                    <button type="button" class="btn-close ms-2 clear-filter" data-filter="price" style="font-size: 0.7rem;"></button>
                                                </span>
                                                @endif
                                                
                                                @if(request('start_date') || request('end_date'))
                                                <span class="badge bg-success d-flex align-items-center py-2">
                                                    <i class="fas fa-calendar me-1"></i>
                                                    {{ request('start_date') ? \Carbon\Carbon::parse(request('start_date'))->format('d/m/Y') : '∞' }} 
                                                    - 
                                                    {{ request('end_date') ? \Carbon\Carbon::parse(request('end_date'))->format('d/m/Y') : '∞' }}
                                                    <button type="button" class="btn-close btn-close-white ms-2 clear-filter" data-filter="date" style="font-size: 0.7rem;"></button>
                                                </span>
                                                @endif
                                            @else
                                                <small class="text-muted">
                                                    <i class="fas fa-info-circle me-1"></i>Aucun filtre actif
                                                </small>
                                            @endif
                                        </div>
                                        <div>
                                            @if(request()->anyFilled(['search', 'location', 'category', 'min_price', 'max_price', 'start_date', 'end_date']))
                                                <button type="button" class="btn btn-outline-danger btn-sm" id="clear-all-filters">
                                                    <i class="fas fa-broom me-1"></i>Tout effacer
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Results Summary -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-lg border-0 section-dark-bg">
                    <div class="card-body py-3">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <span class="badge bg-success-gradient rounded-pill fs-6 px-3 py-2">{{ $events->total() }}</span>
                                        <span class="ms-2 text-bright-white fw-semibold">événement(s) trouvé(s)</span>
                                    </div>
                                    <div class="vr mx-3 bg-light opacity-25"></div>
                                    <div class="text-muted">
                                        <small><i class="fas fa-sync-alt me-1"></i>Dernière mise à jour: {{ now()->format('d/m/Y à H:i') }}</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 text-end">
                                <div class="text-muted">
                                    <small>Page {{ $events->currentPage() }} sur {{ $events->lastPage() }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Events Grid -->
        <div id="events-container">
            <div class="row">
                @forelse($events as $event)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card event-card shadow-hover-3d border-0 h-100 overflow-hidden section-dark-bg">
                        <!-- Image Section with Overlay -->
                        <div class="card-image position-relative">
                            @if($event->images && count($event->images) > 0)
                                <img src="{{ str_starts_with($event->images[0], 'http') ? $event->images[0] : asset('storage/' . $event->images[0]) }}" 
                                    class="card-img-top card-img-eco" 
                                    alt="{{ $event->title }}" 
                                    style="height: 220px; object-fit: cover; transition: transform 0.3s ease;">
                            @else
                                <div class="card-img-top bg-gradient-success d-flex align-items-center justify-content-center card-img-eco" 
                                     style="height: 220px;">
                                    <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark opacity-50"></div>
                                    <div class="position-relative z-1 text-center text-white p-3">
                                        <i class="fas fa-leaf fa-4x mb-3 opacity-75"></i>
                                        <h6 class="mb-0 fw-semibold">{{ $event->title }}</h6>
                                    </div>
                                </div>
                            @endif
                            
                            <!-- Status Badge Overlay -->
                            <div class="position-absolute top-3 start-3">
                                @php
                                    $statusColors = [
                                        'UPCOMING' => 'bg-warning',
                                        'ONGOING' => 'bg-success',
                                        'COMPLETED' => 'bg-info',
                                        'CANCELLED' => 'bg-danger'
                                    ];
                                @endphp
                                <span class="badge {{ $statusColors[$event->status->value] ?? 'bg-secondary' }} text-xs shadow-sm px-2 py-1">
                                    <i class="fas fa-circle me-1 small"></i>{{ $event->status->value }}
                                </span>
                            </div>
                            
                            <!-- Category Badge Overlay -->
                            <div class="position-absolute top-3 end-3">
                                <span class="badge bg-primary text-xs shadow-sm px-2 py-1">
                                    <i class="fas fa-tag me-1"></i>{{ $event->category->name }}
                                </span>
                            </div>
                            
                            <!-- Price Overlay -->
                            <div class="position-absolute bottom-3 end-3">
                                <span class="badge bg-dark text-white px-3 py-2 shadow-sm fw-semibold">
                                    @if($event->price > 0)
                                        <i class="fas fa-ticket-alt me-1"></i>${{ number_format($event->price, 2) }}
                                    @else
                                        <i class="fas fa-gift me-1"></i>GRATUIT
                                    @endif
                                </span>
                            </div>
                        </div>
                        
                        <!-- Card Body -->
                        <div class="card-body d-flex flex-column p-4">
                            <h5 class="card-title fw-bold text-bright-white mb-3 line-clamp-2" style="min-height: 3rem; line-height: 1.4;">
                                {{ $event->title }}
                            </h5>
                            
                            <p class="card-text text-muted mb-4 flex-grow-1 line-clamp-3" style="min-height: 4.5rem; line-height: 1.6;">
                                {{ Str::limit($event->description, 120) }}
                            </p>
                            
                            <!-- Event Meta Information -->
                            <div class="event-meta mb-4">
                                <div class="d-flex align-items-center text-sm text-muted mb-3">
                                    <div class="d-flex align-items-center me-4">
                                        <i class="fas fa-calendar text-success me-2"></i>
                                        <span class="text-bright-white small">{{ $event->start_date->format('d M Y') }}</span>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-clock text-success me-2"></i>
                                        <span class="text-bright-white small">{{ $event->start_date->format('H:i') }}</span>
                                    </div>
                                </div>
                                
                                <div class="d-flex align-items-center text-sm text-muted mb-3">
                                    <i class="fas fa-map-marker-alt text-success me-2"></i>
                                    <span class="text-bright-white small flex-grow-1">{{ Str::limit($event->location, 25) }}</span>
                                </div>
                                
                                <div class="d-flex align-items-center text-sm text-muted">
                                    <i class="fas fa-users text-success me-2"></i>
                                    <span class="text-bright-white small me-3">{{ $event->registrations->count() }}/{{ $event->capacity_max }} inscrits</span>
                                    <div class="progress flex-grow-1" style="height: 6px;">
                                        <div class="progress-bar bg-warning" 
                                            style="--progress-width: {{ $event->capacity_max > 0 ? ($event->registrations->count() / $event->capacity_max) * 100 : 0 }}%; width: var(--progress-width);">
                                        </div>
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
                                       class="btn btn-outline-light btn-sm flex-grow-1 me-2 py-2">
                                        <i class="fas fa-eye me-1"></i>Voir détails
                                    </a>
                                    
                                    @auth
                                        @if($event->status->value === 'UPCOMING' && !$isRegistered && !$isFull)
                                            <a href="{{ route('registrations.create', ['event_id' => $event->id]) }}" 
                                               class="btn btn-success-gradient btn-sm px-3 py-2">
                                                <i class="fas fa-ticket-alt me-1"></i>Participer
                                            </a>
                                        @elseif($isRegistered)
                                            <button class="btn btn-success btn-sm px-3 py-2" disabled>
                                                <i class="fas fa-check me-1"></i>Inscrit
                                            </button>
                                        @elseif($isFull)
                                            <button class="btn btn-danger btn-sm px-3 py-2" disabled>
                                                <i class="fas fa-times me-1"></i>Complet
                                            </button>
                                        @endif
                                    @endauth
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <!-- Empty State -->
                <div class="col-12">
                    <div class="card shadow-lg border-0 section-dark-bg">
                        <div class="card-body text-center py-5">
                            <div class="empty-state-icon mb-4">
                                <i class="fas fa-calendar-times text-muted fa-5x"></i>
                            </div>
                            <h3 class="text-bright-white mb-3 fw-bold">
                                @if(request()->anyFilled(['search', 'location', 'category', 'min_price', 'max_price', 'start_date', 'end_date']))
                                    Aucun événement trouvé
                                @else
                                    Aucun événement à venir
                                @endif
                            </h3>
                            <p class="text-muted mb-4 fs-5">
                                @if(request()->anyFilled(['search', 'location', 'category', 'min_price', 'max_price', 'start_date', 'end_date']))
                                    Aucun résultat pour vos critères de recherche. Essayez de modifier vos filtres.
                                @else
                                    Nous préparons de nouvelles expériences passionnantes pour vous.
                                @endif
                            </p>
                            <div class="d-flex justify-content-center gap-3">
                                @if(request()->anyFilled(['search', 'location', 'category', 'min_price', 'max_price', 'start_date', 'end_date']))
                                    <button type="button" class="btn btn-success-gradient px-4 py-2" id="clear-all-empty">
                                        <i class="fas fa-times me-2"></i>Effacer les filtres
                                    </button>
                                @endif
                                <a href="/" class="btn btn-outline-light px-4 py-2">
                                    <i class="fas fa-home me-2"></i>Retour à l'accueil
                                </a>
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
                    <div class="card shadow-lg border-0 section-dark-bg">
                        <div class="card-body py-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-bright-white">
                                    <small class="fw-semibold">
                                        <i class="fas fa-list me-1"></i>
                                        Affichage de {{ $events->firstItem() }} à {{ $events->lastItem() }} 
                                        sur {{ $events->total() }} événement(s)
                                    </small>
                                </div>
                                <div>
                                    {{ $events->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <style>
        /* Professional Dark Theme Variables */
        :root {
            --color-success-dark: #388e3c;
            --color-success-bright: #c8e6c9;
            --color-info-bright: #b3e5fc;
            --color-dark-main-bg: #102027;
            --color-section-dark: #1a3038;
            --color-dark-navbar-bg: rgba(16, 32, 39, 0.95);
            --color-nav-link: #d4edda;
            --color-success-bright-nav: #81c784;
            --color-dark-input: #2c3e50;
            --color-border-light: rgba(255, 255, 255, 0.1);
        }

        /* Global Styles */
        .main-content-wrapper {
            margin-top: 100px;
        }

        .text-bright-white { 
            color: #fafafa !important; 
        }

        .text-muted {
            color: rgba(255, 255, 255, 0.6) !important;
        }

        /* Section Background */
        .section-dark-bg {
            background-color: var(--color-section-dark) !important;
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            border: 1px solid var(--color-border-light);
        }

        /* Dark Input Styling */
        .bg-dark-input {
            background-color: var(--color-dark-input) !important;
            border-color: #34495e !important;
            color: white !important;
        }

        .bg-dark-input::placeholder {
            color: #bdc3c7 !important;
        }

        .input-group-eco .input-group-text {
            background-color: var(--color-dark-input);
            border-color: #34495e;
            color: #bdc3c7;
        }

        /* Button Gradients */
        .btn-success-gradient {
            background: linear-gradient(135deg, #66bb6a 0%, #43a047 100%);
            border: none;
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-success-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(76, 175, 80, 0.4);
            color: white;
        }

        .bg-success-gradient {
            background: linear-gradient(135deg, #66bb6a 0%, #43a047 100%) !important;
        }

        /* Card Enhancements */
        .card-img-eco {
            height: 220px; 
            object-fit: cover;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
        }

        .shadow-hover-3d {
            transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        }

        .shadow-hover-3d:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 150, 0, 0.2), 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        /* Text Utilities */
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

        /* Badge Enhancements */
        .badge-pill {
            border-radius: 50rem;
        }

        /* Progress Bar */
        .progress {
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 4px;
            overflow: hidden;
        }

        .progress-bar {
            border-radius: 4px;
        }

        /* Canvas Background */
        .fixed-canvas {
            position: fixed;
            top: 0;
            left: 0;
            z-index: -2;
            width: 100vw;
            height: 100vh;
            background-color: var(--color-dark-main-bg);
        }

        /* Loading State */
        .loading {
            opacity: 0.6;
            pointer-events: none;
        }

        /* Clear Filter Buttons */
        .clear-filter {
            transition: all 0.2s ease;
        }

        .clear-filter:hover {
            background-color: #dc3545;
            color: white;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .main-content-wrapper {
                margin-top: 80px;
            }
            
            .card-body {
                padding: 1.5rem !important;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Particle Background
            const canvas = document.getElementById('fullScreenCanvas');
            if (!canvas) return;

            const ctx = canvas.getContext('2d');
            let width, height;
            let mouseX = 0, mouseY = 0;
            let particles = [];
            const particleCount = 80;
            const maxDistance = 100;

            function resizeCanvas() {
                width = window.innerWidth;
                height = window.innerHeight;
                canvas.width = width;
                canvas.height = height;
            }

            class Particle {
                constructor(x, y) {
                    this.x = x;
                    this.y = y;
                    this.size = Math.random() * 2 + 1;
                    this.speedX = Math.random() * 0.3 - 0.15;
                    this.speedY = Math.random() * 0.3 - 0.15;
                    this.color = `rgba(${Math.floor(Math.random() * 50)}, ${Math.floor(180 + Math.random() * 75)}, ${Math.floor(180 + Math.random() * 50)}, 0.6)`;
                }

                update() {
                    this.x += this.speedX;
                    this.y += this.speedY;

                    if (this.x > width || this.x < 0) this.speedX *= -1;
                    if (this.y > height || this.y < 0) this.speedY *= -1;
                }

                draw() {
                    ctx.fillStyle = this.color;
                    ctx.beginPath();
                    ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
                    ctx.fill();
                }
            }

            function init() {
                particles = [];
                for (let i = 0; i < particleCount; i++) {
                    const x = Math.random() * width;
                    const y = Math.random() * height;
                    particles.push(new Particle(x, y));
                }
            }

            function connectParticles() {
                for (let i = 0; i < particles.length; i++) {
                    for (let j = i; j < particles.length; j++) {
                        const dist = Math.sqrt(
                            Math.pow(particles[i].x - particles[j].x, 2) + 
                            Math.pow(particles[i].y - particles[j].y, 2)
                        );

                        if (dist < maxDistance) {
                            ctx.strokeStyle = `rgba(0, 150, 0, ${0.3 - dist / maxDistance})`;
                            ctx.lineWidth = 0.3;
                            ctx.beginPath();
                            ctx.moveTo(particles[i].x, particles[i].y);
                            ctx.lineTo(particles[j].x, particles[j].y);
                            ctx.stroke();
                        }
                    }
                }
            }

            function connectToMouse() {
                for (let i = 0; i < particles.length; i++) {
                    const dist = Math.sqrt(
                        Math.pow(particles[i].x - mouseX, 2) + 
                        Math.pow(particles[i].y - mouseY, 2)
                    );

                    if (dist < maxDistance + 30) {
                        ctx.strokeStyle = `rgba(150, 255, 150, ${0.5 - dist / (maxDistance + 30)})`;
                        ctx.lineWidth = 0.8;
                        ctx.beginPath();
                        ctx.moveTo(particles[i].x, particles[i].y);
                        ctx.lineTo(mouseX, mouseY);
                        ctx.stroke();
                    }
                }
            }

            function animate() {
                requestAnimationFrame(animate);
                ctx.fillStyle = 'rgba(10, 30, 40, 0.03)';
                ctx.fillRect(0, 0, width, height);

                connectParticles();
                connectToMouse();

                particles.forEach(particle => {
                    particle.update();
                    particle.draw();
                });
            }

            document.addEventListener('mousemove', (e) => {
                mouseX = e.clientX;
                mouseY = e.clientY;
            });
            
            window.addEventListener('resize', resizeCanvas);

            resizeCanvas();
            init();
            animate();

            // Filter Functionality
            const filterForm = document.getElementById('filter-form');
            const eventsContainer = document.getElementById('events-container');
            let debounceTimer;

            const filterInputs = [
                document.getElementById('search-input'),
                document.getElementById('location-input'),
                document.getElementById('category-select'),
                document.getElementById('min-price'),
                document.getElementById('max-price'),
                document.getElementById('start-date'),
                document.getElementById('end-date')
            ];

            filterInputs.forEach(input => {
                if (input) {
                    if (input.type === 'text' || input.type === 'number' || input.type === 'date') {
                        input.addEventListener('input', handleFilterChange);
                    } else {
                        input.addEventListener('change', handleFilterChange);
                    }
                }
            });

            document.querySelectorAll('.clear-filter').forEach(button => {
                button.addEventListener('click', function() {
                    const filterType = this.dataset.filter;
                    clearFilter(filterType);
                });
            });

            document.getElementById('clear-all-filters')?.addEventListener('click', clearAllFilters);
            document.getElementById('clear-all-empty')?.addEventListener('click', clearAllFilters);

            function clearFilter(filterType) {
                switch(filterType) {
                    case 'search':
                        document.getElementById('search-input').value = '';
                        break;
                    case 'location':
                        document.getElementById('location-input').value = '';
                        break;
                    case 'category':
                        document.getElementById('category-select').value = '';
                        break;
                    case 'price':
                        document.getElementById('min-price').value = '';
                        document.getElementById('max-price').value = '';
                        break;
                    case 'date':
                        document.getElementById('start-date').value = '';
                        document.getElementById('end-date').value = '';
                        break;
                }
                submitForm();
            }

            function clearAllFilters() {
                document.getElementById('search-input').value = '';
                document.getElementById('location-input').value = '';
                document.getElementById('category-select').value = '';
                document.getElementById('min-price').value = '';
                document.getElementById('max-price').value = '';
                document.getElementById('start-date').value = '';
                document.getElementById('end-date').value = '';
                
                submitForm();
            }

            function handleFilterChange() {
                clearTimeout(debounceTimer);
                eventsContainer.classList.add('loading');
                
                debounceTimer = setTimeout(() => {
                    submitForm();
                }, 500);
            }

            function submitForm() {
                const formData = new FormData(filterForm);
                const params = new URLSearchParams(formData);
                
                fetch(`${filterForm.action}?${params}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newEventsContainer = doc.getElementById('events-container');
                    
                    if (newEventsContainer) {
                        eventsContainer.innerHTML = newEventsContainer.innerHTML;
                        const newActiveFilters = doc.getElementById('active-filters');
                        if (newActiveFilters) {
                            document.getElementById('active-filters').innerHTML = newActiveFilters.innerHTML;
                        }
                    }
                    
                    const newUrl = `${filterForm.action}?${params}`;
                    window.history.pushState({}, '', newUrl);
                    eventsContainer.classList.remove('loading');
                    attachClearButtonListeners();
                })
                .catch(error => {
                    console.error('Error:', error);
                    eventsContainer.classList.remove('loading');
                    filterForm.submit();
                });
            }

            function attachClearButtonListeners() {
                document.querySelectorAll('.clear-filter').forEach(button => {
                    button.addEventListener('click', function() {
                        const filterType = this.dataset.filter;
                        clearFilter(filterType);
                    });
                });

                document.getElementById('clear-all-filters')?.addEventListener('click', clearAllFilters);
                document.getElementById('clear-all-empty')?.addEventListener('click', clearAllFilters);
            }

            window.addEventListener('popstate', function() {
                location.reload();
            });

            attachClearButtonListeners();
        });
    </script>
</x-app-layout>