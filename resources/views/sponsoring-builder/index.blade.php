<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Sponsoring Builder') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">
                    <i class="fas fa-robot text-primary me-2"></i>
                    Sponsoring Builder
                </h1>
                <div class="text-muted">
                    <small>Optimisation IA des budgets de sponsoring</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Étape 1: Configuration du Budget -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-calculator me-2"></i>
                        Étape 1: Configuration du Budget
                    </h5>
                </div>
                <div class="card-body">
                    <form id="budgetForm">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="total_budget" class="form-label">
                                        <i class="fas fa-euro-sign me-1"></i>
                                        Budget Total (€)
                                    </label>
                                    <input type="number" 
                                           class="form-control" 
                                           id="total_budget" 
                                           name="total_budget" 
                                           min="1000" 
                                           step="100" 
                                           required
                                           placeholder="Ex: 50000">
                                    <div class="form-text">Montant total disponible pour le sponsoring</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="preferences" class="form-label">
                                        <i class="fas fa-cog me-1"></i>
                                        Préférences
                                    </label>
                                    <textarea class="form-control" 
                                              id="preferences" 
                                              name="preferences" 
                                              rows="3"
                                              placeholder="Ex: Privilégier les partenaires tech, focus sur la visibilité..."></textarea>
                                    <div class="form-text">Instructions spéciales pour l'optimisation</div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">
                                <i class="fas fa-calendar-alt me-1"></i>
                                Événements à Sponsoriser
                            </label>
                            <div class="row">
                                @foreach($events as $event)
                                <div class="col-md-6 col-lg-4 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input event-checkbox" 
                                               type="checkbox" 
                                               value="{{ $event->id }}" 
                                               id="event_{{ $event->id }}"
                                               name="event_ids[]">
                                        <label class="form-check-label" for="event_{{ $event->id }}">
                                            <strong>{{ $event->title }}</strong><br>
                                            <small class="text-muted">
                                                {{ $event->date }} - {{ $event->category->nom ?? 'Général' }}
                                            </small>
                                        </label>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <div class="form-text">Sélectionnez au moins un événement</div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary btn-lg" id="optimizeBtn">
                                <i class="fas fa-magic me-2"></i>
                                Optimiser avec l'IA
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Étape 2: Résultats de l'Optimisation -->
    <div class="row mb-4" id="optimizationResults" style="display: none;">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-line me-2"></i>
                        Étape 2: Optimisation IA
                    </h5>
                </div>
                <div class="card-body">
                    <div id="optimizationContent">
                        <!-- Contenu généré dynamiquement -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Étape 3: Génération des Propositions -->
    <div class="row" id="proposalGeneration" style="display: none;">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-file-contract me-2"></i>
                        Étape 3: Génération des Propositions
                    </h5>
                </div>
                <div class="card-body">
                    <div id="proposalsContent">
                        <!-- Contenu généré dynamiquement -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Loading Modal -->
<div class="modal fade" id="loadingModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center py-4">
                <div class="spinner-border text-primary mb-3" role="status">
                    <span class="visually-hidden">Chargement...</span>
                </div>
                <h5>L'IA travaille...</h5>
                <p class="text-muted mb-0">Optimisation du budget en cours</p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const budgetForm = document.getElementById('budgetForm');
    const optimizeBtn = document.getElementById('optimizeBtn');
    const loadingModal = new bootstrap.Modal(document.getElementById('loadingModal'));

    // Validation du formulaire
    budgetForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const eventCheckboxes = document.querySelectorAll('.event-checkbox:checked');
        if (eventCheckboxes.length === 0) {
            alert('Veuillez sélectionner au moins un événement.');
            return;
        }

        optimizeBudget();
    });

    function optimizeBudget() {
        const formData = new FormData(budgetForm);
        const data = Object.fromEntries(formData.entries());
        data.event_ids = Array.from(document.querySelectorAll('.event-checkbox:checked')).map(cb => parseInt(cb.value));

        loadingModal.show();

        fetch('{{ route("sponsoring-builder.optimize") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(result => {
            loadingModal.hide();
            
            if (result.success) {
                displayOptimizationResults(result.optimization);
            } else {
                alert('Erreur lors de l\'optimisation: ' + (result.message || 'Erreur inconnue'));
            }
        })
        .catch(error => {
            loadingModal.hide();
            console.error('Error:', error);
            alert('Erreur lors de l\'optimisation. Veuillez réessayer.');
        });
    }

    function displayOptimizationResults(optimization) {
        const resultsDiv = document.getElementById('optimizationResults');
        const contentDiv = document.getElementById('optimizationContent');

        let html = `
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card bg-light">
                        <div class="card-body text-center">
                            <h3 class="text-success">${optimization.total_allocated.toLocaleString()}€</h3>
                            <p class="mb-0">Budget Alloué</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-light">
                        <div class="card-body text-center">
                            <h3 class="text-info">${optimization.roi_estimate}</h3>
                            <p class="mb-0">ROI Estimé</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-light">
                        <div class="card-body text-center">
                            <h3 class="text-primary">${optimization.allocations.length}</h3>
                            <p class="mb-0">Partenaires</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <h6>Stratégie Globale:</h6>
                <p class="text-muted">${optimization.strategy_summary}</p>
            </div>

            <h6>Répartition Proposée:</h6>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Partenaire</th>
                            <th>Événement</th>
                            <th>Montant</th>
                            <th>Type</th>
                            <th>Justification</th>
                        </tr>
                    </thead>
                    <tbody>
        `;

        optimization.allocations.forEach(allocation => {
            html += `
                <tr>
                    <td>${allocation.partner_name || 'Partenaire #' + allocation.partner_id}</td>
                    <td>${allocation.event_name || 'Événement #' + allocation.event_id}</td>
                    <td><strong>${allocation.amount.toLocaleString()}€</strong></td>
                    <td><span class="badge bg-secondary">${allocation.type}</span></td>
                    <td>${allocation.reasoning}</td>
                </tr>
            `;
        });

        html += `
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-end">
                <button type="button" class="btn btn-success btn-lg" onclick="generateProposals()">
                    <i class="fas fa-file-contract me-2"></i>
                    Générer les Propositions
                </button>
            </div>
        `;

        contentDiv.innerHTML = html;
        resultsDiv.style.display = 'block';
        resultsDiv.scrollIntoView({ behavior: 'smooth' });

        // Stocker les données pour la génération de propositions
        window.optimizationData = optimization;
    }

    // Fonction globale pour la génération de propositions
    window.generateProposals = function() {
        if (!window.optimizationData) {
            alert('Aucune donnée d\'optimisation disponible.');
            return;
        }

        loadingModal.show();

        fetch('{{ route("sponsoring-builder.generate-proposals") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                allocations: window.optimizationData.allocations
            })
        })
        .then(response => response.json())
        .then(result => {
            loadingModal.hide();
            
            if (result.success) {
                displayProposals(result.proposals);
            } else {
                alert('Erreur lors de la génération: ' + (result.message || 'Erreur inconnue'));
            }
        })
        .catch(error => {
            loadingModal.hide();
            console.error('Error:', error);
            alert('Erreur lors de la génération. Veuillez réessayer.');
        });
    };

    function displayProposals(proposals) {
        const proposalDiv = document.getElementById('proposalGeneration');
        const contentDiv = document.getElementById('proposalsContent');

        let html = `
            <div class="row">
                <div class="col-12 mb-3">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        ${proposals.length} propositions générées avec succès !
                    </div>
                </div>
            </div>
        `;

        proposals.forEach((proposal, index) => {
            html += `
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <i class="fas fa-envelope me-2"></i>
                            Proposition ${index + 1}: ${proposal.partner.nom} → ${proposal.event.title}
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="proposal-content">
                                    <h6>Objet: ${proposal.proposal.subject}</h6>
                                    <hr>
                                    <p><strong>${proposal.proposal.greeting}</strong></p>
                                    <p>${proposal.proposal.introduction}</p>
                                    <p>${proposal.proposal.proposal_details}</p>
                                    <p><strong>Avantages:</strong> ${proposal.proposal.benefits}</p>
                                    <p>${proposal.proposal.call_to_action}</p>
                                    <p>${proposal.proposal.closing}</p>
                                    <p><strong>${proposal.proposal.signature}</strong></p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6>Détails du Sponsoring</h6>
                                        <p><strong>Montant:</strong> ${proposal.allocation.amount.toLocaleString()}€</p>
                                        <p><strong>Type:</strong> ${proposal.allocation.type}</p>
                                        <p><strong>Partenaire:</strong> ${proposal.partner.nom}</p>
                                        <p><strong>Événement:</strong> ${proposal.event.title}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });

        html += `
            <div class="d-flex justify-content-end">
                <button type="button" class="btn btn-primary btn-lg" onclick="exportProposals()">
                    <i class="fas fa-download me-2"></i>
                    Exporter en PDF
                </button>
            </div>
        `;

        contentDiv.innerHTML = html;
        proposalDiv.style.display = 'block';
        proposalDiv.scrollIntoView({ behavior: 'smooth' });

        // Stocker les propositions pour l'export
        window.proposalsData = proposals;
    };

    // Fonction globale pour l'export
    window.exportProposals = function() {
        if (!window.proposalsData) {
            alert('Aucune proposition à exporter.');
            return;
        }

        // Rediriger vers la route d'export
        window.open('{{ route("sponsoring-builder.export") }}', '_blank');
    };
});
</script>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
