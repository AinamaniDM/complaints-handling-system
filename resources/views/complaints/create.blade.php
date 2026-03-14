@extends('layouts.app')
@section('title','Submit Complaint')
@section('page-title','Submit a Complaint')
@section('content')

<div class="row justify-content-center">
  <div class="col-lg-7">
    <div class="content-card">
      <div class="card-header-custom">
        <h5><i class="fas fa-paper-plane me-2 text-primary"></i>New Complaint</h5>
        <a href="{{ route('user.dashboard') }}" class="btn btn-sm btn-outline-secondary">
          <i class="fas fa-arrow-left me-1"></i>Back
        </a>
      </div>
      <div class="p-4">
        <form method="POST" action="{{ route('complaints.store') }}">
          @csrf
          <div class="mb-3">
            <label class="form-label">Complaint Category</label>
            <select name="category" class="form-select @error('category') is-invalid @enderror" required>
              <option value="">— Select a category —</option>
              @foreach($categories as $cat)
              <option value="{{ $cat }}" {{ old('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
              @endforeach
            </select>
            @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="mb-4">
            <label class="form-label">Complaint Description</label>
            <textarea name="description" rows="6"
              class="form-control @error('description') is-invalid @enderror"
              placeholder="Describe your complaint in detail (minimum 10 characters)…"
              required>{{ old('description') }}</textarea>
            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <button type="submit" class="btn btn-primary w-100 py-2">
            <i class="fas fa-paper-plane me-2"></i>Submit Complaint
          </button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
