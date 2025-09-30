<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <x-app.navbar />
        <div class="container-fluid py-4 px-5">
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-xs border mb-4">
                        <div class="card-header pb-0">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h6 class="font-weight-semibold text-lg mb-0">Users</h6>
                                    <p class="text-sm mb-0">Manage your users</p>
                                </div>
                                <a href="{{ route('users.create') }}" class="btn btn-dark btn-sm">
                                    <i class="fas fa-plus me-2"></i>Add User
                                </a>
                            </div>

                            <!-- Search and Filter Form -->
                            <form method="GET" action="{{ route('users.index') }}" class="mb-3">
                                <div class="row g-3">
                                    <!-- Search -->
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="fas fa-search"></i>
                                            </span>
                                            <input type="text" name="search" class="form-control" placeholder="Search by name or email..." value="{{ request('search') }}">
                                        </div>
                                    </div>

                                    <!-- Role Filter -->
                                    <div class="col-md-4">
                                        <select name="role" class="form-select">
                                            <option value="">All Roles</option>
                                            @foreach($roles as $role)
                                                <option value="{{ $role }}" {{ request('role') == $role ? 'selected' : '' }}>
                                                    {{ ucfirst($role) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Buttons -->
                                    <div class="col-md-2">
                                        <div class="d-flex gap-2">
                                            <button type="submit" class="btn btn-primary btn-sm w-50">
                                                <i class="fas fa-filter me-1"></i>Filter
                                            </button>
                                            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary btn-sm w-50">
                                                <i class="fas fa-redo me-1"></i>Reset
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="card-body px-0 py-0">
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Name</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Email</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Role</th>
                                            <th class="text-secondary opacity-7"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($users as $user)
                                        <tr>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm">{{ $user->name }}</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="text-sm">{{ $user->email }}</span>
                                            </td>
                                            <td>
                                                <span class="badge badge-sm bg-gradient-secondary">{{ $user->role }}</span>
                                            </td>
                                            <td class="align-middle">
                                                <div class="d-flex gap-2">
                                                    <a href="{{ route('users.show', $user->id) }}" class="btn btn-sm btn-outline-info mb-0">
                                                        View
                                                    </a>
                                                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-outline-dark mb-0">
                                                        Edit
                                                    </a>
                                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger mb-0" onclick="return confirm('Are you sure?')">
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
                                {{ $users->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</x-app-layout>
