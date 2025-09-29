<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 bg-slate-900 fixed-start min-vh-100" id="sidenav-main">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
            aria-hidden="true" id="iconSidenav"></i>
        <a class="navbar-brand d-flex align-items-center m-0"
            href="#" target="_blank">
            <span class="font-weight-bold text-lg">EcoEvents</span>
        </a>
    </div>
    <div class="collapse navbar-collapse px-4 w-auto" id="sidenav-collapse-main">
        @php $user = Auth::user(); @endphp
        <ul class="navbar-nav">
            <li class="nav-item mb-1">
                <a class="nav-link d-flex align-items-center {{ is_current_route('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <i class="fas fa-tachometer-alt me-2 text-success"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            @if($user && $user->role === 'admin')
                <li class="nav-item mb-1">
                    <a class="nav-link d-flex align-items-center {{ is_current_route('users.index') ? 'active' : '' }}" href="{{ route('users.index') }}">
                        <i class="fas fa-users-cog me-2 text-info"></i>
                        <span>Users</span>
                    </a>
                </li>
                <li class="nav-item mb-1">
                    <a class="nav-link d-flex align-items-center {{ is_current_route('events.index') ? 'active' : '' }}" href="{{ route('events.index') }}">
                        <i class="fas fa-calendar-check me-2 text-success"></i>
                        <span>Events</span>
                    </a>
                </li>
                <li class="nav-item mb-1">
                    <a class="nav-link d-flex align-items-center {{ is_current_route('categories.index') ? 'active' : '' }}" href="{{ route('categories.index') }}">
                        <i class="fas fa-folder-open me-2 text-warning"></i>
                        <span>Categories</span>
                    </a>
                </li>
                <li class="nav-item mb-1">
                    <a class="nav-link d-flex align-items-center {{ is_current_route('feedback.index') ? 'active' : '' }}" href="{{ route('feedback.index') }}">
                        <i class="fas fa-comments me-2 text-primary"></i>
                        <span>Feedback</span>
                    </a>
                </li>
                <li class="nav-item mb-1">
                    <a class="nav-link d-flex align-items-center {{ is_current_route('evaluations.index') ? 'active' : '' }}" href="{{ route('evaluations.index') }}">
                        <i class="fas fa-star me-2 text-warning"></i>
                        <span>Evaluations</span>
                    </a>
                </li>
                <li class="nav-item mb-1">
                    <a class="nav-link d-flex align-items-center {{ is_current_route('ressources.index') ? 'active' : '' }}" href="{{ route('ressources.index') }}">
                        <i class="fas fa-box-open me-2 text-secondary"></i>
                        <span>Ressources</span>
                    </a>
                </li>
                <li class="nav-item mb-1">
                    <a class="nav-link d-flex align-items-center {{ is_current_route('fournisseurs.index') ? 'active' : '' }}" href="{{ route('fournisseurs.index') }}">
                        <i class="fas fa-truck-loading me-2 text-info"></i>
                        <span>Fournisseurs</span>
                    </a>
                </li>
                <li class="nav-item mb-1">
                    <a class="nav-link d-flex align-items-center {{ is_current_route('inscriptions.index') ? 'active' : '' }}" href="{{ route('inscriptions.index') }}">
                        <i class="fas fa-user-check me-2 text-success"></i>
                        <span>Inscriptions</span>
                    </a>
                </li>
                <li class="nav-item mb-1">
                    <a class="nav-link d-flex align-items-center {{ is_current_route('partenaires.index') ? 'active' : '' }}" href="{{ route('partenaires.index') }}">
                        <i class="fas fa-handshake me-2 text-primary"></i>
                        <span>Partenaires</span>
                    </a>
                </li>
                <li class="nav-item mb-1">
                    <a class="nav-link d-flex align-items-center {{ is_current_route('sponsoring.index') ? 'active' : '' }}" href="{{ route('sponsoring.index') }}">
                        <i class="fas fa-donate me-2 text-danger"></i>
                        <span>Sponsoring</span>
                    </a>
                </li>
            @elseif($user && $user->role === 'fournisseur')
                <li class="nav-item mb-1">
                    <a class="nav-link d-flex align-items-center {{ is_current_route('ressources.index') ? 'active' : '' }}" href="{{ route('ressources.index') }}">
                        <i class="fas fa-box-open me-2 text-secondary"></i>
                        <span>Ressources</span>
                    </a>
                </li>
            @elseif($user && $user->role === 'organisateur')
                <li class="nav-item mb-1">
                    <a class="nav-link d-flex align-items-center {{ is_current_route('events.index') ? 'active' : '' }}" href="{{ route('events.index') }}">
                        <i class="fas fa-calendar-check me-2 text-success"></i>
                        <span>Events</span>
                    </a>
                </li>
                <li class="nav-item mb-1">
                    <a class="nav-link d-flex align-items-center {{ is_current_route('ressources.index') ? 'active' : '' }}" href="{{ route('ressources.index') }}">
                        <i class="fas fa-box-open me-2 text-secondary"></i>
                        <span>Ressources</span>
                    </a>
                </li>
            @endif
        </ul>
        <ul class="navbar-nav mt-4">
            @auth
            <li class="nav-item">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-danger w-100">
                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                    </button>
                </form>
            </li>
            @endauth
        </ul>
    </div>
</aside>
