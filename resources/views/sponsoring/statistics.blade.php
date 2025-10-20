<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <x-app.navbar />
        <div class="container-fluid py-4 px-5">
            <!-- Header -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="font-weight-bolder mb-0">
                                <i class="fas fa-chart-line me-2 text-primary"></i>Statistiques des Sponsorings
                            </h3>
                            <p class="mb-0 text-sm">Analyse complète des partenariats et sponsorings</p>
                        </div>
                        <div>
                            <a href="{{ route('sponsoring.statistics.pdf') }}" class="btn btn-danger btn-sm me-2" target="_blank">
                                <i class="fas fa-file-pdf me-2"></i>Export PDF
                            </a>
                            <a href="{{ route('sponsoring.index') }}" class="btn btn-white btn-sm">
                                <i class="fas fa-arrow-left me-2"></i>Retour à la liste
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- KPI Cards -->
            <div class="row mb-4">
                <!-- Total Sponsorings -->
                <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                    <div class="card shadow-xs border">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-8">
                                    <div class="numbers">
                                        <p class="text-sm mb-0 text-capitalize font-weight-bold opacity-7">Total Sponsorings</p>
                                        <h5 class="font-weight-bolder mb-0">
                                            {{ $stats['total_sponsorings'] }}
                                        </h5>
                                    </div>
                                </div>
                                <div class="col-4 text-end">
                                    <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                        <i class="fas fa-handshake text-lg opacity-10" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Montant -->
                <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                    <div class="card shadow-xs border">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-8">
                                    <div class="numbers">
                                        <p class="text-sm mb-0 text-capitalize font-weight-bold opacity-7">Montant Total</p>
                                        <h5 class="font-weight-bolder mb-0 text-success">
                                            {{ number_format($stats['total_montant'], 2) }} €
                                        </h5>
                                    </div>
                                </div>
                                <div class="col-4 text-end">
                                    <div class="icon icon-shape bg-gradient-success shadow text-center border-radius-md">
                                        <i class="fas fa-euro-sign text-lg opacity-10" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Average Montant -->
                <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                    <div class="card shadow-xs border">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-8">
                                    <div class="numbers">
                                        <p class="text-sm mb-0 text-capitalize font-weight-bold opacity-7">Montant Moyen</p>
                                        <h5 class="font-weight-bolder mb-0 text-info">
                                            {{ number_format($stats['average_montant'], 2) }} €
                                        </h5>
                                    </div>
                                </div>
                                <div class="col-4 text-end">
                                    <div class="icon icon-shape bg-gradient-info shadow text-center border-radius-md">
                                        <i class="fas fa-chart-bar text-lg opacity-10" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Partenaires Actifs -->
                <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                    <div class="card shadow-xs border">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-8">
                                    <div class="numbers">
                                        <p class="text-sm mb-0 text-capitalize font-weight-bold opacity-7">Partenaires Actifs</p>
                                        <h5 class="font-weight-bolder mb-0 text-warning">
                                            {{ $stats['top_partners']->count() }}
                                        </h5>
                                    </div>
                                </div>
                                <div class="col-4 text-end">
                                    <div class="icon icon-shape bg-gradient-warning shadow text-center border-radius-md">
                                        <i class="fas fa-users text-lg opacity-10" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <!-- Répartition par Type -->
                <div class="col-lg-6 mb-4">
                    <div class="card shadow-xs border h-100">
                        <div class="card-header pb-0">
                            <h6 class="font-weight-semibold mb-0">
                                <i class="fas fa-pie-chart me-2 text-primary"></i>Répartition par Type
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Type</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Nombre</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Montant Total</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">%</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($stats['by_type'] as $type)
                                        <tr>
                                            <td>
                                                <span class="badge badge-sm bg-gradient-info">
                                                    {{ \App\TypeSponsoring::from($type->type_sponsoring)->label() }}
                                                </span>
                                            </td>
                                            <td>
                                                <p class="text-sm font-weight-bold mb-0">{{ $type->count }}</p>
                                            </td>
                                            <td>
                                                <p class="text-sm text-success font-weight-bold mb-0">
                                                    {{ number_format($type->total, 2) }} €
                                                </p>
                                            </td>
                                            <td>
                                                <p class="text-sm font-weight-bold mb-0">
                                                    {{ $stats['total_montant'] > 0 ? number_format(($type->total / $stats['total_montant']) * 100, 1) : 0 }}%
                                                </p>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Évolution Mensuelle -->
                <div class="col-lg-6 mb-4">
                    <div class="card shadow-xs border h-100">
                        <div class="card-header pb-0">
                            <h6 class="font-weight-semibold mb-0">
                                <i class="fas fa-chart-line me-2 text-success"></i>Évolution Mensuelle (6 derniers mois)
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Mois</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Nombre</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Montant</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($stats['monthly_trend'] as $month)
                                        <tr>
                                            <td>
                                                <p class="text-sm font-weight-bold mb-0">
                                                    {{ \Carbon\Carbon::createFromFormat('Y-m', $month->month)->format('M Y') }}
                                                </p>
                                            </td>
                                            <td>
                                                <span class="badge badge-sm bg-gradient-primary">{{ $month->count }}</span>
                                            </td>
                                            <td>
                                                <p class="text-sm text-success font-weight-bold mb-0">
                                                    {{ number_format($month->total, 2) }} €
                                                </p>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="3" class="text-center">
                                                <p class="text-sm text-muted mb-0">Aucune donnée disponible</p>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <!-- Top 5 Partenaires -->
                <div class="col-lg-6 mb-4">
                    <div class="card shadow-xs border h-100">
                        <div class="card-header pb-0">
                            <h6 class="font-weight-semibold mb-0">
                                <i class="fas fa-trophy me-2 text-warning"></i>Top 5 Partenaires
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Partenaire</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Sponsorings</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Montant Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($stats['top_partners'] as $partner)
                                        <tr>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm">
                                                            <a href="{{ route('partenaires.show', $partner->id) }}" class="text-dark">
                                                                {{ $partner->nom }}
                                                            </a>
                                                        </h6>
                                                        <p class="text-xs text-secondary mb-0">{{ $partner->type }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge badge-sm bg-gradient-primary">
                                                    {{ $partner->sponsorings_count }}
                                                </span>
                                            </td>
                                            <td>
                                                <p class="text-sm font-weight-bold text-success mb-0">
                                                    {{ number_format($partner->sponsorings_sum_montant ?? 0, 2) }} €
                                                </p>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="3" class="text-center">
                                                <p class="text-sm text-muted mb-0">Aucun partenaire trouvé</p>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Top 5 Événements -->
                <div class="col-lg-6 mb-4">
                    <div class="card shadow-xs border h-100">
                        <div class="card-header pb-0">
                            <h6 class="font-weight-semibold mb-0">
                                <i class="fas fa-star me-2 text-info"></i>Top 5 Événements
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Événement</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Sponsorings</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Montant Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($stats['top_events'] as $event)
                                        <tr>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm">
                                                            <a href="{{ route('events.show', $event->id) }}" class="text-dark">
                                                                {{ Str::limit($event->title, 40) }}
                                                            </a>
                                                        </h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge badge-sm bg-gradient-info">
                                                    {{ $event->sponsorings_count }}
                                                </span>
                                            </td>
                                            <td>
                                                <p class="text-sm font-weight-bold text-success mb-0">
                                                    {{ number_format($event->sponsorings_sum_montant ?? 0, 2) }} €
                                                </p>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="3" class="text-center">
                                                <p class="text-sm text-muted mb-0">Aucun événement trouvé</p>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Sponsorings -->
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-xs border">
                        <div class="card-header pb-0">
                            <h6 class="font-weight-semibold mb-0">
                                <i class="fas fa-clock me-2 text-secondary"></i>Derniers Sponsorings
                            </h6>
                        </div>
                        <div class="card-body px-0 py-0">
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Partenaire</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Événement</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Type</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Montant</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Date</th>
                                            <th class="text-secondary opacity-7"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($stats['recent_sponsorings'] as $sponsoring)
                                        <tr>
                                            <td>
                                                <div class="d-flex px-3 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm">{{ $sponsoring->partner->nom }}</h6>
                                                        <p class="text-xs text-secondary mb-0">{{ $sponsoring->partner->type }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <p class="text-sm text-secondary mb-0">{{ Str::limit($sponsoring->event->title, 30) }}</p>
                                            </td>
                                            <td>
                                                <span class="badge badge-sm bg-gradient-info">{{ $sponsoring->type_sponsoring->label() }}</span>
                                            </td>
                                            <td>
                                                <p class="text-sm font-weight-bold text-success mb-0">{{ number_format($sponsoring->montant, 2) }} €</p>
                                            </td>
                                            <td>
                                                <p class="text-sm text-secondary mb-0">{{ \Carbon\Carbon::parse($sponsoring->date)->format('d/m/Y') }}</p>
                                            </td>
                                            <td class="align-middle">
                                                <a href="{{ route('sponsoring.show', $sponsoring->id) }}" class="text-secondary font-weight-normal text-xs" data-bs-toggle="tooltip" title="Voir">
                                                    <i class="fas fa-eye text-info"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-4">
                                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                                <p class="text-muted">Aucun sponsoring récent</p>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</x-app-layout>

