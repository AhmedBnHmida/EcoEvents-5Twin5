<x-app-layout>
    <x-front-navbar />
    
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <nav aria-label="breadcrumb" class="mb-4">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Accueil</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('registrations.my') }}">Mes inscriptions</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('registrations.show', $registration->id) }}">Détails</a></li>
                        <li class="breadcrumb-item active">Paiement</li>
                    </ol>
                </nav>

                <!-- Error Message -->
                @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                <div class="card shadow-xs border">
                    <div class="card-header bg-gradient-primary">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="text-white mb-0">
                                <i class="fas fa-credit-card me-2"></i>Paiement
                            </h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h5>Détails de l'événement</h5>
                                <p><strong>{{ $event->title }}</strong></p>
                                <p class="text-muted">{{ \Illuminate\Support\Str::limit($event->description, 100) }}</p>
                                <p>
                                    <i class="fas fa-calendar me-2"></i>{{ $event->start_date->format('d/m/Y H:i') }}<br>
                                    <i class="fas fa-map-marker-alt me-2"></i>{{ $event->location }}
                                </p>
                            </div>
                            <div class="col-md-6">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h5>Récapitulatif</h5>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Prix de l'inscription:</span>
                                            <span><strong>{{ number_format($event->price, 2) }} €</strong></span>
                                        </div>
                                        <hr>
                                        <div class="d-flex justify-content-between">
                                            <span>Total:</span>
                                            <span><strong>{{ number_format($event->price, 2) }} €</strong></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-center mb-4">
                            <button id="checkout-button" class="btn btn-primary btn-lg">
                                <i class="fas fa-lock me-2"></i>Procéder au paiement
                            </button>
                        </div>

                        <div class="text-center">
                            <p class="text-muted small">
                                <i class="fas fa-shield-alt me-1"></i>Paiement sécurisé par Stripe
                            </p>
                            <div class="mt-2">
                                <img src="https://stripe.com/img/v3/home/social.png" alt="Stripe" style="height: 30px;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://js.stripe.com/v3/"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const stripe = Stripe('{{ $stripe_key }}');
            const checkoutButton = document.getElementById('checkout-button');
            
            checkoutButton.addEventListener('click', function() {
                // Disable the button to prevent multiple clicks
                checkoutButton.disabled = true;
                checkoutButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Traitement en cours...';
                
                // Redirect to Stripe Checkout
                stripe.redirectToCheckout({
                    sessionId: '{{ $checkout_session_id }}'
                }).then(function(result) {
                    if (result.error) {
                        // Display error to customer
                        alert(result.error.message);
                        checkoutButton.disabled = false;
                        checkoutButton.innerHTML = '<i class="fas fa-lock me-2"></i>Procéder au paiement';
                    }
                });
            });
        });
    </script>
</x-app-layout>
