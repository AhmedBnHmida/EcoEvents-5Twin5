<x-app-layout>
    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <style>
                    @media (min-width: 992px) {
                        /* Fix for sidebar overlap - removed extra margin */
                        .g-sidenav-show .main-content {
                            margin-left: 0;
                            padding-left: 15px;
                        }
                    }
                </style>
                <nav aria-label="breadcrumb" class="mb-4">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Accueil</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('certificates.index') }}">Mes certificats</a></li>
                        <li class="breadcrumb-item active">Certificat</li>
                    </ol>
                </nav>

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
                                <i class="fas fa-certificate me-2"></i>Certificat de Participation
                            </h4>
                            <span class="badge bg-light text-dark">
                                Téléchargé {{ $certificate->download_count }} fois
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h5 class="font-weight-bold">Détails du certificat</h5>
                                <div class="mb-3">
                                    <label class="text-muted text-xs">Événement</label>
                                    <p class="mb-0 font-weight-bold">{{ $certificate->registration->event->title }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="text-muted text-xs">Participant</label>
                                    <p class="mb-0">{{ $certificate->registration->user->name }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="text-muted text-xs">Date de l'événement</label>
                                    <p class="mb-0">{{ $certificate->registration->event->start_date->format('d/m/Y') }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="text-muted text-xs">Date de génération</label>
                                    <p class="mb-0">{{ $certificate->generated_at->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                            <div class="col-md-6 text-md-end">
                                <!-- Buttons removed as requested -->
                            </div>
                        </div>

                        <div class="card shadow-lg border-0">
                            <div class="card-header bg-gradient-success p-3">
                                <h5 class="text-white mb-0 d-flex align-items-center">
                                    <i class="fas fa-certificate me-2"></i>
                                    Aperçu du certificat
                                </h5>
                            </div>
                            <div class="card-body p-0">
                                <div class="certificate-preview position-relative">
                                    <iframe src="{{ route('certificates.download', $certificate->id) }}?display=true" width="100%" height="650px" style="border: none; display: block;"></iframe>
                                </div>
                                <div class="certificate-actions p-4 bg-light border-top">
                                    <p class="text-muted mb-0 text-center">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Ce certificat atteste de votre participation à l'événement
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <style>
                            .certificate-preview {
                                position: relative;
                                overflow: hidden;
                                background-color: #f8f9fa;
                                border-bottom: 1px solid rgba(0,0,0,0.1);
                            }
                            .certificate-preview iframe {
                                background-color: #fff;
                                box-shadow: inset 0 0 20px rgba(0,0,0,0.05);
                            }
                            .card-header.bg-gradient-success {
                                background-image: linear-gradient(310deg, #2dce89 0%, #1b5e20 100%);
                            }
                            .btn-success {
                                background-color: #1b5e20;
                                border-color: #1b5e20;
                                transition: all 0.3s ease;
                            }
                            .btn-success:hover {
                                background-color: #2e7d32;
                                border-color: #2e7d32;
                                transform: translateY(-2px);
                                box-shadow: 0 7px 14px rgba(50, 50, 93, 0.1), 0 3px 6px rgba(0, 0, 0, 0.08);
                            }
                        </style>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
