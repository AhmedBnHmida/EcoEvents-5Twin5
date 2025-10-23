<x-app-layout>
    <canvas id="fullScreenCanvas" class="fixed-canvas"></canvas>
    <x-front-navbar />
    
    <div class="container py-5 main-content-wrapper">
        <!-- Page Header -->
        <div class="row mb-5">
            <div class="col-12 text-center">
                <span class="badge bg-success-gradient text-uppercase py-2 px-3 mb-3 badge-pill">Paiement</span>
                <h1 class="display-5 fw-bold text-bright-white mb-3">
                    <i class="fas fa-credit-card me-3"></i>Finaliser votre inscription
                </h1>
                <p class="lead text-muted">
                    Procédez au paiement sécurisé pour confirmer votre participation
                </p>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Breadcrumb -->
                <nav aria-label="breadcrumb" class="mb-4">
                    <ol class="breadcrumb px-3 py-2 rounded-3 section-dark-bg">
                        <li class="breadcrumb-item">
                            <a href="/" class="text-decoration-none text-bright-white">
                                <i class="fas fa-home me-1"></i>Accueil
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('registrations.my') }}" class="text-decoration-none text-bright-white">
                                Mes inscriptions
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('registrations.show', $registration->id) }}" class="text-decoration-none text-bright-white">
                                Détails
                            </a>
                        </li>
                        <li class="breadcrumb-item active text-success fw-semibold">Paiement</li>
                    </ol>
                </nav>

                <!-- Error Message -->
                @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show shadow-lg border-0 mb-4 section-dark-bg" role="alert">
                    <i class="fas fa-exclamation-circle me-2 text-danger"></i>
                    <span class="text-bright-white">{{ session('error') }}</span>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
                </div>
                @endif

                <div class="card shadow-lg border-0 section-dark-bg">
                    <div class="card-header bg-gradient-success text-white border-0 py-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="mb-0">
                                <i class="fas fa-credit-card me-2"></i>Paiement sécurisé
                            </h4>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h5 class="font-weight-bold text-bright-white mb-3">Détails de l'événement</h5>
                                <div class="mb-3">
                                    <h6 class="text-success fw-semibold">{{ $event->title }}</h6>
                                    <p class="text-muted small">{{ \Illuminate\Support\Str::limit($event->description, 100) }}</p>
                                </div>
                                <div class="mb-3">
                                    <p class="mb-2">
                                        <i class="fas fa-calendar me-2 text-success"></i>
                                        <span class="text-bright-white">{{ $event->start_date->format('d/m/Y H:i') }}</span>
                                    </p>
                                    <p class="mb-0">
                                        <i class="fas fa-map-marker-alt me-2 text-success"></i>
                                        <span class="text-bright-white">{{ $event->location }}</span>
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card bg-dark-input border-0 shadow-sm">
                                    <div class="card-body">
                                        <h5 class="text-bright-white mb-3">Récapitulatif</h5>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span class="text-muted">Prix de l'inscription:</span>
                                            <span class="text-bright-white fw-semibold">{{ number_format($event->price, 2) }} €</span>
                                        </div>
                                        <hr class="border-secondary my-3">
                                        <div class="d-flex justify-content-between">
                                            <span class="text-bright-white fw-bold">Total:</span>
                                            <span class="text-success fw-bold fs-5">{{ number_format($event->price, 2) }} €</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-center mb-4">
                            <button id="checkout-button" class="btn btn-success-gradient btn-lg px-5 py-3">
                                <i class="fas fa-lock me-2"></i>Procéder au paiement sécurisé
                            </button>
                        </div>

                        <div class="text-center">
                            <p class="text-muted small mb-2">
                                <i class="fas fa-shield-alt me-1 text-success"></i>Paiement 100% sécurisé par Stripe
                            </p>
                            <div class="mt-2">
                                <img src="https://b.stripecdn.com/docs-statics-srv/assets/e0e2c8e4e3e9c6e89c7d12ac95628c56.png" alt="Stripe" style="height: 35px; filter: brightness(0) invert(1); opacity: 0.8;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://js.stripe.com/v3/"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Particle Background
            const canvas = document.getElementById('fullScreenCanvas');
            if (canvas) {
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
            }

            // Stripe Payment
            const stripe = Stripe('{{ $stripe_key }}');
            const checkoutButton = document.getElementById('checkout-button');
            
            checkoutButton.addEventListener('click', function() {
                // Disable the button to prevent multiple clicks
                checkoutButton.disabled = true;
                checkoutButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Traitement en cours...';
                
                // Redirect to Stripe Checkout
                stripe.redirectToCheckout({
                    sessionId: '{{ $checkout_session_id }}'
                }).then(function(result) {
                    if (result.error) {
                        // Display error to customer
                        alert(result.error.message);
                        checkoutButton.disabled = false;
                        checkoutButton.innerHTML = '<i class="fas fa-lock me-2"></i>Procéder au paiement sécurisé';
                    }
                });
            });
        });
    </script>

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
            box-shadow: 0 4px 15px rgba(76, 175, 80, 0.3);
        }

        .btn-success-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(76, 175, 80, 0.4);
            color: white;
        }

        .btn-success-gradient:disabled {
            opacity: 0.7;
            transform: none;
            box-shadow: none;
        }

        .bg-gradient-success {
            background: linear-gradient(135deg, #66bb6a 0%, #43a047 100%) !important;
        }

        .bg-success-gradient {
            background: linear-gradient(135deg, #66bb6a 0%, #43a047 100%) !important;
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
            
            .d-flex.justify-content-between.align-items-center {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }
            
            .btn-lg {
                padding: 0.75rem 1.5rem;
                font-size: 1rem;
            }
        }

        @media (max-width: 576px) {
            .display-5 {
                font-size: 2rem;
            }
            
            .card-body {
                padding: 1.5rem !important;
            }
        }
    </style>
</x-app-layout>