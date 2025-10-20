<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Résultats de l\'Optimisation') }}
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
                                        <i class="fas fa-chart-line text-success me-2"></i>
                                        Résultats de l'Optimisation
                                    </h1>
                                    <div class="text-muted">
                                        <small>Budget total: {{ number_format($totalBudget, 0, ',', ' ') }}€</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($optimizationResults)
                            <!-- Résumé des KPIs -->
                            <div class="row mb-4">
                                <div class="col-md-3">
                                    <div class="card bg-light">
                                        <div class="card-body text-center">
                                            <h3 class="text-success">{{ number_format($optimizationResults['total_allocated'] ?? 0, 0, ',', ' ') }}€</h3>
                                            <p class="mb-0">Budget Alloué</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card bg-light">
                                        <div class="card-body text-center">
                                            <h3 class="text-info">{{ $optimizationResults['roi_estimate'] ?? 'N/A' }}</h3>
                                            <p class="mb-0">ROI Estimé</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card bg-light">
                                        <div class="card-body text-center">
                                            <h3 class="text-primary">{{ count($optimizationResults['allocations'] ?? []) }}</h3>
                                            <p class="mb-0">Partenaires</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card bg-light">
                                        <div class="card-body text-center">
                                            <h3 class="text-warning">{{ number_format(($totalBudget - ($optimizationResults['total_allocated'] ?? 0)), 0, ',', ' ') }}€</h3>
                                            <p class="mb-0">Budget Restant</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Stratégie Globale -->
                            @if(isset($optimizationResults['strategy_summary']))
                            <div class="row mb-4">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="mb-0">
                                                <i class="fas fa-lightbulb me-2"></i>
                                                Stratégie Globale
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <p class="text-muted">{{ $optimizationResults['strategy_summary'] }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <!-- Répartition Proposée -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="mb-0">
                                                <i class="fas fa-list me-2"></i>
                                                Répartition Proposée
                                            </h5>
                                        </div>
                                        <div class="card-body">
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
                                                        @foreach($optimizationResults['allocations'] ?? [] as $allocation)
                                                        @php
                                                            $partner = \App\Models\Partner::find($allocation['partner_id'] ?? null);
                                                            $event = \App\Models\Event::find($allocation['event_id'] ?? null);
                                                        @endphp
                                                        <tr>
                                                            <td>{{ $partner->nom ?? 'Partenaire #' . ($allocation['partner_id'] ?? 'N/A') }}</td>
                                                            <td>{{ $event->title ?? 'Événement #' . ($allocation['event_id'] ?? 'N/A') }}</td>
                                                            <td><strong>{{ number_format($allocation['amount'] ?? 0, 0, ',', ' ') }}€</strong></td>
                                                            <td><span class="badge bg-secondary">{{ $allocation['type'] ?? 'N/A' }}</span></td>
                                                            <td>{{ $allocation['reasoning'] ?? 'N/A' }}</td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="d-flex justify-content-between">
                                        <a href="{{ route('sponsoring-builder.index') }}" class="btn btn-secondary">
                                            <i class="fas fa-arrow-left me-2"></i>
                                            Retour à la Configuration
                                        </a>
                                        
                                        <form method="POST" action="{{ route('sponsoring-builder.generate-proposals') }}" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="allocations" value="{{ json_encode($optimizationResults['allocations'] ?? []) }}">
                                            <button type="submit" class="btn btn-success btn-lg">
                                                <i class="fas fa-file-contract me-2"></i>
                                                Générer les Propositions
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="row">
                                <div class="col-12">
                                    <div class="alert alert-warning text-center">
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        Aucun résultat d'optimisation trouvé. Veuillez d'abord optimiser un budget.
                                    </div>
                                    <div class="text-center">
                                        <a href="{{ route('sponsoring-builder.index') }}" class="btn btn-primary">
                                            <i class="fas fa-arrow-left me-2"></i>
                                            Retour à la Configuration
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
