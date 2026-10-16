@extends('layouts.app')

@section('title', 'Login - RKVIT REALESTATE PRIVATE LIMITED')

@section('content')
<div class="auth-wrapper">
    <div class="auth-card">
        <div class="auth-header">
            <div class="auth-logo">R</div>
            <h2>Welcome Back</h2>
            <p>Login to your RKVIT Realestate account</p>
        </div>

        {{-- Session Status --}}
        @if (session('status'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> {{ session('status') }}
        </div>
        @endif

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

        <form method="POST" action="{{ route('login') }}">
            @csrf

            {{-- Email --}}
            <div class="form-group">
                <label for="email">Email Address</label>
                <div class="input-group">
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        class="form-control @error('email') is-invalid @enderror"
                        placeholder="you@example.com"
                        required
                        autofocus
                        autocomplete="username">
                </div>
                @error('email')
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
                        placeholder="Enter your password"
                        required
                        autocomplete="current-password">
                    <i class="fas fa-eye input-icon toggle-password" data-target="#password"></i>
                </div>
                @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Remember & Forgot --}}
            <div class="form-row">
                <div class="checkbox-group">
                    <input type="checkbox" id="remember_me" name="remember">
                    <label for="remember_me">Remember me</label>
                </div>
                @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="link-primary">Forgot Password?</a>
                @endif
            </div>

            {{-- Submit --}}
            <button type="submit" class="btn-primary">
                <i class="fas fa-sign-in-alt"></i> Sign In
            </button>
        </form>

        <div class="divider">New to RKVIT?</div>

        <div class="auth-footer">
            Don't have an account?
            <a href="{{ route('register') }}">Create an account</a>
        </div>
    </div>
</div>
@endsection