<x-app-layout>
    <canvas id="fullScreenCanvas" class="fixed-canvas"></canvas>
    
    <x-front-navbar />
    
    <div class="container py-5 main-content-wrapper">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <nav aria-label="breadcrumb" class="mb-4">
                    <ol class="breadcrumb px-3 py-2 rounded-3 section-dark-bg">
                        <li class="breadcrumb-item">
                            <a href="/" class="text-decoration-none text-bright-white">
                                <i class="fas fa-home me-1"></i>Accueil
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('registrations.my') }}" class="text-decoration-none text-bright-white">Mes inscriptions</a>
                        </li>
                        <li class="breadcrumb-item active text-success fw-semibold">Détails</li>
                    </ol>
                </nav>

                <!-- Success Message -->
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-lg border-0 mb-4 section-dark-bg" role="alert">
                    <i class="fas fa-check-circle me-2 text-success"></i>
                    <span class="text-bright-white">{{ session('success') }}</span>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
                </div>
                @endif

                <div class="card shadow-lg border-0 section-dark-bg">
                    <div class="card-header bg-gradient-success text-white border-0 py-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="mb-0">
                                <i class="fas fa-ticket-alt me-2"></i>Votre Inscription
                            </h4>
                            <span class="badge bg-{{ $registration->status->color() }} text-xs">
                                {{ $registration->status->label() }}
                            </span>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <!-- Event Info -->
                        <div class="mb-4">
                            <h5 class="font-weight-bold text-bright-white">{{ $registration->event->title }}</h5>
                            <p class="text-muted">{{ $registration->event->description }}</p>
                        </div>

                        <!-- Registration Details -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="text-muted text-xs">Code de ticket</label>
                                    <h4 class="font-weight-bold text-success">{{ $registration->ticket_code }}</h4>
                                </div>
                                <div class="mb-3">
                                    <label class="text-muted text-xs">Date d'inscription</label>
                                    <p class="mb-0 text-bright-white">{{ $registration->registered_at->format('d/m/Y à H:i') }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="text-muted text-xs">Statut</label>
                                    <p class="mb-0">
                                        <span class="badge bg-{{ $registration->status->color() }}">
                                            {{ $registration->status->label() }}
                                        </span>
                                    </p>
                                </div>
                                <div class="mb-3">
                                    <label class="text-muted text-xs">Participant</label>
                                    <p class="mb-0 text-bright-white">{{ $registration->user->name }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- QR Code -->
                        <div class="text-center mb-4">
                            <div class="card bg-dark-input border-0">
                                <div class="card-body">
                                    <h6 class="mb-3 text-bright-white">Code QR</h6>
                                    @if(file_exists(storage_path('app/public/' . $registration->qr_code_path)))
                                        <img src="{{ asset('storage/' . $registration->qr_code_path) }}" alt="QR Code" class="img-fluid" style="max-width: 200px;">
                                    @else
                                        <div class="bg-white p-4 d-inline-block border rounded">
                                            <p class="mb-0 text-xs font-monospace text-dark">{{ $registration->ticket_code }}</p>
                                        </div>
                                    @endif
                                    <p class="text-muted text-xs mt-2 mb-0">Présentez ce code QR à l'entrée de l'événement</p>
                                </div>
                            </div>
                        </div>

                        <!-- Event Details -->
                        <div class="card bg-dark-input border-0 mb-4">
                            <div class="card-body">
                                <h6 class="mb-3 text-bright-white">Détails de l'événement</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="mb-2 text-bright-white">
                                            <i class="fas fa-calendar text-success me-2"></i>
                                            <strong>Date:</strong> {{ $registration->event->start_date->format('d/m/Y') }}
                                        </p>
                                        <p class="mb-2 text-bright-white">
                                            <i class="fas fa-clock text-success me-2"></i>
                                            <strong>Heure:</strong> {{ $registration->event->start_date->format('H:i') }}
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="mb-2 text-bright-white">
                                            <i class="fas fa-map-marker-alt text-success me-2"></i>
                                            <strong>Lieu:</strong> {{ $registration->event->location }}
                                        </p>
                                        <p class="mb-2 text-bright-white">
                                            <i class="fas fa-tag text-success me-2"></i>
                                            <strong>Prix:</strong> 
                                            @if($registration->event->price > 0)
                                                ${{ number_format($registration->event->price, 2) }}
                                            @else
                                                <span class="text-success">Gratuit</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('registrations.my') }}" class="btn btn-outline-light">
                                <i class="fas fa-arrow-left me-2"></i>Mes inscriptions
                            </a>
                            @if($registration->status->value !== 'canceled' && $registration->user_id === auth()->id())
                            <form action="{{ route('registrations.destroy', $registration->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir annuler votre inscription ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-times me-2"></i>Annuler mon inscription
                                </button>
                            </form>
                            @endif
                        </div>
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
            color: white !important;
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
            
            .d-flex.justify-content-between {
                flex-direction: column;
                gap: 1rem;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
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