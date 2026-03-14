@extends('layouts.app')
@section('title','Admin Dashboard')
@section('page-title','Admin Dashboard')
@section('content')

<style>
  .stat-card.in-progress { border-left: 4px solid #3b82f6; }
  .stat-card.in-progress .icon-wrap { background: #eff6ff; color: #3b82f6; }
</style>

<div class="row g-3 mb-4">

  <div class="col-sm-6 col-xl-3">
    <div class="stat-card total">
      <div class="icon-wrap"><i class="fas fa-clipboard-list"></i></div>
      <div>
        <div class="stat-value" style="color:#1e293b;">{{ $stats['total'] }}</div>
        <div class="stat-label">Total Complaints</div>
      </div>
    </div>
  </div>

  <div class="col-sm-6 col-xl-3">
    <div class="stat-card pending">
      <div class="icon-wrap"><i class="fas fa-clock"></i></div>
      <div>
        <div class="stat-value" style="color:#f59e0b;">{{ $stats['pending'] }}</div>
        <div class="stat-label">Pending</div>
      </div>
    </div>
  </div>

  <div class="col-sm-6 col-xl-3">
    <div class="stat-card in-progress">
      <div class="icon-wrap"><i class="fas fa-rotate"></i></div>
      <div>
        <div class="stat-value" style="color:#3b82f6;">{{ $stats['in_progress'] }}</div>
        <div class="stat-label">In Progress</div>
      </div>
    </div>
  </div>

  <div class="col-sm-6 col-xl-3">
    <div class="stat-card resolved">
      <div class="icon-wrap"><i class="fas fa-check-circle"></i></div>
      <div>
        <div class="stat-value" style="color:#10b981;">{{ $stats['resolved'] }}</div>
        <div class="stat-label">Resolved</div>
      </div>
    </div>
  </div>

</div>

<div class="content-card">
  <div class="card-header-custom">
    <h5><i class="fas fa-history me-2 text-primary"></i>Recent Complaints</h5>
    <a href="{{ route('admin.complaints.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
  </div>
  <div class="table-responsive">
    <table class="table table-hover mb-0">
      <thead>
        <tr>
          <th>#</th><th>User</th><th>Category</th><th>Description</th><th>Status</th><th>Date</th><th>Action</th>
        </tr>
      </thead>
      <tbody>
        @forelse($recent as $complaint)
        <tr>
          <td class="text-muted small">{{ $complaint->id }}</td>
          <td>
            <div class="fw-semibold small">{{ $complaint->user->name }}</div>
            <div class="text-muted" style="font-size:.75rem;">{{ $complaint->user->email }}</div>
          </td>
          <td><span class="badge bg-light text-dark border">{{ $complaint->category }}</span></td>
          <td>
            <span class="d-inline-block text-truncate" style="max-width:220px;" title="{{ $complaint->description }}">
              {{ $complaint->description }}
            </span>
          </td>
          <td><span class="status-badge {{ $complaint->statusBadgeClass() }}">{{ $complaint->status }}</span></td>
          <td class="text-muted small">{{ $complaint->created_at->format('d M Y') }}</td>
          <td>
            <a href="{{ route('admin.complaints.show', $complaint) }}" class="btn btn-sm btn-outline-secondary">
              <i class="fas fa-eye"></i>
            </a>
          </td>
        </tr>
        @empty
        <tr><td colspan="7" class="text-center text-muted py-4">No complaints yet.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection