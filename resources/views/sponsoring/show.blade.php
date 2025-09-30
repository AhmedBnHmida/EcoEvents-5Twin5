<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <x-app.navbar />
        <div class="container-fluid py-4 px-5">
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-xs border">
                        <div class="card-header pb-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="font-weight-semibold text-lg mb-0">Détails du Sponsoring</h6>
                                    <p class="text-sm mb-0">{{ $sponsoring->partner->nom }} - {{ $sponsoring->event->title }}</p>
                                </div>
                                <div>
                                    <a href="{{ route('sponsoring.index') }}" class="btn btn-white btn-sm">
                                        <i class="fas fa-arrow-left me-2"></i>Retour
                                    </a>
                                    @if(in_array(auth()->user()->role, ['admin', 'organisateur']))
                                    <a href="{{ route('sponsoring.edit', $sponsoring->id) }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-edit me-2"></i>Modifier
                                    </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="font-weight-semibold mb-3">Informations du Partenaire</h6>
                                    <div class="mb-3">
                                        <label class="text-sm text-secondary">Nom:</label>
                                        <p class="text-dark font-weight-bold">{{ $sponsoring->partner->nom }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="text-sm text-secondary">Type:</label>
                                        <p><span class="badge bg-gradient-info">{{ $sponsoring->partner->type }}</span></p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="text-sm text-secondary">Contact:</label>
                                        <p class="text-dark">{{ $sponsoring->partner->contact }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="text-sm text-secondary">Email:</label>
                                        <p class="text-dark"><i class="fas fa-envelope me-2"></i>{{ $sponsoring->partner->email }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="text-sm text-secondary">Téléphone:</label>
                                        <p class="text-dark"><i class="fas fa-phone me-2"></i>{{ $sponsoring->partner->telephone }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="font-weight-semibold mb-3">Détails du Sponsoring</h6>
                                    <div class="mb-3">
                                        <label class="text-sm text-secondary">Événement:</label>
                                        <p class="text-dark font-weight-bold">{{ $sponsoring->event->title }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="text-sm text-secondary">Type de Sponsoring:</label>
                                        <p><span class="badge bg-gradient-primary">{{ $sponsoring->type_sponsoring->label() }}</span></p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="text-sm text-secondary">Montant:</label>
                                        <p class="text-success font-weight-bold text-lg">{{ number_format($sponsoring->montant, 2) }} €</p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="text-sm text-secondary">Date:</label>
                                        <p class="text-dark">{{ \Carbon\Carbon::parse($sponsoring->date)->format('d/m/Y') }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="text-sm text-secondary">Date de création:</label>
                                        <p class="text-dark">{{ $sponsoring->created_at->format('d/m/Y H:i') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</x-app-layout>
