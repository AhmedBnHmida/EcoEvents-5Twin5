<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <x-app.navbar />
        <div class="container-fluid py-4 px-5">
            <div class="row">
                <div class="col-12">
                    <div class="card border shadow-xs mb-4">
                        <div class="card-header border-bottom pb-0">
                            <div class="d-sm-flex align-items-center justify-content-between">
                                <div>
                                    <h6 class="font-weight-semibold text-lg mb-0">Gestion des Inscriptions</h6>
                                    <p class="text-sm">Gérer toutes les inscriptions aux événements</p>
                                </div>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('events.index') }}" class="btn btn-sm btn-dark btn-icon">
                                        <span class="btn-inner--icon">
                                            <i class="fas fa-calendar me-2"></i>
                                        </span>
                                        <span class="btn-inner--text">Événements</span>
                                    </a>
                                </div>
                            </div>
                        </div>

                        @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show mx-4 mt-3" role="alert">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        @endif

                        <div class="card-body px-0 py-0">
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th class="text-secondary text-xs font-weight-semibold opacity-7">Utilisateur</th>
                                            <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-2">Événement</th>
                                            <th class="text-center text-secondary text-xs font-weight-semibold opacity-7">Rôle</th>
                                            <th class="text-center text-secondary text-xs font-weight-semibold opacity-7">Code</th>
                                            <th class="text-center text-secondary text-xs font-weight-semibold opacity-7">Date inscription</th>
                                            <th class="text-center text-secondary text-xs font-weight-semibold opacity-7">Statut</th>
                                            <th class="text-center text-secondary text-xs font-weight-semibold opacity-7">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($registrations as $registration)
                                        <tr>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div class="d-flex align-items-center">
                                                        <i class="fas fa-user-circle text-secondary me-2 fa-2x"></i>
                                                        <div class="d-flex flex-column justify-content-center ms-1">
                                                            <h6 class="mb-0 text-sm font-weight-semibold">{{ $registration->user->name }}</h6>
                                                            <p class="text-sm text-secondary mb-0">{{ $registration->user->email }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm font-weight-semibold">{{ Str::limit($registration->event->title, 40) }}</h6>
                                                    <p class="text-sm text-secondary mb-0">
                                                        <i class="fas fa-calendar me-1"></i>
                                                        {{ $registration->event->start_date->format('d/m/Y H:i') }}
                                                    </p>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                @php
                                                    $roleColors = [
                                                        'participant' => 'success',
                                                        'admin' => 'danger',
                                                        'fournisseur' => 'warning',
                                                        'organisateur' => 'info',
                                                        'utilisateur' => 'secondary'
                                                    ];
                                                    $roleColor = $roleColors[$registration->user->role] ?? 'secondary';
                                                @endphp
                                                <span class="badge badge-sm bg-gradient-{{ $roleColor }}">
                                                    {{ ucfirst($registration->user->role) }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge badge-sm border border-dark text-dark font-monospace">
                                                    {{ $registration->ticket_code }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <span class="text-sm text-secondary">
                                                    {{ $registration->registered_at->format('d/m/Y') }}<br>
                                                    <small>{{ $registration->registered_at->format('H:i') }}</small>
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <form action="{{ route('registrations.updateStatus', $registration->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <select name="status" class="form-select form-select-sm badge bg-{{ $registration->status->color() }} border-0 text-white" 
                                                            onchange="this.form.submit()" style="cursor: pointer;">
                                                        <option value="pending" {{ $registration->status->value === 'pending' ? 'selected' : '' }}>En attente</option>
                                                        <option value="confirmed" {{ $registration->status->value === 'confirmed' ? 'selected' : '' }}>Confirmé</option>
                                                        <option value="canceled" {{ $registration->status->value === 'canceled' ? 'selected' : '' }}>Annulé</option>
                                                        <option value="attended" {{ $registration->status->value === 'attended' ? 'selected' : '' }}>Présent</option>
                                                    </select>
                                                </form>
                                            </td>
                                            <td class="text-center">
                                                <div class="d-flex gap-2 justify-content-center">
                                                    <a href="{{ route('registrations.show', $registration->id) }}" 
                                                       class="btn btn-sm btn-info" 
                                                       data-bs-toggle="tooltip" 
                                                       data-bs-placement="top" 
                                                       title="Voir les détails de l'inscription">
                                                        <i class="fas fa-eye me-1"></i>
                                                        <span class="d-none d-md-inline">Détails</span>
                                                    </a>
                                                    <form action="{{ route('registrations.destroy', $registration->id) }}" 
                                                          method="POST" 
                                                          onsubmit="return confirm('⚠️ Êtes-vous sûr de vouloir supprimer cette inscription ?\n\nUtilisateur: {{ $registration->user->name }}\nÉvénement: {{ $registration->event->title }}');" 
                                                          class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                class="btn btn-sm btn-danger" 
                                                                data-bs-toggle="tooltip" 
                                                                data-bs-placement="top" 
                                                                title="Supprimer cette inscription">
                                                            <i class="fas fa-trash-alt me-1"></i>
                                                            <span class="d-none d-md-inline">Supprimer</span>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-5">
                                                <i class="fas fa-inbox text-muted fa-3x mb-3"></i>
                                                <p class="text-muted">Aucune inscription trouvée</p>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        @if($registrations->hasPages())
                        <div class="card-footer">
                            <div class="d-flex justify-content-center">
                                {{ $registrations->links() }}
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="row">
                <div class="col-xl-3 col-sm-6 mb-4">
                    <div class="card border shadow-xs">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center">
                                <div class="icon icon-shape icon-md bg-gradient-warning text-center border-radius-lg">
                                    <i class="fas fa-clock text-lg opacity-10"></i>
                                </div>
                                <div class="ms-3">
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">En attente</p>
                                    <h5 class="font-weight-bolder mb-0">
                                        {{ \App\Models\Registration::where('status', 'pending')->count() }}
                                    </h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 mb-4">
                    <div class="card border shadow-xs">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center">
                                <div class="icon icon-shape icon-md bg-gradient-success text-center border-radius-lg">
                                    <i class="fas fa-check text-lg opacity-10"></i>
                                </div>
                                <div class="ms-3">
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Confirmés</p>
                                    <h5 class="font-weight-bolder mb-0">
                                        {{ \App\Models\Registration::where('status', 'confirmed')->count() }}
                                    </h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 mb-4">
                    <div class="card border shadow-xs">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center">
                                <div class="icon icon-shape icon-md bg-gradient-info text-center border-radius-lg">
                                    <i class="fas fa-user-check text-lg opacity-10"></i>
                                </div>
                                <div class="ms-3">
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Présents</p>
                                    <h5 class="font-weight-bolder mb-0">
                                        {{ \App\Models\Registration::where('status', 'attended')->count() }}
                                    </h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 mb-4">
                    <div class="card border shadow-xs">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center">
                                <div class="icon icon-shape icon-md bg-gradient-danger text-center border-radius-lg">
                                    <i class="fas fa-times text-lg opacity-10"></i>
                                </div>
                                <div class="ms-3">
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Annulés</p>
                                    <h5 class="font-weight-bolder mb-0">
                                        {{ \App\Models\Registration::where('status', 'canceled')->count() }}
                                    </h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <x-app.footer />
        </div>
    </main>

    @push('js')
    <script>
        // Initialize Bootstrap tooltips
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
    @endpush
</x-app-layout>
