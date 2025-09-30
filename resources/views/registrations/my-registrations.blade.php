<x-app-layout>
    <x-front-navbar />
    
    <div class="container py-5">
        <div class="row">
            <div class="col-12">
                <nav aria-label="breadcrumb" class="mb-4">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Accueil</a></li>
                        <li class="breadcrumb-item active">Mes inscriptions</li>
                    </ol>
                </nav>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="font-weight-bold mb-0">Mes Inscriptions</h2>
                    <a href="{{ route('events.public') }}" class="btn btn-dark">
                        <i class="fas fa-calendar me-2"></i>Découvrir des événements
                    </a>
                </div>

                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                @forelse($registrations as $registration)
                <div class="card shadow-xs border mb-3">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-2 text-center">
                                @if($registration->event->images && count($registration->event->images) > 0)
                                    <img src="{{ $registration->event->images[0] }}" alt="{{ $registration->event->title }}" class="img-fluid rounded" style="max-height: 100px; object-fit: cover;">
                                @else
                                    <div class="bg-gradient-dark rounded d-flex align-items-center justify-content-center" style="height: 100px;">
                                        <i class="fas fa-calendar-alt text-white fa-2x"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <h5 class="font-weight-bold mb-2">{{ $registration->event->title }}</h5>
                                <p class="text-muted text-sm mb-2">{{ Str::limit($registration->event->description, 100) }}</p>
                                <div class="d-flex gap-3 text-xs text-muted">
                                    <span>
                                        <i class="fas fa-calendar me-1"></i>
                                        {{ $registration->event->start_date->format('d/m/Y') }}
                                    </span>
                                    <span>
                                        <i class="fas fa-clock me-1"></i>
                                        {{ $registration->event->start_date->format('H:i') }}
                                    </span>
                                    <span>
                                        <i class="fas fa-map-marker-alt me-1"></i>
                                        {{ Str::limit($registration->event->location, 20) }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-2 text-center">
                                <label class="text-muted text-xs d-block mb-1">Statut</label>
                                <span class="badge bg-{{ $registration->status->color() }} mb-2">
                                    {{ $registration->status->label() }}
                                </span>
                                <p class="text-xs text-muted mb-0">
                                    <strong>Code:</strong><br>{{ $registration->ticket_code }}
                                </p>
                            </div>
                            <div class="col-md-2 text-center">
                                <div class="d-flex flex-column gap-2">
                                    <a href="{{ route('registrations.show', $registration->id) }}" class="btn btn-sm btn-dark">
                                        <i class="fas fa-eye me-1"></i>Détails
                                    </a>
                                    @if($registration->status->value !== 'canceled')
                                    <form action="{{ route('registrations.destroy', $registration->id) }}" method="POST" onsubmit="return confirm('Annuler votre inscription ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger w-100">
                                            <i class="fas fa-times me-1"></i>Annuler
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="card shadow-xs border">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-calendar-times text-muted fa-3x mb-3"></i>
                        <h5 class="text-muted">Aucune inscription</h5>
                        <p class="text-muted mb-4">Vous n'êtes inscrit à aucun événement pour le moment.</p>
                        <a href="{{ route('events.public') }}" class="btn btn-dark">
                            <i class="fas fa-search me-2"></i>Découvrir des événements
                        </a>
                    </div>
                </div>
                @endforelse

                <!-- Pagination -->
                @if($registrations->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $registrations->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
