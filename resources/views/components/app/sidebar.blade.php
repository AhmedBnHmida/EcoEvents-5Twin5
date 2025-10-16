<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 modern-sidebar fixed-start min-vh-100" id="sidenav-main">
    <div class="sidenav-header modern-header">
        <i class="fas fa-times p-3 cursor-pointer text-white opacity-7 position-absolute end-0 top-0 d-none d-xl-none"
            aria-hidden="true" id="iconSidenav"></i>
        <a class="navbar-brand d-flex align-items-center m-0 justify-content-center"
            href="{{ route('dashboard') }}">
            <div class="brand-logo me-2">
                <i class="fas fa-leaf text-success"></i>
            </div>
            <span class="font-weight-bold text-lg text-white">EcoEvents</span>
        </a>
        <div class="brand-subtitle">
            @php $user = Auth::user(); @endphp
            <small class="text-white-50">
                @if($user && $user->role === 'admin')
                    Panneau d'Administration
                @elseif($user && $user->role === 'fournisseur')
                    Espace Fournisseur
                @elseif($user && $user->role === 'organisateur')
                    Espace Organisateur
                @else
                    Dashboard
                @endif
            </small>
        </div>
    </div>
    <div class="collapse navbar-collapse px-3 w-auto h-100" id="sidenav-collapse-main">
        @php $user = Auth::user(); @endphp
        <ul class="navbar-nav pt-3">
            <!-- Dashboard -->
            <li class="nav-item mb-1">
                <a class="nav-link modern-nav-link d-flex align-items-center {{ is_current_route('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <i class="fas fa-tachometer-alt me-3"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            @if($user && $user->role === 'admin')
                <!-- User Management Section -->
                <li class="nav-section-header">
                    <span><i class="fas fa-users-cog me-2"></i>Gestion Utilisateurs</span>
                </li>
                <li class="nav-item mb-1">
                    <a class="nav-link modern-nav-link d-flex align-items-center {{ is_current_route('users.index') ? 'active' : '' }}" href="{{ route('users.index') }}">
                        <i class="fas fa-users me-3"></i>
                        <span>Utilisateurs</span>
                    </a>
                </li>

                <!-- Events Section -->
                <li class="nav-section-header">
                    <span><i class="fas fa-calendar me-2"></i>Événements</span>
                </li>
                <li class="nav-item mb-1">
                    <a class="nav-link modern-nav-link d-flex align-items-center {{ is_current_route('events.index') ? 'active' : '' }}" href="{{ route('events.index') }}">
                        <i class="fas fa-calendar-check me-3"></i>
                        <span>Événements</span>
                    </a>
                </li>
                <li class="nav-item mb-1">
                    <a class="nav-link modern-nav-link d-flex align-items-center {{ is_current_route('categories.index') ? 'active' : '' }}" href="{{ route('categories.index') }}">
                        <i class="fas fa-folder-open me-3"></i>
                        <span>Catégories</span>
                    </a>
                </li>
                <li class="nav-item mb-1">
                    <a class="nav-link modern-nav-link d-flex align-items-center {{ is_current_route('registrations.index') ? 'active' : '' }}" href="{{ route('registrations.index') }}">
                        <i class="fas fa-user-check me-3"></i>
                        <span>Inscriptions</span>
                    </a>
                </li>
                <li class="nav-item mb-1">
                    <a class="nav-link modern-nav-link d-flex align-items-center {{ is_current_route('qrscan.show') ? 'active' : '' }}" href="{{ route('qrscan.show') }}">
                        <i class="fas fa-qrcode me-3"></i>
                        <span>Scanner QR</span>
                    </a>
                </li>

                <!-- Feedback Section -->
                <li class="nav-section-header">
                    <span><i class="fas fa-comments me-2"></i>Retours & Évaluations</span>
                </li>
                <li class="nav-item mb-1">
                    <a class="nav-link modern-nav-link d-flex align-items-center {{ is_current_route('feedback.index') ? 'active' : '' }}" href="{{ route('feedback.index') }}">
                        <i class="fas fa-comment-dots me-3"></i>
                        <span>Feedback</span>
                    </a>
                </li>
                <li class="nav-item mb-1">
                    <a class="nav-link modern-nav-link d-flex align-items-center {{ is_current_route('evaluations.index') ? 'active' : '' }}" href="{{ route('evaluations.index') }}">
                        <i class="fas fa-star me-3"></i>
                        <span>Évaluations</span>
                    </a>
                </li>

                <!-- Resources Section -->
                <li class="nav-section-header">
                    <span><i class="fas fa-box me-2"></i>Ressources & Logistique</span>
                </li>
           
                <li class="nav-item mb-1">
                    <a class="nav-link modern-nav-link d-flex align-items-center {{ is_current_route('fournisseurs.index') ? 'active' : '' }}" href="{{ route('fournisseurs.index') }}">
                        <i class="fas fa-truck-loading me-3"></i>
                        <span>Fournisseurs</span>
                    </a>
                </li>
                <li class="nav-item mb-1">
                    <a class="nav-link modern-nav-link d-flex align-items-center {{ is_current_route('partenaires.index') ? 'active' : '' }}" href="{{ route('partenaires.index') }}">
                        <i class="fas fa-handshake me-3"></i>
                        <span>Partenaires</span>
                    </a>
                </li>
                <li class="nav-item mb-1">
                    <a class="nav-link modern-nav-link d-flex align-items-center {{ is_current_route('sponsoring.index') ? 'active' : '' }}" href="{{ route('sponsoring.index') }}">
                        <i class="fas fa-donate me-3"></i>
                        <span>Sponsoring</span>
                    </a>
                </li>

            @elseif($user && $user->role === 'fournisseur')
                <!-- Fournisseur Section -->
                <li class="nav-section-header">
                    <span><i class="fas fa-box me-2"></i>Mes Ressources</span>
                </li>
                <li class="nav-item mb-1">
                    <a class="nav-link modern-nav-link d-flex align-items-center {{ is_current_route('ressources.index') ? 'active' : '' }}" href="{{ route('ressources.index') }}">
                        <i class="fas fa-box-open me-3"></i>
                        <span>Ressources</span>
                    </a>
                </li>

            @elseif($user && $user->role === 'organisateur')
                <!-- Organisateur Section -->
                <li class="nav-section-header">
                    <span><i class="fas fa-calendar me-2"></i>Mes Événements</span>
                </li>
                <li class="nav-item mb-1">
                    <a class="nav-link modern-nav-link d-flex align-items-center {{ is_current_route('events.index') ? 'active' : '' }}" href="{{ route('events.index') }}">
                        <i class="fas fa-calendar-check me-3"></i>
                        <span>Événements</span>
                    </a>
                </li>
                <li class="nav-item mb-1">
                    <a class="nav-link modern-nav-link d-flex align-items-center {{ is_current_route('registrations.index') ? 'active' : '' }}" href="{{ route('registrations.index') }}">
                        <i class="fas fa-user-check me-3"></i>
                        <span>Inscriptions</span>
                    </a>
                </li>
                <li class="nav-item mb-1">
                    <a class="nav-link modern-nav-link d-flex align-items-center {{ is_current_route('qrscan.show') ? 'active' : '' }}" href="{{ route('qrscan.show') }}">
                        <i class="fas fa-qrcode me-3"></i>
                        <span>Scanner QR</span>
                    </a>
                </li>
                <li class="nav-item mb-1">
                    <a class="nav-link modern-nav-link d-flex align-items-center {{ is_current_route('ressources.index') ? 'active' : '' }}" href="{{ route('ressources.index') }}">
                        <i class="fas fa-box-open me-3"></i>
                        <span>Ressources</span>
                    </a>
                </li>
            @endif
        </ul>

        <!-- Logout Section (Fixed at bottom) -->
        <ul class="navbar-nav mt-auto pt-4 border-top border-secondary">
            @auth
            <li class="nav-item">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-danger w-100 modern-logout-btn">
                        <i class="fas fa-sign-out-alt me-2"></i>Déconnexion
                    </button>
                </form>
            </li>
            @endauth
        </ul>
    </div>
</aside>

<style>
    /* Modern Sidebar Styling */
    .modern-sidebar {
        background: #1e293b !important;
        box-shadow: 2px 0 25px rgba(0,0,0,0.2);
        border-right: 1px solid rgba(255,255,255,0.05);
    }
    
    .modern-header {
        padding: 1.5rem 1rem;
        border-bottom: 2px solid rgba(255,255,255,0.1);
        background: rgba(0,0,0,0.2);
        text-align: center;
    }
    
    .brand-logo {
        width: 40px;
        height: 40px;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
    }
    
    .brand-subtitle {
        margin-top: 0.5rem;
        text-align: center;
    }
    
    /* Navigation Links */
    .modern-nav-link {
        color: #e2e8f0 !important;
        padding: 0.75rem 1rem;
        margin: 0.2rem 0;
        border-radius: 8px;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        font-weight: 500 !important;
    }
    
    .modern-nav-link::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
        transition: left 0.5s ease;
    }
    
    .modern-nav-link:hover {
        background: rgba(59, 130, 246, 0.15);
        color: white !important;
        transform: translateX(5px);
        box-shadow: 0 2px 8px rgba(59, 130, 246, 0.2);
    }
    
    .modern-nav-link:hover::before {
        left: 100%;
    }
    
    .modern-nav-link.active {
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        color: white !important;
        box-shadow: 0 4px 15px rgba(59,130,246,0.4);
        transform: translateX(5px);
    }
    
    .modern-nav-link.active::after {
        content: '';
        position: absolute;
        right: 0;
        top: 50%;
        transform: translateY(-50%);
        width: 4px;
        height: 20px;
        background: white;
        border-radius: 2px 0 0 2px;
    }
    
    .modern-nav-link i {
        width: 20px;
        text-align: center;
        opacity: 0.9;
        transition: all 0.3s ease;
        font-size: 1rem;
    }
    
    .modern-nav-link:hover i,
    .modern-nav-link.active i {
        opacity: 1;
        transform: scale(1.15);
    }
    
    .modern-nav-link span {
        font-weight: 500;
        font-size: 0.925rem;
    }
    
    .modern-nav-link.active span {
        font-weight: 600;
    }
    
    /* User Section */
    .user-section {
        border-top: 1px solid rgba(255,255,255,0.1);
        padding-top: 1rem;
        margin-top: 1rem;
    }
    
    /* Responsive Design */
    @media (max-width: 1199px) {
        .modern-sidebar {
            transform: translateX(-100%);
            transition: transform 0.3s ease;
        }
        
        .modern-sidebar.show {
            transform: translateX(0);
        }
    }
    
    /* Scrollbar Styling */
    .modern-sidebar::-webkit-scrollbar {
        width: 6px;
    }
    
    .modern-sidebar::-webkit-scrollbar-track {
        background: rgba(255,255,255,0.1);
    }
    
    .modern-sidebar::-webkit-scrollbar-thumb {
        background: rgba(255,255,255,0.3);
        border-radius: 3px;
    }
    
    .modern-sidebar::-webkit-scrollbar-thumb:hover {
        background: rgba(255,255,255,0.5);
    }
    
    /* Animation for page load */
    .modern-sidebar {
        animation: slideInLeft 0.6s ease-out;
    }
    
    @keyframes slideInLeft {
        from {
            transform: translateX(-100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    /* Hover effects for better UX */
    .navbar-nav {
        padding: 0.5rem 0;
    }
    
    .nav-item {
        position: relative;
    }
    
    /* Section Headers */
    .nav-section-header {
        margin-top: 1.5rem;
        margin-bottom: 0.5rem;
        padding: 0.75rem 0.75rem 0.5rem;
        list-style: none;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    .nav-section-header span {
        color: #94a3b8 !important;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        display: flex;
        align-items: center;
    }
    
    .nav-section-header i {
        font-size: 0.75rem;
        opacity: 0.8;
    }
    
    /* First section header should have less margin */
    .nav-section-header:first-of-type {
        margin-top: 1rem;
    }
    
    /* Logout Button Styling */
    .modern-logout-btn {
        border-radius: 8px;
        padding: 0.75rem 1rem;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(220, 53, 69, 0.3);
    }
    
    .modern-logout-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(220, 53, 69, 0.5);
    }
    
    /* Scrollbar for navigation */
    #sidenav-collapse-main::-webkit-scrollbar {
        width: 6px;
    }
    
    #sidenav-collapse-main::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.05);
        border-radius: 3px;
    }
    
    #sidenav-collapse-main::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.2);
        border-radius: 3px;
    }
    
    #sidenav-collapse-main::-webkit-scrollbar-thumb:hover {
        background: rgba(255, 255, 255, 0.3);
    }
    
    /* Smooth transitions */
    * {
        transition: all 0.3s ease;
    }
    
    /* Improve spacing */
    .modern-nav-link {
        margin-bottom: 0.25rem;
    }
    
    /* Make sidebar full height */
    .modern-sidebar {
        min-height: 100vh;
        display: flex;
        flex-direction: column;
    }
    
    #sidenav-collapse-main {
        flex: 1;
        display: flex !important;
        flex-direction: column;
        overflow-y: auto;
        padding-top: 0 !important;
        visibility: visible !important;
        position: relative !important;
    }
    
    .navbar-nav:first-child {
        padding-top: 1rem !important;
    }
    
    .navbar-collapse {
        display: flex !important;
    }
</style>
