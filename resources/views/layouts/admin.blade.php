<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'HRMS Dashboard')</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <!-- Styles -->
    @vite(['resources/sass/app.scss', 'resources/css/admin.css', 'resources/js/app.js'])
    
    @stack('styles')
</head>
<body class="bg-light">
    
    <div class="d-flex w-100 vh-100 overflow-hidden">
        
        <!-- Sidebar -->
        <aside class="sidebar bg-white shadow-sm d-flex flex-column" id="sidebar">
            <div class="sidebar-header p-3 border-bottom d-flex align-items-center justify-content-between">
                <a href="{{ route('home') }}" class="text-decoration-none text-dark fs-5 fw-bold d-flex align-items-center gap-2">
                    <div class="bg-primary text-white rounded p-1 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                        <i class="bi bi-buildings"></i>
                    </div>
                    <span class="brand-text">HRMS Pro</span>
                </a>
                <button class="btn btn-sm d-md-none" id="sidebarClose">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            
            <div class="sidebar-menu flex-grow-1 overflow-auto p-2">
                <p class="text-muted small fw-semibold text-uppercase px-3 mb-1 mt-2" style="font-size:0.65rem; letter-spacing:0.08em;">Main</p>
                <ul class="nav flex-column gap-1">
                    <li class="nav-item">
                        <a href="{{ route('home') }}" class="nav-link text-dark rounded px-3 py-2 d-flex align-items-center gap-3 {{ request()->routeIs('home') ? 'active' : '' }}">
                            <i class="bi bi-grid fs-5"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>

                    @can('employee.view')
                    <li class="nav-item">
                        <a href="{{ route('employees.index') }}" class="nav-link text-dark rounded px-3 py-2 d-flex align-items-center gap-3 {{ request()->routeIs('employees.*') ? 'active' : '' }}">
                            <i class="bi bi-people fs-5"></i>
                            <span>Employees</span>
                        </a>
                    </li>
                    @endcan

                    @can('department.manage')
                    <li class="nav-item">
                        <a href="{{ route('departments.index') }}" class="nav-link text-dark rounded px-3 py-2 d-flex align-items-center gap-3 {{ request()->routeIs('departments.*') ? 'active' : '' }}">
                            <i class="bi bi-diagram-3 fs-5"></i>
                            <span>Departments</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('designations.index') }}" class="nav-link text-dark rounded px-3 py-2 d-flex align-items-center gap-3 {{ request()->routeIs('designations.*') ? 'active' : '' }}">
                            <i class="bi bi-award fs-5"></i>
                            <span>Designations</span>
                        </a>
                    </li>
                    @endcan
                </ul>

                <p class="text-muted small fw-semibold text-uppercase px-3 mb-1 mt-3" style="font-size:0.65rem; letter-spacing:0.08em;">Work</p>
                <ul class="nav flex-column gap-1">
                    @can('project.view')
                    <li class="nav-item">
                        <a href="{{ route('projects.index') }}" class="nav-link text-dark rounded px-3 py-2 d-flex align-items-center gap-3 {{ request()->routeIs('projects.*') ? 'active' : '' }}">
                            <i class="bi bi-kanban fs-5"></i>
                            <span>Projects</span>
                        </a>
                    </li>
                    @endcan

                    @can('task.view')
                    <li class="nav-item">
                        <a href="{{ route('tasks.index') }}" class="nav-link text-dark rounded px-3 py-2 d-flex align-items-center gap-3 {{ request()->routeIs('tasks.*') ? 'active' : '' }}">
                            <i class="bi bi-check2-square fs-5"></i>
                            <span>Tasks</span>
                        </a>
                    </li>
                    @endcan

                    <li class="nav-item">
                        <a href="{{ route('teams.index') }}" class="nav-link text-dark rounded px-3 py-2 d-flex align-items-center gap-3 {{ request()->routeIs('teams.*') ? 'active' : '' }}">
                            <i class="bi bi-people fs-5"></i>
                            <span>Teams</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('meetings.index') }}" class="nav-link text-dark rounded px-3 py-2 d-flex align-items-center gap-3 {{ request()->routeIs('meetings.*') ? 'active' : '' }}">
                            <i class="bi bi-calendar-video fs-5"></i>
                            <span>Meetings</span>
                        </a>
                    </li>

                    @can('file.view')
                    <li class="nav-item">
                        <a href="{{ route('files.index') }}" class="nav-link text-dark rounded px-3 py-2 d-flex align-items-center gap-3 {{ request()->routeIs('files.*') ? 'active' : '' }}">
                            <i class="bi bi-folder2-open fs-5"></i>
                            <span>Files</span>
                        </a>
                    </li>
                    @endcan
                </ul>

                <p class="text-muted small fw-semibold text-uppercase px-3 mb-1 mt-3" style="font-size:0.65rem; letter-spacing:0.08em;">HR</p>
                <ul class="nav flex-column gap-1">
                    @can('attendance.view')
                    <li class="nav-item">
                        <a href="{{ route('attendance.index') }}" class="nav-link text-dark rounded px-3 py-2 d-flex align-items-center gap-3 {{ request()->routeIs('attendance.*') ? 'active' : '' }}">
                            <i class="bi bi-clock-history fs-5"></i>
                            <span>Attendance</span>
                        </a>
                    </li>
                    @endcan

                    @can('leave.view')
                    <li class="nav-item">
                        <a href="{{ route('leaves.index') }}" class="nav-link text-dark rounded px-3 py-2 d-flex align-items-center gap-3 {{ request()->routeIs('leaves.*') ? 'active' : '' }}">
                            <i class="bi bi-calendar-event fs-5"></i>
                            <span>Leave</span>
                        </a>
                    </li>
                    @endcan

                    <li class="nav-item">
                        <a href="{{ route('holidays.index') }}" class="nav-link text-dark rounded px-3 py-2 d-flex align-items-center gap-3 {{ request()->routeIs('holidays.*') ? 'active' : '' }}">
                            <i class="bi bi-calendar-check fs-5"></i>
                            <span>Holidays</span>
                        </a>
                    </li>
                </ul>
            </div>
            
            <div class="sidebar-footer p-3 border-top">
                <div class="dropdown">
                    <a href="#" class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle" id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=random" alt="" width="32" height="32" class="rounded-circle me-2">
                        <div class="d-flex flex-column lh-1">
                            <strong>{{ Auth::user()->name }}</strong>
                            <small class="text-muted">{{ Auth::user()->roles->pluck('name')->first() ?? 'User' }}</small>
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser">
                        <li><a class="dropdown-item" href="#">Profile</a></li>
                        <li><a class="dropdown-item" href="#">Settings</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item">Sign out</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </aside>
        
        <!-- Main Content -->
        <main class="main-content flex-grow-1 d-flex flex-column h-100 overflow-hidden">
            <!-- Topbar -->
            <header class="topbar bg-white shadow-sm px-3 py-2 d-flex align-items-center justify-content-between z-index-1">
                <div class="d-flex align-items-center gap-3">
                    <button class="btn btn-light d-md-none" id="sidebarToggle">
                        <i class="bi bi-list fs-4"></i>
                    </button>
                    
                    <div class="search-bar d-none d-md-flex align-items-center bg-light rounded px-3 py-2">
                        <i class="bi bi-search text-muted"></i>
                        <input type="text" class="border-0 bg-transparent ms-2 shadow-none focus-ring focus-ring-light" placeholder="Search across system..." style="width: 300px; outline: none;">
                    </div>
                </div>
                
                <div class="d-flex align-items-center gap-2 gap-md-4">
                    <div class="d-flex align-items-center gap-3">
                        <a href="#" class="text-dark position-relative">
                            <i class="bi bi-bell fs-5"></i>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem;">
                                3
                            </span>
                        </a>
                        <a href="#" class="text-dark position-relative">
                            <i class="bi bi-chat fs-5"></i>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-primary" style="font-size: 0.6rem;">
                                5
                            </span>
                        </a>
                        <div class="vr mx-1"></div>
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-link text-danger p-0 text-decoration-none d-flex align-items-center gap-2" title="Sign out">
                                <i class="bi bi-box-arrow-right fs-5"></i>
                                <span class="d-none d-md-inline fw-semibold">Sign out</span>
                            </button>
                        </form>
                    </div>
                </div>
            </header>
            
            <!-- Content -->
            <div class="content-body flex-grow-1 overflow-auto p-3 p-md-4 bg-light">

                {{-- Global Flash Messages --}}
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 mb-3" role="alert" id="flash-success">
                    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif
                @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 mb-3" role="alert" id="flash-error">
                    <i class="bi bi-exclamation-circle-fill me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif
                @if(session('warning'))
                <div class="alert alert-warning alert-dismissible fade show shadow-sm border-0 mb-3" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('warning') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif
                @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 mb-3" role="alert">
                    <i class="bi bi-exclamation-circle-fill me-2"></i><strong>Please fix the following errors:</strong>
                    <ul class="mb-0 mt-1">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebarClose = document.getElementById('sidebarClose');

            if(sidebarToggle) {
                sidebarToggle.addEventListener('click', () => {
                    sidebar.classList.add('show');
                });
            }

            if(sidebarClose) {
                sidebarClose.addEventListener('click', () => {
                    sidebar.classList.remove('show');
                });
            }

            // Auto-dismiss flash messages after 4 seconds
            setTimeout(function() {
                document.querySelectorAll('.alert.show').forEach(function(el) {
                    var bsAlert = bootstrap.Alert.getOrCreateInstance(el);
                    bsAlert.close();
                });
            }, 4000);
        });
    </script>
    
    @stack('scripts')
</body>
</html>
