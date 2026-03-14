@extends('layouts.app')
@section('title','Complaint #' . $complaint->id)
@section('page-title','Complaint Details')
@section('content')

<div class="row justify-content-center">
  <div class="col-lg-8">
    <div class="content-card">
      <div class="card-header-custom">
        <h5><i class="fas fa-file-alt me-2 text-primary"></i>Complaint #{{ $complaint->id }}</h5>
        <div class="d-flex gap-2">
          @if(auth()->user()->isAdmin())
          <a href="{{ route('admin.complaints.edit', $complaint) }}" class="btn btn-sm btn-primary">
            <i class="fas fa-edit me-1"></i>Update Status
          </a>
          <a href="{{ route('admin.complaints.index') }}" class="btn btn-sm btn-outline-secondary">Back</a>
          @else
          <a href="{{ route('user.dashboard') }}" class="btn btn-sm btn-outline-secondary">Back</a>
          @endif
        </div>
      </div>
      <div class="p-4">
        <div class="row g-4">
          <div class="col-md-6">
            <div class="text-muted small fw-semibold text-uppercase mb-1">Submitted By</div>
            <div class="fw-semibold">{{ $complaint->user->name }}</div>
            <div class="text-muted small">{{ $complaint->user->email }}</div>
          </div>
          <div class="col-md-6">
            <div class="text-muted small fw-semibold text-uppercase mb-1">Category</div>
            <span class="badge bg-light text-dark border">{{ $complaint->category }}</span>
          </div>
          <div class="col-md-6">
            <div class="text-muted small fw-semibold text-uppercase mb-1">Status</div>
            <span class="status-badge {{ $complaint->statusBadgeClass() }}">{{ $complaint->status }}</span>
          </div>
          <div class="col-md-6">
            <div class="text-muted small fw-semibold text-uppercase mb-1">Date Submitted</div>
            <div>{{ $complaint->created_at->format('d M Y, H:i') }}</div>
          </div>
          <div class="col-12">
            <div class="text-muted small fw-semibold text-uppercase mb-2">Description</div>
            <div class="bg-light p-3 rounded" style="white-space:pre-wrap;line-height:1.7;">{{ $complaint->description }}</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
