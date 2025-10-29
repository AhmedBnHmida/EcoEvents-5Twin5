<x-app-layout>
    <style>
        .event-at-risk {
            border: 2px solid #FF5722;
            position: relative;
            transition: all 0.3s ease;
        }
        
        .event-at-risk::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background-color: #FF5722;
        }
        
        .event-at-risk:hover {
            box-shadow: 0 5px 15px rgba(255, 87, 34, 0.4) !important;
        }
        
        .event-at-risk td {
            border-top-color: #FF5722;
            border-bottom-color: #FF5722;
        }
        
        .event-at-risk td:first-child {
            border-left-color: #FF5722;
        }
        
        .event-at-risk td:last-child {
            border-right-color: #FF5722;
        }

        /* Style for action buttons */
        .action-buttons {
            display: flex;
            gap: 5px;
            flex-wrap: wrap;
        }
        
        .action-buttons form {
            margin: 0;
        }
        
        .btn-xs {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
            line-height: 1.5;
            border-radius: 0.2rem;
        }
    </style>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <x-app.navbar />
        <div class="container-fluid py-4 px-5">
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-xs border mb-4">
                        <div class="card-header pb-0">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h6 class="font-weight-semibold text-lg mb-0">Events</h6>
                                    <p class="text-sm mb-0">Manage your events</p>
                                </div>
                                <a href="{{ route('events.create') }}" class="btn btn-dark btn-sm">
                                    <i class="fas fa-plus me-2"></i>Add Event
                                </a>
                            </div>

                            <!-- Search and Filter Form -->
                            <form method="GET" action="{{ route('events.index') }}" class="mb-3">
                                <div class="row g-3">
                                    <!-- Search -->
                                    <div class="col-md-4">
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="fas fa-search"></i>
                                            </span>
                                            <input type="text" name="search" class="form-control" placeholder="Search events..." value="{{ request('search') }}">
                                        </div>
                                    </div>

                                    <!-- Category Filter -->
                                    <div class="col-md-2">
                                        <select name="category" class="form-select">
                                            <option value="">All Categories</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Status Filter -->
                                    <div class="col-md-2">
                                        <select name="status" class="form-select">
                                            <option value="">All Status</option>
                                            @foreach($statuses as $status)
                                                <option value="{{ $status->value }}" {{ request('status') == $status->value ? 'selected' : '' }}>
                                                    {{ $status->value }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Price Range -->
                                    <div class="col-md-2">
                                        <input type="number" name="min_price" class="form-control" placeholder="Min Price" value="{{ request('min_price') }}" step="0.01">
                                    </div>
                                    <div class="col-md-2">
                                        <input type="number" name="max_price" class="form-control" placeholder="Max Price" value="{{ request('max_price') }}" step="0.01">
                                    </div>

                                    <!-- Buttons -->
                                    <div class="col-md-12">
                                        <div class="d-flex gap-2">
                                            <button type="submit" class="btn btn-primary btn-sm">
                                                <i class="fas fa-filter me-1"></i>Filter
                                            </button>
                                            <a href="{{ route('events.index') }}" class="btn btn-outline-secondary btn-sm">
                                                <i class="fas fa-redo me-1"></i>Reset
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Pending Events Table (is_public = false) -->
                    @if($pendingEvents->count() > 0)
                    <div class="card shadow-xs border mb-4">
                        <div class="card-header pb-0 bg-opacity-10">
                            <h6 class="font-weight-semibold text-lg mb-0 text-warning">
                                <i class="fas fa-clock me-2"></i>Pending Approval Events
                            </h6>
                            <p class="text-sm mb-0 text-warning">Events waiting for admin approval</p>
                        </div>
                        <div class="card-body px-0 py-0">
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead class="bg-warning bg-opacity-10">
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Event</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Category</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Date</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Status</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Approval</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Admin Actions</th>
                                            <th class="text-secondary opacity-7"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($pendingEvents as $event)
                                        <tr>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div>
                                                        <h6 class="mb-0 text-sm">{{ $event->title }}</h6>
                                                        <p class="text-xs text-secondary mb-0">{{ Str::limit($event->description, 30) }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="text-sm font-weight-normal">{{ $event->category->name }}</span>
                                            </td>
                                            <td>
                                                <span class="text-sm font-weight-normal">{{ $event->start_date->format('M d, Y') }}</span>
                                            </td>
                                            <td>
                                                @php
                                                    $statusColors = [
                                                        'UPCOMING' => 'warning',
                                                        'ONGOING' => 'success', 
                                                        'CANCELLED' => 'danger',
                                                        'COMPLETED' => 'info'
                                                    ];
                                                @endphp
                                                <span class="badge badge-sm bg-gradient-{{ $statusColors[$event->status->value] ?? 'secondary' }}">
                                                    {{ $event->status->value }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge badge-sm bg-gradient-warning">
                                                    <i class="fas fa-clock me-1"></i> Pending
                                                </span>
                                            </td>
                                            <td>
                                                <!-- Admin approval buttons -->
                                                @if(auth()->user()->role === 'admin')
                                                <div class="action-buttons">
                                                    <form action="{{ route('events.approve', $event->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-success btn-xs" 
                                                                onclick="return confirm('Approve this event?')">
                                                            <i class="fas fa-check me-1"></i>Approve
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('events.reject', $event->id) }}" method="POST" class="d-inline">
                                                        @csrf  
                                                        <button type="submit" class="btn btn-danger btn-xs"
                                                                onclick="return confirm('Reject this event?')">
                                                            <i class="fas fa-times me-1"></i>Reject
                                                        </button>
                                                    </form>
                                                </div>
                                                @else
                                                <span class="text-muted text-xs">Admin only</span>
                                                @endcan
                                            </td>
                                            <td class="align-middle">
                                                <div class="d-flex gap-2">
                                                    <a href="{{ route('events.show', $event->id) }}" class="btn btn-sm btn-outline-info mb-0">
                                                        View
                                                    </a>
                                                    <a href="{{ route('events.edit', $event->id) }}" class="btn btn-sm btn-outline-dark mb-0">
                                                        Edit
                                                    </a>
                                                    <form action="{{ route('events.destroy', $event->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger mb-0" 
                                                                onclick="return confirm('Are you sure you want to delete this event?')">
                                                            Delete
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="card-footer px-3 pb-0">
                                {{ $pendingEvents->links() }}
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Approved Events Table (is_public = true) -->
                    <div class="card shadow-xs border ">
                        <div class="card-header pb-0  bg-opacity-10">
                            <h6 class="font-weight-semibold text-lg mb-0  text-success">
                                <i class="fas fa-check-circle me-2"></i>Approved Events
                            </h6>
                            <p class="text-sm mb-0 text-success">Public events visible to everyone</p>
                        </div>
                        <div class="card-body px-0 py-0">
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead class="bg-success bg-opacity-10">
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Event</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Category</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Date</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Location</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Status</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Risk</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Admin Actions</th>
                                            <th class="text-secondary opacity-7"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($approvedEvents as $event)
                                        <tr class="{{ $event->at_risk ? 'event-at-risk' : '' }}" 
                                           style="{{ $event->at_risk ? 'position: relative; box-shadow: 0 0 10px rgba(255, 87, 34, 0.3);' : '' }}">
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div>
                                                        <h6 class="mb-0 text-sm">{{ $event->title }}</h6>
                                                        <p class="text-xs text-secondary mb-0">{{ Str::limit($event->description, 30) }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="text-sm font-weight-normal">{{ $event->category->name }}</span>
                                            </td>
                                            <td>
                                                <span class="text-sm font-weight-normal">{{ $event->start_date->format('M d, Y') }}</span>
                                            </td>
                                            <td>
                                                <span class="text-sm font-weight-normal">{{ $event->location }}</span>
                                            </td>
                                            <td>
                                                <span class="badge badge-sm bg-gradient-{{ $statusColors[$event->status->value] ?? 'secondary' }}">
                                                    {{ $event->status->value }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($event->at_risk)
                                                    <span class="badge badge-sm bg-gradient-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="Cet événement présente des risques basés sur les retours négatifs">
                                                        <i class="fas fa-exclamation-triangle me-1"></i> À améliorer
                                                    </span>
                                                @else
                                                    <span class="badge badge-sm bg-gradient-success">
                                                        <i class="fas fa-check me-1"></i> OK
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                <!-- Admin actions for approved events -->
                                                @can('admin')
                                                <div class="action-buttons">
                                                    @if($event->is_public)
                                                    <form action="{{ route('events.reject', $event->id) }}" method="POST" class="d-inline">
                                                        @csrf  
                                                        <button type="submit" class="btn btn-warning btn-xs"
                                                                onclick="return confirm('Unpublish this event?')">
                                                            <i class="fas fa-eye-slash me-1"></i>Unpublish
                                                        </button>
                                                    </form>
                                                    @else
                                                    <form action="{{ route('events.approve', $event->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-success btn-xs" 
                                                                onclick="return confirm('Approve this event?')">
                                                            <i class="fas fa-check me-1"></i>Approve
                                                        </button>
                                                    </form>
                                                    @endif
                                                </div>
                                                @else
                                                <span class="badge badge-sm bg-gradient-success">
                                                    <i class="fas fa-check me-1"></i> Approved
                                                </span>
                                                @endcan
                                            </td>
                                            <td class="align-middle">
                                                <div class="d-flex gap-2">
                                                    <a href="{{ route('events.show', $event->id) }}" class="btn btn-sm btn-outline-info mb-0">
                                                        View
                                                    </a>
                                                    <a href="{{ route('events.edit', $event->id) }}" class="btn btn-sm btn-outline-dark mb-0">
                                                        Edit
                                                    </a>
                                                    <form action="{{ route('events.destroy', $event->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger mb-0" 
                                                                onclick="return confirm('Are you sure you want to delete this event?')">
                                                            Delete
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="card-footer px-3 pb-0">
                                {{ $approvedEvents->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</x-app-layout>