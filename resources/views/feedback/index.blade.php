<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <x-app.navbar />
        <div class="container-fluid py-4 px-5">
        <div class="row">
            <div class="col-12">
                <!-- Header -->
                <div class="card shadow-xs border mb-4">
                    <div class="card-header bg-gradient-dark">
                        <div class="row align-items-center">
                            <div class="col">
                                <h4 class="text-white mb-0">
                                    <i class="fas fa-comments me-2"></i>Gestion des Avis
                                </h4>
                                <p class="text-white-50 mb-0 mt-1">Tous les avis des participants</p>
                            </div>
                            <div class="col-auto">
                                <div class="bg-white bg-opacity-20 rounded px-3 py-2">
                                    <span class="text-white fw-bold">{{ $feedbacks->total() }} avis total</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Search and Filter Form -->
                    <div class="card-body">
                        <form method="GET" action="{{ route('feedback.index') }}">
                            <div class="row g-3">
                                <!-- Search -->
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-search"></i>
                                        </span>
                                        <input type="text" name="search" class="form-control" placeholder="Rechercher par commentaire, événement, participant..." value="{{ request('search') }}">
                                    </div>
                                </div>

                                <!-- Rating Filter -->
                                <div class="col-md-3">
                                    <select name="rating" class="form-select">
                                        <option value="">Toutes les notes</option>
                                        <option value="5" {{ request('rating') == '5' ? 'selected' : '' }}>⭐⭐⭐⭐⭐ (5 étoiles)</option>
                                        <option value="4" {{ request('rating') == '4' ? 'selected' : '' }}>⭐⭐⭐⭐ (4 étoiles)</option>
                                        <option value="3" {{ request('rating') == '3' ? 'selected' : '' }}>⭐⭐⭐ (3 étoiles)</option>
                                        <option value="2" {{ request('rating') == '2' ? 'selected' : '' }}>⭐⭐ (2 étoiles)</option>
                                        <option value="1" {{ request('rating') == '1' ? 'selected' : '' }}>⭐ (1 étoile)</option>
                                    </select>
                                </div>

                                <!-- Event Filter -->
                                <div class="col-md-2">
                                    <select name="event_id" class="form-select">
                                        <option value="">Tous les événements</option>
                                        @foreach($events as $event)
                                            <option value="{{ $event->id }}" {{ request('event_id') == $event->id ? 'selected' : '' }}>
                                                {{ Str::limit($event->title, 25) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Category Filter -->
                                <div class="col-md-2">
                                    <select name="category_id" class="form-select">
                                        <option value="">Toutes les catégories</option>
                                        <option value="null" {{ request('category_id') == 'null' ? 'selected' : '' }}>Sans catégorie</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Buttons -->
                                <div class="col-md-1">
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary btn-sm w-100">
                                            <i class="fas fa-filter me-1"></i>Filtrer
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="col-md-12 mt-2 text-end">
                                    <a href="{{ route('feedback.index') }}" class="btn btn-outline-secondary btn-sm">
                                        <i class="fas fa-redo me-1"></i>Réinitialiser les filtres
                                    </a>
                                    <a href="{{ route('feedback.categories.index') }}" class="btn btn-info btn-sm ms-2">
                                        <i class="fas fa-tags me-1"></i>Gérer les catégories
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Statistics Cards -->
                <div class="row mb-4">
                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="card bg-gradient-primary shadow-primary border-0">
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-8">
                                        <div class="numbers">
                                            <p class="text-white text-sm mb-0 text-uppercase font-weight-bold">Total Avis</p>
                                            <h5 class="text-white font-weight-bolder">
                                                {{ $feedbacks->total() }}
                                            </h5>
                                        </div>
                                    </div>
                                    <div class="col-4 text-end">
                                        <div class="icon icon-shape bg-white shadow-primary text-center rounded-circle">
                                            <i class="fas fa-comments text-primary" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="card bg-gradient-info shadow-info border-0">
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-8">
                                        <div class="numbers">
                                            <p class="text-white text-sm mb-0 text-uppercase font-weight-bold">Note Moyenne</p>
                                            <h5 class="text-white font-weight-bolder">
                                                {{ number_format($feedbacks->avg('note') ?? 0, 1) }}/5
                                            </h5>
                                        </div>
                                    </div>
                                    <div class="col-4 text-end">
                                        <div class="icon icon-shape bg-white shadow-info text-center rounded-circle">
                                            <i class="fas fa-star text-info" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="card bg-gradient-success shadow-success border-0">
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-8">
                                        <div class="numbers">
                                            <p class="text-white text-sm mb-0 text-uppercase font-weight-bold">Avec Commentaires</p>
                                            <h5 class="text-white font-weight-bolder">
                                                {{ $feedbacks->whereNotNull('commentaire')->where('commentaire', '!=', '')->count() }}
                                            </h5>
                                        </div>
                                    </div>
                                    <div class="col-4 text-end">
                                        <div class="icon icon-shape bg-white shadow-success text-center rounded-circle">
                                            <i class="fas fa-comment-alt text-success" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="card bg-gradient-warning shadow-warning border-0">
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-8">
                                        <div class="numbers">
                                            <p class="text-white text-sm mb-0 text-uppercase font-weight-bold">Cette Semaine</p>
                                            <h5 class="text-white font-weight-bolder">
                                                {{ $feedbacks->where('date_feedback', '>=', now()->subWeek())->count() }}
                                            </h5>
                                        </div>
                                    </div>
                                    <div class="col-4 text-end">
                                        <div class="icon icon-shape bg-white shadow-warning text-center rounded-circle">
                                            <i class="fas fa-calendar-week text-warning" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Feedback Cards -->
                <div class="row">
                    <div class="col-12">
                        @forelse($feedbacks as $feedback)
                        <div class="card shadow-xs border mb-4">
                            <div class="card-body">
                                <div class="row align-items-start">
                                    <div class="col-md-2 text-center mb-3 mb-md-0">
                                        <!-- Event Image -->
                                        @if($feedback->event->images && count($feedback->event->images) > 0)
                                            <img src="{{ $feedback->event->images[0] }}" 
                                                 alt="{{ $feedback->event->title }}" 
                                                 class="img-fluid rounded shadow-sm"
                                                 style="max-height: 100px; object-fit: cover; width: 100px;">
                                        @else
                                            <div class="bg-gradient-dark rounded d-flex align-items-center justify-content-center shadow-sm" 
                                                 style="height: 100px; width: 100px;">
                                                <i class="fas fa-calendar-alt text-white fa-2x"></i>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <!-- Event & Participant Info -->
                                        <h5 class="font-weight-bold text-dark mb-2">
                                            {{ $feedback->event->title }}
                                        </h5>
                                        
                                        <div class="mb-2">
                                            <span class="badge bg-gradient-primary me-2">
                                                <i class="fas fa-user me-1"></i>
                                                {{ $feedback->participant->name }}
                                            </span>
                                            <span class="badge bg-gradient-info me-2">
                                                <i class="fas fa-calendar me-1"></i>
                                                @if($feedback->date_feedback)
                                                    {{ $feedback->date_feedback->format('d/m/Y à H:i') }}
                                                @else
                                                    Date non définie
                                                @endif
                                            </span>
                                            @if($feedback->category)
                                                <span class="badge" style="background-color: {{ $feedback->category->color }}">
                                                    @if($feedback->category->icon)
                                                        <i class="{{ $feedback->category->icon }} me-1"></i>
                                                    @else
                                                        <i class="fas fa-tag me-1"></i>
                                                    @endif
                                                    {{ $feedback->category->name }}
                                                </span>
                                            @endif
                                        </div>
                                        
                                        <!-- Rating -->
                                        <div class="mb-3">
                                            <div class="d-flex align-items-center">
                                                <div class="rating-display me-3">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        @if ($i <= $feedback->note)
                                                            <i class="fas fa-star text-warning"></i>
                                                        @else
                                                            <i class="far fa-star text-muted"></i>
                                                        @endif
                                                    @endfor
                                                </div>
                                                <span class="rating-label badge">
                                                    @php
                                                        $ratingLabels = [
                                                            1 => ['text' => 'Très mauvais', 'class' => 'bg-gradient-danger'],
                                                            2 => ['text' => 'Mauvais', 'class' => 'bg-gradient-warning'],
                                                            3 => ['text' => 'Moyen', 'class' => 'bg-gradient-info'],
                                                            4 => ['text' => 'Bon', 'class' => 'bg-gradient-primary'],
                                                            5 => ['text' => 'Excellent', 'class' => 'bg-gradient-success']
                                                        ];
                                                        $currentRating = $ratingLabels[$feedback->note] ?? ['text' => 'Non évalué', 'class' => 'bg-gradient-secondary'];
                                                    @endphp
                                                    <span class="badge {{ $currentRating['class'] }}">
                                                        {{ $feedback->note }}/5 - {{ $currentRating['text'] }}
                                                    </span>
                                                </span>
                                            </div>
                                        </div>
                                        
                                        <!-- Comment -->
                                        @if($feedback->commentaire)
                                        <div class="comment-section">
                                            <div class="bg-gray-100 rounded p-3 border-start border-primary border-4">
                                                <small class="text-muted d-block mb-1">
                                                    <i class="fas fa-quote-left me-1"></i>Commentaire:
                                                </small>
                                                <p class="mb-0 text-dark">
                                                    "{{ $feedback->commentaire }}"
                                                </p>
                                            </div>
                                        </div>
                                        @else
                                        <div class="text-muted fst-italic">
                                            <i class="fas fa-comment-slash me-1"></i>
                                            Aucun commentaire fourni
                                        </div>
                                        @endif
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <!-- Event Details -->
                                        <div class="event-details bg-gray-50 rounded p-3">
                                            <h6 class="font-weight-bold text-dark mb-2">
                                                <i class="fas fa-info-circle me-1"></i>Détails de l'événement
                                            </h6>
                                            
                                            <div class="mb-2">
                                                <small class="text-muted">Date de l'événement:</small><br>
                                                <span class="text-dark">
                                                    @if($feedback->event->start_date)
                                                        {{ $feedback->event->start_date->format('d/m/Y à H:i') }}
                                                    @else
                                                        Date non définie
                                                    @endif
                                                </span>
                                            </div>
                                            
                                            <div class="mb-2">
                                                <small class="text-muted">Lieu:</small><br>
                                                <span class="text-dark">{{ $feedback->event->location }}</span>
                                            </div>
                                            
                                            <div class="mb-2">
                                                <small class="text-muted">Catégorie:</small><br>
                                                <span class="badge bg-gradient-secondary">
                                                    {{ $feedback->event->category->name ?? 'Non définie' }}
                                                </span>
                                            </div>
                                            
                                            <div class="pt-2 border-top">
                                                <a href="{{ route('events.show', $feedback->event->id) }}" 
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye me-1"></i>Voir l'événement
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="card shadow-xs border">
                            <div class="card-body text-center py-5">
                                <i class="fas fa-comments fa-4x text-muted mb-3"></i>
                                <h4 class="text-muted">Aucun avis disponible</h4>
                                <p class="text-muted">
                                    Aucun participant n'a encore donné d'avis sur les événements.
                                </p>
                                <a href="{{ route('events.index') }}" class="btn btn-primary mt-2">
                                    <i class="fas fa-calendar me-2"></i>Gérer les événements
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
        </div>
    </div>

    <style>
        .rating-display i {
            font-size: 1.2rem;
            margin-right: 0.2rem;
        }
        
        .comment-section {
            margin-top: 1rem;
        }
        
        .event-details {
            border: 1px solid #e9ecef;
        }
        
        .card:hover {
            transform: translateY(-2px);
            transition: all 0.3s ease;
        }
        
        .icon-shape {
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .shadow-primary {
            box-shadow: 0 4px 20px 0 rgba(0, 123, 255, 0.15) !important;
        }
        
        .shadow-info {
            box-shadow: 0 4px 20px 0 rgba(23, 162, 184, 0.15) !important;
        }
        
        .shadow-success {
            box-shadow: 0 4px 20px 0 rgba(40, 167, 69, 0.15) !important;
        }
        
        .shadow-warning {
            box-shadow: 0 4px 20px 0 rgba(255, 193, 7, 0.15) !important;
        }
    </style>
    </main>
</x-app-layout>
