<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'HRMS Pro') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body class="antialiased bg-gradient-premium min-vh-100">
    <nav class="navbar navbar-expand-md navbar-dark bg-transparent py-4 position-absolute w-100 z-3">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center fw-bold fs-4 text-white" href="{{ url('/') }}">
                <i class="bi bi-buildings-fill me-2 fs-3 text-white"></i> HRMS Pro
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto gap-3">
                    @if (Route::has('login'))
                        @auth
                            <li class="nav-item">
                                <a href="{{ url('/home') }}" class="btn btn-light rounded-pill px-4 fw-semibold shadow-sm premium-btn-light">Dashboard</a>
                            </li>
                        @else
                            <li class="nav-item">
                                <a href="{{ route('login') }}" class="nav-link text-white fw-medium px-3">Log in</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a href="{{ route('register') }}" class="btn btn-light rounded-pill px-4 fw-semibold shadow-sm premium-btn-light">Register</a>
                                </li>
                            @endif
                        @endauth
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <div class="container min-vh-100 d-flex flex-column justify-content-center position-relative z-2">
        <div class="row align-items-center">
            <div class="col-lg-7 text-center text-lg-start pe-lg-5 mb-5 mb-lg-0 pt-5 mt-5 mt-lg-0">
                <div class="badge bg-white text-primary rounded-pill px-3 py-2 mb-4 shadow-sm fw-semibold animate-fade-in-up">
                    <i class="bi bi-stars me-1"></i> The Next Generation HRMS
                </div>
                <h1 class="display-3 fw-bolder text-white mb-4 lh-tight animate-fade-in-up" style="animation-delay: 0.1s;">
                    Manage your workforce with <span class="text-transparent bg-clip-text bg-gradient-text">precision</span>
                </h1>
                <p class="lead text-white-50 mb-5 fs-4 animate-fade-in-up" style="animation-delay: 0.2s;">
                    Everything you need to onboard, manage, and engage your team in one unified, beautiful platform.
                </p>
                <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center justify-content-lg-start animate-fade-in-up" style="animation-delay: 0.3s;">
                    @auth
                        <a href="{{ url('/home') }}" class="btn btn-light btn-lg rounded-pill px-5 fw-bold shadow-lg premium-btn-light">
                            Go to Dashboard <i class="bi bi-arrow-right ms-2"></i>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-light btn-lg rounded-pill px-5 fw-bold shadow-lg premium-btn-light">
                            Get Started <i class="bi bi-arrow-right ms-2"></i>
                        </a>
                        <a href="#" class="btn btn-outline-light btn-lg rounded-pill px-5 fw-bold">
                            Learn More
                        </a>
                    @endauth
                </div>
            </div>
            
            <div class="col-lg-5 d-none d-lg-block animate-fade-in">
                <div class="position-relative">
                    <div class="glass-card rounded-4 p-4 shadow-lg border-0 transform-tilt">
                        <div class="d-flex align-items-center gap-3 mb-4">
                            <img src="https://ui-avatars.com/api/?name=Super+Admin&background=random" class="rounded-circle" width="50" height="50">
                            <div>
                                <h5 class="mb-0 text-dark fw-bold">Super Admin</h5>
                                <small class="text-muted">Welcome back!</small>
                            </div>
                        </div>
                        
                        <div class="row g-3 mb-4">
                            <div class="col-6">
                                <div class="bg-primary bg-opacity-10 rounded-3 p-3 text-center">
                                    <h3 class="fw-bold text-primary mb-1">124</h3>
                                    <small class="text-muted fw-semibold">Employees</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="bg-success bg-opacity-10 rounded-3 p-3 text-center">
                                    <h3 class="fw-bold text-success mb-1">18</h3>
                                    <small class="text-muted fw-semibold">On Leave</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-light rounded-3 p-3 d-flex align-items-center justify-content-between mb-2">
                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-white p-2 rounded-2 shadow-sm text-primary"><i class="bi bi-check2-circle"></i></div>
                                <div class="fw-medium text-dark">Q3 Reviews</div>
                            </div>
                            <span class="badge bg-warning text-dark">Pending</span>
                        </div>
                        
                        <div class="bg-light rounded-3 p-3 d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-white p-2 rounded-2 shadow-sm text-success"><i class="bi bi-calendar-event"></i></div>
                                <div class="fw-medium text-dark">Team Standup</div>
                            </div>
                            <span class="text-muted small">10:00 AM</span>
                        </div>
                    </div>
                    
                    <!-- Decorative elements -->
                    <div class="position-absolute bg-primary rounded-circle blur-blob" style="width: 150px; height: 150px; top: -30px; right: -30px; filter: blur(40px); z-index: -1; opacity: 0.6;"></div>
                    <div class="position-absolute bg-info rounded-circle blur-blob" style="width: 200px; height: 200px; bottom: -50px; left: -50px; filter: blur(50px); z-index: -1; opacity: 0.5;"></div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Animated background shapes -->
    <div class="position-fixed top-0 start-0 w-100 h-100 overflow-hidden" style="z-index: -1;">
        <div class="position-absolute bg-white rounded-circle opacity-10 float-anim-1" style="width: 300px; height: 300px; top: 10%; left: -5%;"></div>
        <div class="position-absolute bg-white rounded-circle opacity-10 float-anim-2" style="width: 400px; height: 400px; bottom: -10%; right: -10%;"></div>
    </div>
</body>
</html>
