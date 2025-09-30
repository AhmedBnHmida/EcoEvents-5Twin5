<x-app-layout>
    <x-front-navbar />
    
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <nav aria-label="breadcrumb" class="mb-4">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Accueil</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('feedback.my') }}">Mes avis</a></li>
                        <li class="breadcrumb-item active">Modifier</li>
                    </ol>
                </nav>

                <div class="card shadow-xs border">
                    <div class="card-header bg-gradient-dark">
                        <h4 class="text-white mb-0">
                            <i class="fas fa-edit me-2"></i>Modifier votre avis
                        </h4>
                    </div>
                    <div class="card-body">
                        <!-- Event Summary -->
                        <div class="alert alert-info mb-4">
                            <h5 class="font-weight-bold mb-3">{{ $feedback->event->title }}</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="mb-2">
                                        <i class="fas fa-calendar me-2"></i>
                                        <strong>Date:</strong> 
                                        @if($feedback->event->start_date)
                                            {{ $feedback->event->start_date->format('d/m/Y à H:i') }}
                                        @else
                                            Date non définie
                                        @endif
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-2">
                                        <i class="fas fa-clock me-2"></i>
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
                                <label class="form-label h5">
                                    <i class="fas fa-star text-warning me-2"></i>Note de l'événement <span class="text-danger">*</span>
                                </label>
                                <p class="text-muted text-sm mb-3">Évaluez votre expérience de 1 à 5 étoiles</p>
                                
                                <div class="star-rating-container p-4 bg-light rounded-3 border">
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

                            <!-- Comment -->
                            <div class="mb-4">
                                <label class="form-label h5">
                                    <i class="fas fa-comment me-2"></i>Votre commentaire
                                </label>
                                <p class="text-muted text-sm mb-3">Partagez votre expérience (optionnel)</p>
                                <div class="comment-container bg-light rounded-3 border p-3">
                                    <textarea 
                                        name="commentaire" 
                                        class="form-control border-0 shadow-none @error('commentaire') is-invalid @enderror" 
                                        rows="6" 
                                        maxlength="1000"
                                        style="background: transparent; resize: vertical;"
                                        placeholder="Qu'avez-vous pensé de cet événement ? Qu'est-ce qui vous a plu ou déplu ? Vos suggestions pour améliorer l'expérience sont les bienvenues...">{{ old('commentaire', $feedback->commentaire) }}</textarea>
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
                                <a href="{{ route('feedback.my') }}" class="btn btn-outline-secondary btn-lg px-4">
                                    <i class="fas fa-arrow-left me-2"></i>Retour
                                </a>
                                <button type="submit" class="btn btn-gradient-dark btn-lg px-4 shadow">
                                    <i class="fas fa-save me-2"></i>Enregistrer les modifications
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
        });
    </script>
    
    <style>
        .star-rating-container {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border: 2px solid #dee2e6;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        
        .star-label:hover {
            transform: scale(1.1) !important;
        }
        
        .star-icon {
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));
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
        
        .comment-container {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border: 2px solid #dee2e6 !important;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        
        .comment-container textarea:focus {
            outline: none;
            box-shadow: none;
        }
        
        .btn-gradient-dark {
            background: linear-gradient(135deg, #343a40 0%, #212529 100%);
            border: none;
            transition: all 0.3s ease;
        }
        
        .btn-gradient-dark:hover {
            background: linear-gradient(135deg, #212529 0%, #0d1117 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.3) !important;
        }
    </style>
    @endpush
</x-app-layout>
