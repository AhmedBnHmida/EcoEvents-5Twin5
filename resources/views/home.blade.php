<x-app-layout>
    <canvas id="fullScreenCanvas" class="fixed-canvas"></canvas>

    <x-front-navbar />

    <main class="main-content-wrapper">
        {{-- MODIFIED: Added mb-5 to create a gap below the hero section --}}
        <section class="py-6 py-md-9 border-bottom  mb-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-10 text-center">
                        <h1 class="display-3 fw-bolder mb-4 text-shadow-dark">
                            Mobilisez-vous pour <span class="text-success-bright">l'Écologie</span> et le <span class="text-info-bright">Développement Durable</span>
                        </h1>
                        <p class="lead mb-5 text-bright-white">
                            Votre point de rencontre en ligne pour <span class="text-success-bright">organiser, promouvoir et participer</span> à des événements qui changent le monde. Soutenez les initiatives citoyennes et passez à l'action collective pour la protection de l'environnement.
                        </p>
                        
                        <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
                            <a href="{{ route('events.public') }}" class="btn btn-success btn-lg shadow-lg px-5 me-sm-3 animate-up btn-success-gradient">
                                <i class="fas fa-calendar-alt me-2"></i>Découvrir les Événements
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        
        <section class="py-7 section-dark-bg border-top mb-5">
            <div class="container">
                <div class="row">
                    <div class="col-12 text-center mb-6">
                        <span class="badge bg-success text-uppercase py-2 px-3 mb-2 badge-pill">Passez à l'Action</span>
                        <h2 class="fw-bold mb-3 text-dark-title">Événements Solidaires et Locaux à Venir</h2>
                        <p class="text-muted fs-5">Découvrez nos prochains rendez-vous pour un impact positif sur l'environnement.</p>
                    </div>

                    @php
                        // Keep your existing Eloquent query logic
                        $featuredEvents = \App\Models\Event::with('category')
                            ->where('is_public', true)
                            ->where('status', '!=', \App\EventStatus::CANCELLED)
                            ->where('start_date', '>', now())
                            ->orderBy('start_date')
                            ->take(6)
                            ->get();
                        $statusColors = [
                            'UPCOMING' => 'bg-warning',
                            'ONGOING' => 'bg-success',
                            'COMPLETED' => 'bg-info',
                            'CANCELLED' => 'bg-danger'
                        ];
                    @endphp

                    @if($featuredEvents->count() > 0)
                    <div class="col-12">
                        <div class="events-slider-container">
                            <div class="events-track">
                                {{-- First set of events --}}
                                @foreach($featuredEvents as $event)
                                <div class="event-item">
                                    <div class="card shadow-hover-3d border-0 h-100 transition-all">
                                        {{-- Image/Placeholder Logic --}}
                                        @if($event->images && count($event->images) > 0)
                                            <img src="{{ str_starts_with($event->images[0], 'http') ? $event->images[0] : asset('storage/' . $event->images[0]) }}" 
                                                class="card-img-top card-img-eco" 
                                                alt="{{ $event->title }}" 
                                                style="height: 220px; object-fit: cover; transition: transform 0.3s ease;">
                                        @else
                                            <div class="card-img-top bg-gradient-success d-flex align-items-center justify-content-center card-img-eco" 
                                                style="height: 220px;">
                                                <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark opacity-50"></div>
                                                <div class="position-relative z-1 text-center text-white p-3">
                                                    <i class="fas fa-leaf fa-4x mb-3 opacity-75"></i>
                                                    <h6 class="mb-0 fw-semibold">{{ $event->title }}</h6>
                                                </div>
                                            </div>
                                        @endif

                                        <div class="card-body d-flex flex-column">
                                            <div class="mb-2">
                                                <span class="badge {{ $statusColors[$event->status->value] ?? 'bg-secondary' }} text-xs me-1">{{ $event->status->value }}</span>
                                                <span class="badge bg-primary text-xs">{{ $event->category->name }}</span>
                                            </div>

                                            <h5 class="card-title fw-bolder mt-2 mb-3 text-truncate text-dark-title">{{ Str::limit($event->title, 50) }}</h5> 
                                            <p class="card-text text-muted small flex-grow-1 mb-3">{{ Str::limit($event->description, 100) }}</p>

                                            <div class="mt-auto pt-3 border-top">
                                                <div class="d-flex justify-content-between align-items-center text-sm text-dark-emphasis mb-2">
                                                    <div><i class="fas fa-calendar-day me-1 text-success"></i> {{ $event->start_date->format('d M Y') }}</div>
                                                    <div><i class="fas fa-clock me-1 text-info"></i> {{ $event->start_date->format('H:i') }}</div>
                                                </div>

                                                <div class="d-flex justify-content-between align-items-center text-sm mb-3">
                                                    <div class="small text-muted"><i class="fas fa-map-marker-alt me-1 text-danger"></i> {{ Str::limit($event->location, 20) }}</div>
                                                    <div class="fw-bolder text-success">
                                                        @if ($event->price > 0)
                                                            ${{ number_format($event->price, 2) }}
                                                        @else
                                                            Gratuit
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span class="text-xs text-secondary">
                                                        <i class="fas fa-user-check me-1"></i> {{ $event->registrations->count() }}/{{ $event->capacity_max }} inscrits
                                                    </span>
                                                    <a href="{{ route('events.public.show', $event->id) }}" class="btn btn-sm btn-success-gradient">
                                                        Détails <i class="fas fa-arrow-right ms-1"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach

                                {{-- Duplicate for seamless loop --}}
                                @foreach($featuredEvents as $event)
                                <div class="event-item">
                                    <div class="card shadow-hover-3d border-0 h-100 transition-all">
                                        {{-- Image/Placeholder Logic --}}
                                        @if($event->images && count($event->images) > 0)
                                            <img src="{{ $event->images[0] }}" class="card-img-top card-img-eco" alt="{{ $event->title }}">
                                        @else
                                            <div class="card-img-top bg-gradient-success d-flex align-items-center justify-content-center card-img-eco">
                                                <i class="fas fa-leaf text-white fa-3x"></i>
                                            </div>
                                        @endif

                                        <div class="card-body d-flex flex-column">
                                            <div class="mb-2">
                                                <span class="badge {{ $statusColors[$event->status->value] ?? 'bg-secondary' }} text-xs me-1">{{ $event->status->value }}</span>
                                                <span class="badge bg-primary text-xs">{{ $event->category->name }}</span>
                                            </div>

                                            <h5 class="card-title fw-bolder mt-2 mb-3 text-truncate text-dark-title">{{ Str::limit($event->title, 50) }}</h5> 
                                            <p class="card-text text-muted small flex-grow-1 mb-3">{{ Str::limit($event->description, 100) }}</p>

                                            <div class="mt-auto pt-3 border-top">
                                                <div class="d-flex justify-content-between align-items-center text-sm text-dark-emphasis mb-2">
                                                    <div><i class="fas fa-calendar-day me-1 text-success"></i> {{ $event->start_date->format('d M Y') }}</div>
                                                    <div><i class="fas fa-clock me-1 text-info"></i> {{ $event->start_date->format('H:i') }}</div>
                                                </div>

                                                <div class="d-flex justify-content-between align-items-center text-sm mb-3">
                                                    <div class="small text-muted"><i class="fas fa-map-marker-alt me-1 text-danger"></i> {{ Str::limit($event->location, 20) }}</div>
                                                    <div class="fw-bolder text-success">
                                                        @if ($event->price > 0)
                                                            ${{ number_format($event->price, 2) }}
                                                        @else
                                                            Gratuit
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span class="text-xs text-secondary">
                                                        <i class="fas fa-user-check me-1"></i> {{ $event->registrations->count() }}/{{ $event->capacity_max }} inscrits
                                                    </span>
                                                    <a href="{{ route('events.public.show', $event->id) }}" class="btn btn-sm btn-success-gradient">
                                                        Détails <i class="fas fa-arrow-right ms-1"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="col-12 text-center py-5">
                        <i class="fas fa-seedling text-success fa-4x mb-3"></i>
                        <h4 class="text-muted">Aucun grand événement à l'horizon...</h4>
                        <p class="text-muted">Soyez le premier à organiser une action écologique !</p>
                        <a href="{{ route('events.create') }}" class="btn btn-success-gradient mt-3">Créer un événement</a>
                    </div>
                    @endif

                    @if($featuredEvents->count() > 0)
                    <div class="col-12 text-center mt-5">
                        <a href="{{ route('events.public') }}" class="btn btn-outline-success btn-lg px-5 transition-all">
                            <i class="fas fa-globe me-2"></i> Explorer tout le Calendrier
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </section>

        <section class="py-4 section-dark-bg border-top mb-5">
            <div class="container">
                <div class="row">
                    <div class="col-12 text-center mb-6">
                        <span class="badge bg-primary text-uppercase py-2 px-3 mb-2 badge-pill">Notre Impact</span>
                        <h2 class="fw-bold text-dark-title">Pourquoi choisir EcoEvents ?</h2>
                    </div>
                    <div class="col-lg-4 col-md-6 text-center mb-4">
                        <div class="icon-box bg-gradient-success shadow-lg mb-3">
                            <i class="fas fa-handshake fa-lg"></i>
                        </div>
                        <h5 class="fw-bold text-dark-title">Faciliter l'Engagement</h5>
                        <p class="text-muted">Rendez vos initiatives accessibles et trouvez facilement des bénévoles prêts à agir.</p>
                    </div>
                    <div class="col-lg-4 col-md-6 text-center mb-4">
                        <div class="icon-box bg-gradient-info shadow-lg mb-3">
                            <i class="fas fa-users fa-lg"></i>
                        </div>
                        <h5 class="fw-bold text-dark-title">Communauté Active</h5>
                        <p class="text-muted">Rejoignez un réseau de citoyens, associations et organisations passionnés par le durable.</p>
                    </div>
                    <div class="col-lg-4 col-md-6 text-center mb-4">
                        <div class="icon-box bg-gradient-primary shadow-lg mb-3">
                            <i class="fas fa-lightbulb fa-lg"></i>
                        </div>
                        <h5 class="fw-bold text-dark-title">Sensibilisation Accrue</h5>
                        <p class="text-muted">Amplifiez votre message et sensibilisez un public plus large aux enjeux écologiques.</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-3 section-dark-bg border-top pb-5">
            <div class="container">
                <div class="row">
                    <div class="col-12 text-center mb-5">
                        <h2 class="fw-bold mb-2 text-dark-title">Nos Partenaires Engagés</h2>
                        <p class="text-muted">Ils soutiennent notre mission et bâtissent un avenir durable avec nous.</p>
                    </div>
                    
                    @php
                        $partners = \App\Models\Partner::orderBy('created_at', 'desc')->take(8)->get();
                    @endphp
                    
                    @if($partners->count() > 0)
                    <div class="col-12">
                        <div class="partners-slider-container">
                            <div class="partners-track">
                                {{-- First set of partners --}}
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

                                {{-- Duplicate for seamless loop --}}
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
                        <i class="fas fa-handshake text-success fa-3x mb-3"></i>
                        <p class="text-muted">Devenez notre premier partenaire et amplifiez votre impact.</p>
                        <a href="{{ route('contact') }}" class="btn btn-outline-success-gradient mt-3">Devenir Partenaire</a>
                    </div>
                    @endif
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

    /* Events Horizontal Scrolling */
    .events-slider-container {
        overflow: hidden;
        padding: 30px 0;
        position: relative;
        width: 100%;
    }

    .events-track {
        display: flex;
        animation: scroll-events 40s linear infinite;
        will-change: transform;
        gap: 2rem;
        padding: 0 1rem;
    }

    .events-track:hover {
        animation-play-state: paused;
    }

    .event-item {
        flex: 0 0 380px;
        min-width: 380px;
    }

    /* Partners Horizontal Scrolling */
    .partners-slider-container {
        overflow: hidden;
        padding: 30px 0;
        position: relative;
        width: 100%;
    }

    .partners-track {
        display: flex;
        animation: scroll-partners 30s linear infinite;
        will-change: transform;
        gap: 2rem;
        padding: 0 1rem;
    }

    .partners-track:hover {
        animation-play-state: paused;
    }

    .partner-item {
        flex: 0 0 280px;
        min-width: 280px;
    }

    /* Animations */
    @keyframes scroll-events {
        0% {
            transform: translateX(0);
        }
        100% {
            transform: translateX(calc(-100% / 2));
        }
    }

    @keyframes scroll-partners {
        0% {
            transform: translateX(0);
        }
        100% {
            transform: translateX(calc(-100% / 2));
        }
    }

    /* Card Enhancements */
    .card {
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        overflow: hidden;
    }

    .card-body {
        padding: 1.5rem;
    }

    .card-img-eco {
        height: 220px; 
        object-fit: cover;
        border-top-left-radius: 12px;
        border-top-right-radius: 12px;
    }

    .shadow-hover-3d {
        transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
    }

    .shadow-hover-3d:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 25px 50px rgba(0, 150, 0, 0.25), 0 10px 25px rgba(0, 0, 0, 0.15);
    }

    /* Enhanced Card Styling for Horizontal Layout */
    .event-item .card {
        height: 100%;
        min-height: 480px;
        transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
    }

    .event-item .card:hover {
        transform: translateY(-10px) scale(1.03);
    }

    .partner-card {
        width: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 25px 20px;
        border-radius: 16px;
        border: 1px solid var(--color-border-light);
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.08) 0%, rgba(255, 255, 255, 0.02) 100%);
        backdrop-filter: blur(15px);
        transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
        min-height: 180px;
        justify-content: center;
        position: relative;
        overflow: hidden;
    }

    .partner-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(76, 175, 80, 0.1), transparent);
        transition: left 0.6s ease;
    }

    .partner-card:hover::before {
        left: 100%;
    }

    .partner-card:hover {
        transform: translateY(-8px) scale(1.05);
        border-color: rgba(76, 175, 80, 0.4);
        box-shadow: 0 20px 40px rgba(0, 150, 0, 0.2), 
                    0 8px 25px rgba(0, 0, 0, 0.3);
    }

    .partner-logo {
        max-width: 120px;
        max-height: 60px;
        object-fit: contain;
        margin-bottom: 20px;
        opacity: 0.9;
        transition: all 0.3s ease;
        filter: brightness(0) invert(1);
    }

    .partner-card:hover .partner-logo {
        opacity: 1;
        transform: scale(1.1);
    }

    .partner-placeholder {
        width: 120px;
        height: 60px;
        background: linear-gradient(135deg, rgba(76, 175, 80, 0.15) 0%, rgba(76, 175, 80, 0.05) 100%);
        border: 2px solid rgba(76, 175, 80, 0.3);
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        margin-bottom: 20px;
        transition: all 0.3s ease;
    }

    .partner-card:hover .partner-placeholder {
        border-color: rgba(76, 175, 80, 0.6);
        background: linear-gradient(135deg, rgba(76, 175, 80, 0.25) 0%, rgba(76, 175, 80, 0.1) 100%);
    }

    .partner-initials {
        font-weight: bold;
        color: var(--color-success-bright);
        font-size: 1.4rem;
        transition: all 0.3s ease;
    }

    .partner-card:hover .partner-initials {
        transform: scale(1.1);
        color: #fff;
    }

    .partner-info {
        text-align: center;
    }

    .partner-name {
        font-size: 1.1rem;
        margin-bottom: 12px;
        font-weight: 700;
        color: #fafafa;
        transition: all 0.3s ease;
    }

    .partner-card:hover .partner-name {
        color: var(--color-success-bright);
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

    /* Icon Boxes - Enhanced */
    .icon-box {
        width: 90px;
        height: 90px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        color: white;
        font-size: 1.8rem;
        margin-bottom: 1.5rem;
        transition: all 0.3s ease;
    }

    .icon-box:hover {
        transform: scale(1.1) rotate(5deg);
    }

    .bg-gradient-success {
        background: linear-gradient(135deg, #66bb6a 0%, #43a047 100%) !important;
    }

    .bg-gradient-info {
        background: linear-gradient(135deg, #42a5f5 0%, #1976d2 100%) !important;
    }

    .bg-gradient-primary {
        background: linear-gradient(135deg, #ab47bc 0%, #8e24aa 100%) !important;
    }

    /* Badge Enhancements */
    .badge-pill {
        border-radius: 50rem;
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    /* Text Colors */
    .text-dark-title { 
        color: white !important; 
        font-weight: 700;
    }

    .text-dark-emphasis {
        color: var(--color-success-bright) !important;
    }

    .text-success-bright { 
        color: var(--color-success-bright) !important; 
    }
    
    .text-info-bright { 
        color: var(--color-info-bright) !important; 
    }

    .text-xxs {
        font-size: 0.65rem;
        padding: 0.3em 0.6em;
    }

    /* Hero Section */
    .hero-section-dark-bg {
        padding-top: 120px !important;
        background: linear-gradient(135deg, var(--color-dark-main-bg) 0%, #0a1920 100%) !important;
        position: relative;
        overflow: hidden;
    }

    .hero-section-dark-bg::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: radial-gradient(circle at 30% 70%, rgba(76, 175, 80, 0.1) 0%, transparent 50%);
        pointer-events: none;
    }

    .text-shadow-dark {
        text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.7);
        color: white !important;
    }

    /* Animation Enhancements */
    .animate-up {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .animate-up:hover {
        transform: translateY(-5px);
    }

    /* Empty State Styling */
    .empty-state {
        padding: 4rem 2rem;
        text-align: center;
    }

    .empty-state i {
        margin-bottom: 1.5rem;
        opacity: 0.7;
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
        
        .hero-section-dark-bg {
            padding-top: 100px !important;
        }
        
        .display-3 {
            font-size: 2.5rem;
        }
        
        .icon-box {
            width: 70px;
            height: 70px;
            font-size: 1.5rem;
        }
        
        .event-item {
            flex: 0 0 320px;
            min-width: 320px;
        }
        
        .partner-item {
            flex: 0 0 240px;
            min-width: 240px;
        }
        
        .events-track,
        .partners-track {
            gap: 1rem;
            padding: 0 0.5rem;
        }
        
        .event-item .card {
            min-height: 420px;
        }
    }

    /* Scroll indicators */
    .events-slider-container::after,
    .partners-slider-container::after {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 100px;
        height: 100%;
        background: linear-gradient(90deg, transparent, var(--color-section-dark));
        pointer-events: none;
    }

    .events-slider-container::before,
    .partners-slider-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100px;
        height: 100%;
        background: linear-gradient(90deg, var(--color-section-dark), transparent);
        pointer-events: none;
        z-index: 2;
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

    @auth
        <x-eco-chatbot title="EcoAssistant" placeholder="Posez une question sur l'écologie..." />
    @endauth
</x-app-layout>