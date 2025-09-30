<x-app-layout>
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <!-- Header -->
                <div class="card shadow-xs border mb-4">
                    <div class="card-header bg-gradient-dark">
                        <div class="row align-items-center">
                            <div class="col">
                                <h4 class="text-white mb-0">
                                    <i class="fas fa-chart-line me-2"></i>Analyse Détaillée
                                </h4>
                                <p class="text-white-50 mb-0 mt-1">{{ $event->title }}</p>
                            </div>
                            <div class="col-auto">
                                <a href="{{ route('evaluations.index') }}" class="btn btn-white btn-sm">
                                    <i class="fas fa-arrow-left me-2"></i>Retour aux évaluations
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                @if($evaluation)
                <!-- Statistics Overview -->
                <div class="row mb-4">
                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="card bg-gradient-primary shadow-primary border-0">
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-8">
                                        <div class="numbers">
                                            <p class="text-white text-sm mb-0 text-uppercase font-weight-bold">Note Moyenne</p>
                                            <h5 class="text-white font-weight-bolder">
                                                {{ number_format($evaluation->moyenne_notes, 2) }}/5
                                            </h5>
                                        </div>
                                    </div>
                                    <div class="col-4 text-end">
                                        <div class="icon icon-shape bg-white shadow-primary text-center rounded-circle">
                                            <i class="fas fa-star text-primary" aria-hidden="true"></i>
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
                                            <p class="text-white text-sm mb-0 text-uppercase font-weight-bold">Total Avis</p>
                                            <h5 class="text-white font-weight-bolder">
                                                {{ $evaluation->nb_feedbacks }}
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
                        <div class="card bg-gradient-info shadow-info border-0">
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-8">
                                        <div class="numbers">
                                            <p class="text-white text-sm mb-0 text-uppercase font-weight-bold">Satisfaction</p>
                                            <h5 class="text-white font-weight-bolder">
                                                {{ number_format($evaluation->taux_satisfaction, 1) }}%
                                            </h5>
                                        </div>
                                    </div>
                                    <div class="col-4 text-end">
                                        <div class="icon icon-shape bg-white shadow-info text-center rounded-circle">
                                            <i class="fas fa-thumbs-up text-info" aria-hidden="true"></i>
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
                                            <p class="text-white text-sm mb-0 text-uppercase font-weight-bold">Participants</p>
                                            <h5 class="text-white font-weight-bolder">
                                                {{ $event->registrations->count() }}
                                            </h5>
                                        </div>
                                    </div>
                                    <div class="col-4 text-end">
                                        <div class="icon icon-shape bg-white shadow-warning text-center rounded-circle">
                                            <i class="fas fa-users text-warning" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Rating Distribution Chart -->
                <div class="row mb-4">
                    <div class="col-lg-6">
                        <div class="card shadow-xs border">
                            <div class="card-header bg-white">
                                <h6 class="mb-0">Distribution des Notes</h6>
                            </div>
                            <div class="card-body">
                                @foreach($feedbacksByRating as $rating => $count)
                                <div class="rating-distribution-item mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <div class="rating-label">
                                            @for($i = 1; $i <= $rating; $i++)
                                                <i class="fas fa-star text-warning"></i>
                                            @endfor
                                            @for($i = $rating + 1; $i <= 5; $i++)
                                                <i class="far fa-star text-muted"></i>
                                            @endfor
                                            <span class="ms-2">{{ $rating }} étoile(s)</span>
                                        </div>
                                        <span class="rating-count">{{ $count }} avis</span>
                                    </div>
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar bg-gradient-primary" 
                                             role="progressbar" 
                                             style="width: {{ $evaluation->nb_feedbacks > 0 ? ($count / $evaluation->nb_feedbacks) * 100 : 0 }}%"
                                             aria-valuenow="{{ $count }}" 
                                             aria-valuemin="0" 
                                             aria-valuemax="{{ $evaluation->nb_feedbacks }}">
                                        </div>
                                    </div>
                                    <small class="text-muted">
                                        {{ $evaluation->nb_feedbacks > 0 ? number_format(($count / $evaluation->nb_feedbacks) * 100, 1) : 0 }}%
                                    </small>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-6">
                        <div class="card shadow-xs border">
                            <div class="card-header bg-white">
                                <h6 class="mb-0">Informations de l'Événement</h6>
                            </div>
                            <div class="card-body">
                                @if($event->images && count($event->images) > 0)
                                <div class="text-center mb-3">
                                    <img src="{{ $event->images[0] }}" 
                                         alt="{{ $event->title }}" 
                                         class="img-fluid rounded"
                                         style="max-height: 150px; object-fit: cover;">
                                </div>
                                @endif
                                
                                <div class="event-info">
                                    <div class="info-item mb-2">
                                        <strong>Date:</strong> 
                                        @if($event->start_date)
                                            {{ $event->start_date->format('d/m/Y à H:i') }}
                                        @else
                                            Date non définie
                                        @endif
                                    </div>
                                    
                                    <div class="info-item mb-2">
                                        <strong>Lieu:</strong> {{ $event->location }}
                                    </div>
                                    
                                    <div class="info-item mb-2">
                                        <strong>Catégorie:</strong> 
                                        <span class="badge bg-gradient-secondary">
                                            {{ $event->category->name ?? 'Non définie' }}
                                        </span>
                                    </div>
                                    
                                    <div class="info-item mb-2">
                                        <strong>Statut:</strong>
                                        <span class="badge bg-gradient-info">
                                            {{ $event->status->value ?? 'Non défini' }}
                                        </span>
                                    </div>
                                    
                                    <div class="info-item">
                                        <strong>Description:</strong>
                                        <p class="text-muted mt-1 mb-0">{{ Str::limit($event->description, 200) }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Individual Feedbacks -->
                <div class="row">
                    <div class="col-12">
                        <div class="card shadow-xs border">
                            <div class="card-header bg-white">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0">Avis des Participants</h6>
                                    <span class="badge bg-gradient-primary">{{ $feedbacks->count() }} avis</span>
                                </div>
                            </div>
                            <div class="card-body">
                                @forelse($feedbacks as $feedback)
                                <div class="feedback-item border-bottom pb-3 mb-3">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="d-flex align-items-start">
                                                <div class="participant-avatar me-3">
                                                    <div class="avatar-circle">
                                                        {{ strtoupper(substr($feedback->participant->name, 0, 1)) }}
                                                    </div>
                                                </div>
                                                <div class="feedback-content">
                                                    <div class="participant-name font-weight-bold">
                                                        {{ $feedback->participant->name }}
                                                    </div>
                                                    <div class="feedback-rating mb-2">
                                                        @for($i = 1; $i <= 5; $i++)
                                                            @if($i <= $feedback->note)
                                                                <i class="fas fa-star text-warning"></i>
                                                            @else
                                                                <i class="far fa-star text-muted"></i>
                                                            @endif
                                                        @endfor
                                                        <span class="ms-2 text-muted">
                                                            @if($feedback->date_feedback)
                                                                {{ $feedback->date_feedback->format('d/m/Y à H:i') }}
                                                            @else
                                                                Date non définie
                                                            @endif
                                                        </span>
                                                    </div>
                                                    @if($feedback->commentaire)
                                                    <div class="feedback-comment">
                                                        <div class="comment-bubble">
                                                            "{{ $feedback->commentaire }}"
                                                        </div>
                                                    </div>
                                                    @else
                                                    <div class="text-muted fst-italic">
                                                        Aucun commentaire fourni
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 text-end">
                                            <div class="rating-badge-large">
                                                <div class="rating-number">{{ $feedback->note }}</div>
                                                <div class="rating-label">/5</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <div class="text-center py-4">
                                    <i class="fas fa-comments fa-3x text-muted mb-3"></i>
                                    <h6 class="text-muted">Aucun avis disponible</h6>
                                    <p class="text-muted">Cet événement n'a pas encore reçu d'avis.</p>
                                </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
                
                @else
                <!-- No Evaluation Available -->
                <div class="row">
                    <div class="col-12">
                        <div class="card shadow-xs border">
                            <div class="card-body text-center py-5">
                                <i class="fas fa-chart-line fa-4x text-muted mb-3"></i>
                                <h4 class="text-muted">Aucune évaluation disponible</h4>
                                <p class="text-muted">
                                    Cet événement n'a pas encore reçu d'évaluation de la part des participants.
                                </p>
                                <a href="{{ route('evaluations.index') }}" class="btn btn-primary mt-2">
                                    <i class="fas fa-arrow-left me-2"></i>Retour aux évaluations
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <style>
        .rating-distribution-item {
            padding: 0.5rem 0;
        }
        
        .rating-label {
            font-size: 0.9rem;
            font-weight: 500;
        }
        
        .rating-count {
            font-weight: 600;
            color: #495057;
        }
        
        .feedback-item:last-child {
            border-bottom: none !important;
            margin-bottom: 0 !important;
            padding-bottom: 0 !important;
        }
        
        .participant-avatar {
            flex-shrink: 0;
        }
        
        .avatar-circle {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.1rem;
        }
        
        .participant-name {
            color: #495057;
            margin-bottom: 0.25rem;
        }
        
        .feedback-rating i {
            font-size: 0.9rem;
        }
        
        .comment-bubble {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-left: 4px solid #3b82f6;
            padding: 0.75rem 1rem;
            border-radius: 0 8px 8px 0;
            font-style: italic;
            color: #495057;
            margin-top: 0.5rem;
        }
        
        .rating-badge-large {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: white;
            border-radius: 12px;
            padding: 0.75rem;
            text-align: center;
            box-shadow: 0 4px 15px rgba(59,130,246,0.3);
            min-width: 70px;
        }
        
        .rating-badge-large .rating-number {
            font-size: 1.5rem;
            font-weight: bold;
            line-height: 1;
        }
        
        .rating-badge-large .rating-label {
            font-size: 0.8rem;
            opacity: 0.8;
        }
        
        .event-info .info-item {
            padding: 0.25rem 0;
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
            .rating-badge-large {
                margin-top: 1rem;
            }
            
            .feedback-content {
                width: 100%;
            }
        }
    </style>
</x-app-layout>
