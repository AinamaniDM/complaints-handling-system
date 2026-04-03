@extends('layouts.app')
@section('title','Login')
@section('content')
<div class="auth-wrapper">
  <div class="auth-card">
    <div style="text-align:center;margin-bottom:26px;">
  <img src="{{ asset('images/utamulogo.jpg') }}" alt="UTAMU Logo" style="height:70px;margin-bottom:12px;">
  <h2 class="fw-bold fs-4 mt-2">Welcome Back</h2>
  <p class="text-muted small">Sign in to your account</p>
</div>
      

    @if($errors->any())
    <div class="alert alert-danger py-2 small mb-3">
      <i class="fas fa-exclamation-circle me-1"></i>{{ $errors->first() }}
    </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
      @csrf
      <div class="mb-3">
        <label class="form-label">Email Address</label>
        <input type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus>
      </div>
      <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control" required>
      </div>
      <div class="mb-4 form-check">
        <input type="checkbox" name="remember" class="form-check-input" id="remember">
        <label class="form-check-label small" for="remember">Remember me</label>
      </div>
      <button type="submit" class="btn btn-primary w-100 py-2">
        <i class="fas fa-sign-in-alt me-2"></i>Sign In
      </button>
    </form>

    <hr class="my-4">
    <p class="text-center text-muted small mb-0">
      Don't have an account?
      <a href="{{ route('register') }}" class="text-decoration-none fw-semibold">Register here</a>
    </p>
  </div>
</div>
@endsection
