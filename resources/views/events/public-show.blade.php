<x-app-layout>
    <x-front-navbar />
    
    <div class="container py-5">
        <!-- Success/Info/Error Messages -->
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-check-circle me-3 fa-lg"></i>
                <div class="flex-grow-1">
                    <h6 class="mb-1">Succès !</h6>
                    <p class="mb-0">{{ session('success') }}</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-exclamation-circle me-3 fa-lg"></i>
                <div class="flex-grow-1">
                    <h6 class="mb-1">Attention</h6>
                    <p class="mb-0">{{ session('error') }}</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
        @endif

        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                <!-- Breadcrumb -->
                <nav aria-label="breadcrumb" class="mb-4">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/" class="text-decoration-none"><i class="fas fa-home me-1"></i>Accueil</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('events.public') }}" class="text-decoration-none">Événements</a></li>
                        <li class="breadcrumb-item active text-dark">{{ Str::limit($event->title, 25) }}</li>
                    </ol>
                </nav>

                <!-- Event Images Gallery -->
                @if($event->images && count($event->images) > 0)
                <div class="card shadow-sm border-0 mb-4 overflow-hidden">
                    <div id="eventCarousel" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner rounded-3">
                            @foreach($event->images as $index => $image)
                            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                <img src="{{ asset('storage/' . $image) }}" 
                                     class="d-block w-100" 
                                     alt="Event image {{ $index + 1 }}" 
                                     style="height: 450px; object-fit: cover;">
                            </div>
                            @endforeach
                        </div>
                        @if(count($event->images) > 1)
                        <button class="carousel-control-prev" type="button" data-bs-target="#eventCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon bg-dark rounded-circle p-3" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#eventCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon bg-dark rounded-circle p-3" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                        @endif
                    </div>
                    
                    <!-- Image Counter -->
                    @if(count($event->images) > 1)
                    <div class="position-absolute bottom-3 end-3">
                        <span class="badge bg-dark bg-opacity-75 text-white px-3 py-2">
                            <i class="fas fa-camera me-1"></i>1/{{ count($event->images) }}
                        </span>
                    </div>
                    @endif
                </div>
                @else
                <!-- Placeholder Image -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body text-center py-5 bg-gradient-primary text-white rounded-3">
                        <i class="fas fa-calendar-alt fa-5x mb-4 opacity-50"></i>
                        <h3 class="mb-3">Image de l'événement</h3>
                        <p class="mb-0 opacity-75">Aucune image disponible pour cet événement</p>
                    </div>
                </div>
                @endif

                <!-- Event Header -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h1 class="h2 font-weight-bold text-dark mb-2">{{ $event->title }}</h1>
                                <div class="d-flex align-items-center flex-wrap gap-2">
                                    @php
                                        $statusColors = [
                                            'UPCOMING' => 'bg-gradient-warning',
                                            'ONGOING' => 'bg-gradient-success',
                                            'COMPLETED' => 'bg-gradient-info',
                                            'CANCELLED' => 'bg-gradient-danger'
                                        ];
                                    @endphp
                                    <span class="badge {{ $statusColors[$event->status->value] ?? 'bg-gradient-secondary' }} text-sm px-3 py-2">
                                        {{ $event->status->value }}
                                    </span>
                                    <span class="badge bg-gradient-primary text-sm px-3 py-2">
                                        {{ $event->category->name }}
                                    </span>
                                    <span class="badge bg-dark text-sm px-3 py-2">
                                        <i class="fas fa-eye me-1"></i>{{ $event->registrations->count() }} participants
                                    </span>
                                </div>
                            </div>
                            <div class="text-end">
                                <h3 class="text-primary mb-0">
                                    @if($event->price > 0)
                                        ${{ number_format($event->price, 2) }}
                                    @else
                                        <span class="badge bg-success px-3 py-2">GRATUIT</span>
                                    @endif
                                </h3>
                            </div>
                        </div>
                        
                        <p class="lead text-muted mb-0">{{ $event->description }}</p>
                    </div>
                </div>

                <!-- Event Details -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-transparent border-0 py-3">
                        <h4 class="mb-0 text-dark">
                            <i class="fas fa-info-circle text-primary me-2"></i>
                            Détails de l'événement
                        </h4>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center p-3 bg-light rounded-3">
                                    <div class="feature-icon bg-primary text-white rounded-circle p-3 me-3">
                                        <i class="fas fa-calendar-alt fa-lg"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">DATE DE DÉBUT</small>
                                        <p class="mb-0 font-weight-bold text-dark">{{ $event->start_date->format('l, d F Y') }}</p>
                                        <small class="text-muted">{{ $event->start_date->format('H:i') }}</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center p-3 bg-light rounded-3">
                                    <div class="feature-icon bg-success text-white rounded-circle p-3 me-3">
                                        <i class="fas fa-clock fa-lg"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">DATE DE FIN</small>
                                        <p class="mb-0 font-weight-bold text-dark">{{ $event->end_date->format('l, d F Y') }}</p>
                                        <small class="text-muted">{{ $event->end_date->format('H:i') }}</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center p-3 bg-light rounded-3">
                                    <div class="feature-icon bg-info text-white rounded-circle p-3 me-3">
                                        <i class="fas fa-map-marker-alt fa-lg"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">LIEU</small>
                                        <p class="mb-0 font-weight-bold text-dark">{{ $event->location }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center p-3 bg-light rounded-3">
                                    <div class="feature-icon bg-warning text-white rounded-circle p-3 me-3">
                                        <i class="fas fa-users fa-lg"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">CAPACITÉ</small>
                                        <p class="mb-0 font-weight-bold text-dark">{{ $event->registrations->count() }}/{{ $event->capacity_max }} participants</p>
                                        <div class="progress mt-2" style="height: 6px;">
                                            @php
                                                $progress = $event->capacity_max > 0 ? ($event->registrations->count() / $event->capacity_max) * 100 : 0;
                                            @endphp
                                            <div class="progress-bar bg-warning" style="width: {{ $progress }}%"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Partners Section -->
                @if($event->partners && $event->partners->count() > 0)
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-gradient-primary text-white border-0 py-3 rounded-top">
                        <h4 class="mb-0">
                            <i class="fas fa-handshake me-2"></i>Nos Partenaires
                        </h4>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            @foreach($event->partners as $partner)
                            <div class="col-md-6">
                                <div class="d-flex align-items-center p-3 border rounded-3 h-100">
                                    <div class="partner-logo bg-gradient-primary text-white rounded-3 p-3 me-3">
                                        <i class="fas fa-building fa-2x"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1 font-weight-bold text-dark">{{ $partner->nom }}</h6>
                                        <p class="text-sm text-muted mb-1">
                                            <i class="fas fa-tag me-1"></i>{{ ucfirst($partner->type) }}
                                        </p>
                                        @if($partner->contact_email)
                                        <p class="text-sm text-muted mb-0">
                                            <i class="fas fa-envelope me-1"></i>{{ $partner->contact_email }}
                                        </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                <!-- Sponsoring Section -->
                @if($event->sponsorings && $event->sponsorings->count() > 0)
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-gradient-warning text-white border-0 py-3 rounded-top">
                        <h4 class="mb-0">
                            <i class="fas fa-donate me-2"></i>Sponsoring & Support
                        </h4>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            @foreach($event->sponsorings as $sponsoring)
                            <div class="col-12">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body p-4">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="d-flex align-items-center">
                                                <div class="sponsor-icon me-4">
                                                    @if($sponsoring->type_sponsoring->value === 'argent')
                                                        <i class="fas fa-coins fa-3x text-success"></i>
                                                    @elseif($sponsoring->type_sponsoring->value === 'materiel')
                                                        <i class="fas fa-box fa-3x text-info"></i>
                                                    @elseif($sponsoring->type_sponsoring->value === 'logistique')
                                                        <i class="fas fa-truck fa-3x text-warning"></i>
                                                    @else
                                                        <i class="fas fa-gift fa-3x text-primary"></i>
                                                    @endif
                                                </div>
                                                <div>
                                                    <h6 class="mb-1 font-weight-bold text-dark">
                                                        {{ $sponsoring->partner->nom }}
                                                    </h6>
                                                    <span class="badge bg-{{ $sponsoring->type_sponsoring->value === 'argent' ? 'success' : ($sponsoring->type_sponsoring->value === 'materiel' ? 'info' : 'warning') }} text-sm">
                                                        {{ $sponsoring->type_sponsoring->label() }}
                                                    </span>
                                                    @if($sponsoring->montant)
                                                    <p class="text-sm text-muted mb-0 mt-2">
                                                        <i class="fas fa-dollar-sign me-1"></i>Contribution: {{ number_format($sponsoring->montant, 2) }} DT
                                                    </p>
                                                    @endif
                                                    @if($sponsoring->description)
                                                    <p class="text-sm text-muted mb-0 mt-1">
                                                        {{ $sponsoring->description }}
                                                    </p>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="text-end">
                                                <small class="text-muted d-block">
                                                    <i class="fas fa-calendar-alt me-1"></i>
                                                    {{ $sponsoring->date->format('d/m/Y') }}
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Registration Card -->
                <div class="card shadow-lg border-0 sticky-top" style="top: 100px;">
                    <div class="card-header bg-dark text-white border-0 py-3 rounded-top">
                        <h5 class="mb-0 text-center">
                            <i class="fas fa-ticket-alt me-2"></i>Réserver ma place
                        </h5>
                    </div>
                    <div class="card-body p-4 text-center">
                        <div class="price-display mb-4">
                            <h2 class="text-primary mb-1">
                                @if($event->price > 0)
                                    ${{ number_format($event->price, 2) }}
                                @else
                                    <span class="text-success">GRATUIT</span>
                                @endif
                            </h2>
                            <small class="text-muted">par personne</small>
                        </div>
                        
                        @php
                            $isRegistered = auth()->check() && $event->registrations()->where('user_id', auth()->id())->exists();
                            $isFull = $event->registrations->count() >= $event->capacity_max;
                            $userRegistration = auth()->check() ? 
                                $event->registrations()
                                    ->where('user_id', auth()->id())
                                    ->whereIn('status', ['confirmed', 'attended'])
                                    ->first() : null;
                            $userFeedback = auth()->check() ? 
                                \App\Models\Feedback::where('id_evenement', $event->id)
                                    ->where('id_participant', auth()->id())
                                    ->first() : null;
                            $userRegistrationAny = auth()->check() ? $event->registrations()->where('user_id', auth()->id())->first() : null;
                        @endphp

                        <!-- Registration Actions -->
                        @if($event->status->value === 'UPCOMING')
                            @if($userRegistrationAny)
                                <a href="{{ route('registrations.show', $userRegistrationAny->id) }}" 
                                   class="btn btn-success btn-lg w-100 mb-3">
                                    <i class="fas fa-check-circle me-2"></i>Déjà Inscrit
                                </a>
                            @elseif($isFull)
                                <button class="btn btn-danger btn-lg w-100 mb-3" disabled>
                                    <i class="fas fa-times-circle me-2"></i>Complet
                                </button>
                            @else
                                <a href="{{ route('registrations.create', ['event_id' => $event->id]) }}" 
                                   class="btn btn-primary btn-lg w-100 mb-3 shadow">
                                    <i class="fas fa-ticket-alt me-2"></i>Réserver maintenant
                                </a>
                            @endif
                        @elseif($event->status->value === 'ONGOING')
                            <button class="btn btn-warning btn-lg w-100 mb-3" disabled>
                                <i class="fas fa-play-circle me-2"></i>Événement en cours
                            </button>
                        @else
                            <button class="btn btn-secondary btn-lg w-100 mb-3" disabled>
                                <i class="fas fa-ban me-2"></i>Événement terminé
                            </button>
                        @endif

                        <!-- Feedback Section -->
                        @auth
                            @if($userRegistration && !$userFeedback)
                                <div class="alert alert-info border-0 mb-3">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-star text-warning me-2"></i>
                                        <small class="font-weight-bold">Donnez votre avis !</small>
                                    </div>
                                </div>
                                <a href="{{ route('feedback.create', ['event_id' => $event->id]) }}" 
                                   class="btn btn-warning btn-lg w-100 mb-3">
                                    <i class="fas fa-star me-2"></i>Évaluer l'événement
                                </a>
                            @elseif($userFeedback)
                                <div class="alert alert-success border-0 mb-3">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="fas fa-check-circle me-2"></i>
                                        <small class="font-weight-bold">Avis donné</small>
                                    </div>
                                    <div class="text-center mb-2">
                                        @for ($i = 1; $i <= 5; $i++)
                                            @if ($i <= $userFeedback->note)
                                                <i class="fas fa-star text-warning"></i>
                                            @else
                                                <i class="far fa-star text-warning"></i>
                                            @endif
                                        @endfor
                                        <span class="ms-2 font-weight-bold">{{ $userFeedback->note }}/5</span>
                                    </div>
                                    <a href="{{ route('feedback.edit', $userFeedback->id_feedback) }}" 
                                       class="btn btn-outline-warning btn-sm w-100">
                                        <i class="fas fa-edit me-1"></i>Modifier
                                    </a>
                                </div>
                            @endif
                        @endauth

                        <!-- Event Info -->
                        <div class="event-info">
                            <div class="info-item d-flex justify-content-between align-items-center py-2 border-bottom">
                                <span class="text-muted"><i class="fas fa-hourglass-end me-2"></i>Date limite</span>
                                <span class="font-weight-bold text-dark">{{ $event->registration_deadline->format('d/m/Y') }}</span>
                            </div>
                            <div class="info-item d-flex justify-content-between align-items-center py-2 border-bottom">
                                <span class="text-muted"><i class="fas fa-tag me-2"></i>Catégorie</span>
                                <span class="badge bg-primary">{{ $event->category->name }}</span>
                            </div>
                            <div class="info-item d-flex justify-content-between align-items-center py-2">
                                <span class="text-muted"><i class="fas fa-chart-line me-2"></i>Statut</span>
                                <span class="badge {{ $statusColors[$event->status->value] ?? 'bg-secondary' }}">
                                    {{ $event->status->value }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Share Event -->
                <div class="card shadow-sm border-0 mt-4">
                    <div class="card-header bg-light border-0 py-3">
                        <h6 class="mb-0"><i class="fas fa-share-alt me-2"></i>Partager</h6>
                    </div>
                    <div class="card-body p-3">
                        <div class="row g-2">
                            <div class="col-4">
                                <button class="btn btn-outline-primary w-100 py-2">
                                    <i class="fab fa-facebook-f"></i>
                                </button>
                            </div>
                            <div class="col-4">
                                <button class="btn btn-outline-info w-100 py-2">
                                    <i class="fab fa-twitter"></i>
                                </button>
                            </div>
                            <div class="col-4">
                                <button class="btn btn-outline-danger w-100 py-2">
                                    <i class="fab fa-instagram"></i>
                                </button>
                            </div>
                            <div class="col-4">
                                <button class="btn btn-outline-success w-100 py-2">
                                    <i class="fab fa-whatsapp"></i>
                                </button>
                            </div>
                            <div class="col-4">
                                <button class="btn btn-outline-dark w-100 py-2">
                                    <i class="fas fa-link"></i>
                                </button>
                            </div>
                            <div class="col-4">
                                <button class="btn btn-outline-secondary w-100 py-2">
                                    <i class="fas fa-envelope"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="card shadow-sm border-0 mt-4">
                    <div class="card-body p-3">
                        <div class="d-grid gap-2">
                            <a href="{{ route('events.public') }}" class="btn btn-outline-dark">
                                <i class="fas fa-arrow-left me-2"></i>Retour aux événements
                            </a>
                            <button class="btn btn-outline-primary">
                                <i class="fas fa-heart me-2"></i>Ajouter aux favoris
                            </button>
                            <button class="btn btn-outline-info">
                                <i class="fas fa-calendar-plus me-2"></i>Ajouter à mon calendrier
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .feature-icon {
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .partner-logo {
            width: 70px;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .sponsor-icon {
            min-width: 80px;
        }
        
        .info-item:last-child {
            border-bottom: none !important;
        }
        
        .carousel-control-prev-icon,
        .carousel-control-next-icon {
            background-size: 1rem;
        }
        
        .price-display {
            padding: 1rem;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 10px;
        }
        
        .sticky-top {
            z-index: 1020;
        }
    </style>
</x-app-layout>