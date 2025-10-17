<x-app-layout>
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <style>
                    @media (min-width: 992px) {
                        .g-sidenav-show .main-content {
                            margin-left: 250px;
                        }
                    }
                </style>
                <div class="card shadow-xs border">
                    <div class="card-header bg-gradient-dark">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="text-white mb-0">
                                <i class="fas fa-certificate me-2"></i>Mes Certificats
                            </h4>
                        </div>
                    </div>
                    <div class="card-body">
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

                        @if($certificates->isEmpty())
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>Vous n'avez pas encore de certificats.
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table align-items-center mb-0 table-hover">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 border-bottom">Événement</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2 border-bottom">Date de génération</th>
                                            @if(Auth::user()->isAdmin())
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2 border-bottom">Participant</th>
                                            @endif
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2 border-bottom">Téléchargements</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2 border-bottom text-center" style="min-width: 180px;">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($certificates as $certificate)
                                            <tr>
                                                <td>
                                                    <div class="d-flex px-2 py-1">
                                                        <div>
                                                            <h6 class="mb-0 text-sm">{{ $certificate->registration->event->title }}</h6>
                                                            <p class="text-xs text-secondary mb-0">{{ $certificate->registration->event->location }}</p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="text-sm font-weight-normal">{{ $certificate->generated_at->format('d/m/Y H:i') }}</span>
                                                </td>
                                                @if(Auth::user()->isAdmin())
                                                    <td>
                                                        <span class="text-sm font-weight-normal">{{ $certificate->registration->user->name }}</span>
                                                    </td>
                                                @endif
                                                <td>
                                                    <span class="badge bg-light text-dark">{{ $certificate->download_count }} fois</span>
                                                </td>
                                                <td class="text-center">
                                                    <div class="d-flex align-items-center justify-content-center gap-2">
                                                        <a href="{{ route('certificates.show', $certificate->id) }}" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" title="Voir le certificat">
                                                            <i class="fas fa-eye"></i>
                                                            <span class="d-none d-lg-inline ms-1">Voir</span>
                                                        </a>
                                                        <a href="{{ route('certificates.download', $certificate->id) }}" class="btn btn-sm btn-success" data-bs-toggle="tooltip" title="Télécharger le certificat">
                                                            <i class="fas fa-download"></i>
                                                            <span class="d-none d-lg-inline ms-1">Télécharger</span>
                                                        </a>
                                                        <form action="{{ route('certificates.destroy', $certificate->id) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce certificat ?');"
                                                                    data-bs-toggle="tooltip" title="Supprimer le certificat">
                                                                <i class="fas fa-trash"></i>
                                                                <span class="d-none d-lg-inline ms-1">Supprimer</span>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="mt-4">
                                {{ $certificates->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });
        });
    </script>
</x-app-layout>
