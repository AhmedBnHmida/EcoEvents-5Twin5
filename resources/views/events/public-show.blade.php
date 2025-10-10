<x-app-layout>
    <x-front-navbar />
    
    <div class="container py-5">
        <!-- Success/Info/Error Messages -->
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 mb-4" role="alert">
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
        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 mb-4" role="alert">
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
                    <ol class="breadcrumb bg-light px-3 py-2 rounded-3">
                        <li class="breadcrumb-item">
                            <a href="/" class="text-decoration-none text-muted">
                                <i class="fas fa-home me-1"></i>Accueil
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('events.public') }}" class="text-decoration-none text-muted">Événements</a>
                        </li>
                        <li class="breadcrumb-item active text-dark fw-semibold">{{ Str::limit($event->title, 25) }}</li>
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
                            <span class="carousel-control-prev-icon bg-dark bg-opacity-50 rounded-circle p-2" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#eventCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon bg-dark bg-opacity-50 rounded-circle p-2" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                        @endif
                    </div>
                    
                    <!-- Image Counter -->
                    @if(count($event->images) > 1)
                    <div class="position-absolute bottom-3 end-3">
                        <span class="badge bg-dark bg-opacity-75 text-white px-3 py-2">
                            <i class="fas fa-camera me-1"></i><span id="currentSlide">1</span>/{{ count($event->images) }}
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
                    <div class="card-body p-5">
                        <div class="d-flex justify-content-between align-items-start mb-4">
                            <div class="flex-grow-1">
                                <h1 class="h2 fw-bold text-dark mb-3">{{ $event->title }}</h1>
                                <div class="d-flex align-items-center flex-wrap gap-2 mb-3">
                                    @php
                                        $statusColors = [
                                            'UPCOMING' => 'bg-gradient-warning',
                                            'ONGOING' => 'bg-gradient-success',
                                            'COMPLETED' => 'bg-gradient-info',
                                            'CANCELLED' => 'bg-gradient-danger'
                                        ];
                                    @endphp
                                    <span class="badge {{ $statusColors[$event->status->value] ?? 'bg-gradient-secondary' }} text-sm px-3 py-2">
                                        <i class="fas fa-circle me-1 small"></i>{{ $event->status->value }}
                                    </span>
                                    <span class="badge bg-gradient-primary text-sm px-3 py-2">
                                        <i class="fas fa-tag me-1"></i>{{ $event->category->name }}
                                    </span>
                                    <span class="badge bg-dark text-sm px-3 py-2">
                                        <i class="fas fa-users me-1"></i>{{ $event->registrations->count() }} participants
                                    </span>
                                </div>
                                <p class="lead text-muted mb-0">{{ $event->description }}</p>
                            </div>
                            <div class="text-end ms-4">
                                <div class="price-display bg-light rounded-3 p-3 text-center">
                                    <h3 class="text-primary mb-1 fw-bold">
                                        @if($event->price > 0)
                                            ${{ number_format($event->price, 2) }}
                                        @else
                                            <span class="text-success">GRATUIT</span>
                                        @endif
                                    </h3>
                                    <small class="text-muted">par personne</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Event Details -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-transparent border-bottom py-4">
                        <h4 class="mb-0 text-dark fw-semibold">
                            <i class="fas fa-info-circle text-primary me-2"></i>
                            Détails de l'événement
                        </h4>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center p-4 bg-light rounded-3 border">
                                    <div class="feature-icon bg-primary text-white rounded-3 p-3 me-4 shadow-sm">
                                        <i class="fas fa-calendar-alt fa-lg"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <small class="text-muted d-block fw-semibold text-uppercase small">DATE DE DÉBUT</small>
                                        <p class="mb-1 fw-bold text-dark h6">{{ $event->start_date->format('l, d F Y') }}</p>
                                        <small class="text-muted">
                                            <i class="fas fa-clock me-1"></i>{{ $event->start_date->format('H:i') }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center p-4 bg-light rounded-3 border">
                                    <div class="feature-icon bg-success text-white rounded-3 p-3 me-4 shadow-sm">
                                        <i class="fas fa-clock fa-lg"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <small class="text-muted d-block fw-semibold text-uppercase small">DATE DE FIN</small>
                                        <p class="mb-1 fw-bold text-dark h6">{{ $event->end_date->format('l, d F Y') }}</p>
                                        <small class="text-muted">
                                            <i class="fas fa-clock me-1"></i>{{ $event->end_date->format('H:i') }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center p-4 bg-light rounded-3 border">
                                    <div class="feature-icon bg-info text-white rounded-3 p-3 me-4 shadow-sm">
                                        <i class="fas fa-map-marker-alt fa-lg"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <small class="text-muted d-block fw-semibold text-uppercase small">LIEU</small>
                                        <p class="mb-0 fw-bold text-dark h6">{{ $event->location }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center p-4 bg-light rounded-3 border">
                                    <div class="feature-icon bg-warning text-white rounded-3 p-3 me-4 shadow-sm">
                                        <i class="fas fa-users fa-lg"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <small class="text-muted d-block fw-semibold text-uppercase small">CAPACITÉ</small>
                                        <p class="mb-2 fw-bold text-dark h6">{{ $event->registrations->count() }}/{{ $event->capacity_max }} participants</p>
                                        <div class="progress" style="height: 8px;">
                                            @php
                                                $progress = $event->capacity_max > 0 ? ($event->registrations->count() / $event->capacity_max) * 100 : 0;
                                            @endphp
                                            <div class="progress-bar bg-warning" style="width: {{ $progress }}%"></div>
                                        </div>
                                        <small class="text-muted mt-1 d-block">
                                            {{ $event->capacity_max - $event->registrations->count() }} places restantes
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Partners Section -->
                @if($event->partners && $event->partners->count() > 0)
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-gradient-primary text-white border-0 py-4">
                        <h4 class="mb-0 fw-semibold">
                            <i class="fas fa-handshake me-2"></i>Nos Partenaires
                        </h4>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            @foreach($event->partners as $partner)
                            <div class="col-md-6 col-lg-4">
                                <div class="card border h-100 transition-all">
                                    <div class="card-body text-center p-4">
                                        <div class="partner-logo bg-gradient-primary text-white rounded-3 p-4 mx-auto mb-3 shadow-sm">
                                            <i class="fas fa-building fa-2x"></i>
                                        </div>
                                        <h6 class="mb-2 fw-bold text-dark">{{ $partner->nom }}</h6>
                                        <span class="badge bg-secondary mb-2">{{ ucfirst($partner->type) }}</span>
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
                    <div class="card-header bg-gradient-warning text-white border-0 py-4">
                        <h4 class="mb-0 fw-semibold">
                            <i class="fas fa-donate me-2"></i>Sponsoring & Support
                        </h4>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            @foreach($event->sponsorings as $sponsoring)
                            <div class="col-12">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-body p-4">
                                        <div class="d-flex align-items-center">
                                            <div class="sponsor-icon me-4">
                                                @if($sponsoring->type_sponsoring->value === 'argent')
                                                    <div class="bg-success text-white rounded-3 p-3">
                                                        <i class="fas fa-coins fa-2x"></i>
                                                    </div>
                                                @elseif($sponsoring->type_sponsoring->value === 'materiel')
                                                    <div class="bg-info text-white rounded-3 p-3">
                                                        <i class="fas fa-box fa-2x"></i>
                                                    </div>
                                                @elseif($sponsoring->type_sponsoring->value === 'logistique')
                                                    <div class="bg-warning text-white rounded-3 p-3">
                                                        <i class="fas fa-truck fa-2x"></i>
                                                    </div>
                                                @else
                                                    <div class="bg-primary text-white rounded-3 p-3">
                                                        <i class="fas fa-gift fa-2x"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-2 fw-bold text-dark">{{ $sponsoring->partner->nom }}</h6>
                                                <span class="badge bg-{{ $sponsoring->type_sponsoring->value === 'argent' ? 'success' : ($sponsoring->type_sponsoring->value === 'materiel' ? 'info' : 'warning') }} text-sm mb-2">
                                                    {{ $sponsoring->type_sponsoring->label() }}
                                                </span>
                                                @if($sponsoring->montant)
                                                <p class="text-sm text-muted mb-2">
                                                    <i class="fas fa-dollar-sign me-1"></i>Contribution: {{ number_format($sponsoring->montant, 2) }} DT
                                                </p>
                                                @endif
                                                @if($sponsoring->description)
                                                <p class="text-sm text-muted mb-0">
                                                    {{ $sponsoring->description }}
                                                </p>
                                                @endif
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
                <!-- Enhanced Registration Card -->
                <div class="card shadow-lg border-0 mt-4" >
                    <div class="card-header bg-gradient-primary text-white border-0 py-4">
                        <h5 class="mb-0 text-center fw-semibold">
                            <i class="fas fa-ticket-alt me-2"></i>Réserver ma place
                        </h5>
                    </div>
                    <div class="card-body p-4">
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

                        <!-- Event Status & Registration -->
                        <div class="registration-status mb-4">
                            @if($event->status->value === 'UPCOMING')
                                @if($userRegistrationAny)
                                    <div class="alert alert-success border-0 text-center">
                                        <i class="fas fa-check-circle fa-2x mb-2 text-success"></i>
                                        <h6 class="fw-bold mb-1">Vous êtes inscrit !</h6>
                                        <p class="small mb-2">Votre participation est confirmée</p>
                                        <a href="{{ route('registrations.show', $userRegistrationAny->id) }}" 
                                           class="btn btn-outline-success btn-sm w-100">
                                            <i class="fas fa-eye me-1"></i>Voir ma réservation
                                        </a>
                                    </div>
                                @elseif($isFull)
                                    <div class="alert alert-danger border-0 text-center">
                                        <i class="fas fa-times-circle fa-2x mb-2 text-danger"></i>
                                        <h6 class="fw-bold mb-1">Complet</h6>
                                        <p class="small mb-0">Aucune place disponible</p>
                                    </div>
                                @else
                                    <div class="text-center mb-3">
                                        <h4 class="text-primary fw-bold mb-1">
                                            @if($event->price > 0)
                                                ${{ number_format($event->price, 2) }}
                                            @else
                                                <span class="text-success">GRATUIT</span>
                                            @endif
                                        </h4>
                                        <small class="text-muted">par personne</small>
                                    </div>
                                    <a href="{{ route('registrations.create', ['event_id' => $event->id]) }}" 
                                       class="btn btn-primary btn-lg w-100 shadow-sm mb-3 py-3">
                                        <i class="fas fa-ticket-alt me-2"></i>Réserver maintenant
                                    </a>
                                    <div class="text-center">
                                        <small class="text-muted">
                                            <i class="fas fa-shield-alt me-1"></i>Réservation sécurisée
                                        </small>
                                    </div>
                                @endif
                            @elseif($event->status->value === 'ONGOING')
                                <div class="alert alert-warning border-0 text-center">
                                    <i class="fas fa-play-circle fa-2x mb-2 text-warning"></i>
                                    <h6 class="fw-bold mb-1">Événement en cours</h6>
                                    <p class="small mb-0">Les inscriptions sont fermées</p>
                                </div>
                            @else
                                <div class="alert alert-secondary border-0 text-center">
                                    <i class="fas fa-ban fa-2x mb-2 text-secondary"></i>
                                    <h6 class="fw-bold mb-1">Événement terminé</h6>
                                    <p class="small mb-0">Merci à tous les participants</p>
                                </div>
                            @endif
                        </div>

                        <!-- Feedback Section -->
                        @auth
                            @if($userRegistration && !$userFeedback && $event->status->value === 'COMPLETED')
                                <div class="alert alert-info border-0 mb-3">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-star text-warning me-2"></i>
                                        <div>
                                            <h6 class="mb-1 fw-bold">Donnez votre avis !</h6>
                                            <p class="small mb-0">Partagez votre expérience</p>
                                        </div>
                                    </div>
                                    <a href="{{ route('feedback.create', ['event_id' => $event->id]) }}" 
                                       class="btn btn-warning w-100 mt-2">
                                        <i class="fas fa-star me-2"></i>Évaluer l'événement
                                    </a>
                                </div>
                            @elseif($userFeedback)
                                <div class="alert alert-success border-0">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="fas fa-check-circle me-2 text-success"></i>
                                        <h6 class="mb-0 fw-bold">Avis donné</h6>
                                    </div>
                                    <div class="text-center mb-2">
                                        @for ($i = 1; $i <= 5; $i++)
                                            @if ($i <= $userFeedback->note)
                                                <i class="fas fa-star text-warning"></i>
                                            @else
                                                <i class="far fa-star text-warning"></i>
                                            @endif
                                        @endfor
                                        <span class="ms-2 fw-bold">{{ $userFeedback->note }}/5</span>
                                    </div>
                                    <a href="{{ route('feedback.edit', $userFeedback->id_feedback) }}" 
                                       class="btn btn-outline-warning btn-sm w-100">
                                        <i class="fas fa-edit me-1"></i>Modifier mon avis
                                    </a>
                                </div>
                            @endif
                        @endauth

                        <!-- Event Quick Info -->
                        <div class="event-quick-info border-top pt-4">
                            <h6 class="fw-semibold mb-3 text-dark">
                                <i class="fas fa-info-circle me-2 text-primary"></i>Informations
                            </h6>
                            <div class="space-y-3">
                                <div class="d-flex justify-content-between align-items-center py-2">
                                    <span class="text-muted small">
                                        <i class="fas fa-hourglass-end me-2"></i>Date limite
                                    </span>
                                    <span class="fw-semibold text-dark">{{ $event->registration_deadline->format('d/m/Y') }}</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center py-2">
                                    <span class="text-muted small">
                                        <i class="fas fa-tag me-2"></i>Catégorie
                                    </span>
                                    <span class="badge bg-primary">{{ $event->category->name }}</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center py-2">
                                    <span class="text-muted small">
                                        <i class="fas fa-chart-line me-2"></i>Statut
                                    </span>
                                    <span class="badge {{ $statusColors[$event->status->value] ?? 'bg-secondary' }}">
                                        {{ $event->status->value }}
                                    </span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center py-2">
                                    <span class="text-muted small">
                                        <i class="fas fa-users me-2"></i>Participants
                                    </span>
                                    <span class="fw-semibold text-dark">{{ $event->registrations->count() }}/{{ $event->capacity_max }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Share Event -->
                <div class="card shadow-sm border-0 mt-4">
                    <div class="card-header bg-light border-0 py-3">
                        <h6 class="mb-0 fw-semibold">
                            <i class="fas fa-share-alt me-2 text-primary"></i>Partager l'événement
                        </h6>
                    </div>
                    <div class="card-body p-3">
                        <div class="row g-2">
                            <div class="col-4">
                                <button class="btn btn-outline-primary w-100 py-2 rounded-2">
                                    <i class="fab fa-facebook-f"></i>
                                </button>
                            </div>
                            <div class="col-4">
                                <button class="btn btn-outline-info w-100 py-2 rounded-2">
                                    <i class="fab fa-twitter"></i>
                                </button>
                            </div>
                            <div class="col-4">
                                <button class="btn btn-outline-danger w-100 py-2 rounded-2">
                                    <i class="fab fa-instagram"></i>
                                </button>
                            </div>
                            <div class="col-4">
                                <button class="btn btn-outline-success w-100 py-2 rounded-2">
                                    <i class="fab fa-whatsapp"></i>
                                </button>
                            </div>
                            <div class="col-4">
                                <button class="btn btn-outline-dark w-100 py-2 rounded-2" onclick="copyEventLink()">
                                    <i class="fas fa-link"></i>
                                </button>
                            </div>
                            <div class="col-4">
                                <button class="btn btn-outline-secondary w-100 py-2 rounded-2">
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
                            <a href="{{ route('events.public') }}" class="btn btn-outline-dark rounded-2 py-2">
                                <i class="fas fa-arrow-left me-2"></i>Retour aux événements
                            </a>
                            <button class="btn btn-outline-primary rounded-2 py-2">
                                <i class="fas fa-heart me-2"></i>Ajouter aux favoris
                            </button>
                            <button class="btn btn-outline-info rounded-2 py-2">
                                <i class="fas fa-calendar-plus me-2"></i>Ajouter au calendrier
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
            width: 80px;
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .sponsor-icon {
            min-width: 80px;
        }
        
        .carousel-control-prev-icon,
        .carousel-control-next-icon {
            background-size: 1rem;
        }
        
        .price-display {
            min-width: 120px;
        }
        
        .sticky-top {
            z-index: 1020;
        }
        
        .transition-all {
            transition: all 0.3s ease;
        }
        
        .transition-all:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        
        .space-y-3 > * + * {
            margin-top: 0.75rem;
        }
        
        .rounded-2 {
            border-radius: 0.75rem !important;
        }
    </style>

    <script>
        // Carousel slide counter
        document.addEventListener('DOMContentLoaded', function() {
            const carousel = document.getElementById('eventCarousel');
            if (carousel) {
                carousel.addEventListener('slid.bs.carousel', function (e) {
                    const activeIndex = e.to;
                    document.getElementById('currentSlide').textContent = activeIndex + 1;
                });
            }
        });

        // Copy event link to clipboard
        function copyEventLink() {
            const url = window.location.href;
            navigator.clipboard.writeText(url).then(function() {
                // Show success message (you can add a toast here)
                console.log('Link copied to clipboard');
            }, function(err) {
                console.error('Could not copy text: ', err);
            });
        }
    </script>
</x-app-layout>