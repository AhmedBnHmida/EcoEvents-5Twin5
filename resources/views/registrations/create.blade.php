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
                            <a href="{{ route('events.public') }}" class="text-decoration-none text-bright-white">Événements</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('events.public.show', $event->id) }}" class="text-decoration-none text-bright-white">{{ Str::limit($event->title, 30) }}</a>
                        </li>
                        <li class="breadcrumb-item active text-success fw-semibold">Inscription</li>
                    </ol>
                </nav>

                <!-- Success/Info/Error Messages -->
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

                @if(session('info'))
                <div class="alert alert-info alert-dismissible fade show shadow-lg border-0 mb-4 section-dark-bg" role="alert">
                    <i class="fas fa-info-circle me-2 text-info"></i>
                    <span class="text-bright-white">{{ session('info') }}</span>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
                </div>
                @endif

                <div class="card shadow-lg border-0 section-dark-bg">
                    <div class="card-header bg-gradient-success text-white border-0 py-4">
                        <h4 class="mb-0">
                            <i class="fas fa-ticket-alt me-2"></i>Inscription à l'événement
                        </h4>
                    </div>
                    <div class="card-body p-4">
                        <!-- Event Summary -->
                        <div class="alert alert-info border-0 section-dark-bg mb-4">
                            <h5 class="font-weight-bold text-bright-white mb-3">{{ $event->title }}</h5>
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <p class="mb-2 text-bright-white">
                                        <i class="fas fa-calendar text-success me-2"></i>
                                        <strong>Date:</strong> {{ $event->start_date->format('d/m/Y à H:i') }}
                                    </p>
                                    <p class="mb-2 text-bright-white">
                                        <i class="fas fa-map-marker-alt text-success me-2"></i>
                                        <strong>Lieu:</strong> {{ $event->location }}
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-2 text-bright-white">
                                        <i class="fas fa-tag text-success me-2"></i>
                                        <strong>Prix:</strong> 
                                        @if($event->price > 0)
                                            ${{ number_format($event->price, 2) }}
                                        @else
                                            <span class="text-success">Gratuit</span>
                                        @endif
                                    </p>
                                    <p class="mb-2 text-bright-white">
                                        <i class="fas fa-users text-success me-2"></i>
                                        <strong>Places restantes:</strong> {{ $event->capacity_max - $event->registrations->count() }}/{{ $event->capacity_max }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Registration Form -->
                        <form action="{{ route('registrations.store') }}" method="POST" class="mt-4">
                            @csrf
                            <input type="hidden" name="event_id" value="{{ $event->id }}">

                            <div class="mb-4">
                                <h5 class="mb-3 text-bright-white">Vos informations</h5>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label text-bright-white">Nom complet</label>
                                        <input type="text" class="form-control bg-dark-input text-white" value="{{ auth()->user()->name }}" disabled>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label text-bright-white">Email</label>
                                        <input type="email" class="form-control bg-dark-input text-white" value="{{ auth()->user()->email }}" disabled>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <div class="card border-0 rounded-3 shadow-sm section-dark-bg">
                                    <div class="card-header bg-transparent border-bottom py-3">
                                        <h5 class="mb-0 fs-6 fw-bold text-bright-white">Conditions de participation</h5>
                                    </div>
                                    <div class="card-body">
                                        <p class="text-muted small mb-3">En vous inscrivant à un événement organisé sur la plateforme EcoEvents, vous acceptez les conditions suivantes :</p>
                                        
                                        <div class="mb-3">
                                            <p class="mb-1 fw-bold text-bright-white"><i class="fas fa-leaf text-success me-2"></i>1️⃣ Respect de l'environnement :</p>
                                            <p class="text-muted small ms-4">Vous vous engagez à adopter un comportement éco-responsable avant, pendant et après l'événement (zéro déchet, recyclage, utilisation de transports durables, respect des espaces verts, etc.).</p>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <p class="mb-1 fw-bold text-bright-white"><i class="fas fa-mobile-alt text-success me-2"></i>2️⃣ Utilisation des ressources numériques :</p>
                                            <p class="text-muted small ms-4">Votre billet électronique remplace les billets papier afin de réduire l'impact écologique. Vous acceptez de le présenter sous forme numérique (QR code).</p>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <p class="mb-1 fw-bold text-bright-white"><i class="fas fa-shield-alt text-success me-2"></i>3️⃣ Protection des données personnelles :</p>
                                            <p class="text-muted small ms-4">Les informations que vous fournissez (nom, email, numéro de téléphone) sont utilisées uniquement pour la gestion de votre inscription, la communication d'informations liées à l'événement et l'envoi de rappels.<br>Aucune donnée ne sera partagée avec des tiers sans votre accord explicite.</p>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <p class="mb-1 fw-bold text-bright-white"><i class="fas fa-handshake text-success me-2"></i>4️⃣ Engagement citoyen :</p>
                                            <p class="text-muted small ms-4">Vous vous engagez à participer activement, à respecter les intervenants, les bénévoles et les autres participants, dans un esprit de collaboration et de durabilité.</p>
                                        </div>
                                        
                                        <div class="mb-0">
                                            <p class="mb-1 fw-bold text-bright-white"><i class="fas fa-calendar-times text-success me-2"></i>5️⃣ Annulation responsable :</p>
                                            <p class="text-muted small ms-4">En cas d'empêchement, merci de signaler votre annulation au plus tard 24 heures avant l'événement afin de libérer une place pour un autre participant.</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-check mt-3">
                                    <input class="form-check-input" type="checkbox" id="terms" required>
                                    <label class="form-check-label fw-bold text-bright-white" for="terms">
                                        J'ai lu et j'accepte les conditions de participation ci-dessus
                                    </label>
                                </div>
                            </div>

                            <div class="alert alert-warning border-0 section-dark-bg mb-4">
                                <i class="fas fa-info-circle me-2 text-warning"></i>
                                <strong class="text-bright-white">Important:</strong> 
                                <span class="text-muted">Après votre inscription, vous recevrez un code de ticket unique et un code QR. Votre inscription sera en attente de confirmation par l'administrateur.</span>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('events.public.show', $event->id) }}" class="btn btn-outline-light">
                                    <i class="fas fa-arrow-left me-2"></i>Retour
                                </a>
                                <button type="submit" class="btn btn-success-gradient">
                                    <i class="fas fa-check me-2"></i>Confirmer mon inscription
                                </button>
                            </div>
                        </form>
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