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
                                    <h6 class="font-weight-semibold text-lg mb-0">Category Details</h6>
                                    <p class="text-sm mb-0">View category information</p>
                                </div>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-dark btn-sm">
                                        <i class="fas fa-edit me-2"></i>Edit
                                    </a>
                                    <a href="{{ route('categories.index') }}" class="btn btn-white btn-sm">
                                        <i class="fas fa-arrow-left me-2"></i>Back
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card shadow-xs border-radius-lg mb-4">
                                        <div class="card-body">
                                            <h6 class="text-sm font-weight-bold mb-3">Basic Information</h6>
                                            <div class="mb-3">
                                                <span class="text-sm text-secondary">Name:</span>
                                                <h6 class="text-dark mb-0">{{ $category->name }}</h6>
                                            </div>
                                            <div class="mb-3">
                                                <span class="text-sm text-secondary">Type:</span>
                                                <span class="badge badge-sm bg-gradient-{{ $category->type->value == 'EVENT' ? 'primary' : ($category->type->value == 'ASSOCIATION' ? 'success' : 'secondary') }} ms-2">
                                                    {{ $category->type->value }}
                                                </span>
                                            </div>
                                            <div>
                                                <span class="text-sm text-secondary">Description:</span>
                                                <p class="text-dark mb-0 mt-1">{{ $category->description ?? 'No description provided' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card shadow-xs border-radius-lg mb-4">
                                        <div class="card-body">
                                            <h6 class="text-sm font-weight-bold mb-3">Statistics</h6>
                                            <div class="mb-3">
                                                <span class="text-sm text-secondary">Total Events:</span>
                                                <h5 class="text-dark mb-0">{{ $category->events->count() }}</h5>
                                            </div>
                                            <div class="mb-3">
                                                <span class="text-sm text-secondary">Created At:</span>
                                                <p class="text-dark mb-0">{{ $category->created_at->format('M d, Y H:i') }}</p>
                                            </div>
                                            <div>
                                                <span class="text-sm text-secondary">Last Updated:</span>
                                                <p class="text-dark mb-0">{{ $category->updated_at->format('M d, Y H:i') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if($category->events->count() > 0)
                            <div class="card shadow-xs border-radius-lg">
                                <div class="card-header">
                                    <h6 class="text-sm font-weight-bold mb-0">Related Events</h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table align-items-center mb-0">
                                            <thead>
                                                <tr>
                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Event Title</th>
                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Start Date</th>
                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Status</th>
                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Location</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($category->events as $event)
                                                <tr>
                                                    <td>
                                                        <div class="d-flex px-2 py-1">
                                                            <div class="d-flex flex-column justify-content-center">
                                                                <h6 class="mb-0 text-sm">{{ $event->title }}</h6>
                                                            </div>
                                                        </div>
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
                                                        <span class="text-sm font-weight-normal">{{ $event->location }}</span>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            @else
                            <div class="text-center py-4">
                                <i class="fas fa-calendar-times text-secondary fa-3x mb-3"></i>
                                <h6 class="text-secondary">No events found for this category</h6>
                                <p class="text-sm text-secondary">Events assigned to this category will appear here.</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</x-app-layout>