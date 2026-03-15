@extends('layouts.app')

@section('title', 'Register - HRMS Pro')

@section('content')
<div class="container-fluid min-vh-100 d-flex align-items-center justify-content-center bg-gradient-premium py-5">
    <div class="row w-100 justify-content-center">
        <div class="col-md-8 col-lg-6 col-xl-5">
            <div class="card glass-card border-0 shadow-lg overflow-hidden rounded-4">
                <div class="card-body p-4 p-md-5">
                    <div class="text-center mb-4">
                        <div class="brand-icon mx-auto mb-3 bg-primary text-white d-flex align-items-center justify-content-center rounded-circle" style="width: 64px; height: 64px;">
                            <i class="bi bi-person-plus-fill fs-2"></i>
                        </div>
                        <h2 class="fw-bold text-dark">Create Account</h2>
                        <p class="text-muted">Join HRMS Pro today</p>
                    </div>

                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="form-floating mb-3">
                            <input id="name" type="text" class="form-control premium-input @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="John Doe">
                            <label for="name">Full Name</label>
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-floating mb-3">
                            <input id="email" type="email" class="form-control premium-input @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="name@example.com">
                            <label for="email">Email Address</label>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input id="password" type="password" class="form-control premium-input @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="Password">
                                    <label for="password">Password</label>
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating mb-4">
                                    <input id="password-confirm" type="password" class="form-control premium-input" name="password_confirmation" required autocomplete="new-password" placeholder="Confirm Password">
                                    <label for="password-confirm">Confirm Password</label>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid mb-4 mt-2">
                            <button type="submit" class="btn btn-primary btn-lg rounded-pill fw-bold premium-btn">
                                Register Account <i class="bi bi-person-plus ms-2"></i>
                            </button>
                        </div>
                        
                        <p class="text-center text-muted mb-0">
                            Already have an account? <a href="{{ route('login') }}" class="text-primary fw-semibold text-decoration-none">Sign in here</a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
