<x-app-layout>
    @php
        $user = Auth::user();
    @endphp
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <x-app.navbar />
        <div class="container-fluid py-4 px-5">
            <!-- Welcome Section -->
            <div class="row">
                <div class="col-md-12">
                    <div class="d-md-flex align-items-center mb-4 mx-2">
                        <div class="mb-md-0 mb-3">
                            <h3 class="font-weight-bold mb-0">
                                <i class="fas fa-hand-wave text-warning me-2"></i>Hello, {{ $user->name }}!
                            </h3>
                            <p class="mb-0 text-sm">
                                @if($user->isAdmin())
                                    <i class="fas fa-user-shield text-primary me-1"></i>Welcome Admin! Here are your platform statistics.
                                @elseif($user->isFournisseur())
                                    <i class="fas fa-truck text-info me-1"></i>Welcome Fournisseur! Manage your resources here.
                                @elseif($user->isOrganisateur())
                                    <i class="fas fa-calendar text-success me-1"></i>Welcome Organisateur! Track your events performance.
                                @elseif($user->isParticipant())
                                    <i class="fas fa-user text-warning me-1"></i>Welcome Participant! View your registrations.
                                @else
                                    Welcome to EcoEvents!
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            @if($user->isAdmin())
                <!-- Admin Stats -->
                <div class="row mb-4">
                    <!-- Events Stats -->
                    <div class="col-xl-3 col-sm-6 mb-3">
                        <div class="card border shadow-xs h-100">
                            <div class="card-body text-start p-3">
                                <div class="icon icon-shape icon-sm bg-gradient-primary text-white text-center border-radius-sm d-flex align-items-center justify-content-center mb-3">
                                    <i class="fas fa-calendar-alt fa-lg"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-secondary mb-1">Total Events</p>
                                    <h4 class="mb-2 font-weight-bold">{{ $stats['total_events'] ?? 0 }}</h4>
                                    <p class="text-xs text-muted mb-0">
                                        <span class="text-success">{{ $stats['upcoming_events'] ?? 0 }}</span> upcoming
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Registrations Stats -->
                    <div class="col-xl-3 col-sm-6 mb-3">
                        <div class="card border shadow-xs h-100">
                            <div class="card-body text-start p-3">
                                <div class="icon icon-shape icon-sm bg-gradient-success text-white text-center border-radius-sm d-flex align-items-center justify-content-center mb-3">
                                    <i class="fas fa-ticket-alt fa-lg"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-secondary mb-1">Total Registrations</p>
                                    <h4 class="mb-2 font-weight-bold">{{ $stats['total_registrations'] ?? 0 }}</h4>
                                    <p class="text-xs text-muted mb-0">
                                        <span class="text-success">{{ $stats['confirmed_registrations'] ?? 0 }}</span> confirmed
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Revenue Stats -->
                    <div class="col-xl-3 col-sm-6 mb-3">
                        <div class="card border shadow-xs h-100">
                            <div class="card-body text-start p-3">
                                <div class="icon icon-shape icon-sm bg-gradient-warning text-white text-center border-radius-sm d-flex align-items-center justify-content-center mb-3">
                                    <i class="fas fa-dollar-sign fa-lg"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-secondary mb-1">Total Revenue</p>
                                    <h4 class="mb-2 font-weight-bold">{{ number_format($stats['total_revenue'] ?? 0, 2) }} DT</h4>
                                    <p class="text-xs text-muted mb-0">
                                        This month: {{ number_format($stats['revenue_this_month'] ?? 0, 2) }} DT
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Users Stats -->
                    <div class="col-xl-3 col-sm-6 mb-3">
                        <div class="card border shadow-xs h-100">
                            <div class="card-body text-start p-3">
                                <div class="icon icon-shape icon-sm bg-gradient-info text-white text-center border-radius-sm d-flex align-items-center justify-content-center mb-3">
                                    <i class="fas fa-users fa-lg"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-secondary mb-1">Total Users</p>
                                    <h4 class="mb-2 font-weight-bold">{{ $stats['total_users'] ?? 0 }}</h4>
                                    <p class="text-xs text-muted mb-0">
                                        <span class="text-info">{{ $stats['participants'] ?? 0 }}</span> participants
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Additional Admin Stats Row -->
                <div class="row mb-4">
                    <div class="col-xl-3 col-sm-6 mb-3">
                        <div class="card border shadow-xs h-100">
                            <div class="card-body text-start p-3">
                                <div class="icon icon-shape icon-sm bg-gradient-danger text-white text-center border-radius-sm d-flex align-items-center justify-content-center mb-3">
                                    <i class="fas fa-star fa-lg"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-secondary mb-1">Average Rating</p>
                                    <h4 class="mb-2 font-weight-bold">{{ number_format($stats['average_rating'] ?? 0, 1) }}/5</h4>
                                    <p class="text-xs text-muted mb-0">
                                        {{ $stats['total_feedback'] ?? 0 }} reviews
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-sm-6 mb-3">
                        <div class="card border shadow-xs h-100">
                            <div class="card-body text-start p-3">
                                <div class="icon icon-shape icon-sm bg-gradient-secondary text-white text-center border-radius-sm d-flex align-items-center justify-content-center mb-3">
                                    <i class="fas fa-percentage fa-lg"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-secondary mb-1">Attendance Rate</p>
                                    <h4 class="mb-2 font-weight-bold">{{ $stats['attendance_rate'] ?? 0 }}%</h4>
                                    <p class="text-xs text-muted mb-0">
                                        {{ $stats['attended_registrations'] ?? 0 }} attended
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-sm-6 mb-3">
                        <div class="card border shadow-xs h-100">
                            <div class="card-body text-start p-3">
                                <div class="icon icon-shape icon-sm bg-gradient-primary text-white text-center border-radius-sm d-flex align-items-center justify-content-center mb-3">
                                    <i class="fas fa-handshake fa-lg"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-secondary mb-1">Partners</p>
                                    <h4 class="mb-2 font-weight-bold">{{ $stats['total_partners'] ?? 0 }}</h4>
                                    <p class="text-xs text-muted mb-0">
                                        {{ $stats['total_sponsorings'] ?? 0 }} sponsorings
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-sm-6 mb-3">
                        <div class="card border shadow-xs h-100">
                            <div class="card-body text-start p-3">
                                <div class="icon icon-shape icon-sm bg-gradient-success text-white text-center border-radius-sm d-flex align-items-center justify-content-center mb-3">
                                    <i class="fas fa-donate fa-lg"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-secondary mb-1">Sponsoring Amount</p>
                                    <h4 class="mb-2 font-weight-bold">{{ number_format($stats['total_sponsoring_amount'] ?? 0, 2) }} DT</h4>
                                    <p class="text-xs text-muted mb-0">
                                        Total contributions
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Popular Events -->
                @if(isset($stats['popular_events']) && $stats['popular_events']->count() > 0)
                <div class="row">
                    <div class="col-12">
                        <div class="card shadow-xs border">
                            <div class="card-header pb-0">
                                <h6 class="font-weight-semibold text-lg mb-0">
                                    <i class="fas fa-fire text-danger me-2"></i>Popular Events
                                </h6>
                                <p class="text-sm mb-0">Top events by registrations</p>
                            </div>
                            <div class="card-body px-0 py-0">
                                <div class="table-responsive p-0">
                                    <table class="table align-items-center mb-0">
                                        <thead class="bg-gray-100">
                                            <tr>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Event</th>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Date</th>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Registrations</th>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Revenue</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($stats['popular_events'] as $event)
                                            <tr>
                                                <td>
                                                    <div class="d-flex px-2 py-1">
                                                        <div>
                                                            <h6 class="mb-0 text-sm">{{ $event->title }}</h6>
                                                            <p class="text-xs text-secondary mb-0">{{ $event->location }}</p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="text-sm font-weight-normal">{{ $event->start_date->format('M d, Y') }}</span>
                                                </td>
                                                <td>
                                                    <span class="badge badge-sm bg-gradient-success">{{ $event->registrations_count }} participants</span>
                                                </td>
                                                <td>
                                                    <span class="text-sm font-weight-bold">{{ number_format($event->price * $event->registrations_count, 2) }} DT</span>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

            @elseif($user->isFournisseur())
                <!-- Fournisseur Stats -->
                <div class="row">
                    <div class="col-xl-4 col-sm-6 mb-3">
                        <div class="card border shadow-xs h-100">
                            <div class="card-body text-start p-3">
                                <div class="icon icon-shape icon-sm bg-gradient-info text-white text-center border-radius-sm d-flex align-items-center justify-content-center mb-3">
                                    <i class="fas fa-boxes fa-lg"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-secondary mb-1">Your Resources</p>
                                    <h4 class="mb-2 font-weight-bold">{{ $stats['total_ressources'] ?? 0 }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-4 col-sm-6 mb-3">
                        <div class="card border shadow-xs h-100">
                            <div class="card-body text-start p-3">
                                <div class="icon icon-shape icon-sm bg-gradient-success text-white text-center border-radius-sm d-flex align-items-center justify-content-center mb-3">
                                    <i class="fas fa-calendar-check fa-lg"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-secondary mb-1">Events Supplied</p>
                                    <h4 class="mb-2 font-weight-bold">{{ $stats['events_supplied'] ?? 0 }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            @elseif($user->isOrganisateur())
                <!-- Organisateur Stats -->
                <div class="row mb-4">
                    <div class="col-xl-3 col-sm-6 mb-3">
                        <div class="card border shadow-xs h-100">
                            <div class="card-body text-start p-3">
                                <div class="icon icon-shape icon-sm bg-gradient-primary text-white text-center border-radius-sm d-flex align-items-center justify-content-center mb-3">
                                    <i class="fas fa-calendar-alt fa-lg"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-secondary mb-1">Your Events</p>
                                    <h4 class="mb-2 font-weight-bold">{{ $stats['total_events'] ?? 0 }}</h4>
                                    <p class="text-xs text-muted mb-0">
                                        <span class="text-success">{{ $stats['upcoming_events'] ?? 0 }}</span> upcoming
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-sm-6 mb-3">
                        <div class="card border shadow-xs h-100">
                            <div class="card-body text-start p-3">
                                <div class="icon icon-shape icon-sm bg-gradient-success text-white text-center border-radius-sm d-flex align-items-center justify-content-center mb-3">
                                    <i class="fas fa-ticket-alt fa-lg"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-secondary mb-1">Total Registrations</p>
                                    <h4 class="mb-2 font-weight-bold">{{ $stats['total_registrations'] ?? 0 }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-sm-6 mb-3">
                        <div class="card border shadow-xs h-100">
                            <div class="card-body text-start p-3">
                                <div class="icon icon-shape icon-sm bg-gradient-warning text-white text-center border-radius-sm d-flex align-items-center justify-content-center mb-3">
                                    <i class="fas fa-dollar-sign fa-lg"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-secondary mb-1">Total Revenue</p>
                                    <h4 class="mb-2 font-weight-bold">{{ number_format($stats['total_revenue'] ?? 0, 2) }} DT</h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-sm-6 mb-3">
                        <div class="card border shadow-xs h-100">
                            <div class="card-body text-start p-3">
                                <div class="icon icon-shape icon-sm bg-gradient-danger text-white text-center border-radius-sm d-flex align-items-center justify-content-center mb-3">
                                    <i class="fas fa-star fa-lg"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-secondary mb-1">Average Rating</p>
                                    <h4 class="mb-2 font-weight-bold">{{ number_format($stats['average_rating'] ?? 0, 1) }}/5</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            @elseif($user->isParticipant())
                <!-- Participant Stats -->
                <div class="row">
                    <div class="col-xl-3 col-sm-6 mb-3">
                        <div class="card border shadow-xs h-100">
                            <div class="card-body text-start p-3">
                                <div class="icon icon-shape icon-sm bg-gradient-primary text-white text-center border-radius-sm d-flex align-items-center justify-content-center mb-3">
                                    <i class="fas fa-ticket-alt fa-lg"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-secondary mb-1">Your Registrations</p>
                                    <h4 class="mb-2 font-weight-bold">{{ $stats['total_registrations'] ?? 0 }}</h4>
                                    <p class="text-xs text-muted mb-0">
                                        <span class="text-success">{{ $stats['confirmed_registrations'] ?? 0 }}</span> confirmed
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-sm-6 mb-3">
                        <div class="card border shadow-xs h-100">
                            <div class="card-body text-start p-3">
                                <div class="icon icon-shape icon-sm bg-gradient-success text-white text-center border-radius-sm d-flex align-items-center justify-content-center mb-3">
                                    <i class="fas fa-check-circle fa-lg"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-secondary mb-1">Events Attended</p>
                                    <h4 class="mb-2 font-weight-bold">{{ $stats['attended_registrations'] ?? 0 }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-sm-6 mb-3">
                        <div class="card border shadow-xs h-100">
                            <div class="card-body text-start p-3">
                                <div class="icon icon-shape icon-sm bg-gradient-warning text-white text-center border-radius-sm d-flex align-items-center justify-content-center mb-3">
                                    <i class="fas fa-calendar-alt fa-lg"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-secondary mb-1">Upcoming Events</p>
                                    <h4 class="mb-2 font-weight-bold">{{ $stats['upcoming_events'] ?? 0 }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-sm-6 mb-3">
                        <div class="card border shadow-xs h-100">
                            <div class="card-body text-start p-3">
                                <div class="icon icon-shape icon-sm bg-gradient-info text-white text-center border-radius-sm d-flex align-items-center justify-content-center mb-3">
                                    <i class="fas fa-comment-dots fa-lg"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-secondary mb-1">Your Feedback</p>
                                    <h4 class="mb-2 font-weight-bold">{{ $stats['total_feedback'] ?? 0 }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <x-app.footer />
        </div>
    </main>
</x-app-layout>