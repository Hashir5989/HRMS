@extends('layouts.app')

@section('title', 'Reset Password - HRMS Pro')

@section('content')
<div class="container-fluid min-vh-100 d-flex align-items-center justify-content-center bg-gradient-premium py-5">
    <div class="row w-100 justify-content-center">
        <div class="col-md-6 col-lg-5 col-xl-4">
            <div class="card glass-card border-0 shadow-lg overflow-hidden rounded-4">
                <div class="card-body p-4 p-md-5">
                    <div class="text-center mb-4">
                        <div class="brand-icon mx-auto mb-3 bg-primary text-white d-flex align-items-center justify-content-center rounded-circle" style="width: 64px; height: 64px;">
                            <i class="bi bi-key-fill fs-2"></i>
                        </div>
                        <h2 class="fw-bold text-dark">Reset Password</h2>
                        <p class="text-muted small">Choose a new password for your account</p>
                    </div>

                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf

                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="form-floating mb-3">
                            <input id="email" type="email" class="form-control premium-input @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus placeholder="name@example.com">
                            <label for="email">Email Address</label>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-floating mb-3">
                            <input id="password" type="password" class="form-control premium-input @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="New Password">
                            <label for="password">New Password</label>
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-floating mb-4">
                            <input id="password-confirm" type="password" class="form-control premium-input" name="password_confirmation" required autocomplete="new-password" placeholder="Confirm Password">
                            <label for="password-confirm">Confirm Password</label>
                        </div>

                        <div class="d-grid mb-4">
                            <button type="submit" class="btn btn-primary btn-lg rounded-pill fw-bold premium-btn">
                                Update Password <i class="bi bi-check-lg ms-2"></i>
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
