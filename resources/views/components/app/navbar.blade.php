<nav class="navbar navbar-main navbar-expand-lg mx-5 px-0 shadow-none rounded" id="navbarBlur" navbar-scroll="true">
    <div class="container-fluid py-1 px-2">
        <nav aria-label="breadcrumb">
            @php
                $user = Auth::user();
                $currentRoute = Route::currentRouteName();
                $pageTitle = ucfirst(str_replace(['.', '-', '_'], ' ', $currentRoute));
            @endphp
            <ol class="breadcrumb bg-transparent mb-1 pb-0 pt-1 px-0 me-sm-6 me-5">
                <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item text-sm text-dark active" aria-current="page">{{ $pageTitle }}</li>
            </ol>
            <h6 class="font-weight-bold mb-0">{{ $pageTitle }}</h6>
        </nav>
        <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
            <div class="ms-md-auto pe-md-3 d-flex align-items-center">
                <div class="input-group">
                    <span class="input-group-text text-body bg-white  border-end-0 ">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16px" height="16px" fill="none"
                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                        </svg>
                    </span>
                    <input type="text" class="form-control ps-0" placeholder="Search">
                </div>
            </div>
            <div class="mb-0 font-weight-bold d-flex align-items-center">
                @auth
                    <span class="text-sm text-dark me-3">
                        <i class="fas fa-user me-1"></i>
                        {{ $user->name }}
                        <span class="badge badge-sm bg-gradient-success ms-1">{{ ucfirst($user->role) }}</span>
                    </span>
                @endauth
                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button class="btn btn-sm btn-outline-danger mb-0" type="submit">
                        <i class="fas fa-sign-out-alt me-1"></i>Log out
                    </button>
                </form>
            </div>
            <ul class="navbar-nav  justify-content-end">
                <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
                    <a href="javascript:;" class="nav-link text-body p-0" id="iconNavbarSidenav">
                        <div class="sidenav-toggler-inner">
                            <i class="sidenav-toggler-line"></i>
                            <i class="sidenav-toggler-line"></i>
                            <i class="sidenav-toggler-line"></i>
                        </div>
                    </a>
                </li>
                <li class="nav-item px-3 d-flex align-items-center">
                    <a href="javascript:;" class="nav-link text-body p-0">
                        <svg width="16" height="16" xmlns="http://www.w3.org/2000/svg"
                            class="fixed-plugin-button-nav cursor-pointer" viewBox="0 0 24 24" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M11.078 2.25c-.917 0-1.699.663-1.85 1.567L9.05 4.889c-.02.12-.115.26-.297.348a7.493 7.493 0 00-.986.57c-.166.115-.334.126-.45.083L6.3 5.508a1.875 1.875 0 00-2.282.819l-.922 1.597a1.875 1.875 0 00.432 2.385l.84.692c.095.078.17.229.154.43a7.598 7.598 0 000 1.139c.015.2-.059.352-.153.43l-.841.692a1.875 1.875 0 00-.432 2.385l.922 1.597a1.875 1.875 0 002.282.818l1.019-.382c.115-.043.283-.031.45.082.312.214.641.405.985.57.182.088.277.228.297.35l.178 1.071c.151.904.933 1.567 1.85 1.567h1.844c.916 0 1.699-.663 1.85-1.567l.178-1.072c.02-.12.114-.26.297-.349.344-.165.673-.356.985-.57.167-.114.335-.125.45-.082l1.02.382a1.875 1.875 0 002.28-.819l.923-1.597a1.875 1.875 0 00-.432-2.385l-.84-.692c-.095-.078-.17-.229-.154-.43a7.614 7.614 0 000-1.139c-.016-.2.059-.352.153-.43l.84-.692c.708-.582.891-1.59.433-2.385l-.922-1.597a1.875 1.875 0 00-2.282-.818l-1.02.382c-.114.043-.282.031-.449-.083a7.49 7.49 0 00-.985-.57c-.183-.087-.277-.227-.297-.348l-.179-1.072a1.875 1.875 0 00-1.85-1.567h-1.843zM12 15.75a3.75 3.75 0 100-7.5 3.75 3.75 0 000 7.5z"
                                clip-rule="evenodd" />
                        </svg>
                    </a>
                </li>
                <li class="nav-item dropdown pe-2 d-flex align-items-center">
                    @php
                        $unreadNotifications = auth()->user()->unreadNotifications;
                        $notificationCount = $unreadNotifications->count();
                    @endphp
                    <a href="javascript:;" class="nav-link p-0" id="dropdownMenuButton"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="position-relative">
                            <div class="p-2 rounded-circle {{ $notificationCount > 0 ? 'bg-gradient-light' : '' }}" 
                                 style="transition: all 0.3s ease;">
                                <i class="fas fa-bell {{ $notificationCount > 0 ? 'text-warning' : 'text-secondary' }}" 
                                   style="font-size: 1.1rem; {{ $notificationCount > 0 ? 'animation: bellShake 2s infinite;' : '' }}"></i>
                            </div>
                            <style>
                                @keyframes bellShake {
                                    0% { transform: rotate(0); }
                                    5% { transform: rotate(15deg); }
                                    10% { transform: rotate(-15deg); }
                                    15% { transform: rotate(0); }
                                    100% { transform: rotate(0); }
                                }
                            </style>
                            @if($notificationCount > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill" 
                                      style="background-color: #FF5722; color: white; font-weight: bold; font-size: 0.85em; box-shadow: 0 2px 5px rgba(0,0,0,0.3); padding: 0.35em 0.65em; border: 2px solid white;">
                                    {{ $notificationCount > 9 ? '9+' : $notificationCount }}
                                    <span class="visually-hidden">unread notifications</span>
                                </span>
                            @endif
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end px-2 py-3 me-sm-n4 notification-dropdown"
                        aria-labelledby="dropdownMenuButton" 
                        style="min-width: 320px; box-shadow: 0 5px 15px rgba(0,0,0,0.2); border: 1px solid rgba(0,0,0,0.1);">
                        @if($notificationCount > 0)
                            <li class="dropdown-header text-center mb-2" style="border-bottom: 1px solid #eee; padding-bottom: 8px;">
                                <h6 class="text-sm font-weight-bold mb-0" style="color: #344767;">Notifications</h6>
                            </li>
                            @foreach($unreadNotifications as $notification)
                        <li class="mb-2">
                                    <a class="dropdown-item border-radius-md {{ $notification->read_at ? '' : 'bg-gradient-light' }}" 
                                       href="{{ route('events.show', $notification->data['event_id']) }}"
                                       style="transition: all 0.2s ease; border-left: 3px solid #FF5722;">
                                <div class="d-flex py-1">
                                    <div class="my-auto">
                                                <div class="avatar avatar-sm border-radius-sm bg-gradient-danger d-flex align-items-center justify-content-center me-3" 
                                                     style="box-shadow: 0 3px 5px rgba(255, 87, 34, 0.3);">
                                                    <i class="fas fa-exclamation-triangle text-white"></i>
                                                </div>
                                    </div>
                                    <div class="d-flex flex-column justify-content-center">
                                                <h6 class="text-sm font-weight-bold mb-1" style="color: #344767;">
                                                    <span style="color: #FF5722;">Événement à risque:</span> {{ $notification->data['event_title'] }}
                                        </h6>
                                                <p class="text-xs mb-0 d-flex align-items-center" style="color: #67748e;">
                                            <i class="fa fa-clock opacity-6 me-1"></i>
                                                    {{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>
                            </a>
                        </li>
                            @endforeach
                            <li class="mt-3 text-center">
                                <a href="{{ route('notifications.markAllAsRead') }}" class="btn btn-sm w-100" 
                                   style="background-color: #FF5722; color: white; font-weight: 500; box-shadow: 0 3px 5px rgba(255, 87, 34, 0.3);">
                                    <i class="fas fa-check-double me-1"></i> Marquer tout comme lu
                            </a>
                        </li>
                        @else
                            <li class="text-center py-3">
                                <div class="d-flex flex-column align-items-center">
                                    <div class="avatar avatar-lg bg-light rounded-circle mb-2 d-flex align-items-center justify-content-center">
                                        <i class="fas fa-bell-slash text-secondary opacity-6" style="font-size: 1.5rem;"></i>
                                    </div>
                                    <p class="text-sm font-weight-bold mb-0" style="color: #67748e;">Aucune notification</p>
                                </div>
                        </li>
                        @endif
                    </ul>
                </li>
                <li class="nav-item ps-2 d-flex align-items-center">
                    <a href="{{ route('profile.edit') }}" class="nav-link text-body p-0" title="Edit Profile">
                        @auth
                            <div class="avatar avatar-sm bg-gradient-primary d-flex align-items-center justify-content-center rounded-circle">
                                <span class="text-white font-weight-bold">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                            </div>
                        @else
                            <img src="../assets/img/team-2.jpg" class="avatar avatar-sm" alt="avatar" />
                        @endauth
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<!-- End Navbar -->
