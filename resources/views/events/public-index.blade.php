<x-app-layout>
    <x-front-navbar />
    
    <div class="container py-5">

        <!-- Real-time Search and Filter Bar -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-xs border">
                    <div class="card-body">
                        <form method="GET" action="{{ route('events.public') }}" id="filter-form">
                            <div class="row g-3 align-items-end">
                                <!-- Search Input -->
                                <div class="col-md-3">
                                    <label class="form-label text-dark fw-bold">Recherche</label>
                                    <div class="input-group">
                                        <input type="text" 
                                               name="search" 
                                               class="form-control" 
                                               value="{{ request('search') }}"
                                               placeholder="Titre, catégorie..."
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
                                    <label class="form-label text-dark fw-bold">Lieu</label>
                                    <div class="input-group">
                                        <input type="text" 
                                               name="location" 
                                               class="form-control" 
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
                                    <label class="form-label text-dark fw-bold">Catégorie</label>
                                    <div class="input-group">
                                        <select name="category" class="form-control form-select" id="category-select">
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
                                    <label class="form-label text-dark fw-bold">Prix</label>
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <div class="input-group">
                                                <input type="number" 
                                                       name="min_price" 
                                                       class="form-control" 
                                                       value="{{ request('min_price') }}"
                                                       placeholder="Min" 
                                                       min="0"
                                                       id="min-price">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="input-group">
                                                <input type="number" 
                                                       name="max_price" 
                                                       class="form-control" 
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
                                    <label class="form-label text-dark fw-bold">Date</label>
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <input type="date" 
                                                   name="start_date" 
                                                   class="form-control" 
                                                   value="{{ request('start_date') }}"
                                                   id="start-date"
                                                   placeholder="Début">
                                        </div>
                                        <div class="col-6">
                                            <div class="input-group">
                                                <input type="date" 
                                                       name="end_date" 
                                                       class="form-control" 
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
                            <div class="row mt-3">
                                <div class="col-12">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div id="active-filters" class="d-flex flex-wrap gap-2 align-items-center">
                                            @if(request()->anyFilled(['search', 'location', 'category', 'min_price', 'max_price', 'start_date', 'end_date']))
                                                <small class="text-muted me-2">Filtres actifs:</small>
                                                
                                                @if(request('search'))
                                                <span class="badge bg-primary d-flex align-items-center">
                                                    Recherche: "{{ request('search') }}"
                                                    <button type="button" class="btn-close btn-close-white ms-2 clear-filter" data-filter="search" style="font-size: 0.7rem;"></button>
                                                </span>
                                                @endif

                                                @if(request('location'))
                                                <span class="badge bg-secondary d-flex align-items-center">
                                                    Lieu: "{{ request('location') }}"
                                                    <button type="button" class="btn-close btn-close-white ms-2 clear-filter" data-filter="location" style="font-size: 0.7rem;"></button>
                                                </span>
                                                @endif
                                                
                                                @if(request('category'))
                                                    @php
                                                        $selectedCategory = $categories->firstWhere('id', request('category'));
                                                    @endphp
                                                    @if($selectedCategory)
                                                    <span class="badge bg-info d-flex align-items-center">
                                                        Catégorie: {{ $selectedCategory->name }}
                                                        <button type="button" class="btn-close btn-close-white ms-2 clear-filter" data-filter="category" style="font-size: 0.7rem;"></button>
                                                    </span>
                                                    @endif
                                                @endif
                                                
                                                @if(request('min_price') || request('max_price'))
                                                <span class="badge bg-warning text-dark d-flex align-items-center">
                                                    Prix: 
                                                    ${{ request('min_price', 0) }} - ${{ request('max_price', '∞') }}
                                                    <button type="button" class="btn-close ms-2 clear-filter" data-filter="price" style="font-size: 0.7rem;"></button>
                                                </span>
                                                @endif
                                                
                                                @if(request('start_date') || request('end_date'))
                                                <span class="badge bg-success d-flex align-items-center">
                                                    Date: 
                                                    {{ request('start_date') ? \Carbon\Carbon::parse(request('start_date'))->format('d/m/Y') : '∞' }} 
                                                    - 
                                                    {{ request('end_date') ? \Carbon\Carbon::parse(request('end_date'))->format('d/m/Y') : '∞' }}
                                                    <button type="button" class="btn-close btn-close-white ms-2 clear-filter" data-filter="date" style="font-size: 0.7rem;"></button>
                                                </span>
                                                @endif
                                            @else
                                                <small class="text-muted">Aucun filtre actif</small>
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
                <div class="card shadow-xs border">
                    <div class="card-body py-3">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <span class="badge bg-primary rounded-pill">{{ $events->total() }}</span>
                                        <span class="ms-2 text-dark">événement(s) trouvé(s)</span>
                                    </div>
                                    <div class="vr mx-3"></div>
                                    <div class="text-muted">
                                        <small>Dernière mise à jour: {{ now()->format('d/m/Y') }}</small>
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
                    <div class="card event-card shadow-xs border-0 h-100 overflow-hidden">
                        <!-- Image Section with Overlay -->
                        <div class="card-image position-relative">
                            @if($event->images && count($event->images) > 0)
                                <img src="{{ str_starts_with($event->images[0], 'http') ? $event->images[0] : asset('storage/' . $event->images[0]) }}" 
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
                            <h3 class="text-muted mb-3">
                                @if(request()->anyFilled(['search', 'location', 'category', 'min_price', 'max_price', 'start_date', 'end_date']))
                                    Aucun événement trouvé
                                @else
                                    Aucun événement à venir
                                @endif
                            </h3>
                            <p class="text-muted mb-4">
                                @if(request()->anyFilled(['search', 'location', 'category', 'min_price', 'max_price', 'start_date', 'end_date']))
                                    Aucun résultat pour vos critères de recherche. Essayez de modifier vos filtres.
                                @else
                                    Nous préparons de nouvelles expériences passionnantes pour vous.
                                @endif
                            </p>
                            <div class="d-flex justify-content-center gap-3">
                                @if(request()->anyFilled(['search', 'location', 'category', 'min_price', 'max_price', 'start_date', 'end_date']))
                                    <button type="button" class="btn btn-dark" id="clear-all-empty">
                                        <i class="fas fa-times me-2"></i>Effacer les filtres
                                    </button>
                                @endif
                                <a href="/" class="btn btn-outline-dark">
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
                    <div class="card shadow-xs border-0">
                        <div class="card-body py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-muted">
                                    <small>
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

        .form-select, .form-control {
            border: 1px solid #d2d6da;
            border-radius: 8px;
        }

        #events-container {
            transition: opacity 0.3s ease;
        }

        .loading {
            opacity: 0.6;
            pointer-events: none;
        }

        .clear-filter {
            border-left: none;
            transition: all 0.2s ease;
        }

        .clear-filter:hover {
            background-color: #dc3545;
            color: white;
        }

        .input-group .form-control {
            border-right: none;
        }

        .input-group .clear-filter {
            border-left: 1px solid #d2d6da;
        }
        
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filterForm = document.getElementById('filter-form');
            const eventsContainer = document.getElementById('events-container');
            let debounceTimer;

            // Get all filter inputs
            const filterInputs = [
                document.getElementById('search-input'),
                document.getElementById('location-input'),
                document.getElementById('category-select'),
                document.getElementById('min-price'),
                document.getElementById('max-price'),
                document.getElementById('start-date'),
                document.getElementById('end-date')
            ];

            // Add event listeners for real-time filtering
            filterInputs.forEach(input => {
                if (input) {
                    if (input.type === 'text' || input.type === 'number' || input.type === 'date') {
                        input.addEventListener('input', handleFilterChange);
                    } else {
                        input.addEventListener('change', handleFilterChange);
                    }
                }
            });

            // Clear individual filter buttons
            document.querySelectorAll('.clear-filter').forEach(button => {
                button.addEventListener('click', function() {
                    const filterType = this.dataset.filter;
                    clearFilter(filterType);
                });
            });

            // Clear all filters button
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
                // Clear all filter inputs
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
                
                // Show loading state
                eventsContainer.classList.add('loading');
                
                // Debounce to avoid too many requests
                debounceTimer = setTimeout(() => {
                    submitForm();
                }, 500); // 500ms delay
            }

            function submitForm() {
                const formData = new FormData(filterForm);
                const params = new URLSearchParams(formData);
                
                // Use Fetch API to get updated results
                fetch(`${filterForm.action}?${params}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.text())
                .then(html => {
                    // Parse the response and update the events container
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newEventsContainer = doc.getElementById('events-container');
                    
                    if (newEventsContainer) {
                        eventsContainer.innerHTML = newEventsContainer.innerHTML;
                        
                        // Update active filters display
                        const newActiveFilters = doc.getElementById('active-filters');
                        if (newActiveFilters) {
                            document.getElementById('active-filters').innerHTML = newActiveFilters.innerHTML;
                        }
                    }
                    
                    // Update URL without page reload
                    const newUrl = `${filterForm.action}?${params}`;
                    window.history.pushState({}, '', newUrl);
                    
                    // Remove loading state
                    eventsContainer.classList.remove('loading');
                    
                    // Re-attach event listeners to new clear buttons
                    attachClearButtonListeners();
                })
                .catch(error => {
                    console.error('Error:', error);
                    eventsContainer.classList.remove('loading');
                    // Fallback to traditional form submission if AJAX fails
                    filterForm.submit();
                });
            }

            function attachClearButtonListeners() {
                // Re-attach event listeners to new clear buttons
                document.querySelectorAll('.clear-filter').forEach(button => {
                    button.addEventListener('click', function() {
                        const filterType = this.dataset.filter;
                        clearFilter(filterType);
                    });
                });

                // Re-attach clear all button
                document.getElementById('clear-all-filters')?.addEventListener('click', clearAllFilters);
                document.getElementById('clear-all-empty')?.addEventListener('click', clearAllFilters);
            }

            // Handle browser back/forward buttons
            window.addEventListener('popstate', function() {
                location.reload();
            });

            // Initial attachment of event listeners
            attachClearButtonListeners();
        });
    </script>
</x-app-layout>