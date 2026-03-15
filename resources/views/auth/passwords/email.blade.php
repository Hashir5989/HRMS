@extends('layouts.app')

@section('title', 'Forgot Password - HRMS Pro')

@section('content')
<div class="container-fluid min-vh-100 d-flex align-items-center justify-content-center bg-gradient-premium py-5">
    <div class="row w-100 justify-content-center">
        <div class="col-md-6 col-lg-5 col-xl-4">
            <div class="card glass-card border-0 shadow-lg overflow-hidden rounded-4">
                <div class="card-body p-4 p-md-5">
                    <div class="text-center mb-4">
                        <div class="brand-icon mx-auto mb-3 bg-primary text-white d-flex align-items-center justify-content-center rounded-circle" style="width: 64px; height: 64px;">
                            <i class="bi bi-shield-lock-fill fs-2"></i>
                        </div>
                        <h2 class="fw-bold text-dark">Forgot Password?</h2>
                        <p class="text-muted small">Enter your email address and we'll send you a link to reset your password.</p>
                    </div>

                    @if (session('status'))
                        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 mb-4" role="alert">
                            <i class="bi bi-check-circle-fill me-2"></i> {{ session('status') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="form-floating mb-4">
                            <input id="email" type="email" class="form-control premium-input @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="name@example.com">
                            <label for="email">Email Address</label>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="d-grid mb-4">
                            <button type="submit" class="btn btn-primary btn-lg rounded-pill fw-bold premium-btn">
                                Send Reset Link <i class="bi bi-send ms-2"></i>
                            </button>
                        </div>

                        <div class="text-center">
                            <a href="{{ route('login') }}" class="text-decoration-none text-muted small fw-semibold">
                                <i class="bi bi-arrow-left me-1"></i> Back to Login
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
