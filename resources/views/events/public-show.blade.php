<x-app-layout>
    <x-front-navbar />
    
    <div class="container py-5">
        <!-- Success/Info/Error Messages -->
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <i class="fas fa-info-circle me-2"></i>{{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <div class="row">
            <!-- Event Details -->
            <div class="col-lg-8">
                <nav aria-label="breadcrumb" class="mb-4">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Accueil</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('events.public') }}">Événements</a></li>
                        <li class="breadcrumb-item active">{{ Str::limit($event->title, 30) }}</li>
                    </ol>
                </nav>

                <!-- Event Images -->
                @if($event->images && count($event->images) > 0)
                <div class="card shadow-xs border mb-4">
                    <div id="eventCarousel" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            @foreach($event->images as $index => $image)
                            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                <img src="{{ $image }}" class="d-block w-100" alt="Event image {{ $index + 1 }}" style="height: 400px; object-fit: cover;">
                            </div>
                            @endforeach
                        </div>
                        @if(count($event->images) > 1)
                        <button class="carousel-control-prev" type="button" data-bs-target="#eventCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#eventCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                        @endif
                    </div>
                </div>
                @else
                <div class="card shadow-xs border mb-4">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-calendar-alt text-muted fa-5x mb-3"></i>
                        <h4 class="text-muted">Image de l'événement</h4>
                    </div>
                </div>
                @endif

                <!-- Event Description -->
                <div class="card shadow-xs border mb-4">
                    <div class="card-body">
                        <h2 class="card-title font-weight-bold mb-3">{{ $event->title }}</h2>
                        <p class="card-text">{{ $event->description }}</p>
                    </div>
                </div>

                <!-- Event Details -->
                <div class="card shadow-xs border mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Détails de l'événement</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center mb-3">
                                    <i class="fas fa-calendar-alt text-primary me-3 fa-lg"></i>
                                    <div>
                                        <small class="text-muted">Date de début</small>
                                        <p class="mb-0 font-weight-bold">{{ $event->start_date->format('l, d F Y') }}</p>
                                        <small class="text-muted">{{ $event->start_date->format('H:i') }}</small>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center mb-3">
                                    <i class="fas fa-clock text-primary me-3 fa-lg"></i>
                                    <div>
                                        <small class="text-muted">Date de fin</small>
                                        <p class="mb-0 font-weight-bold">{{ $event->end_date->format('l, d F Y') }}</p>
                                        <small class="text-muted">{{ $event->end_date->format('H:i') }}</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center mb-3">
                                    <i class="fas fa-map-marker-alt text-primary me-3 fa-lg"></i>
                                    <div>
                                        <small class="text-muted">Lieu</small>
                                        <p class="mb-0 font-weight-bold">{{ $event->location }}</p>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center mb-3">
                                    <i class="fas fa-users text-primary me-3 fa-lg"></i>
                                    <div>
                                        <small class="text-muted">Capacité</small>
                                        <p class="mb-0 font-weight-bold">{{ $event->registrations->count() }}/{{ $event->capacity_max }} participants</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Partners Section -->
                @if($event->partners && $event->partners->count() > 0)
                <div class="card shadow-xs border mb-4">
                    <div class="card-header bg-gradient-primary">
                        <h5 class="mb-0 text-white">
                            <i class="fas fa-handshake me-2"></i>Nos Partenaires
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($event->partners as $partner)
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center p-3 border rounded">
                                    <div class="avatar avatar-lg bg-gradient-primary text-white rounded-circle me-3">
                                        <i class="fas fa-building"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-1 font-weight-bold">{{ $partner->nom }}</h6>
                                        <p class="text-sm text-muted mb-0">
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
                <div class="card shadow-xs border">
                    <div class="card-header bg-gradient-warning">
                        <h5 class="mb-0 text-white">
                            <i class="fas fa-donate me-2"></i>Sponsoring & Support
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($event->sponsorings as $sponsoring)
                            <div class="col-md-12 mb-3">
                                <div class="card border-left-{{ $sponsoring->type_sponsoring->value === 'argent' ? 'success' : ($sponsoring->type_sponsoring->value === 'materiel' ? 'info' : 'warning') }} shadow-sm">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="d-flex align-items-center">
                                                <div class="me-3">
                                                    @if($sponsoring->type_sponsoring->value === 'argent')
                                                        <i class="fas fa-coins fa-2x text-success"></i>
                                                    @elseif($sponsoring->type_sponsoring->value === 'materiel')
                                                        <i class="fas fa-box fa-2x text-info"></i>
                                                    @elseif($sponsoring->type_sponsoring->value === 'logistique')
                                                        <i class="fas fa-truck fa-2x text-warning"></i>
                                                    @else
                                                        <i class="fas fa-gift fa-2x text-primary"></i>
                                                    @endif
                                                </div>
                                                <div>
                                                    <h6 class="mb-1 font-weight-bold">
                                                        {{ $sponsoring->partner->nom }}
                                                    </h6>
                                                    <span class="badge badge-sm bg-gradient-{{ $sponsoring->type_sponsoring->value === 'argent' ? 'success' : ($sponsoring->type_sponsoring->value === 'materiel' ? 'info' : 'warning') }}">
                                                        {{ $sponsoring->type_sponsoring->label() }}
                                                    </span>
                                                    @if($sponsoring->montant)
                                                    <p class="text-sm text-muted mb-0 mt-1">
                                                        <i class="fas fa-dollar-sign me-1"></i>{{ number_format($sponsoring->montant, 2) }} DT
                                                    </p>
                                                    @endif
                                                    @if($sponsoring->description)
                                                    <p class="text-sm text-muted mb-0 mt-1">
                                                        {{ Str::limit($sponsoring->description, 100) }}
                                                    </p>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="text-end">
                                                <small class="text-muted">
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
                <div class="card shadow-xs border sticky-top" style="top: 100px;">
                    <div class="card-body text-center">
                        <h4 class="text-primary mb-3">
                            @if($event->price > 0)
                                ${{ number_format($event->price, 2) }}
                            @else
                                Gratuit
                            @endif
                        </h4>
                        
                        @php
                            $isRegistered = auth()->check() && $event->registrations()->where('user_id', auth()->id())->exists();
                            $isFull = $event->registrations->count() >= $event->capacity_max;
                        @endphp

                        @php
                            // Check if user has a confirmed or attended registration
                            $userRegistration = auth()->check() ? 
                                $event->registrations()
                                    ->where('user_id', auth()->id())
                                    ->whereIn('status', ['confirmed', 'attended'])
                                    ->first() : null;
                            
                            // Check if user already gave feedback
                            $userFeedback = auth()->check() ? 
                                \App\Models\Feedback::where('id_evenement', $event->id)
                                    ->where('id_participant', auth()->id())
                                    ->first() : null;
                            
                            $userRegistrationAny = auth()->check() ? $event->registrations()->where('user_id', auth()->id())->first() : null;
                        @endphp

                        @if($event->status->value === 'UPCOMING')
                            @if($userRegistrationAny)
                                <a href="{{ route('registrations.show', $userRegistrationAny->id) }}" class="btn btn-outline-dark w-100 mb-3">
                                    <i class="fas fa-eye me-2"></i>Voir mon inscription
                                </a>
                            @elseif($isFull)
                                <button class="btn btn-danger btn-lg w-100 mb-3" disabled>
                                    <i class="fas fa-times me-2"></i>Complet
                                </button>
                            @else
                                <a href="{{ route('registrations.create', ['event_id' => $event->id]) }}" class="btn btn-dark btn-lg w-100 mb-3">
                                    <i class="fas fa-ticket-alt me-2"></i>Participer
                                </a>
                            @endif
                        @elseif($event->status->value === 'ONGOING')
                            <button class="btn btn-success btn-lg w-100 mb-3" disabled>
                                <i class="fas fa-play me-2"></i>En cours
                            </button>
                        @else
                            <button class="btn btn-secondary btn-lg w-100 mb-3" disabled>
                                <i class="fas fa-ban me-2"></i>Terminé
                            </button>
                        @endif

                        {{-- Feedback Section --}}
                        @auth
                            @if($userRegistration && !$userFeedback)
                                {{-- User can give feedback --}}
                                <div class="alert alert-info mb-3">
                                    <i class="fas fa-star me-2"></i>
                                    <small>Vous pouvez maintenant donner votre avis sur cet événement!</small>
                                </div>
                                <a href="{{ route('feedback.create', ['event_id' => $event->id]) }}" 
                                   class="btn btn-warning btn-lg w-100 mb-3">
                                    <i class="fas fa-star me-2"></i>Donner mon avis
                                </a>
                            @elseif($userFeedback)
                                {{-- User already gave feedback --}}
                                <div class="alert alert-success mb-3">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="fas fa-check-circle me-2"></i>
                                        <small class="font-weight-bold">Vous avez donné votre avis</small>
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
                                       class="btn btn-sm btn-outline-warning w-100">
                                        <i class="fas fa-edit me-1"></i>Modifier mon avis
                                    </a>
                                </div>
                            @endif
                        @endauth

                        <div class="text-start">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Date limite:</span>
                                <span class="font-weight-bold">{{ $event->registration_deadline->format('d/m/Y') }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Catégorie:</span>
                                <span class="badge bg-gradient-primary">{{ $event->category->name }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Statut:</span>
                                @php
                                    $statusColors = [
                                        'UPCOMING' => 'bg-gradient-warning',
                                        'ONGOING' => 'bg-gradient-success',
                                        'COMPLETED' => 'bg-gradient-info',
                                        'CANCELLED' => 'bg-gradient-danger'
                                    ];
                                @endphp
                                <span class="badge {{ $statusColors[$event->status->value] ?? 'bg-gradient-secondary' }}">
                                    {{ $event->status->value }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Share Event -->
                <div class="card shadow-xs border mt-4">
                    <div class="card-body">
                        <h6 class="mb-3">Partager cet événement</h6>
                        <div class="d-flex gap-2">
                            <button class="btn btn-outline-primary btn-sm flex-fill">
                                <i class="fab fa-facebook-f"></i>
                            </button>
                            <button class="btn btn-outline-info btn-sm flex-fill">
                                <i class="fab fa-twitter"></i>
                            </button>
                            <button class="btn btn-outline-danger btn-sm flex-fill">
                                <i class="fab fa-instagram"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Back to Events -->
                <div class="card shadow-xs border mt-4">
                    <div class="card-body text-center">
                        <a href="{{ route('events.public') }}" class="btn btn-outline-dark w-100">
                            <i class="fas fa-arrow-left me-2"></i>Retour aux événements
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>