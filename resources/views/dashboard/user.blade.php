@extends('layouts.app')
@section('title','My Complaints')
@section('page-title','My Complaints')
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
        <div class="stat-label">Total</div>
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
    <h5><i class="fas fa-list-alt me-2 text-primary"></i>My Submitted Complaints</h5>
    <a href="{{ route('complaints.create') }}" class="btn btn-sm btn-primary">
      <i class="fas fa-plus me-1"></i>New Complaint
    </a>
  </div>

  <div class="table-responsive">
    <table class="table table-hover mb-0">
      <thead>
        <tr>
          <th>#</th>
          <th>Category</th>
          <th>Description</th>
          <th>Status</th>
          <th>Submitted</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($complaints as $complaint)
        <tr>
          <td class="text-muted small">{{ $complaint->id }}</td>
          <td><span class="badge bg-light text-dark border">{{ $complaint->category->name }}</span></td>
          <td>
            <span class="d-inline-block text-truncate" style="max-width:260px;" title="{{ $complaint->description }}">
              {{ $complaint->description }}
            </span>
          </td>
          <td>
            <span class="status-badge {{ $complaint->statusBadgeClass() }}">
              {{ $complaint->status }}
            </span>
          </td>
          <td class="text-muted small">{{ $complaint->created_at->format('d M Y') }}</td>
          <td>
            <div class="d-flex gap-1">
              <a href="{{ route('complaints.show', $complaint) }}" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-eye"></i>
              </a>
              <form method="POST" action="{{ route('complaints.destroy', $complaint) }}"
                    onsubmit="return confirm('Are you sure you want to delete this complaint?')">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-outline-danger" title="Delete">
                  <i class="fas fa-trash"></i>
                </button>
              </form>
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="6" class="text-center text-muted py-5">
            <i class="fas fa-inbox fs-3 d-block mb-2 opacity-50"></i>
            You have not submitted any complaints yet.
            <a href="{{ route('complaints.create') }}" class="d-block mt-2 text-primary">Submit your first complaint</a>
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  @if($complaints->hasPages())
  <div class="p-3 border-top">{{ $complaints->links() }}</div>
  @endif
</div>
@endsection