<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <x-app.navbar />
        <div class="container-fluid py-4 px-5">
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-xs border mb-4">
                        <div class="card-header pb-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="font-weight-semibold text-lg mb-0">Détails du Partenaire</h6>
                                    <p class="text-sm mb-0">{{ $partner->nom }}</p>
                                </div>
                                <div>
                                    <a href="{{ route('partenaires.index') }}" class="btn btn-white btn-sm">
                                        <i class="fas fa-arrow-left me-2"></i>Retour
                                    </a>
                                    @if(auth()->user()->role === 'admin')
                                    <a href="{{ route('partenaires.edit', $partner->id) }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-edit me-2"></i>Modifier
                                    </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="text-sm text-secondary">Nom:</label>
                                        <p class="text-dark font-weight-bold">{{ $partner->nom }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="text-sm text-secondary">Type:</label>
                                        <p><span class="badge bg-gradient-info">{{ $partner->type }}</span></p>
                                    </div>
                                    @if($partner->user_id)
                                    <div class="mb-3">
                                        <label class="text-sm text-secondary">Utilisateur lié:</label>
                                        <p class="text-dark">
                                            <span class="badge bg-gradient-success">
                                                <i class="fas fa-link me-1"></i>{{ $partner->user->name }}
                                            </span>
                                        </p>
                                    </div>
                                    @endif
                                    <div class="mb-3">
                                        <label class="text-sm text-secondary">Personne de Contact:</label>
                                        <p class="text-dark">{{ $partner->contact_name }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="text-sm text-secondary">Email:</label>
                                        <p class="text-dark"><i class="fas fa-envelope me-2"></i>{{ $partner->contact_email }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="text-sm text-secondary">Téléphone:</label>
                                        <p class="text-dark"><i class="fas fa-phone me-2"></i>{{ $partner->telephone }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="text-sm text-secondary">Date de création:</label>
                                        <p class="text-dark">{{ $partner->created_at->format('d/m/Y H:i') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sponsorings List -->
                    <div class="card shadow-xs border">
                        <div class="card-header pb-0">
                            <h6 class="font-weight-semibold mb-0">
                                <i class="fas fa-donate me-2"></i>Sponsorings ({{ $partner->sponsorings->count() }})
                            </h6>
                        </div>
                        <div class="card-body px-0 py-0">
                            @if($partner->sponsorings->count() > 0)
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Événement</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Type</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Montant</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($partner->sponsorings as $sponsoring)
                                        <tr>
                                            <td>
                                                <p class="text-sm px-3 mb-0">{{ $sponsoring->event->title ?? 'N/A' }}</p>
                                            </td>
                                            <td>
                                                <span class="badge badge-sm bg-gradient-primary">
                                                    {{ $sponsoring->type_sponsoring?->label() ?? 'N/A' }}
                                                </span>
                                            </td>
                                            <td>
                                                <p class="text-sm text-success font-weight-bold mb-0">{{ number_format($sponsoring->montant, 2) }} €</p>
                                            </td>
                                            <td>
                                                <p class="text-sm text-secondary mb-0">{{ \Carbon\Carbon::parse($sponsoring->date)->format('d/m/Y') }}</p>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @else
                            <div class="text-center py-4">
                                <i class="fas fa-donate fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Aucun sponsoring pour ce partenaire</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</x-app-layout>
