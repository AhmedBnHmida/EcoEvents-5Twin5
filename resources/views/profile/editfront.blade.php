<x-app-layout>
    <canvas id="fullScreenCanvas" class="fixed-canvas"></canvas>
    <x-front-navbar />
    
    <div class="container py-5 main-content-wrapper">
        <!-- Page Header -->
        <div class="row mb-5">
            <div class="col-12 text-center">
                <span class="badge bg-success-gradient text-uppercase py-2 px-3 mb-3 badge-pill">Mon Profil</span>
                <h1 class="display-5 fw-bold text-bright-white mb-3">
                    <i class="fas fa-user-cog me-3"></i>Paramètres du Compte
                </h1>
                <p class="lead text-muted">
                    Gérez vos informations personnelles et la sécurité de votre compte
                    <span class="badge bg-primary ms-2">3 sections</span>
                </p>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <!-- Breadcrumb -->
                <nav aria-label="breadcrumb" class="mb-4">
                    <ol class="breadcrumb px-3 py-2 rounded-3 section-dark-bg">
                        <li class="breadcrumb-item">
                            <a href="/" class="text-decoration-none text-bright-white">
                                <i class="fas fa-home me-1"></i>Accueil
                            </a>
                        </li>
                        <li class="breadcrumb-item active text-success fw-semibold">Mon Profil</li>
                    </ol>
                </nav>

                <!-- Action Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="font-weight-bold mb-0 text-bright-white">Gestion du Compte</h2>
                    <div class="d-flex gap-2">
                        <a href="{{ route('dashboard') }}" class="btn btn-outline-light">
                            <i class="fas fa-arrow-left me-2"></i>Retour
                        </a>
                    </div>
                </div>

                @if (session('status') === 'profile-updated')
                    <div class="alert alert-success alert-dismissible fade show shadow-lg border-0 mb-4 section-dark-bg" role="alert">
                        <i class="fas fa-check-circle me-2 text-success"></i>
                        <span class="text-bright-white">Profil mis à jour avec succès !</span>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if (session('status') === 'password-updated')
                    <div class="alert alert-success alert-dismissible fade show shadow-lg border-0 mb-4 section-dark-bg" role="alert">
                        <i class="fas fa-check-circle me-2 text-success"></i>
                        <span class="text-bright-white">Mot de passe mis à jour avec succès !</span>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Update Profile Information -->
                <div class="card shadow-hover-3d border-0 mb-4 section-dark-bg">
                    <div class="card-header bg-gradient-success border-0 py-4">
                        <div class="d-flex align-items-center">
                            <div class="icon-container bg-white-20 rounded-circle p-3 me-3">
                                <i class="fas fa-user-circle text-white fa-lg"></i>
                            </div>
                            <div>
                                <h4 class="font-weight-bold mb-1 text-bright-white">
                                    Informations du Profil
                                </h4>
                                <p class="text-success-bright mb-0">Mettez à jour vos informations personnelles et votre adresse email</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col-lg-8">
                                <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
                                    @csrf
                                    @method('patch')

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="name" class="form-label">
                                                    <i class="fas fa-user me-2 text-success"></i>Nom complet
                                                </label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-dark-input border-end-0">
                                                        <i class="fas fa-id-card text-muted"></i>
                                                    </span>
                                                    <input type="text" id="name" name="name" 
                                                           value="{{ old('name', $user->name) }}" 
                                                           class="form-control border-start-0" 
                                                           required autofocus autocomplete="name">
                                                </div>
                                                @error('name')
                                                    <div class="invalid-feedback d-block">
                                                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="email" class="form-label">
                                                    <i class="fas fa-envelope me-2 text-success"></i>Adresse email
                                                </label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-dark-input border-end-0">
                                                        <i class="fas fa-at text-muted"></i>
                                                    </span>
                                                    <input type="email" id="email" name="email" 
                                                           value="{{ old('email', $user->email) }}" 
                                                           class="form-control border-start-0" 
                                                           required autocomplete="email">
                                                </div>
                                                @error('email')
                                                    <div class="invalid-feedback d-block">
                                                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Additional Fields -->
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="first_name" class="form-label">
                                                    <i class="fas fa-signature me-2 text-success"></i>Prénom
                                                </label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-dark-input border-end-0">
                                                        <i class="fas fa-user text-muted"></i>
                                                    </span>
                                                    <input type="text" id="first_name" name="first_name" 
                                                           value="{{ old('first_name', $user->first_name) }}" 
                                                           class="form-control border-start-0" 
                                                           autocomplete="given-name">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="last_name" class="form-label">
                                                    <i class="fas fa-signature me-2 text-success"></i>Nom de famille
                                                </label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-dark-input border-end-0">
                                                        <i class="fas fa-user text-muted"></i>
                                                    </span>
                                                    <input type="text" id="last_name" name="last_name" 
                                                           value="{{ old('last_name', $user->last_name) }}" 
                                                           class="form-control border-start-0" 
                                                           autocomplete="family-name">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label">
                                            <i class="fas fa-shield-alt me-2 text-success"></i>Statut de vérification
                                        </label>
                                        <div class="verification-status">
                                            @if ($user->email_verified_at)
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check-circle me-1"></i>Email vérifié
                                                </span>
                                                <small class="text-muted ms-2">Vérifié le {{ $user->email_verified_at->format('d/m/Y') }}</small>
                                            @else
                                                <span class="badge bg-warning text-dark">
                                                    <i class="fas fa-exclamation-triangle me-1"></i>Email non vérifié
                                                </span>
                                                <small class="text-muted ms-2">Veuillez vérifier votre adresse email</small>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center pt-3 border-top border-light">
                                        <div>
                                            <button type="submit" class="btn btn-success-gradient">
                                                <i class="fas fa-save me-2"></i>Enregistrer les modifications
                                            </button>
                                        </div>
                                        <div class="text-muted small">
                                            <i class="fas fa-info-circle me-1"></i>
                                            Dernière modification: {{ $user->updated_at->diffForHumans() }}
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-lg-4">
                                <div class="profile-sidebar">
                                    <div class="info-card bg-dark-input rounded-3 p-4">
                                        <h6 class="text-success-bright mb-3">
                                            <i class="fas fa-info-circle me-2"></i>Informations importantes
                                        </h6>
                                        <ul class="list-unstyled text-sm text-muted">
                                            <li class="mb-2">
                                                <i class="fas fa-check text-success me-2"></i>
                                                Votre nom sera visible par les autres utilisateurs
                                            </li>
                                            <li class="mb-2">
                                                <i class="fas fa-check text-success me-2"></i>
                                                L'email est utilisé pour la connexion et les notifications
                                            </li>
                                            <li class="mb-2">
                                                <i class="fas fa-check text-success me-2"></i>
                                                La vérification email est requise pour certaines fonctionnalités
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Update Password -->
                <div class="card shadow-hover-3d border-0 mb-4 section-dark-bg">
                    <div class="card-header bg-gradient-warning border-0 py-4">
                        <div class="d-flex align-items-center">
                            <div class="icon-container bg-white-20 rounded-circle p-3 me-3">
                                <i class="fas fa-key text-white fa-lg"></i>
                            </div>
                            <div>
                                <h4 class="font-weight-bold mb-1 text-bright-white">
                                    Modifier le Mot de Passe
                                </h4>
                                <p class="text-warning mb-0">Assurez-vous que votre compte utilise un mot de passe long et aléatoire</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col-lg-8">
                                <form method="post" action="{{ route('password.update') }}" class="space-y-6">
                                    @csrf
                                    @method('put')

                                    <div class="form-group">
                                        <label for="current_password" class="form-label">
                                            <i class="fas fa-lock me-2 text-warning"></i>Mot de passe actuel
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-dark-input border-end-0">
                                                <i class="fas fa-key text-muted"></i>
                                            </span>
                                            <input type="password" id="current_password" name="current_password" 
                                                   class="form-control border-start-0 password-toggle" 
                                                   autocomplete="current-password">
                                            <button type="button" class="btn btn-outline-light border-start-0 toggle-password">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                        @error('current_password')
                                            <div class="invalid-feedback d-block">
                                                <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="password" class="form-label">
                                                    <i class="fas fa-lock me-2 text-warning"></i>Nouveau mot de passe
                                                </label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-dark-input border-end-0">
                                                        <i class="fas fa-key text-muted"></i>
                                                    </span>
                                                    <input type="password" id="password" name="password" 
                                                           class="form-control border-start-0 password-toggle" 
                                                           autocomplete="new-password">
                                                    <button type="button" class="btn btn-outline-light border-start-0 toggle-password">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                </div>
                                                @error('password')
                                                    <div class="invalid-feedback d-block">
                                                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="password_confirmation" class="form-label">
                                                    <i class="fas fa-lock me-2 text-warning"></i>Confirmer le mot de passe
                                                </label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-dark-input border-end-0">
                                                        <i class="fas fa-key text-muted"></i>
                                                    </span>
                                                    <input type="password" id="password_confirmation" name="password_confirmation" 
                                                           class="form-control border-start-0 password-toggle" 
                                                           autocomplete="new-password">
                                                    <button type="button" class="btn btn-outline-light border-start-0 toggle-password">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Password Strength Indicator -->
                                    <div class="form-group">
                                        <label class="form-label">Sécurité du mot de passe</label>
                                        <div class="password-strength">
                                            <div class="strength-bar">
                                                <div class="strength-progress" id="password-strength-bar"></div>
                                            </div>
                                            <div class="strength-text text-sm mt-1" id="password-strength-text"></div>
                                        </div>
                                    </div>

                                    <!-- Password Requirements -->
                                    <div class="form-group">
                                        <label class="form-label">Exigences de sécurité</label>
                                        <div class="requirements-list">
                                            <div class="requirement-item" data-requirement="length">
                                                <i class="fas fa-times text-danger me-2"></i>
                                                <span>Au moins 8 caractères</span>
                                            </div>
                                            <div class="requirement-item" data-requirement="uppercase">
                                                <i class="fas fa-times text-danger me-2"></i>
                                                <span>Une lettre majuscule</span>
                                            </div>
                                            <div class="requirement-item" data-requirement="lowercase">
                                                <i class="fas fa-times text-danger me-2"></i>
                                                <span>Une lettre minuscule</span>
                                            </div>
                                            <div class="requirement-item" data-requirement="number">
                                                <i class="fas fa-times text-danger me-2"></i>
                                                <span>Un chiffre</span>
                                            </div>
                                            <div class="requirement-item" data-requirement="special">
                                                <i class="fas fa-times text-danger me-2"></i>
                                                <span>Un caractère spécial</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center pt-3 border-top border-light">
                                        <button type="submit" class="btn btn-warning text-dark">
                                            <i class="fas fa-key me-2"></i>Mettre à jour le mot de passe
                                        </button>
                                        <div class="text-muted small">
                                            <i class="fas fa-shield-alt me-1"></i>
                                            Sécurité renforcée
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-lg-4">
                                <div class="security-sidebar">
                                    <div class="info-card bg-dark-input rounded-3 p-4">
                                        <h6 class="text-warning mb-3">
                                            <i class="fas fa-shield-alt me-2"></i>Conseils de sécurité
                                        </h6>
                                        <ul class="list-unstyled text-sm text-muted">
                                            <li class="mb-2">
                                                <i class="fas fa-check text-warning me-2"></i>
                                                Utilisez au moins 12 caractères
                                            </li>
                                            <li class="mb-2">
                                                <i class="fas fa-check text-warning me-2"></i>
                                                Mélangez lettres, chiffres et symboles
                                            </li>
                                            <li class="mb-2">
                                                <i class="fas fa-check text-warning me-2"></i>
                                                Évitez les mots du dictionnaire
                                            </li>
                                            <li class="mb-2">
                                                <i class="fas fa-check text-warning me-2"></i>
                                                Ne réutilisez pas d'anciens mots de passe
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Delete Account -->
                <div class="card shadow-hover-3d border-0 mb-4 section-dark-bg delete-card">
                    <div class="card-header bg-gradient-danger border-0 py-4">
                        <div class="d-flex align-items-center">
                            <div class="icon-container bg-white-20 rounded-circle p-3 me-3">
                                <i class="fas fa-exclamation-triangle text-white fa-lg"></i>
                            </div>
                            <div>
                                <h4 class="font-weight-bold mb-1 text-bright-white">
                                    Supprimer le Compte
                                </h4>
                                <p class="text-danger mb-0">Supprimez définitivement votre compte et toutes vos données</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col-lg-8">
                                <div class="delete-warning bg-dark-input rounded-3 p-4 mb-4">
                                    <div class="d-flex align-items-start">
                                        <i class="fas fa-exclamation-triangle text-danger fa-lg me-3 mt-1"></i>
                                        <div>
                                            <h6 class="text-danger mb-2">Action irréversible</h6>
                                            <p class="text-muted mb-0">
                                                Une fois votre compte supprimé, toutes vos données seront effacées définitivement. 
                                                Cette action ne peut pas être annulée.
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <form method="post" action="{{ route('profile.destroy') }}" id="deleteAccountForm">
                                    @csrf
                                    @method('delete')

                                    <div class="form-group">
                                        <label for="delete_password" class="form-label text-danger">
                                            <i class="fas fa-lock me-2"></i>Confirmez votre mot de passe
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-dark-input border-end-0">
                                                <i class="fas fa-key text-muted"></i>
                                            </span>
                                            <input type="password" id="delete_password" name="password" 
                                                   class="form-control border-start-0" 
                                                   placeholder="Entrez votre mot de passe pour confirmer">
                                        </div>
                                        @error('password')
                                            <div class="invalid-feedback d-block">
                                                <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="confirmDelete" required>
                                            <label class="form-check-label text-muted" for="confirmDelete">
                                                Je comprends que cette action est irréversible et que toutes mes données seront perdues.
                                            </label>
                                        </div>
                                    </div>

                                    <div class="pt-3 border-top border-light">
                                        <button type="button" class="btn btn-danger-gradient" id="deleteAccountBtn" disabled>
                                            <i class="fas fa-trash me-2"></i>Supprimer définitivement mon compte
                                        </button>
                                    </div>
                                </form>
                            </div>
                            <div class="col-lg-4">
                                <div class="delete-sidebar">
                                    <div class="info-card bg-dark-input rounded-3 p-4">
                                        <h6 class="text-danger mb-3">
                                            <i class="fas fa-info-circle me-2"></i>Ce qui sera supprimé
                                        </h6>
                                        <ul class="list-unstyled text-sm text-muted">
                                            <li class="mb-2">
                                                <i class="fas fa-times text-danger me-2"></i>
                                                Votre profil utilisateur
                                            </li>
                                            <li class="mb-2">
                                                <i class="fas fa-times text-danger me-2"></i>
                                                Toutes vos inscriptions
                                            </li>
                                            <li class="mb-2">
                                                <i class="fas fa-times text-danger me-2"></i>
                                                Vos avis et commentaires
                                            </li>
                                            <li class="mb-2">
                                                <i class="fas fa-times text-danger me-2"></i>
                                                Votre historique d'activité
                                            </li>
                                            <li class="mb-2">
                                                <i class="fas fa-times text-danger me-2"></i>
                                                Toutes vos données personnelles
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Previous CSS remains the same, adding new enhancements */
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

        /* Section Background */
        .section-dark-bg {
            background-color: var(--color-section-dark) !important;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            border: 1px solid var(--color-border-light);
            backdrop-filter: blur(10px);
        }

        /* Card Enhancements */
        .shadow-hover-3d {
            transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        }

        .shadow-hover-3d:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 150, 0, 0.2), 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        /* Card Header Styles */
        .card-header.bg-gradient-success {
            background: linear-gradient(135deg, var(--color-success-dark) 0%, #43a047 100%) !important;
            border-radius: 16px 16px 0 0 !important;
        }

        .card-header.bg-gradient-warning {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%) !important;
            border-radius: 16px 16px 0 0 !important;
        }

        .card-header.bg-gradient-danger {
            background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%) !important;
            border-radius: 16px 16px 0 0 !important;
        }

        .icon-container {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
        }

        /* Form Styling */
        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            font-weight: 600;
            color: var(--color-success-bright);
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }

        .form-control {
            background-color: var(--color-dark-input) !important;
            border: 1px solid var(--color-border-light);
            border-radius: 12px;
            padding: 0.75rem 1rem;
            color: var(--color-success-bright);
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            background-color: var(--color-section-dark) !important;
            border-color: var(--color-success-dark);
            box-shadow: 0 0 0 0.2rem rgba(76, 175, 80, 0.25);
            color: var(--color-success-bright);
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.4);
        }

        /* Button Gradients */
        .btn-success-gradient {
            background: linear-gradient(135deg, #66bb6a 0%, #43a047 100%);
            border: none;
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
            border-radius: 12px;
            padding: 0.75rem 1.5rem;
        }

        .btn-success-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(76, 175, 80, 0.4);
            color: white;
        }

        .btn-danger-gradient {
            background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);
            border: none;
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
            border-radius: 12px;
            padding: 0.75rem 1.5rem;
        }

        .btn-danger-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(220, 38, 38, 0.4);
            color: white;
        }

        .btn-outline-light {
            border: 2px solid var(--color-border-light);
            color: var(--color-success-bright);
            background: transparent;
            border-radius: 12px;
            padding: 0.75rem 1.5rem;
            transition: all 0.3s ease;
        }

        .btn-outline-light:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: var(--color-success-bright);
            transform: translateY(-2px);
        }

        /* Alert Styling */
        .alert {
            border: none;
            border-radius: 12px;
            backdrop-filter: blur(10px);
        }

        .alert-success {
            background: linear-gradient(135deg, rgba(76, 175, 80, 0.2) 0%, rgba(76, 175, 80, 0.1) 100%);
            border-left: 4px solid var(--color-success-dark);
        }

        /* Delete Card Special Styling */
        .delete-card {
            border: 1px solid rgba(220, 38, 38, 0.3);
        }

        .delete-card:hover {
            border-color: rgba(220, 38, 38, 0.5);
            box-shadow: 0 20px 40px rgba(220, 38, 38, 0.2), 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        /* Badge Enhancements */
        .badge-pill {
            border-radius: 50rem;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .bg-success-gradient {
            background: linear-gradient(135deg, #66bb6a 0%, #43a047 100%) !important;
        }

        /* Text Colors */
        .text-success-bright { 
            color: var(--color-success-bright) !important; 
        }

        .text-warning {
            color: #fbbf24 !important;
        }

        .text-danger {
            color: #ef4444 !important;
        }

        /* Error Messages */
        .invalid-feedback {
            color: #ef4444 !important;
            font-size: 0.8rem;
            margin-top: 0.25rem;
            background: rgba(239, 68, 68, 0.1);
            padding: 0.5rem;
            border-radius: 8px;
            border-left: 3px solid #ef4444;
        }

        .form-control.is-invalid {
            border-color: #ef4444;
            box-shadow: 0 0 0 0.2rem rgba(239, 68, 68, 0.25);
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

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .main-content-wrapper {
                margin-top: 80px;
            }
            
            .d-flex.justify-content-between.align-items-center {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }
            
            .card-body {
                padding: 1.5rem !important;
            }
            
            .card-header .d-flex {
                flex-direction: column;
                text-align: center;
                gap: 1rem;
            }
            
            .icon-container {
                align-self: center;
            }
            
            .btn {
                width: 100%;
                margin-top: 0.5rem;
            }
        }

        /* Animation Enhancements */
        .card {
            animation: fadeInUp 0.6s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Input Focus Animation */
        .form-control:focus {
            animation: inputGlow 0.3s ease;
        }

        @keyframes inputGlow {
            0% {
                box-shadow: 0 0 0 0 rgba(76, 175, 80, 0);
            }
            100% {
                box-shadow: 0 0 0 0.2rem rgba(76, 175, 80, 0.25);
            }
        }

        /* Breadcrumb Styling */
        .breadcrumb {
            background: var(--color-section-dark) !important;
            border: 1px solid var(--color-border-light);
        }

        .breadcrumb-item.active {
            color: var(--color-success-bright) !important;
        }

        /* Page Header Enhancement */
        .display-5 {
            background: linear-gradient(135deg, var(--color-success-bright) 0%, var(--color-info-bright) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Enhanced Form Styles */
        .input-group-text {
            background-color: var(--color-dark-input) !important;
            border: 1px solid var(--color-border-light);
            color: var(--color-success-bright) !important;
        }

        .input-group .form-control {
            border-left: none;
        }

        .input-group .form-control:focus {
            border-color: var(--color-success-dark);
            box-shadow: none;
        }

        /* Password Strength Indicator */
        .password-strength {
            margin-top: 0.5rem;
        }

        .strength-bar {
            width: 100%;
            height: 6px;
            background: var(--color-dark-input);
            border-radius: 3px;
            overflow: hidden;
        }

        .strength-progress {
            height: 100%;
            width: 0%;
            border-radius: 3px;
            transition: all 0.3s ease;
        }

        .strength-text {
            font-size: 0.8rem;
            font-weight: 600;
        }

        /* Requirements List */
        .requirements-list {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .requirement-item {
            display: flex;
            align-items: center;
            font-size: 0.8rem;
            transition: all 0.3s ease;
        }

        .requirement-item.valid {
            color: var(--color-success-bright);
        }

        .requirement-item.valid i {
            color: var(--color-success-dark) !important;
        }

        /* Verification Status */
        .verification-status {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        /* Profile Sidebar */
        .profile-sidebar,
        .security-sidebar,
        .delete-sidebar {
            position: sticky;
            top: 100px;
        }

        .info-card {
            border-left: 3px solid var(--color-success-dark);
        }

        .info-card h6 {
            font-weight: 600;
        }

        /* Delete Warning */
        .delete-warning {
            border-left: 3px solid #dc2626;
        }

        /* Form Check */
        .form-check-input {
            background-color: var(--color-dark-input);
            border: 1px solid var(--color-border-light);
        }

        .form-check-input:checked {
            background-color: var(--color-success-dark);
            border-color: var(--color-success-dark);
        }

        /* Toggle Password Button */
        .toggle-password {
            border: 1px solid var(--color-border-light);
            border-left: none;
            color: var(--color-success-bright);
        }

        .toggle-password:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        /* Responsive Enhancements */
        @media (max-width: 991px) {
            .profile-sidebar,
            .security-sidebar,
            .delete-sidebar {
                position: static;
                margin-top: 2rem;
            }
        }

        /* Animation for requirement items */
        @keyframes requirementValid {
            0% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
            100% {
                transform: scale(1);
            }
        }

        .requirement-item.valid {
            animation: requirementValid 0.3s ease;
        }

        /* Password strength colors */
        .strength-weak { background: #dc2626; }
        .strength-fair { background: #f59e0b; }
        .strength-good { background: #3b82f6; }
        .strength-strong { background: #10b981; }
        .strength-very-strong { background: #059669; }

        /* Button states */
        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Particle Background (keep existing code)
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

            // Password visibility toggle
            document.querySelectorAll('.toggle-password').forEach(button => {
                button.addEventListener('click', function() {
                    const input = this.closest('.input-group').querySelector('.password-toggle');
                    const icon = this.querySelector('i');
                    
                    if (input.type === 'password') {
                        input.type = 'text';
                        icon.className = 'fas fa-eye-slash';
                    } else {
                        input.type = 'password';
                        icon.className = 'fas fa-eye';
                    }
                });
            });

            // Password strength indicator
            const passwordInput = document.getElementById('password');
            const strengthBar = document.getElementById('password-strength-bar');
            const strengthText = document.getElementById('password-strength-text');
            const requirementItems = document.querySelectorAll('.requirement-item');

            if (passwordInput) {
                passwordInput.addEventListener('input', function() {
                    const password = this.value;
                    const strength = calculatePasswordStrength(password);
                    
                    updateStrengthIndicator(strength);
                    updateRequirements(password);
                });
            }

            function calculatePasswordStrength(password) {
                let strength = 0;
                
                // Length check
                if (password.length >= 8) strength += 1;
                if (password.length >= 12) strength += 1;
                
                // Character variety checks
                if (/[A-Z]/.test(password)) strength += 1;
                if (/[a-z]/.test(password)) strength += 1;
                if (/[0-9]/.test(password)) strength += 1;
                if (/[^A-Za-z0-9]/.test(password)) strength += 1;
                
                return Math.min(strength, 5);
            }

            function updateStrengthIndicator(strength) {
                const percentages = [0, 20, 40, 60, 80, 100];
                const colors = [
                    'strength-weak',
                    'strength-weak',
                    'strength-fair',
                    'strength-good',
                    'strength-strong',
                    'strength-very-strong'
                ];
                const texts = [
                    'Très faible',
                    'Faible',
                    'Moyen',
                    'Bon',
                    'Fort',
                    'Très fort'
                ];

                strengthBar.style.width = percentages[strength] + '%';
                strengthBar.className = 'strength-progress ' + colors[strength];
                strengthText.textContent = texts[strength];
                strengthText.className = 'strength-text text-' + colors[strength].replace('strength-', '');
            }

            function updateRequirements(password) {
                const requirements = {
                    length: password.length >= 8,
                    uppercase: /[A-Z]/.test(password),
                    lowercase: /[a-z]/.test(password),
                    number: /[0-9]/.test(password),
                    special: /[^A-Za-z0-9]/.test(password)
                };

                requirementItems.forEach(item => {
                    const requirement = item.getAttribute('data-requirement');
                    const icon = item.querySelector('i');
                    const isValid = requirements[requirement];
                    
                    if (isValid) {
                        item.classList.add('valid');
                        icon.className = 'fas fa-check text-success me-2';
                    } else {
                        item.classList.remove('valid');
                        icon.className = 'fas fa-times text-danger me-2';
                    }
                });
            }

            // Delete account confirmation
            const confirmCheckbox = document.getElementById('confirmDelete');
            const deletePassword = document.getElementById('delete_password');
            const deleteButton = document.getElementById('deleteAccountBtn');
            const deleteForm = document.getElementById('deleteAccountForm');

            function updateDeleteButton() {
                const isChecked = confirmCheckbox.checked;
                const hasPassword = deletePassword.value.length > 0;
                deleteButton.disabled = !(isChecked && hasPassword);
            }

            confirmCheckbox.addEventListener('change', updateDeleteButton);
            deletePassword.addEventListener('input', updateDeleteButton);

            deleteButton.addEventListener('click', function(e) {
                e.preventDefault();
                
                if (!confirm('Êtes-vous ABSOLUMENT SÛR de vouloir supprimer votre compte ? Cette action est IRREVERSIBLE et toutes vos données seront PERDUES DEFINITIVEMENT.')) {
                    return;
                }
                
                deleteForm.submit();
            });

            // Real-time validation for profile form
            const profileForm = document.querySelector('form[action*="profile.update"]');
            if (profileForm) {
                const inputs = profileForm.querySelectorAll('input[required]');
                
                inputs.forEach(input => {
                    input.addEventListener('blur', function() {
                        if (!this.value.trim()) {
                            this.classList.add('is-invalid');
                        } else {
                            this.classList.remove('is-invalid');
                        }
                    });
                    
                    input.addEventListener('input', function() {
                        if (this.value.trim()) {
                            this.classList.remove('is-invalid');
                        }
                    });
                });
            }
        });
    </script>
</x-app-layout>