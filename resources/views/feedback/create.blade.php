<x-app-layout>
    <x-front-navbar />
    
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <nav aria-label="breadcrumb" class="mb-4">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Accueil</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('events.public') }}">Événements</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('events.public.show', $event->id) }}">{{ Str::limit($event->title, 30) }}</a></li>
                        <li class="breadcrumb-item active">Donner mon avis</li>
                    </ol>
                </nav>

                @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                <div class="card shadow-xs border">
                    <div class="card-header bg-gradient-dark">
                        <h4 class="text-white mb-0">
                            <i class="fas fa-star me-2"></i>Donner votre avis
                        </h4>
                    </div>
                    <div class="card-body">
                        <!-- Event Summary -->
                        <div class="alert alert-info mb-4">
                            <h5 class="font-weight-bold mb-3">{{ $event->title }}</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="mb-2">
                                        <i class="fas fa-calendar me-2"></i>
                                        <strong>Date:</strong> 
                                        @if($event->start_date)
                                            {{ $event->start_date->format('d/m/Y à H:i') }}
                                        @else
                                            Date non définie
                                        @endif
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-2">
                                        <i class="fas fa-map-marker-alt me-2"></i>
                                        <strong>Lieu:</strong> {{ $event->location }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Feedback Form -->
                        <form action="{{ route('feedback.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="event_id" value="{{ $event->id }}">

                            <!-- Modern Star Rating -->
                            <div class="mb-4">
                                <label class="form-label h5">
                                    <i class="fas fa-star text-warning me-2"></i>Note de l'événement <span class="text-danger">*</span>
                                </label>
                                <p class="text-muted text-sm mb-3">Évaluez votre expérience de 1 à 5 étoiles</p>
                                
                                <div class="modern-star-rating-container">
                                    <div class="star-rating-wrapper">
                                        <!-- Hidden Input -->
                                        <input type="hidden" name="note" id="selectedRating" value="" required>
                                        
                                        <!-- Stars Container -->
                                        <div class="stars-container" id="starsContainer">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <div class="star-wrapper" data-rating="{{ $i }}">
                                                    <span class="star-icon">★</span>
                                                </div>
                                            @endfor
                                        </div>
                                        
                                        <!-- Rating Text -->
                                        <div class="rating-feedback-container">
                                            <div class="rating-text" id="modernRatingText">Cliquez sur les étoiles pour noter</div>
                                            <div class="rating-description" id="modernRatingDescription">Votre avis nous aide à améliorer nos événements</div>
                                        </div>
                                    </div>
                                </div>
                                @error('note')
                                    <span class="text-danger text-sm mt-2 d-block">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Category Selection -->
                            <div class="mb-4">
                                <label class="form-label h5">
                                    <i class="fas fa-tag me-2"></i>Catégorie
                                </label>
                                <p class="text-muted text-sm mb-3">Sélectionnez une catégorie pour votre avis (optionnel)</p>
                                <div class="modern-category-container">
                                    <select id="feedback-category" name="category_id" class="form-control @error('category_id') is-invalid @enderror">
                                        <option value="">-- Sélectionnez une catégorie --</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}
                                                data-color="{{ $category->color }}" data-icon="{{ $category->icon }}">
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <span class="text-danger text-sm mt-2 d-block">{{ $message }}</span>
                                    @enderror
                                    
                                    <div class="mt-3">
                                        <button type="button" id="ai-suggest-btn" class="btn btn-sm btn-ai-suggest" disabled>
                                            <i class="fas fa-robot me-1"></i>Suggérer un commentaire avec l'IA
                                        </button>
                                        <div id="ai-loading" class="d-none">
                                            <div class="spinner-border spinner-border-sm text-primary me-2" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                            <span>Génération en cours...</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Comment Section -->
                            <div class="mb-4">
                                <label class="form-label h5">
                                    <i class="fas fa-comment me-2"></i>Votre commentaire
                                </label>
                                <p class="text-muted text-sm mb-3">Partagez votre expérience (optionnel)</p>
                                <div class="modern-comment-container">
                                    <textarea 
                                        id="feedback-comment"
                                        name="commentaire" 
                                        class="form-control modern-textarea @error('commentaire') is-invalid @enderror" 
                                        rows="6" 
                                        maxlength="1000"
                                        placeholder="Qu'avez-vous pensé de cet événement ? Qu'est-ce qui vous a plu ou déplu ? Vos suggestions pour améliorer l'expérience sont les bienvenues...">{{ old('commentaire') }}</textarea>
                                    
                                    <!-- AI Suggestion Result -->
                                    <div id="ai-suggestion-container" class="ai-suggestion-container d-none">
                                        <div class="ai-suggestion-header">
                                            <i class="fas fa-robot me-2"></i>Suggestion IA
                                            <button type="button" class="btn-close btn-sm" aria-label="Close" id="close-suggestion"></button>
                                        </div>
                                        <div id="ai-suggestion-content" class="ai-suggestion-content"></div>
                                        <div class="ai-suggestion-footer">
                                            <button type="button" id="use-suggestion-btn" class="btn btn-sm btn-ai-use">
                                                <i class="fas fa-check me-1"></i>Utiliser cette suggestion
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <div class="comment-footer">
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

                            <div class="alert alert-warning modern-alert">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Important:</strong> Votre avis sera visible par les administrateurs et pourra être utilisé pour améliorer nos événements futurs.
                            </div>

                            <div class="d-flex justify-content-between pt-3">
                                <a href="{{ route('events.public.show', $event->id) }}" class="btn btn-modern-outline">
                                    <i class="fas fa-arrow-left me-2"></i>Retour
                                </a>
                                <button type="submit" class="btn btn-modern-primary">
                                    <i class="fas fa-paper-plane me-2"></i>Envoyer mon avis
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('🌟 Dynamic Star Rating System - Loading...');
            
            const starsContainer = document.getElementById('starsContainer');
            const selectedRatingInput = document.getElementById('selectedRating');
            const ratingText = document.getElementById('modernRatingText');
            const ratingDescription = document.getElementById('modernRatingDescription');
            
            if (!starsContainer) {
                console.error('Stars container not found!');
                return;
            }
            
            const starWrappers = starsContainer.querySelectorAll('.star-wrapper');
            let currentRating = 0;
            let hoverRating = 0;
            const ratingLabels = ['Très mauvais', 'Mauvais', 'Moyen', 'Bon', 'Excellent'];
            const ratingDescriptions = [
                'Cette expérience ne répondait pas à vos attentes',
                'Cette expérience était en dessous de vos attentes',
                'Cette expérience était correcte',
                'Cette expérience était au-dessus de vos attentes',
                'Cette expérience était exceptionnelle!'
            ];
            
            console.log(`🎯 Found ${starWrappers.length} stars`);
            
            // Update star visual state
            function updateStarDisplay(rating, isHover = false) {
                starWrappers.forEach((wrapper, index) => {
                    const starRating = index + 1;
                    const starIcon = wrapper.querySelector('.star-icon');
                    
                    wrapper.classList.remove('active', 'hover', 'selected');
                    
                    if (starRating <= rating) {
                        if (isHover) {
                            wrapper.classList.add('hover');
                        } else {
                            wrapper.classList.add('active');
                        }
                    }
                    
                    if (starRating <= currentRating) {
                        wrapper.classList.add('selected');
                    }
                });
            }
            
            // Update text display
            function updateText(rating) {
                if (rating > 0) {
                    ratingText.textContent = ratingLabels[rating - 1];
                    ratingText.classList.add('active');
                    ratingDescription.textContent = ratingDescriptions[rating - 1];
                } else {
                    ratingText.textContent = 'Cliquez sur les étoiles pour noter';
                    ratingText.classList.remove('active');
                    ratingDescription.textContent = 'Votre avis nous aide à améliorer nos événements';
                }
            }
            
            // Add event listeners to each star
            starWrappers.forEach((wrapper, index) => {
                const rating = index + 1;
                
                // Mouse enter - very responsive
                wrapper.addEventListener('mouseenter', function() {
                    hoverRating = rating;
                    console.log(`✨ Hover on star ${rating}`);
                    updateStarDisplay(rating, true);
                    updateText(rating);
                    
                    // Add immediate visual feedback
                    wrapper.style.transform = 'scale(1.2)';
                });
                
                // Mouse move - track mouse movement
                wrapper.addEventListener('mousemove', function() {
                    if (hoverRating !== rating) {
                        hoverRating = rating;
                        updateStarDisplay(rating, true);
                        updateText(rating);
                    }
                });
                
                // Mouse leave
                wrapper.addEventListener('mouseleave', function() {
                    console.log(`🔄 Leave star ${rating}`);
                    wrapper.style.transform = 'scale(1)';
                });
                
                // Click
                wrapper.addEventListener('click', function(e) {
                    e.preventDefault();
                    console.log(`🎯 Click on star ${rating}`);
                    
                    // Toggle rating - if clicking same star, deselect
                    if (currentRating === rating) {
                        currentRating = 0;
                        selectedRatingInput.value = '';
                        console.log('⭐ Rating deselected');
                    } else {
                        currentRating = rating;
                        selectedRatingInput.value = rating;
                        console.log(`⭐ Rating set to ${rating}`);
                    }
                    
                    // Animate click
                    wrapper.classList.add('clicked');
                    setTimeout(() => {
                        wrapper.classList.remove('clicked');
                    }, 300);
                    
                    updateStarDisplay(currentRating);
                    updateText(currentRating);
                });
                
                // Touch events for mobile
                wrapper.addEventListener('touchstart', function(e) {
                    e.preventDefault();
                    console.log(`📱 Touch star ${rating}`);
                    
                    // Immediate visual feedback
                    wrapper.style.transform = 'scale(1.2)';
                    updateStarDisplay(rating, true);
                    updateText(rating);
                });
                
                wrapper.addEventListener('touchend', function(e) {
                    e.preventDefault();
                    
                    if (currentRating === rating) {
                        currentRating = 0;
                        selectedRatingInput.value = '';
                    } else {
                        currentRating = rating;
                        selectedRatingInput.value = rating;
                    }
                    
                    wrapper.style.transform = 'scale(1)';
                    updateStarDisplay(currentRating);
                    updateText(currentRating);
                });
            });
            
            // Container mouse leave - reset to current selection
            starsContainer.addEventListener('mouseleave', function() {
                console.log('🔄 Left stars container');
                hoverRating = 0;
                
                // Reset all transforms
                starWrappers.forEach(wrapper => {
                    wrapper.style.transform = 'scale(1)';
                });
                
                updateStarDisplay(currentRating);
                updateText(currentRating);
            });
            
            // Initialize
            updateStarDisplay(0);
            updateText(0);
            
            console.log('🌟 Dynamic Star Rating System - Ready!');
            
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
    
    <style>
        /* Modern Star Rating Container */
        .modern-star-rating-container {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border: 2px solid #dee2e6;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            margin: 1rem 0;
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
        }
        
        .btn-ai-suggest:disabled {
            background: linear-gradient(135deg, #a29bfe 0%, #cda7dd 100%);
            cursor: not-allowed;
            opacity: 0.7;
        }
        
        .star-rating-wrapper {
            text-align: center;
        }
        
        /* Stars Container */
        .stars-container {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            margin-bottom: 1.5rem;
            padding: 1rem;
        }
        
        /* Individual Star Wrapper */
        .star-wrapper {
            cursor: pointer !important;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            user-select: none;
            -webkit-user-select: none;
            padding: 0.5rem;
            border-radius: 50%;
            position: relative;
            z-index: 10;
        }
        
        .star-wrapper:hover {
            background: rgba(255, 193, 7, 0.1);
            box-shadow: 0 0 20px rgba(255, 193, 7, 0.3);
        }
        
        /* Star Icon */
        .star-icon {
            font-size: 2.5rem;
            color: #d0d0d0;
            transition: all 0.2s ease;
            display: block;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
            pointer-events: none; /* Allow clicks to pass through to wrapper */
            cursor: pointer;
            -webkit-text-stroke: 1px #bbb;
        }
        
        /* Star States */
        .star-wrapper.hover .star-icon {
            color: #ffeb3b;
            text-shadow: 0 0 15px rgba(255, 235, 59, 0.7);
            transform: scale(1.1);
        }
        
        .star-wrapper.active .star-icon {
            color: #ffc107;
            text-shadow: 0 0 15px rgba(255, 193, 7, 0.8);
        }
        
        .star-wrapper.selected .star-icon {
            color: #ff9800;
            text-shadow: 0 0 20px rgba(255, 152, 0, 0.9);
        }
        
        .star-wrapper.clicked {
            animation: starClick 0.3s ease;
        }
        
        /* Pulse effect on hover */
        .star-wrapper:hover .star-icon {
            animation: starPulse 0.6s ease-in-out infinite alternate;
        }
        
        @keyframes starClick {
            0% { transform: scale(1); }
            50% { transform: scale(1.4); }
            100% { transform: scale(1); }
        }
        
        @keyframes starPulse {
            0% { transform: scale(1); }
            100% { transform: scale(1.15); }
        }
        
        /* Rating Text */
        .rating-feedback-container {
            margin-top: 1rem;
        }
        
        .rating-text {
            font-size: 1.25rem;
            font-weight: 600;
            color: #6c757d;
            margin-bottom: 0.5rem;
            transition: all 0.3s ease;
        }
        
        .rating-text.active {
            color: #ffc107;
            font-weight: 700;
        }
        
        .rating-description {
            font-size: 0.9rem;
            color: #8898aa;
            font-style: italic;
        }
        
        /* Modern Category Container */
        .modern-category-container {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border: 2px solid #dee2e6;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            margin-bottom: 1rem;
        }
        
        /* Modern Comment Container */
        .modern-comment-container {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border: 2px solid #dee2e6;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }
        
        .modern-textarea {
            background: transparent !important;
            border: none !important;
            box-shadow: none !important;
            resize: vertical;
            font-size: 1rem;
            line-height: 1.6;
        }
        
        .modern-textarea:focus {
            outline: none !important;
            background: rgba(255,255,255,0.7) !important;
            border-radius: 8px;
        }
        
        .comment-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid #dee2e6;
        }
        
        /* AI Suggestion Container */
        .ai-suggestion-container {
            background: linear-gradient(135deg, #f0f7ff 0%, #e6f0fd 100%);
            border: 2px solid #c9deff;
            border-radius: 12px;
            margin: 1rem 0;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 123, 255, 0.1);
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
            color: #495057;
            line-height: 1.6;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .ai-suggestion-footer {
            padding: 0.75rem 1rem;
            text-align: right;
            background-color: rgba(108, 92, 231, 0.05);
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
        }
        
        /* Modern Buttons */
        .btn-modern-outline {
            background: transparent;
            border: 2px solid #6c757d;
            color: #6c757d;
            font-weight: 600;
            padding: 0.75rem 2rem;
            border-radius: 50px;
            transition: all 0.3s ease;
        }
        
        .btn-modern-outline:hover {
            background: #6c757d;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(108, 117, 125, 0.3);
        }
        
        .btn-modern-primary {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            border: none;
            color: white;
            font-weight: 600;
            padding: 0.75rem 2rem;
            border-radius: 50px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 123, 255, 0.3);
        }
        
        .btn-modern-primary:hover {
            background: linear-gradient(135deg, #0056b3 0%, #004085 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 123, 255, 0.4);
            color: white;
        }
        
        /* Modern Alert */
        .modern-alert {
            background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
            border: 2px solid #ffeaa7;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(255, 193, 7, 0.1);
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .star-wrapper {
                font-size: 2.5rem;
            }
            
            .stars-container {
                gap: 8px;
            }
            
            .modern-star-rating-container,
            .modern-comment-container {
                padding: 1.5rem 1rem;
            }
        }
    </style>
    @endpush
</x-app-layout>
