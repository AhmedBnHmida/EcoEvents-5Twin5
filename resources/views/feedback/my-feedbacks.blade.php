<x-app-layout>
    <x-front-navbar />
    
    <!-- Hero Section -->
    <div class="hero-section">
        <div class="container">
            <div class="row align-items-center py-5">
                <div class="col-lg-8">
                    <nav aria-label="breadcrumb" class="mb-3">
                        <ol class="breadcrumb hero-breadcrumb">
                            <li class="breadcrumb-item"><a href="/" class="text-white">Accueil</a></li>
                            <li class="breadcrumb-item active text-white">Mes avis</li>
                        </ol>
                    </nav>
                    <h1 class="display-4 fw-bold text-white mb-3">
                        <i class="fas fa-comments me-3"></i>Mes Avis
                    </h1>
                    <p class="lead text-white-50 mb-0">
                        Gérez tous vos commentaires et évaluations d'événements
                        <span class="badge bg-warning text-dark ms-2">{{ $feedbacks->total() }} avis</span>
                    </p>
                </div>
                <div class="col-lg-4 text-end">
                    <a href="{{ route('events.public') }}" class="btn btn-hero">
                        <i class="fas fa-calendar me-2"></i>Découvrir des événements
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="container py-5">
        <div class="row">
            <div class="col-12">

                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                @forelse($feedbacks as $feedback)
                <div class="modern-feedback-card">
                    <div class="card-content">
                        <div class="row align-items-center">
                            <div class="col-md-3">
                                <div class="event-image-container">
                                    @if($feedback->event->images && count($feedback->event->images) > 0)
                                        <img src="{{ $feedback->event->images[0] }}" alt="{{ $feedback->event->title }}" 
                                             class="event-image">
                                    @else
                                        <div class="event-image-placeholder">
                                            <i class="fas fa-calendar-alt"></i>
                                        </div>
                                    @endif
                                    <div class="rating-badge">
                                        <span class="rating-number">{{ $feedback->note }}</span>
                                        <div class="rating-stars">
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($i <= $feedback->note)
                                                    <i class="fas fa-star"></i>
                                                @else
                                                    <i class="far fa-star"></i>
                                                @endif
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="feedback-content">
                                    <h4 class="event-title">{{ $feedback->event->title }}</h4>
                                    
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
                                        <span class="badge bg-{{ $currentRating['color'] }} rating-label-badge me-2">
                                            {{ $currentRating['text'] }}
                                        </span>
                                        
                                        @if($feedback->category)
                                            <span class="badge category-badge" style="background-color: {{ $feedback->category->color }}">
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
                                    <div class="comment-container">
                                        <div class="comment-text">
                                            "{{ Str::limit($feedback->commentaire, 120) }}"
                                        </div>
                                    </div>
                                    @else
                                    <div class="no-comment">
                                        <i class="fas fa-comment-slash me-2"></i>
                                        <span class="text-muted">Aucun commentaire ajouté</span>
                                    </div>
                                    @endif
                                    
                                    <!-- Date -->
                                    <div class="feedback-date">
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
                                       class="btn-modern btn-view">
                                        <i class="fas fa-eye me-2"></i>Voir l'événement
                                    </a>
                                    <a href="{{ route('feedback.edit', $feedback->id_feedback) }}" 
                                       class="btn-modern btn-edit">
                                        <i class="fas fa-edit me-2"></i>Modifier
                                    </a>
                                    <form action="{{ route('feedback.destroy', $feedback->id_feedback) }}" 
                                          method="POST" 
                                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet avis ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-modern btn-delete">
                                            <i class="fas fa-trash me-2"></i>Supprimer
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="card shadow-xs border">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-comment-slash text-muted fa-3x mb-3"></i>
                        <h5 class="text-muted">Aucun avis</h5>
                        <p class="text-muted mb-4">Vous n'avez pas encore donné d'avis sur des événements.</p>
                        <a href="{{ route('events.public') }}" class="btn btn-dark">
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
        /* Hero Section */
        .hero-section {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            position: relative;
            overflow: hidden;
        }
        
        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.1);
            z-index: 1;
        }
        
        .hero-section .container {
            position: relative;
            z-index: 2;
        }
        
        .hero-breadcrumb {
            background: transparent;
            padding: 0;
            margin: 0;
        }
        
        .btn-hero {
            background: rgba(255,255,255,0.2);
            border: 2px solid rgba(255,255,255,0.3);
            color: white;
            font-weight: 600;
            padding: 0.75rem 2rem;
            border-radius: 50px;
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
        }
        
        .btn-hero:hover {
            background: rgba(255,255,255,0.3);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.2);
        }
        
        /* Modern Feedback Cards */
        .modern-feedback-card {
            background: linear-gradient(135deg, #ffffff 0%, #f1f5f9 100%);
            border: none;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.08);
            margin-bottom: 2rem;
            overflow: hidden;
            transition: all 0.3s ease;
            position: relative;
            border: 1px solid #e2e8f0;
        }
        
        .modern-feedback-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }
        
        .card-content {
            padding: 2rem;
        }
        
        /* Event Image Container */
        .event-image-container {
            position: relative;
            display: flex;
            justify-content: center;
        }
        
        .event-image {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        }
        
        .event-image-placeholder {
            width: 120px;
            height: 120px;
            background: linear-gradient(135deg, #64748b 0%, #475569 100%);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2rem;
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        }
        
        /* Floating Rating Badge */
        .rating-badge {
            position: absolute;
            top: -10px;
            right: -10px;
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: white;
            border-radius: 12px;
            padding: 0.5rem;
            box-shadow: 0 5px 15px rgba(59,130,246,0.4);
            text-align: center;
            min-width: 60px;
        }
        
        .rating-number {
            font-size: 1.2rem;
            font-weight: bold;
            display: block;
        }
        
        .rating-stars {
            font-size: 0.7rem;
            margin-top: 0.2rem;
        }
        
        .rating-stars i {
            color: rgba(255,255,255,0.9);
        }
        
        /* Feedback Content */
        .feedback-content {
            padding-left: 1rem;
        }
        
        .event-title {
            font-size: 1.4rem;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 1rem;
            line-height: 1.3;
        }
        
        .rating-label-badge, 
        .category-badge {
            font-size: 0.9rem;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
        }
        
        /* Comment Container */
        .comment-container {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border-left: 4px solid #3b82f6;
            padding: 1rem 1.5rem;
            border-radius: 0 10px 10px 0;
            margin: 1rem 0;
            position: relative;
        }
        
        .comment-container::before {
            content: '"';
            position: absolute;
            top: -5px;
            left: 10px;
            font-size: 2rem;
            color: #3b82f6;
            font-weight: bold;
        }
        
        .comment-text {
            font-style: italic;
            color: #4a5568;
            line-height: 1.6;
            margin-left: 1rem;
        }
        
        .no-comment {
            padding: 0.75rem 0;
            opacity: 0.7;
        }
        
        .feedback-date {
            color: #718096;
            font-size: 0.9rem;
            margin-top: 1rem;
        }
        
        /* Action Buttons */
        .action-buttons {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            align-items: stretch;
        }
        
        .btn-modern {
            padding: 0.6rem 1.2rem;
            border-radius: 25px;
            font-weight: 600;
            font-size: 0.9rem;
            border: none;
            text-decoration: none;
            text-align: center;
            transition: all 0.3s ease;
            display: block;
        }
        
        .btn-view {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(59,130,246,0.3);
        }
        
        .btn-view:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(59,130,246,0.4);
            color: white;
        }
        
        .btn-edit {
            background: linear-gradient(135deg, #64748b 0%, #475569 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(100,116,139,0.3);
        }
        
        .btn-edit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(100,116,139,0.4);
            color: white;
        }
        
        .btn-delete {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(220,38,38,0.3);
        }
        
        .btn-delete:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(220,38,38,0.4);
            color: white;
        }
        
        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            background: linear-gradient(135deg, #f7fafc 0%, #edf2f7 100%);
            border-radius: 20px;
            border: 2px dashed #cbd5e0;
        }
        
        .empty-state i {
            color: #a0aec0;
            margin-bottom: 1rem;
        }
        
        .empty-state h4 {
            color: #4a5568;
            margin-bottom: 1rem;
        }
        
        .empty-state p {
            color: #718096;
            margin-bottom: 2rem;
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .hero-section {
                text-align: center;
            }
            
            .btn-hero {
                margin-top: 1rem;
            }
            
            .modern-feedback-card {
                margin-bottom: 1.5rem;
            }
            
            .card-content {
                padding: 1.5rem;
            }
            
            .event-image,
            .event-image-placeholder {
                width: 80px;
                height: 80px;
            }
            
            .rating-badge {
                top: -5px;
                right: -5px;
                min-width: 50px;
                padding: 0.3rem;
            }
            
            .rating-number {
                font-size: 1rem;
            }
            
            .action-buttons {
                margin-top: 1rem;
            }
        }
    </style>
</x-app-layout>
