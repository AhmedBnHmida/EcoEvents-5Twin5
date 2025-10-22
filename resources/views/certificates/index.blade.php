<x-app-layout>
    <canvas id="fullScreenCanvas" class="fixed-canvas"></canvas>
    <x-front-navbar />
    
    <div class="container py-5 main-content-wrapper">
        <!-- Page Header -->
        <div class="row mb-5">
            <div class="col-12 text-center">
                <span class="badge bg-success-gradient text-uppercase py-2 px-3 mb-3 badge-pill">Certificats</span>
                <h1 class="display-5 fw-bold text-bright-white mb-3">
                    <i class="fas fa-certificate me-3"></i>Mes Certificats
                </h1>
                <p class="lead text-muted">
                    Consultez et téléchargez tous vos certificats de participation
                </p>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <!-- Breadcrumb -->
                <nav aria-label="breadcrumb" class="mb-4">
                    <ol class="breadcrumb px-3 py-2 rounded-3 section-dark-bg">
                        <li class="breadcrumb-item">
                            <a href="/" class="text-decoration-none text-bright-white">
                                <i class="fas fa-home me-1"></i>Accueil
                            </a>
                        </li>
                        <li class="breadcrumb-item active text-success fw-semibold">Mes certificats</li>
                    </ol>
                </nav>

                <div class="card shadow-lg border-0 section-dark-bg">
                    <div class="card-header bg-gradient-success text-white border-0 py-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="mb-0">
                                <i class="fas fa-certificate me-2"></i>Mes Certificats
                            </h4>
                            <span class="badge bg-light text-dark">
                                {{ $certificates->count() }} certificat(s)
                            </span>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show shadow-lg border-0 mb-4 section-dark-bg" role="alert">
                                <i class="fas fa-check-circle me-2 text-success"></i>
                                <span class="text-bright-white">{{ session('success') }}</span>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show shadow-lg border-0 mb-4 section-dark-bg" role="alert">
                                <i class="fas fa-exclamation-circle me-2 text-danger"></i>
                                <span class="text-bright-white">{{ session('error') }}</span>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if($certificates->isEmpty())
                            <div class="alert alert-info border-0 section-dark-bg text-center py-5">
                                <i class="fas fa-certificate fa-3x text-info mb-3"></i>
                                <h5 class="text-bright-white mb-3">Aucun certificat disponible</h5>
                                <p class="text-muted mb-0">Vous n'avez pas encore de certificats de participation.</p>
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table align-items-center mb-0 table-hover">
                                    <thead class="bg-dark-input">
                                        <tr>
                                            <th class="text-uppercase text-bright-white text-xs font-weight-bolder opacity-7 border-bottom border-secondary">Événement</th>
                                            <th class="text-uppercase text-bright-white text-xs font-weight-bolder opacity-7 ps-2 border-bottom border-secondary">Date de génération</th>
                                            @if(Auth::user()->isAdmin())
                                                <th class="text-uppercase text-bright-white text-xs font-weight-bolder opacity-7 ps-2 border-bottom border-secondary">Participant</th>
                                            @endif
                                            <th class="text-uppercase text-bright-white text-xs font-weight-bolder opacity-7 ps-2 border-bottom border-secondary">Téléchargements</th>
                                            <th class="text-uppercase text-bright-white text-xs font-weight-bolder opacity-7 ps-2 border-bottom border-secondary text-center" style="min-width: 180px;">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($certificates as $certificate)
                                            <tr class="border-bottom border-secondary">
                                                <td>
                                                    <div class="d-flex align-items-center px-2 py-1">
                                                        <div class="flex-shrink-0 me-3">
                                                            @if($certificate->registration->event->images && count($certificate->registration->event->images) > 0)
                                                                <img src="{{ $certificate->registration->event->images[0] }}" 
                                                                     alt="{{ $certificate->registration->event->title }}" 
                                                                     class="rounded" 
                                                                     style="width: 50px; height: 50px; object-fit: cover;">
                                                            @else
                                                                <div class="bg-gradient-success rounded d-flex align-items-center justify-content-center" 
                                                                     style="width: 50px; height: 50px;">
                                                                    <i class="fas fa-leaf text-white"></i>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <h6 class="mb-0 text-bright-white text-sm">{{ $certificate->registration->event->title }}</h6>
                                                            <p class="text-xs text-muted mb-0">{{ $certificate->registration->event->location }}</p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="text-sm font-weight-normal text-bright-white">{{ $certificate->generated_at->format('d/m/Y H:i') }}</span>
                                                </td>
                                                @if(Auth::user()->isAdmin())
                                                    <td>
                                                        <span class="text-sm font-weight-normal text-bright-white">{{ $certificate->registration->user->name }}</span>
                                                    </td>
                                                @endif
                                                <td>
                                                    <span class="badge bg-warning text-dark">{{ $certificate->download_count }} fois</span>
                                                </td>
                                                <td class="text-center">
                                                    <div class="d-flex align-items-center justify-content-center gap-2">
                                                        <a href="{{ route('certificates.show', $certificate->id) }}" 
                                                           class="btn btn-sm btn-outline-light" 
                                                           data-bs-toggle="tooltip" 
                                                           title="Voir le certificat">
                                                            <i class="fas fa-eye"></i>
                                                            <span class="d-none d-lg-inline ms-1">Voir</span>
                                                        </a>
                                                        <a href="{{ route('certificates.download', $certificate->id) }}" 
                                                           class="btn btn-sm btn-success-gradient" 
                                                           data-bs-toggle="tooltip" 
                                                           title="Télécharger le certificat">
                                                            <i class="fas fa-download"></i>
                                                            <span class="d-none d-lg-inline ms-1">Télécharger</span>
                                                        </a>
                                                        <form action="{{ route('certificates.destroy', $certificate->id) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" 
                                                                    class="btn btn-sm btn-outline-danger" 
                                                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce certificat ?');"
                                                                    data-bs-toggle="tooltip" 
                                                                    title="Supprimer le certificat">
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

    <style>
        /* Professional Dark Theme Variables */
        :root {
            --color-success-dark: #388e3c;
            --color-success-bright: #c8e6c9;
            --color-info-bright: #b3e5fc;
            --color-dark-main-bg: #102027;
            --color-section-dark: #1a3038;
            --color-dark-navbar-bg: rgba(16, 32, 39, 0.95);
            --color-nav-link: #d4edda;
            --color-success-bright-nav: #81c784;
            --color-dark-input: #2c3e50;
            --color-border-light: rgba(255, 255, 255, 0.1);
        }

        /* Global Styles */
        .main-content-wrapper {
            margin-top: 100px;
        }

        .text-bright-white { 
            color: #fafafa !important; 
        }

        .text-muted {
            color: rgba(255, 255, 255, 0.6) !important;
        }

        /* Section Background */
        .section-dark-bg {
            background-color: var(--color-section-dark) !important;
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            border: 1px solid var(--color-border-light);
        }

        /* Dark Input Styling */
        .bg-dark-input {
            background-color: var(--color-dark-input) !important;
            border-color: #34495e !important;
        }

        /* Button Gradients */
        .btn-success-gradient {
            background: linear-gradient(135deg, #66bb6a 0%, #43a047 100%);
            border: none;
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-success-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(76, 175, 80, 0.4);
            color: white;
        }

        .bg-success-gradient {
            background: linear-gradient(135deg, #66bb6a 0%, #43a047 100%) !important;
        }

        /* Table Styling */
        .table {
            --bs-table-bg: transparent;
            --bs-table-color: var(--text-bright-white);
            border-color: var(--color-border-light);
        }

        .table-hover tbody tr:hover {
            background-color: rgba(255, 255, 255, 0.05);
        }

        .table th {
            border-bottom: 1px solid var(--color-border-light);
            font-weight: 600;
            font-size: 0.8rem;
            padding: 1rem 0.75rem;
        }

        .table td {
            border-bottom: 1px solid var(--color-border-light);
            padding: 1rem 0.75rem;
            vertical-align: middle;
        }

        /* Badge Enhancements */
        .badge-pill {
            border-radius: 50rem;
        }

        /* Canvas Background */
        .fixed-canvas {
            position: fixed;
            top: 0;
            left: 0;
            z-index: -2;
            width: 100vw;
            height: 100vh;
            background-color: var(--color-dark-main-bg);
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .main-content-wrapper {
                margin-top: 80px;
            }
            
            .table-responsive {
                font-size: 0.875rem;
            }
            
            .btn span.d-none.d-lg-inline {
                display: none !important;
            }
            
            .d-flex.justify-content-between.align-items-center {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }
        }

        @media (max-width: 576px) {
            .table-responsive {
                font-size: 0.8rem;
            }
            
            .btn {
                padding: 0.25rem 0.5rem;
                font-size: 0.75rem;
            }
            
            .gap-2 {
                gap: 0.5rem !important;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });

            // Particle Background
            const canvas = document.getElementById('fullScreenCanvas');
            if (!canvas) return;

            const ctx = canvas.getContext('2d');
            let width, height;
            let mouseX = 0, mouseY = 0;
            let particles = [];
            const particleCount = 80;
            const maxDistance = 100;

            function resizeCanvas() {
                width = window.innerWidth;
                height = window.innerHeight;
                canvas.width = width;
                canvas.height = height;
            }

            class Particle {
                constructor(x, y) {
                    this.x = x;
                    this.y = y;
                    this.size = Math.random() * 2 + 1;
                    this.speedX = Math.random() * 0.3 - 0.15;
                    this.speedY = Math.random() * 0.3 - 0.15;
                    this.color = `rgba(${Math.floor(Math.random() * 50)}, ${Math.floor(180 + Math.random() * 75)}, ${Math.floor(180 + Math.random() * 50)}, 0.6)`;
                }

                update() {
                    this.x += this.speedX;
                    this.y += this.speedY;

                    if (this.x > width || this.x < 0) this.speedX *= -1;
                    if (this.y > height || this.y < 0) this.speedY *= -1;
                }

                draw() {
                    ctx.fillStyle = this.color;
                    ctx.beginPath();
                    ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
                    ctx.fill();
                }
            }

            function init() {
                particles = [];
                for (let i = 0; i < particleCount; i++) {
                    const x = Math.random() * width;
                    const y = Math.random() * height;
                    particles.push(new Particle(x, y));
                }
            }

            function connectParticles() {
                for (let i = 0; i < particles.length; i++) {
                    for (let j = i; j < particles.length; j++) {
                        const dist = Math.sqrt(
                            Math.pow(particles[i].x - particles[j].x, 2) + 
                            Math.pow(particles[i].y - particles[j].y, 2)
                        );

                        if (dist < maxDistance) {
                            ctx.strokeStyle = `rgba(0, 150, 0, ${0.3 - dist / maxDistance})`;
                            ctx.lineWidth = 0.3;
                            ctx.beginPath();
                            ctx.moveTo(particles[i].x, particles[i].y);
                            ctx.lineTo(particles[j].x, particles[j].y);
                            ctx.stroke();
                        }
                    }
                }
            }

            function connectToMouse() {
                for (let i = 0; i < particles.length; i++) {
                    const dist = Math.sqrt(
                        Math.pow(particles[i].x - mouseX, 2) + 
                        Math.pow(particles[i].y - mouseY, 2)
                    );

                    if (dist < maxDistance + 30) {
                        ctx.strokeStyle = `rgba(150, 255, 150, ${0.5 - dist / (maxDistance + 30)})`;
                        ctx.lineWidth = 0.8;
                        ctx.beginPath();
                        ctx.moveTo(particles[i].x, particles[i].y);
                        ctx.lineTo(mouseX, mouseY);
                        ctx.stroke();
                    }
                }
            }

            function animate() {
                requestAnimationFrame(animate);
                ctx.fillStyle = 'rgba(10, 30, 40, 0.03)';
                ctx.fillRect(0, 0, width, height);

                connectParticles();
                connectToMouse();

                particles.forEach(particle => {
                    particle.update();
                    particle.draw();
                });
            }

            document.addEventListener('mousemove', (e) => {
                mouseX = e.clientX;
                mouseY = e.clientY;
            });
            
            window.addEventListener('resize', resizeCanvas);

            resizeCanvas();
            init();
            animate();
        });
    </script>
</x-app-layout>