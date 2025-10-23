<x-app-layout>
    <canvas id="fullScreenCanvas" class="fixed-canvas"></canvas>

    <x-front-navbar />
    
    <div class="container py-5 main-content-wrapper">
        <!-- Success/Info/Error Messages -->
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-lg border-0 mb-4 section-dark-bg" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-check-circle me-3 fa-lg text-success"></i>
                <div class="flex-grow-1">
                    <h6 class="mb-1 text-bright-white">Succès !</h6>
                    <p class="mb-0 text-bright-white">{{ session('success') }}</p>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
            </div>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-lg border-0 mb-4 section-dark-bg" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-exclamation-circle me-3 fa-lg text-danger"></i>
                <div class="flex-grow-1">
                    <h6 class="mb-1 text-bright-white">Attention</h6>
                    <p class="mb-0 text-bright-white">{{ session('error') }}</p>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
            </div>
        </div>
        @endif

        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                <!-- Breadcrumb -->
                <nav aria-label="breadcrumb" class="mb-4">
                    <ol class="breadcrumb px-3 py-2 rounded-3 section-dark-bg">
                        <li class="breadcrumb-item">
                            <a href="/" class="text-decoration-none text-bright-white">
                                <i class="fas fa-home me-1"></i>Accueil
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('events.public') }}" class="text-decoration-none text-bright-white">Événements</a>
                        </li>
                        <li class="breadcrumb-item active text-success fw-semibold">{{ Str::limit($event->title, 25) }}</li>
                    </ol>
                </nav>

                <!-- Event Images Gallery -->
                @if($event->images && count($event->images) > 0)
                <div class="card shadow-lg border-0 mb-4 overflow-hidden section-dark-bg">
                    <div id="eventCarousel" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner rounded-3">
                            @foreach($event->images as $index => $image)
                                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                    <img src="{{ str_starts_with($image, 'http') ? $image : asset('storage/' . $image) }}" 
                                        class="d-block w-100 card-img-eco" 
                                        alt="Event image {{ $index + 1 }}" 
                                        style="height: 450px; object-fit: cover;">
                                </div>
                            @endforeach
                        </div>
                        @if(count($event->images) > 1)
                        <button class="carousel-control-prev" type="button" data-bs-target="#eventCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon bg-dark bg-opacity-75 rounded-circle p-3" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#eventCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon bg-dark bg-opacity-75 rounded-circle p-3" aria-hidden="true"></span>
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
                <div class="card shadow-lg border-0 mb-4 section-dark-bg">
                    <div class="card-body text-center py-5 bg-gradient-success text-white rounded-3">
                        <i class="fas fa-leaf fa-5x mb-4 opacity-75"></i>
                        <h3 class="mb-3">Image de l'événement</h3>
                        <p class="mb-0 opacity-75">Aucune image disponible pour cet événement</p>
                    </div>
                </div>
                @endif

                <!-- Event Header -->
                <div class="card shadow-lg border-0 mb-4 section-dark-bg">
                    <div class="card-body p-5">
                        <div class="d-flex justify-content-between align-items-start mb-4">
                            <div class="flex-grow-1">
                                <h1 class="h2 fw-bold text-bright-white mb-3">{{ $event->title }}</h1>
                                <div class="d-flex align-items-center flex-wrap gap-2 mb-3">
                                    @php
                                        $statusColors = [
                                            'UPCOMING' => 'bg-warning',
                                            'ONGOING' => 'bg-success',
                                            'COMPLETED' => 'bg-info',
                                            'CANCELLED' => 'bg-danger'
                                        ];
                                    @endphp
                                    <span class="badge {{ $statusColors[$event->status->value] ?? 'bg-secondary' }} text-sm px-3 py-2">
                                        <i class="fas fa-circle me-1 small"></i>{{ $event->status->value }}
                                    </span>
                                    <span class="badge bg-primary text-sm px-3 py-2">
                                        <i class="fas fa-tag me-1"></i>{{ $event->category->name }}
                                    </span>
                                    <span class="badge bg-dark text-sm px-3 py-2">
                                        <i class="fas fa-users me-1"></i>{{ $event->registrations->count() }} participants
                                    </span>
                                </div>
                                <p class="lead text-muted mb-0">{{ $event->description }}</p>
                            </div>
                            <div class="text-end ms-4">
                                <div class="price-display bg-dark-input rounded-3 p-3 text-center border">
                                    <h3 class="text-success mb-1 fw-bold">
                                        @if($event->price > 0)
                                            {{ number_format($event->price, 2) }} TND
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
                <div class="card shadow-lg border-0 mb-4 section-dark-bg">
                    <div class="card-header bg-transparent border-bottom py-4">
                        <h4 class="mb-0 text-bright-white fw-semibold">
                            <i class="fas fa-info-circle text-success me-2"></i>
                            Détails de l'événement
                        </h4>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center p-4 bg-dark-input rounded-3 border">
                                    <div class="feature-icon bg-success text-white rounded-3 p-3 me-4 shadow-sm">
                                        <i class="fas fa-calendar-alt fa-lg"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <small class="text-muted d-block fw-semibold text-uppercase small">DATE DE DÉBUT</small>
                                        <p class="mb-1 fw-bold text-bright-white h6">{{ $event->start_date->format('l, d F Y') }}</p>
                                        <small class="text-muted">
                                            <i class="fas fa-clock me-1"></i>{{ $event->start_date->format('H:i') }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center p-4 bg-dark-input rounded-3 border">
                                    <div class="feature-icon bg-success text-white rounded-3 p-3 me-4 shadow-sm">
                                        <i class="fas fa-clock fa-lg"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <small class="text-muted d-block fw-semibold text-uppercase small">DATE DE FIN</small>
                                        <p class="mb-1 fw-bold text-bright-white h6">{{ $event->end_date->format('l, d F Y') }}</p>
                                        <small class="text-muted">
                                            <i class="fas fa-clock me-1"></i>{{ $event->end_date->format('H:i') }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center p-4 bg-dark-input rounded-3 border">
                                    <div class="feature-icon bg-success text-white rounded-3 p-3 me-4 shadow-sm">
                                        <i class="fas fa-map-marker-alt fa-lg"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <small class="text-muted d-block fw-semibold text-uppercase small">LIEU</small>
                                        <p class="mb-0 fw-bold text-bright-white h6">{{ $event->location }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center p-4 bg-dark-input rounded-3 border">
                                    <div class="feature-icon bg-warning text-white rounded-3 p-3 me-4 shadow-sm">
                                        <i class="fas fa-users fa-lg"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <small class="text-muted d-block fw-semibold text-uppercase small">CAPACITÉ</small>
                                        <p class="mb-2 fw-bold text-bright-white h6">{{ $event->registrations->count() }}/{{ $event->capacity_max }} participants</p>
                                        <div class="progress" style="height: 8px;">
                                            <div class="progress-bar bg-warning" 
                                                style="--progress-width: {{ $event->capacity_max > 0 ? ($event->registrations->count() / $event->capacity_max) * 100 : 0 }}%; width: var(--progress-width);">
                                            </div>
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
                <div class="card shadow-lg border-0 mb-4 section-dark-bg">
                    <div class="card-header bg-gradient-primary text-white border-0 py-4">
                        <h4 class="mb-0 fw-semibold">
                            <i class="fas fa-handshake me-2"></i>Nos Partenaires
                        </h4>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            @foreach($event->partners as $partner)
                            <div class="col-md-6 col-lg-4">
                                <div class="card border-0 shadow-hover-3d h-100 section-dark-bg">
                                    <div class="card-body text-center p-4">
                                        <div class="partner-logo bg-gradient-primary text-white rounded-3 p-4 mx-auto mb-3 shadow-sm">
                                            <i class="fas fa-building fa-2x"></i>
                                        </div>
                                        <h6 class="mb-2 fw-bold text-bright-white">{{ $partner->nom }}</h6>
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
                <div class="card shadow-lg border-0 section-dark-bg">
                    <div class="card-header bg-gradient-warning text-white border-0 py-4">
                        <h4 class="mb-0 fw-semibold">
                            <i class="fas fa-donate me-2"></i>Sponsoring & Support
                        </h4>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            @foreach($event->sponsorings as $sponsoring)
                            <div class="col-12">
                                <div class="card border-0 shadow-sm h-100 section-dark-bg">
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
                                                <h6 class="mb-2 fw-bold text-bright-white">{{ $sponsoring->partner->nom }}</h6>
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
                <div class="card shadow-lg border-0 mt-4 section-dark-bg" >
                    <div class="card-header bg-gradient-success text-white border-0 py-4">
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
                                    <div class="alert alert-success border-0 text-center section-dark-bg">
                                        <i class="fas fa-check-circle fa-2x mb-2 text-success"></i>
                                        <h6 class="fw-bold mb-1 text-bright-white">Vous êtes inscrit !</h6>
                                        <p class="small mb-2 text-muted">Votre participation est confirmée</p>
                                        <a href="{{ route('registrations.show', $userRegistrationAny->id) }}" 
                                           class="btn btn-outline-success btn-sm w-100">
                                            <i class="fas fa-eye me-1"></i>Voir ma réservation
                                        </a>
                                    </div>
                                @elseif($isFull)
                                    <div class="alert alert-danger border-0 text-center section-dark-bg">
                                        <i class="fas fa-times-circle fa-2x mb-2 text-danger"></i>
                                        <h6 class="fw-bold mb-1 text-bright-white">Complet</h6>
                                        <p class="small mb-0 text-muted">Aucune place disponible</p>
                                    </div>
                                    <!-- Déclenchement automatique de la popup pour événement complet -->
                                    <script>
                                        document.addEventListener('DOMContentLoaded', function() {
                                            setTimeout(function() {
                                                showEventFullModal();
                                            }, 800);
                                        });
                                    </script>
                                @else
                                    <div class="text-center mb-3">
                                        <h4 class="text-success fw-bold mb-1">
                                            @if($event->price > 0)
                                                {{ number_format($event->price, 2) }} TND
                                            @else
                                                <span class="text-success">GRATUIT</span>
                                            @endif
                                        </h4>
                                        <small class="text-muted">par personne</small>
                                    </div>
                                    @auth
                                        <a href="{{ route('registrations.create', ['event_id' => $event->id]) }}" 
                                           class="btn btn-success-gradient btn-lg w-100 shadow-sm mb-3 py-3">
                                            <i class="fas fa-ticket-alt me-2"></i>Réserver maintenant
                                        </a>
                                    @else
                                        <a href="{{ route('login') }}" 
                                           class="btn btn-success-gradient btn-lg w-100 shadow-sm mb-3 py-3">
                                            <i class="fas fa-ticket-alt me-2"></i>Réserver maintenant
                                        </a>
                                    @endauth
                                    <div class="text-center">
                                        <small class="text-muted">
                                            <i class="fas fa-shield-alt me-1"></i>Réservation sécurisée
                                        </small>
                                    </div>
                                @endif
                            @elseif($event->status->value === 'ONGOING')
                                <div class="alert alert-warning border-0 text-center section-dark-bg">
                                    <i class="fas fa-play-circle fa-2x mb-2 text-warning"></i>
                                    <h6 class="fw-bold mb-1 text-bright-white">Événement en cours</h6>
                                    <p class="small mb-0 text-muted">Les inscriptions sont fermées</p>
                                </div>
                            @else
                                <div class="alert alert-secondary border-0 text-center section-dark-bg">
                                    <i class="fas fa-ban fa-2x mb-2 text-secondary"></i>
                                    <h6 class="fw-bold mb-1 text-bright-white">Événement terminé</h6>
                                    <p class="small mb-0 text-muted">Merci à tous les participants</p>
                                </div>
                            @endif
                        </div>

                        <!-- Feedback Section -->
                        @auth
                            @if($userRegistration && !$userFeedback && $event->status->value === 'COMPLETED')
                                <div class="alert alert-info border-0 mb-3 section-dark-bg">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-star text-warning me-2"></i>
                                        <div>
                                            <h6 class="mb-1 fw-bold text-bright-white">Donnez votre avis !</h6>
                                            <p class="small mb-0 text-muted">Partagez votre expérience</p>
                                        </div>
                                    </div>
                                    <a href="{{ route('feedback.create', ['event_id' => $event->id]) }}" 
                                       class="btn btn-warning w-100 mt-2">
                                        <i class="fas fa-star me-2"></i>Évaluer l'événement
                                    </a>
                                </div>
                            @elseif($userFeedback)
                                <div class="alert alert-success border-0 section-dark-bg">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="fas fa-check-circle me-2 text-success"></i>
                                        <h6 class="mb-0 fw-bold text-bright-white">Avis donné</h6>
                                    </div>
                                    <div class="text-center mb-2">
                                        @for ($i = 1; $i <= 5; $i++)
                                            @if ($i <= $userFeedback->note)
                                                <i class="fas fa-star text-warning"></i>
                                            @else
                                                <i class="far fa-star text-warning"></i>
                                            @endif
                                        @endfor
                                        <span class="ms-2 fw-bold text-bright-white">{{ $userFeedback->note }}/5</span>
                                    </div>
                                    <a href="{{ route('feedback.edit', $userFeedback->id_feedback) }}" 
                                       class="btn btn-outline-warning btn-sm w-100">
                                        <i class="fas fa-edit me-1"></i>Modifier mon avis
                                    </a>
                                </div>
                            @endif
                        @endauth

                        <!-- Event Quick Info -->
                        <div class="event-quick-info border-top border-secondary pt-4">
                            <h6 class="fw-semibold mb-3 text-bright-white">
                                <i class="fas fa-info-circle me-2 text-success"></i>Informations
                            </h6>
                            
                            <!-- Countdown Timer - Add this at the top -->
                            @if($event->isUpcoming() && $event->isRegistrationOpen())
                            <div class="countdown-mini mb-3 p-3 bg-dark-input rounded-3 border">
                                <div class="text-center">
                                    <small class="text-muted d-block mb-2">
                                        <i class="fas fa-clock me-1"></i>Inscriptions ferment dans
                                    </small>
                                    <div class="countdown-timer-mini" data-deadline="{{ $event->registration_deadline->toISOString() }}">
                                        <div class="d-flex justify-content-center align-items-center text-center">
                                            <div class="countdown-item-mini mx-1">
                                                <div class="countdown-value-mini bg-dark rounded-2 py-1 px-2 fw-bold text-warning" data-days>00</div>
                                                <small class="text-muted">j</small>
                                            </div>
                                            <div class="countdown-separator-mini text-muted fw-bold mx-1">:</div>
                                            <div class="countdown-item-mini mx-1">
                                                <div class="countdown-value-mini bg-dark rounded-2 py-1 px-2 fw-bold text-warning" data-hours>00</div>
                                                <small class="text-muted">h</small>
                                            </div>
                                            <div class="countdown-separator-mini text-muted fw-bold mx-1">:</div>
                                            <div class="countdown-item-mini mx-1">
                                                <div class="countdown-value-mini bg-dark rounded-2 py-1 px-2 fw-bold text-warning" data-minutes>00</div>
                                                <small class="text-muted">m</small>
                                            </div>
                                            <div class="countdown-separator-mini text-muted fw-bold mx-1">:</div>
                                            <div class="countdown-item-mini mx-1">
                                                <div class="countdown-value-mini bg-dark rounded-2 py-1 px-2 fw-bold text-warning" data-seconds>00</div>
                                                <small class="text-muted">s</small>
                                            </div>
                                        </div>
                                    </div>
                                    @php
                                        $countdownData = $event->getRegistrationCountdownData();
                                    @endphp
                                    @if($countdownData['is_urgent'])
                                    <small class="text-warning fw-semibold mt-2 d-block">
                                        <i class="fas fa-exclamation-triangle me-1"></i>Dernières heures!
                                    </small>
                                    @elseif($countdownData['is_ending_soon'])
                                    <small class="text-info fw-semibold mt-2 d-block">
                                        <i class="fas fa-clock me-1"></i>Bientôt terminé!
                                    </small>
                                    @endif
                                </div>
                            </div>
                            @elseif(!$event->isRegistrationOpen())
                            <div class="alert alert-danger border-0 text-center py-2 mb-3">
                                <small class="fw-semibold">
                                    <i class="fas fa-times-circle me-1"></i>Inscriptions fermées
                                </small>
                            </div>
                            @endif
                            <div class="space-y-3">
                                <div class="d-flex justify-content-between align-items-center py-2">
                                    <span class="text-muted small">
                                        <i class="fas fa-hourglass-end me-2"></i>Date limite
                                    </span>
                                    <span class="fw-semibold text-bright-white">{{ $event->registration_deadline->format('d/m/Y') }}</span>
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
                                    <span class="fw-semibold text-bright-white">{{ $event->registrations->count() }}/{{ $event->capacity_max }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Share Event -->
                <div class="card shadow-lg border-0 mt-4 section-dark-bg">
                    <div class="card-header bg-transparent border-bottom py-3">
                        <h6 class="mb-0 fw-semibold text-bright-white">
                            <i class="fas fa-share-alt me-2 text-success"></i>Partager l'événement
                        </h6>
                    </div>
                    <div class="card-body p-3">
                        <div class="row g-2">
                            <div class="col-4">
                                <button class="btn btn-outline-primary w-100 py-2 rounded-2 share-btn" data-platform="facebook">
                                    <i class="fab fa-facebook-f"></i>
                                </button>
                            </div>
                            <div class="col-4">
                                <button class="btn btn-outline-info w-100 py-2 rounded-2 share-btn" data-platform="twitter">
                                    <i class="fab fa-twitter"></i>
                                </button>
                            </div>
                            <div class="col-4">
                                <button class="btn btn-outline-danger w-100 py-2 rounded-2 share-btn" data-platform="instagram">
                                    <i class="fab fa-instagram"></i>
                                </button>
                            </div>
                            <div class="col-4">
                                <button class="btn btn-outline-success w-100 py-2 rounded-2 share-btn" data-platform="whatsapp">
                                    <i class="fab fa-whatsapp"></i>
                                </button>
                            </div>
                            <div class="col-4">
                                <button class="btn btn-outline-light w-100 py-2 rounded-2" onclick="copyEventLink()">
                                    <i class="fas fa-link"></i>
                                </button>
                            </div>
                            <div class="col-4">
                                <button class="btn btn-outline-secondary w-100 py-2 rounded-2 share-btn" data-platform="email">
                                    <i class="fas fa-envelope"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="card shadow-lg border-0 mt-4 section-dark-bg">
                    <div class="card-body p-3">
                        <div class="d-grid gap-2">
                            <a href="{{ route('events.public') }}" class="btn btn-outline-light rounded-2 py-2">
                                <i class="fas fa-arrow-left me-2"></i>Retour aux événements
                            </a>
                            <button class="btn btn-outline-primary rounded-2 py-2">
                                <i class="fas fa-heart me-2"></i>Ajouter aux favoris
                            </button>
                            <button class="btn btn-outline-info rounded-2 py-2" id="add-to-calendar">
                                <i class="fas fa-calendar-plus me-2"></i>Ajouter au calendrier
                            </button>
                        </div>
                    </div>
                </div>
            </div>
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

        /* Feature Icons */
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
        
        .organizer-avatar {
            width: 80px;
            height: 80px;
            font-size: 2rem;
        }

        .carousel-control-prev-icon,
        .carousel-control-next-icon {
            background-size: 1rem;
        }
        
        .price-display {
            min-width: 120px;
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

        /* Progress Bar */
        .progress {
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 4px;
            overflow: hidden;
        }

        .progress-bar {
            border-radius: 4px;
            transition: width 0.5s ease-in-out;
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

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .main-content-wrapper {
                margin-top: 80px;
            }
            
            .card-body {
                padding: 1.5rem !important;
            }
            
            .d-flex.justify-content-between.align-items-start {
                flex-direction: column;
            }
            
            .text-end.ms-4 {
                margin-top: 1rem;
                margin-left: 0 !important;
                text-align: center !important;
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

            // Carousel slide counter
        const carousel = document.getElementById('eventCarousel');
        if (carousel) {
            carousel.addEventListener('slid.bs.carousel', function (e) {
                const activeIndex = e.to;
                document.getElementById('currentSlide').textContent = activeIndex + 1;
            });
        }

        // Enhanced Copy event link to clipboard
        window.copyEventLink = function(button) {
            const url = window.location.href;
            const title = document.querySelector('h1')?.textContent || 'Événement EcoGuard';
            
            navigator.clipboard.writeText(url).then(function() {
                showTemporaryAlert('Lien copié dans le presse-papier !', 'success');
                
                // Visual feedback on the button
                if (button) {
                    const originalHTML = button.innerHTML;
                    button.innerHTML = '<i class="fas fa-check"></i>';
                    button.classList.remove('btn-outline-success');
                    button.classList.add('btn-success');
                    
                    setTimeout(() => {
                        button.innerHTML = originalHTML;
                        button.classList.remove('btn-success');
                        button.classList.add('btn-outline-success');
                    }, 2000);
                }
            }, function(err) {
                console.error('Could not copy text: ', err);
                // Fallback for older browsers
                fallbackCopyToClipboard(url);
            });
        };

        // Fallback copy method
        function fallbackCopyToClipboard(text) {
            const textArea = document.createElement('textarea');
            textArea.value = text;
            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();
            try {
                document.execCommand('copy');
                showTemporaryAlert('Lien copié dans le presse-papier !', 'success');
            } catch (err) {
                console.error('Fallback copy failed: ', err);
                alert('Impossible de copier le lien. Veuillez le copier manuellement: ' + text);
            }
            document.body.removeChild(textArea);
        }

        // Enhanced Share buttons functionality
        document.querySelectorAll('.share-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const platform = this.dataset.platform;
                const url = encodeURIComponent(window.location.href);
                const title = encodeURIComponent(document.querySelector('h1')?.textContent || 'Événement EcoGuard');
                const description = encodeURIComponent(document.querySelector('.lead')?.textContent || '');
                
                let shareUrl;
                
                switch(platform) {
                    case 'facebook':
                        shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${url}&quote=${title}`;
                        break;
                    case 'twitter':
                        shareUrl = `https://twitter.com/intent/tweet?text=${title}&url=${url}&hashtags=EcoGuard,Événement`;
                        break;
                    case 'whatsapp':
                        shareUrl = `https://wa.me/?text=${title}%20${url}`;
                        break;
                    case 'linkedin':
                        shareUrl = `https://www.linkedin.com/sharing/share-offsite/?url=${url}`;
                        break;
                    case 'email':
                        shareUrl = `mailto:?subject=${title}&body=Découvrez cet événement: ${url}`;
                        break;
                    default:
                        return;
                }
                
                if (platform === 'email') {
                    window.location.href = shareUrl;
                } else {
                    window.open(shareUrl, '_blank', 'width=600,height=400,menubar=no,toolbar=no,resizable=yes,scrollbars=yes');
                }
            });
        });

        // Enhanced Add to calendar functionality
        document.getElementById('add-to-calendar')?.addEventListener('click', function() {
            const event = {
                title: `{{ $event->title }}`,
                start: `{{ $event->start_date->format('Y-m-d\\TH:i:s') }}`,
                end: `{{ $event->end_date->format('Y-m-d\\TH:i:s') }}`,
                location: `{{ $event->location }}`,
                description: `{{ Str::limit($event->description, 100) }}`
            };
            
            // Create .ics file content with proper formatting
            const icsContent = [
                'BEGIN:VCALENDAR',
                'VERSION:2.0',
                'CALSCALE:GREGORIAN',
                'BEGIN:VEVENT',
                `SUMMARY:${event.title.replace(/,/g, '\\,')}`,
                `DTSTART:${event.start.replace(/[-:]/g, '')}`,
                `DTEND:${event.end.replace(/[-:]/g, '')}`,
                `LOCATION:${event.location.replace(/,/g, '\\,')}`,
                `DESCRIPTION:${event.description.replace(/,/g, '\\,')}`,
                'URL:' + window.location.href,
                'STATUS:CONFIRMED',
                'END:VEVENT',
                'END:VCALENDAR'
            ].join('\r\n');
            
            // Create and trigger download
            const blob = new Blob([icsContent], { type: 'text/calendar;charset=utf-8' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = '{{ Str::slug($event->title) }}.ics';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            window.URL.revokeObjectURL(url);
            
            showTemporaryAlert('Fichier calendrier téléchargé !', 'success');
        });

        // Add to favorites functionality
        document.getElementById('add-to-favorites')?.addEventListener('click', function() {
            const button = this;
            const icon = button.querySelector('i');
            
            // Toggle visual state
            if (icon.classList.contains('far')) {
                icon.classList.remove('far');
                icon.classList.add('fas', 'text-danger');
                button.innerHTML = '<i class="fas fa-heart me-2 text-danger"></i>Ajouté aux favoris';
                button.classList.remove('btn-outline-primary');
                button.classList.add('btn-outline-danger');
                
                showTemporaryAlert('Événement ajouté aux favoris !', 'success');
            } else {
                icon.classList.remove('fas', 'text-danger');
                icon.classList.add('far');
                button.innerHTML = '<i class="far fa-heart me-2"></i>Ajouter aux favoris';
                button.classList.remove('btn-outline-danger');
                button.classList.add('btn-outline-primary');
                
                showTemporaryAlert('Événement retiré des favoris.', 'info');
            }
            
            // Here you would typically make an AJAX call to save to favorites
            // saveToFavorites({{ $event->id }});
        });

        // Utility function to show temporary alerts
        function showTemporaryAlert(message, type = 'success') {
            const alert = document.createElement('div');
            alert.className = `alert alert-${type} alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-5`;
            alert.style.zIndex = '1060';
            alert.innerHTML = `
                <i class="fas fa-${type === 'success' ? 'check' : 'info'}-circle me-2"></i>
                ${message}
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
            `;
            document.body.appendChild(alert);
            
            setTimeout(() => {
                if (alert.parentNode) {
                    alert.remove();
                }
            }, 3000);
        }
        
        // Fonction pour afficher la modal d'événement complet
        function showEventFullModal() {
            const eventFullModal = new bootstrap.Modal(document.getElementById('eventFullModal'));
            eventFullModal.show();
        }

        // Initialize countdown timers
        initializeCountdownTimers();
    });

    // Countdown Timer Functionality (keep this unchanged)
    function initializeCountdownTimers() {
        console.log('Initializing countdown timers...');
        
        const countdownTimers = document.querySelectorAll('.countdown-timer, .countdown-timer-large, .countdown-timer-mini');
        
        countdownTimers.forEach(timer => {
            // Skip if already initialized
            if (timer.dataset.initialized === 'true') {
                console.log('Timer already initialized, skipping...');
                return;
            }
            
            const deadline = new Date(timer.dataset.deadline).getTime();
            console.log('Deadline:', timer.dataset.deadline, 'Parsed:', deadline);
            
            const daysElement = timer.querySelector('[data-days]');
            const hoursElement = timer.querySelector('[data-hours]');
            const minutesElement = timer.querySelector('[data-minutes]');
            const secondsElement = timer.querySelector('[data-seconds]');
            
            // Mark as initialized
            timer.dataset.initialized = 'true';
            
            function updateCountdown() {
                const now = new Date().getTime();
                const distance = deadline - now;
                
                if (distance < 0) {
                    // Countdown finished
                    if (daysElement) daysElement.textContent = '00';
                    if (hoursElement) hoursElement.textContent = '00';
                    if (minutesElement) minutesElement.textContent = '00';
                    if (secondsElement) secondsElement.textContent = '00';
                    
                    // Update UI to show registration closed
                    const countdownSection = timer.closest('.countdown-mini, .countdown-section');
                    if (countdownSection) {
                        countdownSection.innerHTML = `
                            <div class="alert alert-danger border-0 text-center py-2 mb-0">
                                <small class="fw-semibold">
                                    <i class="fas fa-times-circle me-1"></i>Inscriptions fermées
                                </small>
                            </div>
                        `;
                    }
                    return;
                }
                
                // Calculate time units
                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                
                // Update display
                if (daysElement) daysElement.textContent = days.toString().padStart(2, '0');
                if (hoursElement) hoursElement.textContent = hours.toString().padStart(2, '0');
                if (minutesElement) minutesElement.textContent = minutes.toString().padStart(2, '0');
                if (secondsElement) secondsElement.textContent = seconds.toString().padStart(2, '0');
                
                // Update urgency styling
                updateUrgencyStyling(timer, distance);
            }
            
            function updateUrgencyStyling(timerElement, distance) {
                const daysRemaining = Math.floor(distance / (1000 * 60 * 60 * 24));
                const hoursRemaining = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                
                // Remove existing urgency classes
                timerElement.classList.remove('countdown-urgent', 'countdown-warning', 'countdown-normal');
                
                // Add appropriate class based on time remaining
                if (daysRemaining === 0 && hoursRemaining < 24) {
                    timerElement.classList.add('countdown-urgent');
                } else if (daysRemaining < 3) {
                    timerElement.classList.add('countdown-warning');
                } else {
                    timerElement.classList.add('countdown-normal');
                }
            }
            
            // Initial update
            updateCountdown();
            
            // Update every second
            const countdownInterval = setInterval(updateCountdown, 1000);
            
            // Store interval ID for cleanup if needed
            timer.dataset.intervalId = countdownInterval;
            
            console.log('Countdown timer initialized successfully');
        });
    }
</script>
    
    <!-- Modal pour événement complet -->
    <div class="modal fade" id="eventFullModal" tabindex="-1" aria-labelledby="eventFullModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow section-dark-bg">
                <div class="modal-header bg-danger text-white border-0">
                    <h5 class="modal-title" id="eventFullModalLabel">
                        <i class="fas fa-exclamation-circle me-2"></i>Événement complet
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 text-center">
                    <div class="py-3">
                        <i class="fas fa-users-slash fa-4x text-danger opacity-75 mb-3"></i>
                        <h4 class="fw-bold mb-3 text-bright-white">Désolé, cet événement est complet !</h4>
                        <p class="text-muted mb-4">
                            Toutes les places disponibles pour cet événement ont été réservées. 
                            Nous vous invitons à consulter nos autres événements à venir.
                        </p>
                        
                        <div class="alert alert-light border mb-4 section-dark-bg">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-lightbulb text-warning fa-2x me-3"></i>
                                <div class="text-start">
                                    <h6 class="fw-bold mb-1 text-bright-white">Astuce</h6>
                                    <p class="small mb-0 text-muted">
                                        Des places peuvent se libérer en cas d'annulations. Revenez consulter cette page ultérieurement !
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <a href="{{ route('events.public') }}" class="btn btn-success-gradient">
                        <i class="fas fa-calendar-alt me-2"></i>Voir d'autres événements
                    </a>
                    <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>