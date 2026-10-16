@extends('layouts.app')

@section('title', 'Register - RKVIT REALESTATE PRIVATE LIMITED')

@section('content')
<div class="auth-wrapper">
    <div class="auth-card">
        <div class="auth-header">
            <div class="auth-logo">R</div>
            <h2>Create Account</h2>
            <p>Join RKVIT Realestate and find your dream property</p>
        </div>

        {{-- Validation Errors --}}
        @if ($errors->any())
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i>
            <div>
                @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
                @endforeach
            </div>
        </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf

            {{-- Name --}}
            <div class="form-group">
                <label for="name">Full Name</label>
                <input
                    id="name"
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    class="form-control @error('name') is-invalid @enderror"
                    placeholder="John Doe"
                    required
                    autofocus
                    autocomplete="name">
                @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Email --}}
            <div class="form-group">
                <label for="email">Email Address</label>
                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    class="form-control @error('email') is-invalid @enderror"
                    placeholder="you@example.com"
                    required
                    autocomplete="username">
                @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Phone (Optional - extend with your DB) --}}
            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input
                    id="phone"
                    type="tel"
                    name="phone"
                    value="{{ old('phone') }}"
                    class="form-control @error('phone') is-invalid @enderror"
                    placeholder="+91 98765 43210"
                    autocomplete="tel">
                @error('phone')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Password --}}
            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-group">
                    <input
                        id="password"
                        type="password"
                        name="password"
                        class="form-control @error('password') is-invalid @enderror"
                        placeholder="Minimum 8 characters"
                        required
                        autocomplete="new-password">
                    <i class="fas fa-eye input-icon toggle-password" data-target="#password"></i>
                </div>
                @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Confirm Password --}}
            <div class="form-group">
                <label for="password_confirmation">Confirm Password</label>
                <div class="input-group">
                    <input
                        id="password_confirmation"
                        type="password"
                        name="password_confirmation"
                        class="form-control"
                        placeholder="Re-enter your password"
                        required
                        autocomplete="new-password">
                    <i class="fas fa-eye input-icon toggle-password" data-target="#password_confirmation"></i>
                </div>
            </div>

            {{-- Terms --}}
            <div class="form-row" style="margin-bottom: 20px;">
                <div class="checkbox-group">
                    <input type="checkbox" id="terms" name="terms" required>
                    <label for="terms">
                        I agree to the
                        <a href="#" class="link-primary">Terms & Conditions</a>
                    </label>
                </div>
            </div>

            {{-- Submit --}}
            <button type="submit" class="btn-primary">
                <i class="fas fa-user-plus"></i> Create Account
            </button>
        </form>

        <div class="divider">Already registered?</div>

        <div class="auth-footer">
            Already have an account?
            <a href="{{ route('login') }}">Sign in here</a>
        </div>

        <p class="terms-text">
            By creating an account, you agree to receive marketing communications from RKVIT Realestate.
        </p>
    </div>
</div>
@endsection