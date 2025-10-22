<x-app-layout>
    <canvas id="fullScreenCanvas" class="fixed-canvas"></canvas>
    <x-front-navbar />
    
    <div class="container py-5 main-content-wrapper">
        <!-- Page Header -->
        <div class="row mb-5">
            <div class="col-12 text-center">
                <span class="badge bg-success-gradient text-uppercase py-2 px-3 mb-3 badge-pill">Mes Avis</span>
                <h1 class="display-5 fw-bold text-bright-white mb-3">
                    <i class="fas fa-comments me-3"></i>Mes Avis
                </h1>
                <p class="lead text-muted">
                    Gérez tous vos commentaires et évaluations d'événements
                    <span class="badge bg-warning text-dark ms-2">{{ $feedbacks->total() }} avis</span>
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
                        <li class="breadcrumb-item active text-success fw-semibold">Mes avis</li>
                    </ol>
                </nav>

                <!-- Action Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="font-weight-bold mb-0 text-bright-white">Mes Évaluations</h2>
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

                @forelse($feedbacks as $feedback)
                <div class="card shadow-hover-3d border-0 mb-4 section-dark-bg">
                    <div class="card-body p-4">
                        <div class="row align-items-center">
                            <div class="col-md-3 text-center">
                                <div class="position-relative">
                                    @if($feedback->event->images && count($feedback->event->images) > 0)
                                        <img src="{{ $feedback->event->images[0] }}" alt="{{ $feedback->event->title }}" 
                                             class="img-fluid rounded card-img-eco" style="height: 120px; object-fit: cover;">
                                    @else
                                        <div class="bg-gradient-success rounded d-flex align-items-center justify-content-center" style="height: 120px;">
                                            <i class="fas fa-leaf text-white fa-2x"></i>
                                        </div>
                                    @endif
                                    <!-- Rating Badge -->
                                    <div class="position-absolute top-0 end-0">
                                        <div class="bg-success text-white rounded-3 px-3 py-2 shadow-sm">
                                            <div class="text-center">
                                                <span class="fw-bold d-block">{{ $feedback->note }}</span>
                                                <div class="rating-stars-small">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        @if ($i <= $feedback->note)
                                                            <i class="fas fa-star text-warning"></i>
                                                        @else
                                                            <i class="far fa-star text-warning"></i>
                                                        @endif
                                                    @endfor
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="feedback-content">
                                    <h4 class="event-title text-bright-white mb-2">{{ $feedback->event->title }}</h4>
                                    
                                    <!-- Rating Label -->
                                    <div class="rating-label mb-3">
                                        @php
                                            $ratingLabels = [
                                                1 => ['text' => 'Très mauvais', 'color' => 'danger'],
                                                2 => ['text' => 'Mauvais', 'color' => 'warning'],
                                                3 => ['text' => 'Moyen', 'color' => 'info'],
                                                4 => ['text' => 'Bon', 'color' => 'primary'],
                                                5 => ['text' => 'Excellent', 'color' => 'success']
                                            ];
                                            $currentRating = $ratingLabels[$feedback->note] ?? ['text' => 'Non évalué', 'color' => 'secondary'];
                                        @endphp
                                        <span class="badge bg-{{ $currentRating['color'] }} me-2">
                                            {{ $currentRating['text'] }}
                                        </span>
                                        
                                        @if($feedback->category)
                                            <span class="badge bg-secondary">
                                                @if($feedback->category->icon)
                                                    <i class="{{ $feedback->category->icon }} me-1"></i>
                                                @else
                                                    <i class="fas fa-tag me-1"></i>
                                                @endif
                                                {{ $feedback->category->name }}
                                            </span>
                                        @endif
                                    </div>
                                    
                                    <!-- Comment -->
                                    @if($feedback->commentaire)
                                    <div class="comment-container bg-dark-input rounded-3 p-3 mb-3">
                                        <div class="comment-text text-muted">
                                            <i class="fas fa-quote-left text-success me-2"></i>
                                            {{ Str::limit($feedback->commentaire, 120) }}
                                        </div>
                                    </div>
                                    @else
                                    <div class="no-comment text-muted mb-3">
                                        <i class="fas fa-comment-slash me-2"></i>
                                        <span>Aucun commentaire ajouté</span>
                                    </div>
                                    @endif
                                    
                                    <!-- Date -->
                                    <div class="feedback-date text-muted">
                                        <i class="fas fa-clock me-1"></i>
                                        @if($feedback->date_feedback)
                                            {{ $feedback->date_feedback->format('d/m/Y à H:i') }}
                                        @else
                                            Date non définie
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="action-buttons">
                                    <a href="{{ route('events.public.show', $feedback->event->id) }}" 
                                       class="btn btn-outline-light btn-sm w-100 mb-2">
                                        <i class="fas fa-eye me-2"></i>Voir l'événement
                                    </a>
                                    <a href="{{ route('feedback.edit', $feedback->id_feedback) }}" 
                                       class="btn btn-outline-primary btn-sm w-100 mb-2">
                                        <i class="fas fa-edit me-2"></i>Modifier
                                    </a>
                                    <form action="{{ route('feedback.destroy', $feedback->id_feedback) }}" 
                                          method="POST" 
                                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet avis ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm w-100">
                                            <i class="fas fa-trash me-2"></i>Supprimer
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="card shadow-lg border-0 section-dark-bg">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-comment-slash text-muted fa-3x mb-3"></i>
                        <h5 class="text-bright-white">Aucun avis</h5>
                        <p class="text-muted mb-4">Vous n'avez pas encore donné d'avis sur des événements.</p>
                        <a href="{{ route('events.public') }}" class="btn btn-success-gradient">
                            <i class="fas fa-search me-2"></i>Découvrir des événements
                        </a>
                    </div>
                </div>
                @endforelse

                <!-- Pagination -->
                @if($feedbacks->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $feedbacks->links() }}
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

        /* Card Enhancements */
        .card-img-eco {
            object-fit: cover;
            border-radius: 8px;
        }

        .shadow-hover-3d {
            transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        }

        .shadow-hover-3d:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 150, 0, 0.2), 0 8px 20px rgba(0, 0, 0, 0.1);
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

        /* Dark Input Styling */
        .bg-dark-input {
            background-color: var(--color-dark-input) !important;
            border-color: #34495e !important;
            color: white !important;
        }

        /* Rating Stars */
        .rating-stars-small {
            font-size: 0.7rem;
            line-height: 1;
        }

        /* Comment Container */
        .comment-container {
            border-left: 3px solid #66bb6a;
        }

        .event-title {
            font-size: 1.3rem;
            font-weight: 600;
            line-height: 1.4;
        }

        .rating-label {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            align-items: center;
        }

        .action-buttons {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
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
            
            .card-body {
                padding: 1.5rem !important;
            }
            
            .action-buttons {
                margin-top: 1rem;
            }
            
            .rating-label {
                justify-content: center;
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