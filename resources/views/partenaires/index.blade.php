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
                                        <i class="fas fa-handshake me-2"></i>Gestion des Partenaires
                                    </h6>
                                    <p class="text-sm mb-0">Liste de tous les partenaires</p>
                                </div>
                                @if(auth()->user()->role === 'admin')
                                <a href="{{ route('partenaires.create') }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus me-2"></i>Nouveau Partenaire
                                </a>
                                @endif
                            </div>

                            <!-- Search and Filter Form -->
                            <form method="GET" action="{{ route('partenaires.index') }}" class="mb-3">
                                <div class="row g-3">
                                    <!-- Search -->
                                    <div class="col-md-5">
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="fas fa-search"></i>
                                            </span>
                                            <input type="text" name="search" class="form-control" placeholder="Rechercher par nom, contact, email..." value="{{ request('search') }}">
                                        </div>
                                    </div>

                                    <!-- Type Filter -->
                                    <div class="col-md-3">
                                        <select name="type" class="form-select">
                                            <option value="">Tous les types</option>
                                            @foreach($types as $type)
                                                <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>
                                                    {{ ucfirst($type) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Linked User Filter -->
                                    <div class="col-md-2">
                                        <select name="has_user" class="form-select">
                                            <option value="">Tous</option>
                                            <option value="1" {{ request('has_user') == '1' ? 'selected' : '' }}>Liés aux utilisateurs</option>
                                        </select>
                                    </div>

                                    <!-- Buttons -->
                                    <div class="col-md-2">
                                        <div class="d-flex gap-2">
                                            <button type="submit" class="btn btn-primary btn-sm w-50">
                                                <i class="fas fa-filter me-1"></i>Filtrer
                                            </button>
                                            <a href="{{ route('partenaires.index') }}" class="btn btn-outline-secondary btn-sm w-50">
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
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nom</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Type</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Contact</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Email</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Téléphone</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Sponsorings</th>
                                            <th class="text-secondary opacity-7"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($partners as $partner)
                                        <tr>
                                            <td>
                                                <div class="d-flex px-3 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm">{{ $partner->nom }}</h6>
                                                        @if($partner->user_id)
                                                        <small class="text-muted">
                                                            <i class="fas fa-link me-1"></i>Lié à {{ $partner->user->name }}
                                                        </small>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge badge-sm bg-gradient-info">{{ $partner->type }}</span>
                                            </td>
                                            <td>
                                                <p class="text-sm text-secondary mb-0">{{ $partner->contact_name }}</p>
                                            </td>
                                            <td>
                                                <p class="text-sm text-secondary mb-0">
                                                    <i class="fas fa-envelope me-1"></i>{{ $partner->contact_email }}
                                                </p>
                                            </td>
                                            <td>
                                                <p class="text-sm text-secondary mb-0">
                                                    <i class="fas fa-phone me-1"></i>{{ $partner->telephone }}
                                                </p>
                                            </td>
                                            <td>
                                                <span class="badge badge-sm bg-gradient-success">
                                                    {{ $partner->sponsorings->count() }} sponsorings
                                                </span>
                                            </td>
                                            <td class="align-middle">
                                                <a href="{{ route('partenaires.show', $partner->id) }}" class="text-secondary font-weight-normal text-xs" data-bs-toggle="tooltip" title="Voir">
                                                    <i class="fas fa-eye text-info"></i>
                                                </a>
                                                @if(auth()->user()->role === 'admin')
                                                <a href="{{ route('partenaires.edit', $partner->id) }}" class="text-secondary font-weight-normal text-xs ms-2" data-bs-toggle="tooltip" title="Modifier">
                                                    <i class="fas fa-edit text-primary"></i>
                                                </a>
                                                <form action="{{ route('partenaires.destroy', $partner->id) }}" method="POST" class="d-inline">
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
                                            <td colspan="7" class="text-center py-4">
                                                <i class="fas fa-handshake fa-3x text-muted mb-3"></i>
                                                <p class="text-muted">Aucun partenaire disponible</p>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @if($partners->hasPages())
                        <div class="card-footer">
                            {{ $partners->links() }}
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </main>
</x-app-layout>
