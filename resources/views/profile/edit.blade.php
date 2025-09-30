<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <x-app.navbar />
        <div class="container-fluid py-4 px-5">
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-xs border mb-4">
                        <div class="card-header pb-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="font-weight-semibold text-lg mb-0">
                                        <i class="fas fa-user-edit me-2"></i>Profile Settings
                                    </h6>
                                    <p class="text-sm mb-0">Manage your account information and security</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Update Profile Information -->
                    <div class="card shadow-xs border mb-4 profile-card">
                        <div class="card-header pb-0 bg-gradient-primary">
                            <h6 class="font-weight-semibold mb-0 text-white">
                                <i class="fas fa-user-circle me-2"></i>Informations du Profil
                            </h6>
                            <p class="text-white-50 text-sm mb-0">Mettez à jour vos informations personnelles</p>
                        </div>
                        <div class="card-body p-4">
                            @include('profile.partials.update-profile-information-form')
                        </div>
                    </div>

                    <!-- Update Password -->
                    <div class="card shadow-xs border mb-4 profile-card">
                        <div class="card-header pb-0 bg-gradient-warning">
                            <h6 class="font-weight-semibold mb-0 text-white">
                                <i class="fas fa-key me-2"></i>Modifier le Mot de Passe
                            </h6>
                            <p class="text-white-50 text-sm mb-0">Assurez-vous que votre mot de passe est sécurisé</p>
                        </div>
                        <div class="card-body p-4">
                            @include('profile.partials.update-password-form')
                        </div>
                    </div>

                    <!-- Delete Account -->
                    <div class="card shadow-xs border mb-4 profile-card delete-card">
                        <div class="card-header pb-0 bg-gradient-danger">
                            <h6 class="font-weight-semibold mb-0 text-white">
                                <i class="fas fa-exclamation-triangle me-2"></i>Supprimer le Compte
                            </h6>
                            <p class="text-white-50 text-sm mb-0">Cette action est irréversible</p>
                        </div>
                        <div class="card-body p-4">
                            @include('profile.partials.delete-user-form')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <style>
        /* Profile Page Styling */
        .profile-card {
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
        }

        .profile-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.15) !important;
        }

        .profile-card .card-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.2);
        }

        .profile-card .card-header h6 {
            display: flex;
            align-items: center;
            margin-bottom: 0.25rem;
        }

        .profile-card .card-header i {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        /* Form Styling */
        .profile-card .form-group {
            margin-bottom: 1.5rem;
        }

        .profile-card label {
            font-weight: 600;
            color: #344767;
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
        }

        .profile-card .form-control {
            border: 1px solid #d2d6da;
            border-radius: 0.5rem;
            padding: 0.625rem 0.75rem;
            font-size: 0.875rem;
            transition: all 0.15s ease;
        }

        .profile-card .form-control:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 0.2rem rgba(59,130,246,0.15);
        }

        /* Button Styling */
        .profile-card .btn {
            padding: 0.625rem 1.5rem;
            font-weight: 600;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
            text-transform: none;
        }

        .profile-card .btn-primary {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            border: none;
            box-shadow: 0 4px 15px rgba(59,130,246,0.3);
        }

        .profile-card .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(59,130,246,0.4);
        }

        .profile-card .btn-danger {
            background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);
            border: none;
            box-shadow: 0 4px 15px rgba(220,38,38,0.3);
        }

        .profile-card .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(220,38,38,0.4);
        }

        /* Delete Card Special Styling */
        .delete-card {
            border-left-color: #dc2626;
        }

        .delete-card:hover {
            border-left-color: #991b1b;
        }

        /* Success Messages */
        .alert-success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            border: none;
            border-radius: 0.5rem;
            padding: 1rem 1.25rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 15px rgba(16,185,129,0.3);
        }

        /* Error Messages */
        .text-danger,
        .invalid-feedback {
            color: #dc2626 !important;
            font-size: 0.8rem;
            margin-top: 0.25rem;
        }

        /* Card Header Gradients */
        .bg-gradient-primary {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        }

        .bg-gradient-warning {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        }

        .bg-gradient-danger {
            background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .profile-card .card-header {
                padding: 1rem;
            }

            .profile-card .card-body {
                padding: 1.25rem !important;
            }

            .profile-card .btn {
                width: 100%;
                margin-top: 0.5rem;
            }
        }

        /* Input Focus Animation */
        .profile-card .form-control:focus {
            animation: inputGlow 0.3s ease;
        }

        @keyframes inputGlow {
            0% {
                box-shadow: 0 0 0 0 rgba(59,130,246,0);
            }
            100% {
                box-shadow: 0 0 0 0.2rem rgba(59,130,246,0.15);
            }
        }

        /* Smooth Transitions */
        * {
            transition: all 0.3s ease;
        }

        /* Page Header Card */
        .card.shadow-xs.border.mb-4:first-child {
            background: linear-gradient(135deg, rgba(59,130,246,0.05) 0%, rgba(29,78,216,0.05) 100%);
            border-left: 4px solid #3b82f6;
        }

        /* Helper Text Styling */
        .text-muted,
        .text-sm {
            color: #6b7280 !important;
        }

        /* Card Spacing */
        .profile-card + .profile-card {
            margin-top: 1.5rem;
        }
    </style>
</x-app-layout>