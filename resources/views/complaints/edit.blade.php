@extends('layouts.app')
@section('title','Update Status')
@section('page-title','Update Complaint Status')
@section('content')

<div class="row justify-content-center">
  <div class="col-lg-6">
    <div class="content-card">
      <div class="card-header-custom">
        <h5><i class="fas fa-edit me-2 text-primary"></i>Update Complaint #{{ $complaint->id }}</h5>
        <a href="{{ route('admin.complaints.index') }}" class="btn btn-sm btn-outline-secondary">
          <i class="fas fa-arrow-left me-1"></i>Back
        </a>
      </div>
      <div class="p-4">
        {{-- Complaint summary --}}
        <div class="bg-light rounded p-3 mb-4">
          <div class="text-muted small mb-1">Submitted by:</div>
          <div class="fw-semibold">{{ $complaint->user->name }}</div>
          <div class="text-muted small">{{ $complaint->user->email }}</div>
          <div class="mt-2 d-flex gap-2 align-items-center flex-wrap">
            <span class="badge bg-light text-dark border">{{ $complaint->category }}</span>
            <span class="status-badge {{ $complaint->statusBadgeClass() }}">{{ $complaint->status }}</span>
          </div>
          <div class="mt-2 text-muted small">{{ Str::limit($complaint->description, 150) }}</div>
        </div>

        <form method="POST" action="{{ route('admin.complaints.update', $complaint) }}">
          @csrf @method('PUT')
          <div class="mb-4">
            <label class="form-label">New Status</label>
            <select name="status" class="form-select @error('status') is-invalid @enderror" required>
              @foreach($statuses as $status)
              <option value="{{ $status }}" {{ $complaint->status == $status ? 'selected' : '' }}>
                {{ $status }}
              </option>
              @endforeach
            </select>
            @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="d-flex gap-3">
            <button type="submit" class="btn btn-primary flex-fill">
              <i class="fas fa-save me-2"></i>Save Changes
            </button>
            <a href="{{ route('admin.complaints.index') }}" class="btn btn-outline-secondary flex-fill">
              Cancel
            </a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
