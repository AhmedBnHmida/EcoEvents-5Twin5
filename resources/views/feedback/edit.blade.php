<x-app-layout>
    <canvas id="fullScreenCanvas" class="fixed-canvas"></canvas>
    <x-front-navbar />
    
    <div class="container py-5 main-content-wrapper">
        <!-- Page Header -->
        <div class="row mb-5">
            <div class="col-12 text-center">
                <span class="badge bg-success-gradient text-uppercase py-2 px-3 mb-3 badge-pill">Avis</span>
                <h1 class="display-5 fw-bold text-bright-white mb-3">
                    <i class="fas fa-edit me-3"></i>Modifier votre avis
                </h1>
                <p class="lead text-muted">
                    Partagez votre expérience et aidez-nous à nous améliorer
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
                            <a href="{{ route('feedback.my') }}" class="text-decoration-none text-bright-white">
                                Mes avis
                            </a>
                        </li>
                        <li class="breadcrumb-item active text-success fw-semibold">Modifier</li>
                    </ol>
                </nav>

                <div class="card shadow-lg border-0 section-dark-bg">
                    <div class="card-header bg-gradient-success text-white border-0 py-4">
                        <h4 class="mb-0">
                            <i class="fas fa-edit me-2"></i>Modifier votre évaluation
                        </h4>
                    </div>
                    <div class="card-body p-4">
                        <!-- Event Summary -->
                        <div class="alert section-dark-bg border-success mb-4">
                            <h5 class="font-weight-bold text-bright-white mb-3">{{ $feedback->event->title }}</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="mb-2 text-bright-white">
                                        <i class="fas fa-calendar me-2 text-success"></i>
                                        <strong>Date:</strong> 
                                        @if($feedback->event->start_date)
                                            {{ $feedback->event->start_date->format('d/m/Y à H:i') }}
                                        @else
                                            Date non définie
                                        @endif
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-2 text-bright-white">
                                        <i class="fas fa-clock me-2 text-success"></i>
                                        <strong>Avis donné le:</strong> 
                                        @if($feedback->date_feedback)
                                            {{ $feedback->date_feedback->format('d/m/Y') }}
                                        @else
                                            Date non définie
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Feedback Form -->
                        <form action="{{ route('feedback.update', $feedback->id_feedback) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <!-- Rating -->
                            <div class="mb-4">
                                <label class="form-label h5 text-bright-white">
                                    <i class="fas fa-star text-warning me-2"></i>Note de l'événement <span class="text-danger">*</span>
                                </label>
                                <p class="text-muted text-sm mb-3">Évaluez votre expérience de 1 à 5 étoiles</p>
                                
                                <div class="star-rating-container section-dark-bg p-4 rounded-3 border border-secondary">
                                    <div class="star-rating-input d-flex align-items-center justify-content-center flex-column">
                                        <div class="stars-wrapper d-flex align-items-center mb-3">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <div class="star-input me-1">
                                                    <input type="radio" name="note" value="{{ $i }}" id="star{{ $i }}" 
                                                           class="d-none" required {{ old('note', $feedback->note) == $i ? 'checked' : '' }}>
                                                    <label for="star{{ $i }}" class="mb-0 star-label" data-rating="{{ $i }}" 
                                                           style="cursor: pointer; font-size: 2.5rem; transition: all 0.2s ease;">
                                                        <i class="{{ old('note', $feedback->note) >= $i ? 'fas' : 'far' }} fa-star star-icon {{ old('note', $feedback->note) >= $i ? 'text-warning filled' : 'text-muted' }}" 
                                                           data-rating="{{ $i }}">{{ old('note', $feedback->note) >= $i ? '★' : '☆' }}</i>
                                                    </label>
                                                </div>
                                            @endfor
                                        </div>
                                        <div class="rating-feedback text-center">
                                            <span class="rating-text text-warning fs-5 font-weight-bold" id="ratingText">
                                                @php
                                                    $labels = ['Très mauvais', 'Mauvais', 'Moyen', 'Bon', 'Excellent'];
                                                    echo $labels[old('note', $feedback->note) - 1] ?? 'Cliquez sur les étoiles pour noter';
                                                @endphp
                                            </span>
                                            <div class="rating-description text-muted mt-1" id="ratingDescription">
                                                @php
                                                    $descriptions = [
                                                        'Cette expérience ne répondait pas à vos attentes',
                                                        'Cette expérience était en dessous de vos attentes',
                                                        'Cette expérience était correcte',
                                                        'Cette expérience était au-dessus de vos attentes',
                                                        'Cette expérience était exceptionnelle!'
                                                    ];
                                                    echo $descriptions[old('note', $feedback->note) - 1] ?? 'Votre avis nous aide à améliorer nos événements';
                                                @endphp
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @error('note')
                                    <span class="text-danger text-sm mt-2 d-block">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Category -->
                            <div class="mb-4">
                                <label class="form-label h5 text-bright-white">
                                    <i class="fas fa-tag me-2"></i>Catégorie
                                </label>
                                <p class="text-muted text-sm mb-3">Sélectionnez une catégorie pour votre avis (optionnel)</p>
                                <div class="category-container section-dark-bg rounded-3 border border-secondary p-3">
                                    <select id="feedback-category" name="category_id" class="form-control bg-dark-input border-secondary text-bright-white @error('category_id') is-invalid @enderror">
                                        <option value="">-- Sélectionnez une catégorie --</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" 
                                                {{ old('category_id', $feedback->category_id) == $category->id ? 'selected' : '' }}
                                                data-color="{{ $category->color }}" 
                                                data-icon="{{ $category->icon }}">
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <span class="text-danger text-sm mt-2 d-block">{{ $message }}</span>
                                    @enderror
                                    
                                    <div class="mt-3">
                                        <button type="button" id="ai-suggest-btn" class="btn btn-sm btn-ai-suggest" {{ !$feedback->category_id ? 'disabled' : '' }}>
                                            <i class="fas fa-robot me-1"></i>Suggérer un commentaire avec l'IA
                                        </button>
                                        <div id="ai-loading" class="d-none">
                                            <div class="spinner-border spinner-border-sm text-primary me-2" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                            <span class="text-bright-white">Génération en cours...</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Comment -->
                            <div class="mb-4">
                                <label class="form-label h5 text-bright-white">
                                    <i class="fas fa-comment me-2"></i>Votre commentaire
                                </label>
                                <p class="text-muted text-sm mb-3">Partagez votre expérience (optionnel)</p>
                                <div class="comment-container section-dark-bg rounded-3 border border-secondary p-3">
                                    <textarea 
                                        id="feedback-comment"
                                        name="commentaire" 
                                        class="form-control bg-dark-input border-0 shadow-none text-bright-white @error('commentaire') is-invalid @enderror" 
                                        rows="6" 
                                        maxlength="1000"
                                        style="background: transparent; resize: vertical;"
                                        placeholder="Qu'avez-vous pensé de cet événement ? Qu'est-ce qui vous a plu ou déplu ? Vos suggestions pour améliorer l'expérience sont les bienvenues...">{{ old('commentaire', $feedback->commentaire) }}</textarea>
                                    
                                    <!-- AI Suggestion Result -->
                                    <div id="ai-suggestion-container" class="ai-suggestion-container d-none">
                                        <div class="ai-suggestion-header">
                                            <i class="fas fa-robot me-2"></i>Suggestion IA
                                            <button type="button" class="btn-close btn-close-white btn-sm" aria-label="Close" id="close-suggestion"></button>
                                        </div>
                                        <div id="ai-suggestion-content" class="ai-suggestion-content text-bright-white"></div>
                                        <div class="ai-suggestion-footer">
                                            <button type="button" id="use-suggestion-btn" class="btn btn-sm btn-ai-use">
                                                <i class="fas fa-check me-1"></i>Utiliser cette suggestion
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <div class="d-flex justify-content-between align-items-center mt-2">
                                        <small class="text-muted">
                                            <i class="fas fa-info-circle me-1"></i>
                                            Votre commentaire aide à améliorer nos événements
                                        </small>
                                        <small class="text-muted">Maximum 1000 caractères</small>
                                    </div>
                                    @error('commentaire')
                                        <span class="text-danger text-sm mt-2 d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="d-flex justify-content-between pt-3">
                                <a href="{{ route('feedback.my') }}" class="btn btn-outline-light btn-lg px-4">
                                    <i class="fas fa-arrow-left me-2"></i>Retour
                                </a>
                                <button type="submit" class="btn btn-success-gradient btn-lg px-4 shadow">
                                    <i class="fas fa-save me-2"></i>Enregistrer les modifications
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
            color: #fafafa !important;
        }

        .bg-dark-input::placeholder {
            color: rgba(255, 255, 255, 0.5) !important;
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

        /* Star Rating Styles */
        .star-rating-container {
            background: linear-gradient(135deg, var(--color-section-dark) 0%, var(--color-dark-input) 100%);
            border: 2px solid var(--color-border-light) !important;
            box-shadow: 0 4px 6px rgba(0,0,0,0.3);
        }
        
        /* AI Suggestion Button */
        .btn-ai-suggest {
            background: linear-gradient(135deg, #6c5ce7 0%, #8e44ad 100%);
            color: white;
            border: none;
            border-radius: 50px;
            padding: 0.5rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(108, 92, 231, 0.3);
        }
        
        .btn-ai-suggest:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(108, 92, 231, 0.4);
            color: white;
        }
        
        .btn-ai-suggest:disabled {
            background: linear-gradient(135deg, #a29bfe 0%, #cda7dd 100%);
            cursor: not-allowed;
            opacity: 0.7;
        }
        
        .star-label:hover {
            transform: scale(1.1) !important;
        }
        
        .star-icon {
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.3));
            display: inline-block;
            color: #6c757d;
            font-style: normal;
        }
        
        .star-icon:before {
            content: "";
            font-size: inherit;
        }
        
        .star-icon.fas:before,
        .star-icon.filled:before {
            content: "";
        }
        
        .star-icon.text-warning,
        .star-icon.filled {
            color: #ffc107 !important;
            text-shadow: 0 0 10px rgba(255, 193, 7, 0.5);
        }
        
        .star-icon.text-muted {
            color: #6c757d !important;
        }
        
        .rating-feedback {
            min-height: 60px;
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        
        .star-label.selected {
            animation: pulse 0.3s ease-in-out;
        }
        
        .category-container,
        .comment-container {
            background: linear-gradient(135deg, var(--color-section-dark) 0%, var(--color-dark-input) 100%);
            border: 2px solid var(--color-border-light) !important;
            box-shadow: 0 4px 6px rgba(0,0,0,0.3);
        }
        
        /* AI Suggestion Container */
        .ai-suggestion-container {
            background: linear-gradient(135deg, #1a3038 0%, #2c3e50 100%);
            border: 2px solid #6c5ce7;
            border-radius: 12px;
            margin: 1rem 0;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(108, 92, 231, 0.3);
            transition: all 0.3s ease;
        }
        
        .ai-suggestion-header {
            background: linear-gradient(135deg, #6c5ce7 0%, #8e44ad 100%);
            color: white;
            padding: 0.75rem 1rem;
            font-weight: 600;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .ai-suggestion-content {
            padding: 1rem;
            font-style: italic;
            line-height: 1.6;
            border-bottom: 1px solid var(--color-border-light);
        }
        
        .ai-suggestion-footer {
            padding: 0.75rem 1rem;
            text-align: right;
            background-color: rgba(108, 92, 231, 0.1);
        }
        
        .btn-ai-use {
            background: linear-gradient(135deg, #6c5ce7 0%, #8e44ad 100%);
            color: white;
            border: none;
            border-radius: 50px;
            padding: 0.4rem 1rem;
            font-weight: 600;
            font-size: 0.85rem;
            transition: all 0.3s ease;
        }
        
        .btn-ai-use:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(108, 92, 231, 0.3);
            color: white;
        }
        
        .comment-container textarea:focus {
            outline: none;
            box-shadow: 0 0 0 2px rgba(102, 187, 106, 0.25);
            background-color: var(--color-dark-input) !important;
        }

        /* Form Control Focus */
        .form-control:focus, .form-select:focus {
            background-color: var(--color-dark-input);
            border-color: #66bb6a;
            box-shadow: 0 0 0 0.2rem rgba(102, 187, 106, 0.25);
            color: #fafafa;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .main-content-wrapper {
                margin-top: 80px;
            }
            
            .display-5 {
                font-size: 2rem;
            }
            
            .btn-lg {
                padding: 0.75rem 1.5rem;
                font-size: 1rem;
            }
            
            .stars-wrapper {
                transform: scale(0.9);
            }
        }

        @media (max-width: 576px) {
            .card-body {
                padding: 1.5rem !important;
            }
            
            .d-flex.justify-content-between {
                flex-direction: column;
                gap: 1rem;
            }
            
            .d-flex.justify-content-between .btn {
                width: 100%;
                text-align: center;
            }
        }
    </style>

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

            // Star Rating System
            const stars = document.querySelectorAll('.star-icon');
            const starLabels = document.querySelectorAll('.star-label');
            const ratingText = document.getElementById('ratingText');
            const ratingDescription = document.getElementById('ratingDescription');
            const ratingLabels = ['Très mauvais', 'Mauvais', 'Moyen', 'Bon', 'Excellent'];
            const ratingDescriptions = [
                'Cette expérience ne répondait pas à vos attentes',
                'Cette expérience était en dessous de vos attentes',
                'Cette expérience était correcte',
                'Cette expérience était au-dessus de vos attentes',
                'Cette expérience était exceptionnelle!'
            ];
            
            // Get initial selected rating
            let selectedRating = 0;
            const checkedInput = document.querySelector('input[name="note"]:checked');
            if (checkedInput) {
                selectedRating = parseInt(checkedInput.value);
            }
            
            starLabels.forEach((label, index) => {
                const rating = index + 1;
                const input = document.getElementById(label.getAttribute('for'));
                
                // Hover effect
                label.addEventListener('mouseenter', function() {
                    highlightStars(rating, true);
                    updateText(rating, true);
                });
                
                // Click effect
                label.addEventListener('click', function() {
                    selectedRating = rating;
                    input.checked = true;
                    highlightStars(rating, false);
                    updateText(rating, false);
                    
                    // Add a small animation
                    label.style.transform = 'scale(1.2)';
                    setTimeout(() => {
                        label.style.transform = 'scale(1)';
                    }, 150);
                });
            });
            
            // Reset on mouse leave
            document.querySelector('.star-rating-input').addEventListener('mouseleave', function() {
                if (selectedRating > 0) {
                    highlightStars(selectedRating, false);
                    updateText(selectedRating, false);
                }
            });
            
            function highlightStars(rating, isHover) {
                stars.forEach((star, index) => {
                    const starRating = index + 1;
                    const label = star.closest('.star-label');
                    
                    if (starRating <= rating) {
                        star.classList.remove('far', 'text-muted');
                        star.classList.add('fas', 'text-warning', 'filled');
                        star.textContent = '★';
                        if (isHover) {
                            label.style.transform = 'scale(1.1)';
                        }
                    } else {
                        star.classList.remove('fas', 'text-warning', 'filled');
                        star.classList.add('far', 'text-muted');
                        star.textContent = '☆';
                        if (isHover) {
                            label.style.transform = 'scale(1)';
                        }
                    }
                });
            }
            
            function updateText(rating, isHover) {
                ratingText.textContent = ratingLabels[rating - 1];
                ratingDescription.textContent = ratingDescriptions[rating - 1];
                ratingText.classList.remove('text-muted');
                ratingText.classList.add('text-warning');
                
                if (!isHover) {
                    ratingText.classList.add('font-weight-bold');
                }
            }
            
            // AI Recommendation System
            const categorySelect = document.getElementById('feedback-category');
            const aiSuggestBtn = document.getElementById('ai-suggest-btn');
            const aiLoading = document.getElementById('ai-loading');
            const aiSuggestionContainer = document.getElementById('ai-suggestion-container');
            const aiSuggestionContent = document.getElementById('ai-suggestion-content');
            const useSuggestionBtn = document.getElementById('use-suggestion-btn');
            const closeSuggestionBtn = document.getElementById('close-suggestion');
            const feedbackComment = document.getElementById('feedback-comment');
            
            // Enable/disable AI suggestion button based on category selection
            categorySelect.addEventListener('change', function() {
                if (this.value) {
                    aiSuggestBtn.disabled = false;
                } else {
                    aiSuggestBtn.disabled = true;
                    aiSuggestionContainer.classList.add('d-none');
                }
            });
            
            // Handle AI suggestion button click
            aiSuggestBtn.addEventListener('click', function() {
                const categoryId = categorySelect.value;
                if (!categoryId) return;
                
                // Show loading indicator
                aiSuggestBtn.disabled = true;
                aiSuggestBtn.classList.add('d-none');
                aiLoading.classList.remove('d-none');
                
                // Make API request to get recommendation
                fetch('{{ route('api.feedback.recommendations') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        category_id: categoryId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    // Hide loading indicator
                    aiLoading.classList.add('d-none');
                    aiSuggestBtn.classList.remove('d-none');
                    aiSuggestBtn.disabled = false;
                    
                    if (data.success && data.data && data.data.suggestion) {
                        // Show suggestion
                        aiSuggestionContent.textContent = data.data.suggestion;
                        aiSuggestionContainer.classList.remove('d-none');
                    } else {
                        // Show error
                        alert('Désolé, nous n\'avons pas pu générer une suggestion. Veuillez réessayer plus tard.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    aiLoading.classList.add('d-none');
                    aiSuggestBtn.classList.remove('d-none');
                    aiSuggestBtn.disabled = false;
                    alert('Une erreur s\'est produite. Veuillez réessayer plus tard.');
                });
            });
            
            // Handle use suggestion button click
            useSuggestionBtn.addEventListener('click', function() {
                const suggestion = aiSuggestionContent.textContent;
                feedbackComment.value = suggestion;
                aiSuggestionContainer.classList.add('d-none');
            });
            
            // Handle close suggestion button click
            closeSuggestionBtn.addEventListener('click', function() {
                aiSuggestionContainer.classList.add('d-none');
            });
        });
    </script>
</x-app-layout>