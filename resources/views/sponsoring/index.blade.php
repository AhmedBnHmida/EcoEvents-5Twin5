<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <x-app.navbar />
        <div class="container-fluid py-4 px-5">
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-xs border mb-4">
                        <div class="card-header pb-0">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h6 class="font-weight-semibold text-lg mb-0">
                                        <i class="fas fa-donate me-2"></i>Gestion des Sponsorings
                                    </h6>
                                    <p class="text-sm mb-0">Liste de tous les sponsorings</p>
                                </div>
                                @if(in_array(auth()->user()->role, ['admin', 'organisateur']))
                                <a href="{{ route('sponsoring.create') }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus me-2"></i>Nouveau Sponsoring
                                </a>
                                @endif
                            </div>

                            <!-- Search and Filter Form -->
                            <form method="GET" action="{{ route('sponsoring.index') }}" class="mb-3">
                                <div class="row g-3">
                                    <!-- Search -->
                                    <div class="col-md-4">
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="fas fa-search"></i>
                                            </span>
                                            <input type="text" name="search" class="form-control" placeholder="Rechercher..." value="{{ request('search') }}">
                                        </div>
                                    </div>

                                    <!-- Type Filter -->
                                    <div class="col-md-2">
                                        <select name="type" class="form-select">
                                            <option value="">Tous les types</option>
                                            @foreach($types as $type)
                                                <option value="{{ $type->value }}" {{ request('type') == $type->value ? 'selected' : '' }}>
                                                    {{ $type->label() }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Partner Filter -->
                                    <div class="col-md-2">
                                        <select name="partner_id" class="form-select">
                                            <option value="">Tous les partenaires</option>
                                            @foreach($partners as $partner)
                                                <option value="{{ $partner->id }}" {{ request('partner_id') == $partner->id ? 'selected' : '' }}>
                                                    {{ $partner->nom }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Event Filter -->
                                    <div class="col-md-2">
                                        <select name="event_id" class="form-select">
                                            <option value="">Tous les événements</option>
                                            @foreach($events as $event)
                                                <option value="{{ $event->id }}" {{ request('event_id') == $event->id ? 'selected' : '' }}>
                                                    {{ Str::limit($event->title, 30) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Buttons -->
                                    <div class="col-md-2">
                                        <div class="d-flex gap-2">
                                            <button type="submit" class="btn btn-primary btn-sm w-50">
                                                <i class="fas fa-filter me-1"></i>Filtrer
                                            </button>
                                            <a href="{{ route('sponsoring.index') }}" class="btn btn-outline-secondary btn-sm w-50">
                                                <i class="fas fa-redo me-1"></i>Reset
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="card-body px-0 py-0">
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Partenaire</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Événement</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Type</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Montant</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Date</th>
                                            <th class="text-secondary opacity-7"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($sponsorings as $sponsoring)
                                        <tr>
                                            <td>
                                                <div class="d-flex px-3 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm">{{ $sponsoring->partner->nom }}</h6>
                                                        <p class="text-xs text-secondary mb-0">{{ $sponsoring->partner->type }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <p class="text-sm text-secondary mb-0">{{ $sponsoring->event->title }}</p>
                                            </td>
                                            <td>
                                                <span class="badge badge-sm bg-gradient-info">{{ $sponsoring->type_sponsoring->label() }}</span>
                                            </td>
                                            <td>
                                                <p class="text-sm font-weight-bold text-success mb-0">{{ number_format($sponsoring->montant, 2) }} €</p>
                                            </td>
                                            <td>
                                                <p class="text-sm text-secondary mb-0">{{ \Carbon\Carbon::parse($sponsoring->date)->format('d/m/Y') }}</p>
                                            </td>
                                            <td class="align-middle">
                                                <a href="{{ route('sponsoring.show', $sponsoring->id) }}" class="text-secondary font-weight-normal text-xs" data-bs-toggle="tooltip" title="Voir">
                                                    <i class="fas fa-eye text-info"></i>
                                                </a>
                                                @if(in_array(auth()->user()->role, ['admin', 'organisateur']))
                                                <a href="{{ route('sponsoring.edit', $sponsoring->id) }}" class="text-secondary font-weight-normal text-xs ms-2" data-bs-toggle="tooltip" title="Modifier">
                                                    <i class="fas fa-edit text-primary"></i>
                                                </a>
                                                @endif
                                                @if(auth()->user()->role === 'admin')
                                                <form action="{{ route('sponsoring.destroy', $sponsoring->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-link text-secondary font-weight-normal text-xs ms-2 p-0" onclick="return confirm('Êtes-vous sûr?')" data-bs-toggle="tooltip" title="Supprimer">
                                                        <i class="fas fa-trash text-danger"></i>
                                                    </button>
                                                </form>
                                                @endif
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-4">
                                                <i class="fas fa-donate fa-3x text-muted mb-3"></i>
                                                <p class="text-muted">Aucun sponsoring disponible</p>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @if($sponsorings->hasPages())
                        <div class="card-footer">
                            {{ $sponsorings->links() }}
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </main>
</x-app-layout>
