<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <x-app.navbar />
        <div class="container-fluid py-4 px-5">
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-xs border">
                        <div class="card-header pb-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="font-weight-semibold text-lg mb-0">Event Details</h6>
                                    <p class="text-sm mb-0">View event information</p>
                                </div>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('events.edit', $event->id) }}" class="btn btn-dark btn-sm">
                                        <i class="fas fa-edit me-2"></i>Edit
                                    </a>
                                    <a href="{{ route('events.index') }}" class="btn btn-white btn-sm">
                                        <i class="fas fa-arrow-left me-2"></i>Back
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="card shadow-xs border-radius-lg mb-4">
                                        <div class="card-body">
                                            <h4 class="text-dark font-weight-bold mb-3">{{ $event->title }}</h4>
                                            <p class="text-dark mb-4">{{ $event->description }}</p>
                                            
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <span class="text-sm text-secondary">Category:</span>
                                                        <h6 class="text-dark mb-0">{{ $event->category->name }}</h6>
                                                    </div>
                                                    <div class="mb-3">
                                                        <span class="text-sm text-secondary">Location:</span>
                                                        <h6 class="text-dark mb-0">{{ $event->location }}</h6>
                                                    </div>
                                                    <div class="mb-3">
                                                        <span class="text-sm text-secondary">Max Capacity:</span>
                                                        <h6 class="text-dark mb-0">{{ $event->capacity_max }} people</h6>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <span class="text-sm text-secondary">Price:</span>
                                                        <h6 class="text-dark mb-0">${{ number_format($event->price, 2) }}</h6>
                                                    </div>
                                                    <div class="mb-3">
                                                        <span class="text-sm text-secondary">Visibility:</span>
                                                        <span class="badge badge-sm bg-gradient-{{ $event->is_public ? 'success' : 'secondary' }}">
                                                            {{ $event->is_public ? 'Public' : 'Private' }}
                                                        </span>
                                                    </div>
                                                    <div class="mb-3">
                                                        <span class="text-sm text-secondary">Status:</span>
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
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card shadow-xs border-radius-lg mb-4">
                                        <div class="card-body">
                                            <h6 class="text-sm font-weight-bold mb-3">Event Timeline</h6>
                                            <div class="mb-3">
                                                <span class="text-sm text-secondary">Start Date:</span>
                                                <p class="text-dark mb-0">
                                                    <i class="fas fa-calendar-alt me-2"></i>
                                                    {{ $event->start_date->format('M d, Y H:i') }}
                                                </p>
                                            </div>
                                            <div class="mb-3">
                                                <span class="text-sm text-secondary">End Date:</span>
                                                <p class="text-dark mb-0">
                                                    <i class="fas fa-calendar-alt me-2"></i>
                                                    {{ $event->end_date->format('M d, Y H:i') }}
                                                </p>
                                            </div>
                                            <div class="mb-3">
                                                <span class="text-sm text-secondary">Registration Deadline:</span>
                                                <p class="text-dark mb-0">
                                                    <i class="fas fa-clock me-2"></i>
                                                    {{ $event->registration_deadline->format('M d, Y H:i') }}
                                                </p>
                                            </div>
                                            <div class="mb-3">
                                                <span class="text-sm text-secondary">Duration:</span>
                                                <p class="text-dark mb-0">
                                                    {{ $event->start_date->diffInHours($event->end_date) }} hours
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="card shadow-xs border-radius-lg">
                                        <div class="card-body">
                                            <h6 class="text-sm font-weight-bold mb-3">Event Statistics</h6>
                                            <div class="mb-3">
                                                <span class="text-sm text-secondary">Total Registrations:</span>
                                                <h6 class="text-dark mb-0">{{ $event->registrations->count() }}</h6>
                                            </div>
                                            <div class="mb-3">
                                                <span class="text-sm text-secondary">Available Spots:</span>
                                                <h6 class="text-dark mb-0">{{ $event->capacity_max - $event->registrations->count() }}</h6>
                                            </div>
                                            <div class="mb-3">
                                                <span class="text-sm text-secondary">Total Resources:</span>
                                                <h6 class="text-dark mb-0">{{ $event->ressources->count() }}</h6>
                                            </div>
                                            <div>
                                                <span class="text-sm text-secondary">Feedback Count:</span>
                                                <h6 class="text-dark mb-0">{{ $event->feedbacks->count() }}</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Resources Section -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="card shadow-xs border-radius-lg">
                                        <div class="card-header">
                                            <h6 class="text-sm font-weight-bold mb-0">Resources</h6>
                                        </div>
                                        <div class="card-body">
                                            @if($event->ressources->count() > 0)
                                                <div class="table-responsive">
                                                    <table class="table align-items-center mb-0">
                                                        <thead>
                                                            <tr>
                                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Name</th>
                                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Type</th>
                                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Supplier</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($event->ressources as $resource)
                                                                <tr>
                                                                    <td class="text-sm text-dark">{{ $resource->nom }}</td>
                                                                    <td class="text-sm text-dark">{{ $resource->type }}</td>
                                                                    <td class="text-sm text-dark">{{ $resource->fournisseur->nom_societe }}</td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @else
                                                <p class="text-sm text-secondary mb-0">No resources assigned to this event.</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

<!-- Images Section -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card shadow-xs border-radius-lg">
            <div class="card-header">
                <h6 class="text-sm font-weight-bold mb-0">Event Images</h6>
            </div>
            <div class="card-body">
                @if($event->images && count($event->images) > 0)
                    <div class="row">
                        @foreach($event->images as $index => $imagePath)
                            <div class="col-md-3 mb-3">
                                <div class="text-center">
                                    <img src="{{ asset('storage/' . $imagePath) }}" 
                                         alt="Event Image {{ $index + 1 }}" 
                                         class="img-fluid rounded shadow-xs mb-2"
                                         style="height: 200px; object-fit: cover; width: 100%;"
                                         onerror="this.src='https://via.placeholder.com/300x200?text=Image+Not+Found'">
                                    <small class="text-muted">Image {{ $index + 1 }}</small>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-secondary mb-0">No images uploaded for this event.</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Additional Information -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card shadow-xs border-radius-lg">
            <div class="card-header">
                <h6 class="text-sm font-weight-bold mb-0">Additional Information</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <span class="text-sm text-secondary">Created At:</span>
                            <p class="text-dark mb-0">{{ $event->created_at->format('M d, Y H:i') }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <span class="text-sm text-secondary">Last Updated:</span>
                            <p class="text-dark mb-0">{{ $event->updated_at->format('M d, Y H:i') }}</p>
                        </div>
                    </div>
                </div>
                @if($event->images)
                <div class="mb-3">
                    <span class="text-sm text-secondary">Total Images:</span>
                    <p class="text-dark mb-0">{{ count($event->images) }} images</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</x-app-layout>