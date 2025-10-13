<x-app-layout>
    <head>
        <!-- Add FontAwesome for icons if not already included -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
        <style>
            .bg-gradient-primary    { background: linear-gradient(90deg, #007bff 0%, #00c6ff 100%) !important; }
            .bg-gradient-success    { background: linear-gradient(90deg, #28a745 0%, #85ffbd 100%) !important; }
            .bg-gradient-warning    { background: linear-gradient(90deg, #ffc107 0%, #ffecd2 100%) !important; }
            .bg-gradient-info       { background: linear-gradient(90deg, #17a2b8 0%, #b2fefa 100%) !important; }
            .stat-card-icon {
                box-shadow: 0 2px 8px rgba(0,0,0,0.08);
                font-size: 1.7rem;
                width: 56px;
                height: 56px;
                display: flex;
                align-items: center;
                justify-content: center;
                border-radius: 50%;
                margin: 0 auto 12px auto;
            }
        </style>
    </head>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <x-app.navbar />
        <div class="container-fluid py-4 px-5">
            <!-- Success Alert -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Enhanced Statistics Section -->
            <div class="row mb-4">
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="card border-0 shadow-sm bg-light h-100">
                        <div class="card-body text-center">
                            <div class="stat-card-icon bg-gradient-primary text-white">
                                <i class="fas fa-users"></i>
                            </div>
                            <h6 class="font-weight-bold text-uppercase text-primary mb-1">Fournisseurs</h6>
                            <h2 class="font-weight-bold text-dark mb-0">{{ $totalFournisseurs }}</h2>
                            <span class="text-muted small">Total inscrits</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="card border-0 shadow-sm bg-light h-100">
                        <div class="card-body text-center">
                            <div class="stat-card-icon bg-gradient-success text-white">
                                <i class="fas fa-cubes"></i>
                            </div>
                            <h6 class="font-weight-bold text-uppercase text-success mb-1">Ressources</h6>
                            <h2 class="font-weight-bold text-dark mb-0">{{ $totalRessources }}</h2>
                            <span class="text-muted small">Total disponibles</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="card border-0 shadow-sm bg-light h-100">
                        <div class="card-body text-center">
                            <div class="stat-card-icon bg-gradient-warning text-white">
                                <i class="fas fa-chart-bar"></i>
                            </div>
                            <h6 class="font-weight-bold text-uppercase text-warning mb-1">Moyenne/R.</h6>
                            <h2 class="font-weight-bold text-dark mb-0">{{ $averageRessourcesPerFournisseur }}</h2>
                            <span class="text-muted small">Ressources par fournisseur</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="card border-0 shadow-sm bg-light h-100">
                        <div class="card-body text-center">
                            <div class="stat-card-icon bg-gradient-info text-white">
                                <i class="fas fa-tags"></i>
                            </div>
                            <h6 class="font-weight-bold text-uppercase text-info mb-1">Types</h6>
                            <h2 class="font-weight-bold text-dark mb-0">{{ $totalTypes }}</h2>
                            <span class="text-muted small">Types de ressources</span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Statistics Section -->

            <!-- Filter Section -->
            <div class="card border-0 shadow-xs mb-4">
                <div class="card-body py-3">
                    <form method="GET" action="{{ route('fournisseurs.index') }}">
                        <div class="row g-2 align-items-end">
                            <div class="col-md-3">
                                <label for="filter_nom" class="form-label mb-1">Nom Société</label>
                                <input type="text" class="form-control" id="filter_nom" name="nom_societe" value="{{ request('nom_societe') }}" placeholder="Nom...">
                            </div>
                            <div class="col-md-3">
                                <label for="filter_domaine" class="form-label mb-1">Domaine Service</label>
                                <select class="form-select" id="filter_domaine" name="domaine_service">
                                    <option value="" {{ request('domaine_service') ? '' : 'selected' }}>Tous</option>
                                    @foreach([
                                        'Décoration', 'Nourriture', 'Matériel', 'Transport',
                                        'Électronique', 'Hygiène', 'Communication', 'Papeterie',
                                        'Énergie', 'Nettoyage', 'Sécurité', 'Autre'
                                    ] as $type)
                                        <option value="{{ $type }}" {{ request('domaine_service') == $type ? 'selected' : '' }}>
                                            {{ $type }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="filter_adresse" class="form-label mb-1">Adresse</label>
                                <input type="text" class="form-control" id="filter_adresse" name="adresse" value="{{ request('adresse') }}" placeholder="Adresse...">
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="fas fa-filter me-1"></i> Filtrer
                                </button>
                                <a href="{{ route('fournisseurs.index') }}" class="btn btn-outline-secondary">
                                    Réinitialiser
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- End Filter Section -->

            <!-- Card for Table Header and Add Button -->
            <div class="row">
                <div class="col-12">
                    <div class="card border shadow-xs mb-4">
                        <div class="card-header border-bottom pb-0">
                            <div class="d-sm-flex align-items-center">
                                <div>
                                    <h6 class="font-weight-semibold text-lg mb-0">Liste des Fournisseurs</h6>
                                    <p class="text-sm">Voir les informations sur tous les fournisseurs</p>
                                </div>
                                <div class="ms-auto d-flex">
                                    <a href="{{ route('fournisseurs.create') }}"
                                        class="btn btn-sm btn-dark btn-icon d-flex align-items-center me-2">
                                        <span class="btn-inner--icon">
                                            <svg width="16" height="16" xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 24 24" fill="currentColor" class="d-block me-2">
                                                <path
                                                    d="M6.25 6.375a4.125 4.125 0 118.25 0 4.125 4.125 0 01-8.25 0zM3.25 19.125a7.125 7.125 0 0114.25 0v.003l-.001.119a.75.75 0 01-.363.63 13.067 13.067 0 01-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 01-.364-.63l-.001-.122zM19.75 7.5a.75.75 0 00-1.5 0v2.25H16a.75.75 0 000 1.5h2.25v2.25a.75.75 0 001.5 0v-2.25H22a.75.75 0 000-1.5h-2.25V7.5z" />
                                            </svg>
                                        </span>
                                        <span class="btn-inner--text">Ajouter Fournisseur</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body px-0 py-0">
                            <!-- Supplier Table -->
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th class="text-secondary text-xs font-weight-semibold opacity-7">Nom Société</th>
                                            <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-2">Domaine Service</th>
                                            <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-2">Adresse</th>
                                            <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-2">Email</th>
                                            <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-2">Téléphone</th>
                                            <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-2">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($fournisseurs as $fournisseur)
                                            <tr>
                                                <td>
                                                    <div class="d-flex px-2 py-1">
                                                        <div class="d-flex flex-column justify-content-center">
                                                            <h6 class="mb-0 text-sm font-weight-semibold">{{ $fournisseur->nom_societe }}</h6>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <p class="text-sm text-dark font-weight-semibold mb-0">{{ $fournisseur->domaine_service }}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm text-dark font-weight-semibold mb-0">{{ $fournisseur->adresse }}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm text-dark font-weight-semibold mb-0">{{ $fournisseur->email }}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm text-dark font-weight-semibold mb-0">{{ $fournisseur->telephone }}</p>
                                                </td>
                                                <td class="align-middle">
                                                    <a href="{{ route('fournisseurs.show', $fournisseur) }}"
                                                        class="btn btn-sm btn-info me-1" data-bs-toggle="tooltip" title="Voir">
                                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                                            xmlns="http://www.w3.org/2000/svg" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M15 12c0-1.657-1.343-3-3-3s-3 1.343-3 3 1.343 3 3 3 3-1.343 3-3zm5.657-2.343c-1.657-2.829-4.686-4.657-8.657-4.657s-7 1.828-8.657 4.657c-.586 1 0 2 0 2 1.657 2.829 4.686 4.657 8.657 4.657s7-1.828 8.657-4.657c.586-1 0-2 0-2z" />
                                                        </svg>
                                                    </a>
                                                    <a href="{{ route('fournisseurs.edit', $fournisseur) }}"
                                                        class="btn btn-sm btn-warning me-1" data-bs-toggle="tooltip" title="Modifier">
                                                        <svg width="14" height="14" viewBox="0 0 15 16" fill="none"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <path
                                                                d="M11.2201 2.02495C10.8292 1.63482 10.196 1.63545 9.80585 2.02636C9.41572 2.41727 9.41635 3.05044 9.80726 3.44057L11.2201 2.02495ZM12.5572 6.18502C12.9481 6.57516 13.5813 6.57453 13.9714 6.18362C14.3615 5.79271 14.3609 5.15954 13.97 4.7694L12.5572 6.18502ZM11.6803 1.56839L12.3867 2.2762L12.3867 2.27619L11.6803 1.56839ZM14.4302 4.31284L15.1367 5.02065L15.1367 5.02064L14.4302 4.31284ZM3.72198 15V16C3.98686 16 4.24091 15.8949 4.42839 15.7078L3.72198 15ZM0.999756 15H-0.000244141C-0.000244141 15.5523 0.447471 16 0.999756 16L0.999756 15ZM0.999756 12.2279L0.293346 11.5201C0.105383 11.7077 -0.000244141 11.9624 -0.000244141 12.2279H0.999756ZM9.80726 3.44057L12.5572 6.18502L13.97 4.7694L11.2201 2.02495L9.80726 3.44057ZM12.3867 2.27619C12.7557 1.90794 13.3549 1.90794 13.7238 2.27619L15.1367 0.860593C13.9869 -0.286864 12.1236 -0.286864 10.9739 0.860593L12.3867 2.27619ZM13.7238 2.27619C14.0917 2.64337 14.0917 3.23787 13.7238 3.60504L15.1367 5.02064C16.2875 3.8721 16.2875 2.00913 15.1367 0.860593L13.7238 2.27619ZM13.7238 3.60504L3.01557 14.2922L4.42839 15.7078L15.1367 5.02065L13.7238 3.60504ZM3.72198 14H0.999756V16H3.72198V14ZM1.99976 15V12.2279H-0.000244141V15H1.99976ZM1.70617 12.9357L12.3867 2.2762L10.9739 0.86059L0.293346 11.5201L1.70617 12.9357Z"
                                                                fill="#64748B" />
                                                        </svg>
                                                    </a>
                                                    <form action="{{ route('fournisseurs.destroy', $fournisseur) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-sm btn-danger"
                                                            onclick="return confirm('Supprimer ce fournisseur ?')"
                                                            data-bs-toggle="tooltip" title="Supprimer">
                                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                                                xmlns="http://www.w3.org/2000/svg" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                    d="M6 18L18 6M6 6l12 12" />
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!-- Pagination -->
                            <div class="border-top py-3 px-3 d-flex align-items-center">
                                <p class="font-weight-semibold mb-0 text-dark text-sm">Page {{ $fournisseurs->currentPage() }} of {{ $fournisseurs->lastPage() }}</p>
                                <div class="ms-auto">
                                    @if($fournisseurs->onFirstPage())
                                        <button class="btn btn-sm btn-white mb-0" disabled>Précédent</button>
                                    @else
                                        <a href="{{ $fournisseurs->previousPageUrl() }}" class="btn btn-sm btn-white mb-0">Précédent</a>
                                    @endif
                                    @if($fournisseurs->hasMorePages())
                                        <a href="{{ $fournisseurs->nextPageUrl() }}" class="btn btn-sm btn-white mb-0">Suivant</a>
                                    @else
                                        <button class="btn btn-sm btn-white mb-0" disabled>Suivant</button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <x-app.footer />
        </div>
    </main>
</x-app-layout>