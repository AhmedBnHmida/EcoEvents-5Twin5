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
                                    <h6 class="font-weight-semibold text-lg mb-0">User Details</h6>
                                    <p class="text-sm mb-0">View user information</p>
                                </div>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-dark btn-sm">
                                        <i class="fas fa-edit me-2"></i>Edit
                                    </a>
                                    <a href="{{ route('users.index') }}" class="btn btn-white btn-sm">
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
                                                <h6 class="text-dark mb-0">{{ $user->name }}</h6>
                                            </div>
                                            <div class="mb-3">
                                                <span class="text-sm text-secondary">Email:</span>
                                                <h6 class="text-dark mb-0">{{ $user->email }}</h6>
                                            </div>
                                            <div class="mb-3">
                                                <span class="text-sm text-secondary">Role:</span>
                                                <span class="badge badge-sm bg-gradient-secondary ms-2">{{ $user->role }}</span>
                                            </div>
                                            <div class="mb-3">
                                                <span class="text-sm text-secondary">Created At:</span>
                                                <p class="text-dark mb-0">{{ $user->created_at->format('M d, Y H:i') }}</p>
                                            </div>
                                            <div>
                                                <span class="text-sm text-secondary">Last Updated:</span>
                                                <p class="text-dark mb-0">{{ $user->updated_at->format('M d, Y H:i') }}</p>
                                            </div>
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
