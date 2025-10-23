<x-app-layout>
    <canvas id="fullScreenCanvas" class="fixed-canvas"></canvas>
    
    <x-front-navbar />
    
    <div class="container py-5 main-content-wrapper">
        <div class="row">
            <div class="col-12">
                <nav aria-label="breadcrumb" class="mb-4">
                    <ol class="breadcrumb px-3 py-2 rounded-3 section-dark-bg">
                        <li class="breadcrumb-item">
                            <a href="/" class="text-decoration-none text-bright-white">
                                <i class="fas fa-home me-1"></i>Accueil
                            </a>
                        </li>
                        <li class="breadcrumb-item active text-success fw-semibold">Mes inscriptions</li>
                    </ol>
                </nav>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="font-weight-bold mb-0 text-bright-white">Mes Inscriptions</h2>
                    <a href="{{ route('events.public') }}" class="btn btn-success-gradient">
                        <i class="fas fa-calendar me-2"></i>Découvrir des événements
                    </a>
                </div>

                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-lg border-0 mb-4 section-dark-bg" role="alert">
                    <i class="fas fa-check-circle me-2 text-success"></i>
                    <span class="text-bright-white">{{ session('success') }}</span>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
                </div>
                @endif

                @forelse($registrations as $registration)
                <div class="card shadow-lg border-0 mb-4 section-dark-bg">
                    <div class="card-body p-4">
                        <div class="row align-items-center">
                            <div class="col-md-2 text-center">
                                @if($registration->event->images && count($registration->event->images) > 0)
                                    <img src="{{ $registration->event->images[0] }}" alt="{{ $registration->event->title }}" class="img-fluid rounded card-img-eco" style="max-height: 100px; object-fit: cover;">
                                @else
                                    <div class="bg-gradient-success rounded d-flex align-items-center justify-content-center" style="height: 100px;">
                                        <i class="fas fa-leaf text-white fa-2x"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <h5 class="font-weight-bold mb-2 text-bright-white">{{ $registration->event->title }}</h5>
                                <p class="text-muted text-sm mb-2">{{ Str::limit($registration->event->description, 100) }}</p>
                                <div class="d-flex gap-3 text-xs text-muted">
                                    <span>
                                        <i class="fas fa-calendar me-1"></i>
                                        {{ $registration->event->start_date->format('d/m/Y') }}
                                    </span>
                                    <span>
                                        <i class="fas fa-clock me-1"></i>
                                        {{ $registration->event->start_date->format('H:i') }}
                                    </span>
                                    <span>
                                        <i class="fas fa-map-marker-alt me-1"></i>
                                        {{ Str::limit($registration->event->location, 20) }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-2 text-center">
                                <label class="text-muted text-xs d-block mb-1">Statut</label>
                                <span class="badge bg-{{ $registration->status->color() }} mb-2">
                                    {{ $registration->status->label() }}
                                </span>
                                <p class="text-xs text-muted mb-0">
                                    <strong>Code:</strong><br>{{ $registration->ticket_code }}
                                </p>
                            </div>
                            <div class="col-md-2 text-center">
                                <div class="d-flex flex-column gap-2">
                                    <a href="{{ route('registrations.show', $registration->id) }}" class="btn btn-sm btn-success-gradient">
                                        <i class="fas fa-eye me-1"></i>Détails
                                    </a>
                                    
                                    @if($registration->status->value === 'pending' && $registration->event->price > 0)
                                    <a href="{{ route('payment.checkout', $registration->id) }}" class="btn btn-sm btn-payment-small w-100">
                                        <i class="fas fa-credit-card me-1"></i>Payer
                                    </a>
                                    @endif
                                    
                                    @if($registration->status->value !== 'canceled')
                                    <form action="{{ route('registrations.destroy', $registration->id) }}" method="POST" onsubmit="return confirm('Annuler votre inscription ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger w-100">
                                            <i class="fas fa-times me-1"></i>Annuler
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="card shadow-lg border-0 section-dark-bg">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-calendar-times text-muted fa-3x mb-3"></i>
                        <h5 class="text-bright-white">Aucune inscription</h5>
                        <p class="text-muted mb-4">Vous n'êtes inscrit à aucun événement pour le moment.</p>
                        <a href="{{ route('events.public') }}" class="btn btn-success-gradient">
                            <i class="fas fa-search me-2"></i>Découvrir des événements
                        </a>
                    </div>
                </div>
                @endforelse

                <!-- Pagination -->
                @if($registrations->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $registrations->links() }}
                </div>
                @endif
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
            --color-payment-gradient-start: #4facfe;
            --color-payment-gradient-end: #00f2fe;
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

        /* Card Image */
        .card-img-eco {
            object-fit: cover;
            border-radius: 8px;
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

        /* Payment Button Styles */
        .btn-payment-small {
            background: linear-gradient(135deg, var(--color-payment-gradient-start) 0%, var(--color-payment-gradient-end) 100%);
            border: none;
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 8px rgba(79, 172, 254, 0.3);
            position: relative;
            overflow: hidden;
        }
        
        .btn-payment-small:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(79, 172, 254, 0.4);
            color: white;
        }
        
        .btn-payment-small:active {
            transform: translateY(-1px);
        }
        
        .btn-payment-small::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, rgba(255,255,255,0) 0%, rgba(255,255,255,0.2) 50%, rgba(255,255,255,0) 100%);
            transform: translateX(-100%);
            transition: transform 0.6s;
        }
        
        .btn-payment-small:hover::before {
            transform: translateX(100%);
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .main-content-wrapper {
                margin-top: 80px;
            }
            
            .d-flex.justify-content-between.align-items-center {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
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