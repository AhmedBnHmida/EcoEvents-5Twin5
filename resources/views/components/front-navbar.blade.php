<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm py-3">
    <div class="container">
        <a class="navbar-brand fw-bold text-primary" href="/">
            <i class="fas fa-leaf me-2"></i>EcoEvents
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#frontNavbar" aria-controls="frontNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="frontNavbar">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-center">
                <li class="nav-item">
                    <a class="nav-link" href="/">
                        <i class="fas fa-home me-1"></i>Accueil
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('events.public') }}">
                        <i class="fas fa-calendar-alt me-1"></i>Événements
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#contact">
                        <i class="fas fa-envelope me-1"></i>Contact
                    </a>
                </li>
                @auth
                    @php
                        $user = Auth::user();
                    @endphp
                    <!-- Show Dashboard link for admin/fournisseur/organisateur -->
                    @if(in_array($user->role, ['admin', 'fournisseur', 'organisateur']))
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('dashboard') }}">
                            <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                        </a>
                    </li>
                    @endif
                    
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="avatar avatar-sm bg-gradient-primary d-flex align-items-center justify-content-center rounded-circle me-2" style="width: 32px; height: 32px;">
                                <span class="text-white font-weight-bold small">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                            </div>
                            <span>{{ $user->name }}</span>
                            <span class="badge bg-success ms-2">{{ ucfirst($user->role) }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li>
                                <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                    <i class="fas fa-user-edit me-2"></i>Mon Profil
                                </a>
                            </li>
                            @if($user->isParticipant() || $user->role === 'participant')
                            <li>
                                <a class="dropdown-item" href="{{ route('registrations.my') }}">
                                    <i class="fas fa-ticket-alt me-2"></i>Mes Inscriptions
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('feedback.my') }}">
                                    <i class="fas fa-comments me-2"></i>Mes Avis
                                </a>
                            </li>
                            @endif
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="fas fa-sign-out-alt me-2"></i>Déconnexion
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @else
                <li class="nav-item">
                    <a class="nav-link btn btn-outline-primary px-3 ms-2" href="{{ route('login') }}">
                        <i class="fas fa-sign-in-alt me-1"></i>Connexion
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link btn btn-primary text-white px-3 ms-2" href="{{ route('register') }}">
                        <i class="fas fa-user-plus me-1"></i>Inscription
                    </a>
                </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>
