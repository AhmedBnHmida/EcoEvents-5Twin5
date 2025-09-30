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
                                    <i class="fas fa-chart-bar me-2"></i>Évaluations Globales
                                </h4>
                                <p class="text-white-50 mb-0 mt-1">Analyse des performances des événements</p>
                            </div>
                            <div class="col-auto">
                                <div class="bg-white bg-opacity-20 rounded px-3 py-2">
                                    <span class="text-white fw-bold">{{ $evaluations->total() }} événements évalués</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Search and Filter Form -->
                    <div class="card-body">
                        <form method="GET" action="{{ route('evaluations.index') }}">
                            <div class="row g-3">
                                <!-- Search -->
                                <div class="col-md-5">
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-search"></i>
                                        </span>
                                        <input type="text" name="search" class="form-control" placeholder="Rechercher par titre d'événement ou lieu..." value="{{ request('search') }}">
                                    </div>
                                </div>

                                <!-- Minimum Rating Filter -->
                                <div class="col-md-3">
                                    <select name="min_rating" class="form-select">
                                        <option value="">Note minimale</option>
                                        <option value="4.5" {{ request('min_rating') == '4.5' ? 'selected' : '' }}>⭐ 4.5+ Excellent</option>
                                        <option value="4" {{ request('min_rating') == '4' ? 'selected' : '' }}>⭐ 4.0+ Très bien</option>
                                        <option value="3" {{ request('min_rating') == '3' ? 'selected' : '' }}>⭐ 3.0+ Bien</option>
                                        <option value="2" {{ request('min_rating') == '2' ? 'selected' : '' }}>⭐ 2.0+ Moyen</option>
                                        <option value="1" {{ request('min_rating') == '1' ? 'selected' : '' }}>⭐ 1.0+ Faible</option>
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

                                <!-- Buttons -->
                                <div class="col-md-2">
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary btn-sm w-50">
                                            <i class="fas fa-filter me-1"></i>Filtrer
                                        </button>
                                        <a href="{{ route('evaluations.index') }}" class="btn btn-outline-secondary btn-sm w-50">
                                            <i class="fas fa-redo me-1"></i>Reset
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Statistics Dashboard -->
                <div class="row mb-4">
                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="card bg-gradient-success shadow-success border-0">
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-8">
                                        <div class="numbers">
                                            <p class="text-white text-sm mb-0 text-uppercase font-weight-bold">Total Feedbacks</p>
                                            <h5 class="text-white font-weight-bolder">
                                                {{ number_format($totalFeedbacks) }}
                                            </h5>
                                        </div>
                                    </div>
                                    <div class="col-4 text-end">
                                        <div class="icon icon-shape bg-white shadow-success text-center rounded-circle">
                                            <i class="fas fa-comments text-success" aria-hidden="true"></i>
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
                                            <p class="text-white text-sm mb-0 text-uppercase font-weight-bold">Note Moyenne</p>
                                            <h5 class="text-white font-weight-bolder">
                                                {{ number_format($averageRating ?? 0, 2) }}/5
                                                <div class="rating-stars mt-1">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        @if($i <= round($averageRating ?? 0))
                                                            <i class="fas fa-star text-white" style="font-size: 0.8rem;"></i>
                                                        @else
                                                            <i class="far fa-star text-white opacity-50" style="font-size: 0.8rem;"></i>
                                                        @endif
                                                    @endfor
                                                </div>
                                            </h5>
                                        </div>
                                    </div>
                                    <div class="col-4 text-end">
                                        <div class="icon icon-shape bg-white shadow-warning text-center rounded-circle">
                                            <i class="fas fa-star text-warning" aria-hidden="true"></i>
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
                                            <p class="text-white text-sm mb-0 text-uppercase font-weight-bold">Événements Évalués</p>
                                            <h5 class="text-white font-weight-bolder">
                                                {{ $eventsWithFeedback }}
                                            </h5>
                                        </div>
                                    </div>
                                    <div class="col-4 text-end">
                                        <div class="icon icon-shape bg-white shadow-info text-center rounded-circle">
                                            <i class="fas fa-calendar-check text-info" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="card bg-gradient-primary shadow-primary border-0">
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-8">
                                        <div class="numbers">
                                            <p class="text-white text-sm mb-0 text-uppercase font-weight-bold">Satisfaction Moyenne</p>
                                            <h5 class="text-white font-weight-bolder">
                                                {{ number_format(($averageRating ?? 0) / 5 * 100, 1) }}%
                                            </h5>
                                        </div>
                                    </div>
                                    <div class="col-4 text-end">
                                        <div class="icon icon-shape bg-white shadow-primary text-center rounded-circle">
                                            <i class="fas fa-thumbs-up text-primary" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Evaluations List -->
                <div class="row">
                    <div class="col-12">
                        @forelse($evaluations as $evaluation)
                        <div class="card shadow-xs border mb-4 evaluation-card">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-md-2 text-center mb-3 mb-md-0">
                                        <!-- Event Image -->
                                        @if($evaluation->event->images && count($evaluation->event->images) > 0)
                                            <img src="{{ $evaluation->event->images[0] }}" 
                                                 alt="{{ $evaluation->event->title }}" 
                                                 class="img-fluid rounded shadow-sm event-image">
                                        @else
                                            <div class="event-placeholder">
                                                <i class="fas fa-calendar-alt text-white fa-2x"></i>
                                            </div>
                                        @endif
                                        
                                        <!-- Rating Badge -->
                                        <div class="rating-badge">
                                            <div class="rating-number">{{ number_format($evaluation->moyenne_notes, 1) }}</div>
                                            <div class="rating-label">/ 5</div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <!-- Event Info -->
                                        <h5 class="font-weight-bold text-dark mb-2">
                                            {{ $evaluation->event->title }}
                                        </h5>
                                        
                                        <div class="mb-2">
                                            <span class="badge bg-gradient-primary me-2">
                                                <i class="fas fa-calendar me-1"></i>
                                                @if($evaluation->event->start_date)
                                                    {{ $evaluation->event->start_date->format('d/m/Y') }}
                                                @else
                                                    Date non définie
                                                @endif
                                            </span>
                                            @if($evaluation->event->category)
                                            <span class="badge bg-gradient-secondary">
                                                {{ $evaluation->event->category->name }}
                                            </span>
                                            @endif
                                        </div>
                                        
                                        <div class="location-info text-muted">
                                            <i class="fas fa-map-marker-alt me-1"></i>
                                            {{ $evaluation->event->location }}
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-3">
                                        <!-- Evaluation Metrics -->
                                        <div class="evaluation-metrics">
                                            <div class="metric-item">
                                                <div class="metric-label">Note Moyenne</div>
                                                <div class="metric-value">
                                                    <div class="stars-display">
                                                        @for($i = 1; $i <= 5; $i++)
                                                            @if($i <= round($evaluation->moyenne_notes))
                                                                <i class="fas fa-star text-warning"></i>
                                                            @else
                                                                <i class="far fa-star text-muted"></i>
                                                            @endif
                                                        @endfor
                                                    </div>
                                                    <span class="rating-text">{{ number_format($evaluation->moyenne_notes, 2) }}/5</span>
                                                </div>
                                            </div>
                                            
                                            <div class="metric-item">
                                                <div class="metric-label">Nombre d'avis</div>
                                                <div class="metric-value text-primary">
                                                    <i class="fas fa-comments me-1"></i>
                                                    {{ $evaluation->nb_feedbacks }} avis
                                                </div>
                                            </div>
                                            
                                            <div class="metric-item">
                                                <div class="metric-label">Taux de satisfaction</div>
                                                <div class="metric-value">
                                                    @php
                                                        $satisfaction = $evaluation->taux_satisfaction;
                                                        $colorClass = $satisfaction >= 80 ? 'text-success' : 
                                                                     ($satisfaction >= 60 ? 'text-warning' : 'text-danger');
                                                    @endphp
                                                    <span class="{{ $colorClass }} font-weight-bold">
                                                        {{ number_format($satisfaction, 1) }}%
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-3 text-end">
                                        <!-- Action Buttons -->
                                        <div class="action-buttons">
                                            <a href="{{ route('evaluations.show', $evaluation->event->id) }}" 
                                               class="btn btn-primary btn-sm mb-2">
                                                <i class="fas fa-chart-line me-1"></i>
                                                Analyse Détaillée
                                            </a>
                                            
                                            <a href="{{ route('events.show', $evaluation->event->id) }}" 
                                               class="btn btn-outline-secondary btn-sm mb-2">
                                                <i class="fas fa-eye me-1"></i>
                                                Voir l'Événement
                                            </a>
                                            
                                            <a href="{{ route('feedback.index') }}?event={{ $evaluation->event->id }}" 
                                               class="btn btn-outline-info btn-sm">
                                                <i class="fas fa-comments me-1"></i>
                                                Voir les Avis
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Progress Bar for Satisfaction -->
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <div class="satisfaction-progress">
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <small class="text-muted">Niveau de satisfaction</small>
                                                <small class="text-muted">{{ number_format($evaluation->taux_satisfaction, 1) }}%</small>
                                            </div>
                                            <div class="progress" style="height: 8px;">
                                                @php
                                                    $satisfaction = $evaluation->taux_satisfaction;
                                                    $progressClass = $satisfaction >= 80 ? 'bg-success' : 
                                                                    ($satisfaction >= 60 ? 'bg-warning' : 'bg-danger');
                                                @endphp
                                                <div class="progress-bar {{ $progressClass }}" 
                                                     role="progressbar" 
                                                     style="width: {{ $satisfaction }}%"
                                                     aria-valuenow="{{ $satisfaction }}" 
                                                     aria-valuemin="0" 
                                                     aria-valuemax="100">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="card shadow-xs border">
                            <div class="card-body text-center py-5">
                                <i class="fas fa-chart-bar fa-4x text-muted mb-3"></i>
                                <h4 class="text-muted">Aucune évaluation disponible</h4>
                                <p class="text-muted">
                                    Aucun événement n'a encore reçu d'évaluation de la part des participants.
                                </p>
                                <a href="{{ route('events.index') }}" class="btn btn-primary mt-2">
                                    <i class="fas fa-calendar me-2"></i>Gérer les événements
                                </a>
                            </div>
                        </div>
                        @endforelse
                        
                        <!-- Pagination -->
                        @if($evaluations->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $evaluations->links() }}
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .evaluation-card {
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
        }
        
        .evaluation-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
            border-left-color: #3b82f6;
        }
        
        .event-image {
            width: 100px;
            height: 100px;
            object-fit: cover;
        }
        
        .event-placeholder {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, #64748b 0%, #475569 100%);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
        }
        
        .rating-badge {
            position: absolute;
            top: -10px;
            right: -10px;
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: white;
            border-radius: 12px;
            padding: 0.5rem;
            box-shadow: 0 4px 15px rgba(59,130,246,0.4);
            text-align: center;
            min-width: 60px;
        }
        
        .rating-number {
            font-size: 1.1rem;
            font-weight: bold;
            line-height: 1;
        }
        
        .rating-label {
            font-size: 0.7rem;
            opacity: 0.8;
        }
        
        .evaluation-metrics {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 1rem;
            border: 1px solid #e9ecef;
        }
        
        .metric-item {
            margin-bottom: 0.75rem;
        }
        
        .metric-item:last-child {
            margin-bottom: 0;
        }
        
        .metric-label {
            font-size: 0.8rem;
            color: #6c757d;
            font-weight: 500;
            margin-bottom: 0.25rem;
        }
        
        .metric-value {
            font-weight: 600;
            color: #495057;
        }
        
        .stars-display {
            margin-bottom: 0.25rem;
        }
        
        .stars-display i {
            font-size: 1rem;
            margin-right: 0.1rem;
        }
        
        .rating-text {
            font-size: 0.9rem;
            color: #6c757d;
        }
        
        .action-buttons {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }
        
        .action-buttons .btn {
            font-size: 0.85rem;
        }
        
        .satisfaction-progress .progress {
            border-radius: 4px;
        }
        
        .satisfaction-progress .progress-bar {
            border-radius: 4px;
            transition: width 0.6s ease;
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
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .action-buttons {
                margin-top: 1rem;
            }
            
            .evaluation-metrics {
                margin-top: 1rem;
            }
            
            .rating-badge {
                position: relative;
                top: auto;
                right: auto;
                margin-top: 0.5rem;
                display: inline-block;
            }
        }
    </style>
    </main>
</x-app-layout>
