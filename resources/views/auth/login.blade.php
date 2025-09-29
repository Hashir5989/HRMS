@extends('layouts.app')

@section('content')
<div class="container-fluid min-vh-100 d-flex align-items-center justify-content-center bg-gradient-premium">
    <div class="row w-100 justify-content-center">
        <div class="col-md-6 col-lg-4">
            <div class="card glass-card border-0 shadow-lg overflow-hidden rounded-4">
                <div class="card-body p-5">
                    <div class="text-center mb-5">
                        <div class="brand-icon mx-auto mb-3 bg-primary text-white d-flex align-items-center justify-content-center rounded-circle" style="width: 64px; height: 64px;">
                            <i class="bi bi-buildings-fill fs-2"></i>
                        </div>
                        <h2 class="fw-bold text-dark">Welcome Back</h2>
                        <p class="text-muted">Sign in to your HRMS Pro account</p>
                    </div>

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="form-floating mb-4 position-relative">
                            <input id="email" type="email" class="form-control premium-input @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="name@example.com">
                            <label for="email">Email Address</label>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-floating mb-4 position-relative">
                            <input id="password" type="password" class="form-control premium-input @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Password">
                            <label for="password">Password</label>
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input custom-switch" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label text-muted" for="remember">
                                    {{ __('Remember Me') }}
                                </label>
                            </div>
                            
                            @if (Route::has('password.request'))
                                <a class="text-primary text-decoration-none fw-semibold fs-7" href="{{ route('password.request') }}">
                                    Forgot Password?
                                </a>
                            @endif
                        </div>

                        <div class="d-grid mb-4">
                            <button type="submit" class="btn btn-primary btn-lg rounded-pill fw-bold premium-btn">
                                Sign In <i class="bi bi-arrow-right ms-2"></i>
                            </button>
                        </div>
                        
                        @if (Route::has('register'))
                            <p class="text-center text-muted mb-0">
                                Don't have an account? <a href="{{ route('register') }}" class="text-primary fw-semibold text-decoration-none">Create an account</a>
                            </p>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
