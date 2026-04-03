@extends('layouts.app')
@section('title','All Complaints')
@section('page-title','Complaints Management')
@section('content')

<div class="row g-2 mb-4">
  @foreach([['total','Total','#1e293b'],['pending','Pending','#f59e0b'],['in_progress','In Progress','#3b82f6'],['resolved','Resolved','#10b981']] as [$key,$label,$color])
  <div class="col-6 col-md-3">
    <div class="content-card p-3 d-flex align-items-center gap-3">
      <div>
        <div class="fw-bold fs-5" style="color:{{ $color }}">{{ $stats[$key] }}</div>
        <div class="text-muted small">{{ $label }}</div>
      </div>
    </div>
  </div>
  @endforeach
</div>

<div class="content-card">
  <div class="card-header-custom">
    <h5><i class="fas fa-list-alt me-2 text-primary"></i>All Complaints</h5>
    {{-- NEW: Export buttons --}}
    <div class="d-flex gap-2">
      <a href="{{ route('admin.complaints.export.csv') }}" class="btn btn-sm btn-outline-success">
        <i class="fas fa-file-csv me-1"></i>Export CSV
      </a>
      <a href="{{ route('admin.complaints.export.pdf') }}" class="btn btn-sm btn-outline-danger">
        <i class="fas fa-file-pdf me-1"></i>Export PDF
      </a>
    </div>
  </div>

  {{-- Search, Status filter, NEW: Category filter --}}
  <div class="p-3 border-bottom bg-light">
    <form method="GET" action="{{ route('admin.complaints.index') }}" class="row g-2 align-items-end">
      <div class="col-md-4">
        <label class="form-label mb-1 small fw-semibold">Search</label>
        <input type="text" name="search" class="form-control form-control-sm"
               placeholder="Name, email, description…" value="{{ request('search') }}">
      </div>
      <div class="col-md-3">
        <label class="form-label mb-1 small fw-semibold">Status</label>
        <select name="status" class="form-select form-select-sm">
          <option value="">All Statuses</option>
          @foreach($statuses as $s)
          <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ $s }}</option>
          @endforeach
        </select>
      </div>
      {{-- NEW: Category filter --}}
      <div class="col-md-3">
        <label class="form-label mb-1 small fw-semibold">Category</label>
        <select name="category" class="form-select form-select-sm">
          <option value="">All Categories</option>
          @foreach($categories as $cat)
          <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
            {{ $cat->name }}
          </option>
          @endforeach
        </select>
      </div>
      <div class="col-md-2 d-flex gap-2">
        <button type="submit" class="btn btn-sm btn-primary flex-fill">
          <i class="fas fa-filter me-1"></i>Apply
        </button>
        <a href="{{ route('admin.complaints.index') }}" class="btn btn-sm btn-outline-secondary flex-fill">
          Clear
        </a>
      </div>
    </form>
  </div>

  <div class="table-responsive">
    <table class="table table-hover mb-0">
      <thead>
        <tr>
          <th>#</th>
          <th>User</th>
          <th>Category</th>
          <th>Description</th>
          {{-- NEW: Attachment column --}}
          <th>Attach</th>
          <th>Status</th>
          <th>Date</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($complaints as $complaint)
        <tr>
          <td class="text-muted small">{{ $complaint->id }}</td>
          <td>
            <div class="fw-semibold small">{{ $complaint->user->name }}</div>
            <div class="text-muted" style="font-size:.75rem;">{{ $complaint->user->email }}</div>
          </td>
          <td>
            <span class="badge bg-light text-dark border">{{ $complaint->category->name }}</span>
          </td>
          <td>
            <span class="d-inline-block text-truncate" style="max-width:180px;"
                  title="{{ $complaint->description }}">{{ $complaint->description }}</span>
          </td>
          {{-- NEW: Attachment icon --}}
          <td>
            @if($complaint->attachment)
              @if($complaint->isImage())  <i class="fas fa-image text-success" title="Image"></i>
              @elseif($complaint->isPdf()) <i class="fas fa-file-pdf text-danger" title="PDF"></i>
              @elseif($complaint->isAudio()) <i class="fas fa-music text-primary" title="Audio"></i>
              @elseif($complaint->isVideo()) <i class="fas fa-video text-warning" title="Video"></i>
              @endif
            @else
              <span class="text-muted">—</span>
            @endif
          </td>
          <td>
            <span class="status-badge {{ $complaint->statusBadgeClass() }}">
              {{ $complaint->status }}
            </span>
          </td>
          <td class="text-muted small">{{ $complaint->created_at->format('d M Y') }}</td>
          <td>
            <div class="d-flex gap-1">
              <a href="{{ route('admin.complaints.show', $complaint) }}"
                 class="btn btn-sm btn-outline-secondary" title="View">
                <i class="fas fa-eye"></i>
              </a>
              <a href="{{ route('admin.complaints.edit', $complaint) }}"
                 class="btn btn-sm btn-outline-primary" title="Update Status">
                <i class="fas fa-edit"></i>
              </a>
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="8" class="text-center text-muted py-5">
            <i class="fas fa-inbox fs-3 d-block mb-2 opacity-50"></i>
            No complaints found.
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
