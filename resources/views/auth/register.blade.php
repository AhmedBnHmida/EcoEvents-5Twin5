<x-app-layout>
    <canvas id="fullScreenCanvas" class="fixed-canvas"></canvas>

    <x-front-navbar />

    <main class="main-content-wrapper">
        <section class="py-6 py-md-9 border-bottom">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-xl-5 col-lg-6 col-md-8 d-flex flex-column mx-auto">
                        <div class="card section-dark-bg border-0 shadow-hover-3d">
                            <div class="card-header pb-0 text-center bg-transparent">
                                <h3 class="font-weight-black text-dark-title display-6 mb-2">Rejoignez EcoEvents</h3>
                                <p class="text-muted mb-0">Créez votre compte pour participer aux événements écologiques</p>
                            </div>
                            <div class="card-body px-lg-5 py-lg-5">
                                <!-- Session Status -->
                                <x-auth-session-status class="mb-4" :status="session('status')" />
                                
                                <form method="POST" action="{{ route('register') }}" role="form">
                                    @csrf
                                    
                                    <div class="mb-4">
                                        <label class="form-label text-dark-title">Nom</label>
                                        <div class="input-group input-group-alternative">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-user text-success"></i></span>
                                            </div>
                                            <input type="text" name="name" class="form-control form-control-dark" placeholder="Votre nom complet" aria-label="Name" value="{{ old('name') }}" required autofocus autocomplete="name">
                                        </div>
                                        <x-input-error :messages="$errors->get('name')" class="mt-2 text-danger" />
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label class="form-label text-dark-title">Adresse Email</label>
                                        <div class="input-group input-group-alternative">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-envelope text-success"></i></span>
                                            </div>
                                            <input type="email" name="email" class="form-control form-control-dark" placeholder="Votre adresse email" aria-label="Email" value="{{ old('email') }}" required autocomplete="username">
                                        </div>
                                        <x-input-error :messages="$errors->get('email')" class="mt-2 text-danger" />
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label class="form-label text-dark-title">Mot de passe</label>
                                        <div class="input-group input-group-alternative">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-lock text-success"></i></span>
                                            </div>
                                            <input type="password" name="password" class="form-control form-control-dark" placeholder="Votre mot de passe" aria-label="Password" required autocomplete="new-password">
                                        </div>
                                        <x-input-error :messages="$errors->get('password')" class="mt-2 text-danger" />
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label class="form-label text-dark-title">Confirmer le mot de passe</label>
                                        <div class="input-group input-group-alternative">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-lock text-success"></i></span>
                                            </div>
                                            <input type="password" name="password_confirmation" class="form-control form-control-dark" placeholder="Confirmez votre mot de passe" aria-label="Confirm Password" required autocomplete="new-password">
                                        </div>
                                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-danger" />
                                    </div>
                                    
                                    <div class="form-check mb-4">
                                        <input class="form-check-input" type="checkbox" value="1" id="flexCheckDefault" name="terms" required>
                                        <label class="form-check-label text-muted" for="flexCheckDefault">
                                            J'accepte les <a href="#" class="text-success-bright">conditions d'utilisation</a> et la <a href="#" class="text-success-bright">politique de confidentialité</a>
                                        </label>
                                        <x-input-error :messages="$errors->get('terms')" class="mt-2 text-danger" />
                                    </div>
                                    
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-success-gradient w-100 py-3 mb-3">
                                            <i class="fas fa-user-plus me-2"></i>Créer mon compte
                                        </button>
                                        <button type="button" class="btn btn-outline-success w-100 mb-3">
                                            <span class="btn-inner--icon me-1">
                                                <i class="fab fa-google"></i>
                                            </span>
                                            <span class="btn-inner--text">S'inscrire avec Google</span>
                                        </button>
                                    </div>
                                </form>
                            </div>
                            <div class="card-footer text-center pt-0 px-lg-5 px-3">
                                <p class="mb-4 text-muted">
                                    Déjà un compte ?
                                    <a href="{{ route('login') }}" class="text-success-bright font-weight-bold">Se connecter</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

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

        /* Section Background - Enhanced */
        .section-dark-bg {
            background-color: var(--color-section-dark) !important;
            border-radius: 16px;
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.4);
            border: 1px solid var(--color-border-light);
            padding: 2.5rem 1.5rem;
            margin-bottom: 2rem;
        }

        /* Card Enhancements */
        .card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            overflow: hidden;
        }

        .shadow-hover-3d {
            transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        }

        .shadow-hover-3d:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 25px 50px rgba(0, 150, 0, 0.25), 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        /* Button Gradients - Enhanced */
        .btn-success-gradient {
            background: linear-gradient(135deg, #66bb6a 0%, #43a047 100%);
            border: none;
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
            border-radius: 8px;
        }

        .btn-success-gradient:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(76, 175, 80, 0.5);
            color: white;
        }

        .btn-outline-success {
            border: 2px solid #66bb6a;
            color: #66bb6a;
            background: transparent;
            transition: all 0.3s ease;
        }

        .btn-outline-success:hover {
            background: #66bb6a;
            color: white;
            transform: translateY(-2px);
        }

        /* Form Styling */
        .form-control-dark {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            border-radius: 8px;
            padding: 12px 16px;
            transition: all 0.3s ease;
        }

        .form-control-dark:focus {
            background: rgba(255, 255, 255, 0.12);
            border-color: #66bb6a;
            box-shadow: 0 0 0 2px rgba(102, 187, 106, 0.25);
            color: white;
        }

        .form-control-dark::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }

        .input-group-alternative {
            box-shadow: none;
            border: none;
        }

        .input-group-text {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-right: none;
            color: var(--color-success-bright);
        }

        .input-group .form-control-dark {
            border-left: none;
        }

        .form-label {
            color: var(--color-success-bright);
            font-weight: 600;
            margin-bottom: 8px;
        }

        .form-check-input {
            background-color: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .form-check-input:checked {
            background-color: #66bb6a;
            border-color: #66bb6a;
        }

        /* Text Colors */
        .text-dark-title { 
            color: white !important; 
            font-weight: 700;
        }

        .text-success-bright { 
            color: var(--color-success-bright) !important; 
        }
        
        .text-info-bright { 
            color: var(--color-info-bright) !important; 
        }

        /* Hero Section */
        .hero-section-dark-bg {
            padding-top: 120px !important;
            background: linear-gradient(135deg, var(--color-dark-main-bg) 0%, #0a1920 100%) !important;
            position: relative;
            overflow: hidden;
        }

        .text-shadow-dark {
            text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.7);
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

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .main-content-wrapper {
                margin-top: 80px;
            }
            
            .section-dark-bg {
                padding: 1.5rem 1rem;
                border-radius: 12px;
            }
            
            .display-6 {
                font-size: 1.8rem;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Particle Background - Same as landing page
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