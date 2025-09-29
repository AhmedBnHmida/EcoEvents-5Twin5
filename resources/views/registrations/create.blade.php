<x-app-layout>
    <x-front-navbar />
    
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <nav aria-label="breadcrumb" class="mb-4">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Accueil</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('events.public') }}">Événements</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('events.public.show', $event->id) }}">{{ Str::limit($event->title, 30) }}</a></li>
                        <li class="breadcrumb-item active">Inscription</li>
                    </ol>
                </nav>

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

                <div class="card shadow-xs border">
                    <div class="card-header bg-gradient-dark">
                        <h4 class="text-white mb-0">
                            <i class="fas fa-ticket-alt me-2"></i>Inscription à l'événement
                        </h4>
                    </div>
                    <div class="card-body">
                        <!-- Event Summary -->
                        <div class="alert alert-info">
                            <h5 class="font-weight-bold">{{ $event->title }}</h5>
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <p class="mb-2">
                                        <i class="fas fa-calendar me-2"></i>
                                        <strong>Date:</strong> {{ $event->start_date->format('d/m/Y à H:i') }}
                                    </p>
                                    <p class="mb-2">
                                        <i class="fas fa-map-marker-alt me-2"></i>
                                        <strong>Lieu:</strong> {{ $event->location }}
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-2">
                                        <i class="fas fa-tag me-2"></i>
                                        <strong>Prix:</strong> 
                                        @if($event->price > 0)
                                            ${{ number_format($event->price, 2) }}
                                        @else
                                            Gratuit
                                        @endif
                                    </p>
                                    <p class="mb-2">
                                        <i class="fas fa-users me-2"></i>
                                        <strong>Places restantes:</strong> {{ $event->capacity_max - $event->registrations->count() }}/{{ $event->capacity_max }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Registration Form -->
                        <form action="{{ route('registrations.store') }}" method="POST" class="mt-4">
                            @csrf
                            <input type="hidden" name="event_id" value="{{ $event->id }}">

                            <div class="mb-4">
                                <h5 class="mb-3">Vos informations</h5>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Nom complet</label>
                                        <input type="text" class="form-control" value="{{ auth()->user()->name }}" disabled>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" class="form-control" value="{{ auth()->user()->email }}" disabled>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="terms" required>
                                    <label class="form-check-label" for="terms">
                                        J'accepte les <a href="#" target="_blank">conditions générales</a> et je confirme ma participation à cet événement
                                    </label>
                                </div>
                            </div>

                            <div class="alert alert-warning">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Important:</strong> Après votre inscription, vous recevrez un code de ticket unique et un code QR. Votre inscription sera en attente de confirmation par l'administrateur.
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('events.public.show', $event->id) }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>Retour
                                </a>
                                <button type="submit" class="btn btn-dark">
                                    <i class="fas fa-check me-2"></i>Confirmer mon inscription
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
