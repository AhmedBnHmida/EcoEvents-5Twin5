<x-app-layout>
    <x-front-navbar />
    
    <div class="container py-5">
        <!-- Hero Section -->
        <div class="row">
            <div class="col-12 text-center py-5">
                <h1 class="display-4 fw-bold text-dark mb-3">Bienvenue sur EcoEvents 🎉</h1>
                <p class="lead text-muted mb-4">Découvrez et participez à des événements exceptionnels</p>
                <a href="{{ route('events.public') }}" class="btn btn-dark btn-lg">
                    <i class="fas fa-calendar me-2"></i>Voir tous les événements
                </a>
            </div>
        </div>

        <!-- Featured Events Section -->
        <div class="row mt-5">
            <div class="col-12">
                <h2 class="text-center mb-4">Événements à venir</h2>
                <p class="text-center text-muted mb-5">Découvrez nos prochains événements passionnants</p>
            </div>
            
            @php
                $featuredEvents = \App\Models\Event::with('category')
                    ->where('is_public', true)
                    ->where('status', '!=', \App\EventStatus::CANCELLED)
                    ->where('start_date', '>', now())
                    ->orderBy('start_date')
                    ->take(6)
                    ->get();
            @endphp
            
            @foreach($featuredEvents as $event)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card shadow-xs border h-100 hover-scale">
                    @if($event->images && count($event->images) > 0)
                        <img src="{{ $event->images[0] }}" class="card-img-top" alt="{{ $event->title }}" style="height: 200px; object-fit: cover;">
                    @else
                        <div class="card-img-top bg-gradient-dark d-flex align-items-center justify-content-center" style="height: 200px;">
                            <i class="fas fa-calendar-alt text-white fa-3x"></i>
                        </div>
                    @endif
                    
                    <div class="card-body d-flex flex-column">
                        <div class="mb-2">
                            @php
                                $statusColors = [
                                    'UPCOMING' => 'bg-gradient-warning',
                                    'ONGOING' => 'bg-gradient-success',
                                    'COMPLETED' => 'bg-gradient-info',
                                    'CANCELLED' => 'bg-gradient-danger'
                                ];
                            @endphp
                            <span class="badge {{ $statusColors[$event->status->value] ?? 'bg-gradient-secondary' }} text-xs">
                                {{ $event->status->value }}
                            </span>
                            <span class="badge bg-gradient-primary text-xs ms-1">
                                {{ $event->category->name }}
                            </span>
                        </div>
                        
                        <h5 class="card-title font-weight-bold">{{ Str::limit($event->title, 50) }}</h5>
                        <p class="card-text text-muted flex-grow-1">{{ Str::limit($event->description, 100) }}</p>
                        
                        <div class="mt-auto">
                            <div class="d-flex justify-content-between align-items-center text-sm text-muted mb-2">
                                <div>
                                    <i class="fas fa-calendar me-1"></i>
                                    {{ $event->start_date->format('M d, Y') }}
                                </div>
                                <div>
                                    <i class="fas fa-clock me-1"></i>
                                    {{ $event->start_date->format('H:i') }}
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center text-sm text-muted mb-3">
                                <div>
                                    <i class="fas fa-map-marker-alt me-1"></i>
                                    {{ Str::limit($event->location, 20) }}
                                </div>
                                <div class="fw-bold text-dark">
                                    ${{ number_format($event->price, 2) }}
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-xs text-muted">
                                    <i class="fas fa-users me-1"></i>
                                    {{ $event->registrations->count() }}/{{ $event->capacity_max }} inscrits
                                </span>
                                <a href="{{ route('events.public.show', $event->id) }}" class="btn btn-sm btn-dark">
                                    Voir détails
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
            
            @if($featuredEvents->count() === 0)
            <div class="col-12 text-center py-5">
                <i class="fas fa-calendar-times text-muted fa-4x mb-3"></i>
                <h4 class="text-muted">Aucun événement à venir</h4>
                <p class="text-muted">Revenez plus tard pour découvrir nos prochains événements.</p>
            </div>
            @endif
            
            @if($featuredEvents->count() > 0)
            <div class="col-12 text-center mt-4">
                <a href="{{ route('events.public') }}" class="btn btn-outline-dark btn-lg">
                    Voir tous les événements
                </a>
            </div>
            @endif
        </div>

        <!-- Partners Section -->
        <div class="row mt-5 py-5 bg-light rounded-3">
            <div class="col-12 text-center mb-4">
                <h2 class="mb-2">Nos Partenaires</h2>
                <p class="text-muted">Ils nous font confiance</p>
            </div>
            
            @php
                $partners = \App\Models\Partner::orderBy('created_at', 'desc')->take(8)->get();
            @endphp
            
            @if($partners->count() > 0)
            <div class="col-12 position-relative">
                <button class="partner-nav partner-nav-left" id="scrollLeft">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="partner-nav partner-nav-right" id="scrollRight">
                    <i class="fas fa-chevron-right"></i>
                </button>
                
                <div class="partners-slider" id="partnersSlider">
                    <div class="partners-track" id="partnersTrack">
                        @foreach($partners as $partner)
                        <div class="partner-item">
                            <div class="partner-card">
                                @if($partner->logo)
                                    <img src="{{ $partner->logo_url }}" alt="{{ $partner->nom }}" class="partner-logo">
                                @else
                                    <div class="partner-placeholder">
                                        <span class="partner-initials">{{ strtoupper(substr($partner->nom, 0, 2)) }}</span>
                                    </div>
                                @endif
                                <div class="partner-info">
                                    <h6 class="partner-name">{{ $partner->nom }}</h6>
                                    <span class="badge bg-gradient-primary text-xxs">{{ $partner->type }}</span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        <!-- Duplicate for seamless loop -->
                        @foreach($partners as $partner)
                        <div class="partner-item">
                            <div class="partner-card">
                                @if($partner->logo)
                                    <img src="{{ $partner->logo_url }}" alt="{{ $partner->nom }}" class="partner-logo">
                                @else
                                    <div class="partner-placeholder">
                                        <span class="partner-initials">{{ strtoupper(substr($partner->nom, 0, 2)) }}</span>
                                    </div>
                                @endif
                                <div class="partner-info">
                                    <h6 class="partner-name">{{ $partner->nom }}</h6>
                                    <span class="badge bg-gradient-primary text-xxs">{{ $partner->type }}</span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @else
            <div class="col-12 text-center py-4">
                <i class="fas fa-handshake text-muted fa-3x mb-3"></i>
                <p class="text-muted">Aucun partenaire pour le moment</p>
            </div>
            @endif
        </div>

        <!-- Features Section -->
        <div class="row mt-5 py-5">
            <div class="col-12 text-center mb-5">
                <h2>Pourquoi choisir EcoEvents ?</h2>
            </div>
            <div class="col-lg-4 col-md-6 text-center mb-4">
                <div class="icon icon-shape icon-lg bg-gradient-primary text-white rounded-circle shadow mb-3">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <h5>Événements variés</h5>
                <p class="text-muted">Découvrez une large gamme d'événements adaptés à tous les goûts</p>
            </div>
            <div class="col-lg-4 col-md-6 text-center mb-4">
                <div class="icon icon-shape icon-lg bg-gradient-success text-white rounded-circle shadow mb-3">
                    <i class="fas fa-ticket-alt"></i>
                </div>
                <h5>Réservation facile</h5>
                <p class="text-muted">Réservez vos places en quelques clics seulement</p>
            </div>
            <div class="col-lg-4 col-md-6 text-center mb-4">
                <div class="icon icon-shape icon-lg bg-gradient-info text-white rounded-circle shadow mb-3">
                    <i class="fas fa-users"></i>
                </div>
                <h5>Communauté active</h5>
                <p class="text-muted">Rejoignez une communauté passionnée par les événements</p>
            </div>
        </div>
    </div>

    <style>
        .hover-scale {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .hover-scale:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
        }
        .icon-shape {
            width: 80px;
            height: 80px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        /* Partners Slider */
        .partners-slider {
            overflow: hidden;
            position: relative;
            width: 100%;
            padding: 20px 0;
        }

        .partners-track {
            display: flex;
            gap: 30px;
            animation: scroll 30s linear infinite;
            width: fit-content;
        }

        .partners-track:hover {
            animation-play-state: paused;
        }

        @keyframes scroll {
            0% {
                transform: translateX(0);
            }
            100% {
                transform: translateX(-50%);
            }
        }

        .partner-item {
            flex-shrink: 0;
            width: 220px;
        }

        .partner-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            text-align: center;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .partner-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
        }

        .partner-logo {
            width: 120px;
            height: 80px;
            object-fit: contain;
            margin-bottom: 15px;
            filter: grayscale(100%);
            transition: filter 0.3s ease;
        }

        .partner-card:hover .partner-logo {
            filter: grayscale(0%);
        }

        .partner-placeholder {
            width: 120px;
            height: 80px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
        }

        .partner-initials {
            font-size: 28px;
            font-weight: bold;
            color: white;
        }

        .partner-info {
            width: 100%;
        }

        .partner-name {
            font-size: 14px;
            font-weight: 600;
            color: #344767;
            margin-bottom: 5px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Navigation Arrows */
        .partner-nav {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            z-index: 10;
            background: white;
            border: 2px solid #667eea;
            color: #667eea;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.2);
        }

        .partner-nav:hover {
            background: #667eea;
            color: white;
            transform: translateY(-50%) scale(1.1);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }

        .partner-nav-left {
            left: -25px;
        }

        .partner-nav-right {
            right: -25px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .partner-item {
                width: 180px;
            }
            .partner-logo {
                width: 100px;
                height: 60px;
            }
            .partner-placeholder {
                width: 100px;
                height: 60px;
            }
            .partner-nav {
                width: 40px;
                height: 40px;
            }
            .partner-nav-left {
                left: 10px;
            }
            .partner-nav-right {
                right: 10px;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const track = document.getElementById('partnersTrack');
            const leftBtn = document.getElementById('scrollLeft');
            const rightBtn = document.getElementById('scrollRight');
            
            if (track && leftBtn && rightBtn) {
                let isAnimating = false;
                const scrollAmount = 250; // pixels to scroll
                
                leftBtn.addEventListener('click', function() {
                    if (!isAnimating) {
                        isAnimating = true;
                        track.style.animation = 'none';
                        const currentScroll = track.scrollLeft || 0;
                        track.style.transform = `translateX(-${Math.max(0, currentScroll - scrollAmount)}px)`;
                        
                        setTimeout(() => {
                            isAnimating = false;
                        }, 300);
                    }
                });
                
                rightBtn.addEventListener('click', function() {
                    if (!isAnimating) {
                        isAnimating = true;
                        track.style.animation = 'none';
                        const currentScroll = track.scrollLeft || 0;
                        track.style.transform = `translateX(-${currentScroll + scrollAmount}px)`;
                        
                        setTimeout(() => {
                            isAnimating = false;
                        }, 300);
                    }
                });
                
                // Pause animation on hover
                track.addEventListener('mouseenter', function() {
                    track.style.animationPlayState = 'paused';
                });
                
                track.addEventListener('mouseleave', function() {
                    track.style.animationPlayState = 'running';
                });
            }
        });
    </script>
    
    @auth
        <x-eco-chatbot title="EcoAssistant" placeholder="Posez une question sur l'écologie..." />
    @endauth
</x-app-layout>