<x-app-layout>
    <x-front-navbar />
    
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <nav aria-label="breadcrumb" class="mb-4">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Accueil</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('registrations.my') }}">Mes inscriptions</a></li>
                        <li class="breadcrumb-item active">Détails</li>
                    </ol>
                </nav>

                <!-- Success Message -->
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                <div class="card shadow-xs border">
                    <div class="card-header bg-gradient-dark">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="text-white mb-0">
                                <i class="fas fa-ticket-alt me-2"></i>Votre Inscription
                            </h4>
                            <span class="badge bg-{{ $registration->status->color() }} text-xs">
                                {{ $registration->status->label() }}
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Event Info -->
                        <div class="mb-4">
                            <h5 class="font-weight-bold">{{ $registration->event->title }}</h5>
                            <p class="text-muted">{{ $registration->event->description }}</p>
                        </div>

                        <!-- Registration Details -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="text-muted text-xs">Code de ticket</label>
                                    <h4 class="font-weight-bold text-dark">{{ $registration->ticket_code }}</h4>
                                </div>
                                <div class="mb-3">
                                    <label class="text-muted text-xs">Date d'inscription</label>
                                    <p class="mb-0">{{ $registration->registered_at->format('d/m/Y à H:i') }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="text-muted text-xs">Statut</label>
                                    <p class="mb-0">
                                        <span class="badge bg-{{ $registration->status->color() }}">
                                            {{ $registration->status->label() }}
                                        </span>
                                    </p>
                                </div>
                                <div class="mb-3">
                                    <label class="text-muted text-xs">Participant</label>
                                    <p class="mb-0">{{ $registration->user->name }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- QR Code -->
                        <div class="text-center mb-4">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="mb-3">Code QR</h6>
                                    @if(file_exists(storage_path('app/public/' . $registration->qr_code_path)))
                                        <img src="{{ asset('storage/' . $registration->qr_code_path) }}" alt="QR Code" class="img-fluid" style="max-width: 200px;">
                                    @else
                                        <div class="bg-white p-4 d-inline-block border">
                                            <p class="mb-0 text-xs font-monospace">{{ $registration->ticket_code }}</p>
                                        </div>
                                    @endif
                                    <p class="text-muted text-xs mt-2 mb-0">Présentez ce code QR à l'entrée de l'événement</p>
                                </div>
                            </div>
                        </div>

                        <!-- Event Details -->
                        <div class="card bg-light mb-4">
                            <div class="card-body">
                                <h6 class="mb-3">Détails de l'événement</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="mb-2">
                                            <i class="fas fa-calendar text-dark me-2"></i>
                                            <strong>Date:</strong> {{ $registration->event->start_date->format('d/m/Y') }}
                                        </p>
                                        <p class="mb-2">
                                            <i class="fas fa-clock text-dark me-2"></i>
                                            <strong>Heure:</strong> {{ $registration->event->start_date->format('H:i') }}
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="mb-2">
                                            <i class="fas fa-map-marker-alt text-dark me-2"></i>
                                            <strong>Lieu:</strong> {{ $registration->event->location }}
                                        </p>
                                        <p class="mb-2">
                                            <i class="fas fa-tag text-dark me-2"></i>
                                            <strong>Prix:</strong> 
                                            @if($registration->event->price > 0)
                                                ${{ number_format($registration->event->price, 2) }}
                                            @else
                                                Gratuit
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('registrations.my') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Mes inscriptions
                            </a>
                            @if($registration->status->value !== 'canceled' && $registration->user_id === auth()->id())
                            <form action="{{ route('registrations.destroy', $registration->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir annuler votre inscription ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-times me-2"></i>Annuler mon inscription
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
