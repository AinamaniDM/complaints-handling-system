@extends('layouts.app')
@section('title','Register')
@section('content')
<div class="auth-wrapper">
  <div class="auth-card">
    <div class="logo-area">
      <div class="badge-org">UTAMU — Complaints System</div>
      <h2 class="fw-bold fs-4 mt-2">Create Account</h2>
      <p class="text-muted small">Register to submit and track your complaints</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
      @csrf
      <div class="mb-3">
        <label class="form-label">Full Name</label>
        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
               value="{{ old('name') }}" placeholder="e.g. John Doe" required>
        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      <div class="mb-3">
        <label class="form-label">Email Address</label>
        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
               value="{{ old('email') }}" placeholder="e.g. john@mail.com" required>
        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
               placeholder="Minimum 6 characters" required>
        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      <div class="mb-4">
        <label class="form-label">Confirm Password</label>
        <input type="password" name="password_confirmation" class="form-control"
               placeholder="Repeat your password" required>
      </div>
      <button type="submit" class="btn btn-primary w-100 py-2">
        <i class="fas fa-user-plus me-2"></i>Create Account
      </button>
    </form>

    <hr class="my-4">
    <p class="text-center text-muted small mb-0">
      Already have an account?
      <a href="{{ route('login') }}" class="text-decoration-none fw-semibold">Sign in</a>
    </p>
  </div>
</div>
@endsection
