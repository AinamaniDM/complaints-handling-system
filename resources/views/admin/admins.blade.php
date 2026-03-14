@extends('layouts.app')
@section('title','Manage Admins')
@section('page-title','Manage Admins')
@section('content')

<div class="row g-4">
  {{-- Add new admin form --}}
  <div class="col-lg-5">
    <div class="content-card">
      <div class="card-header-custom">
        <h5><i class="fas fa-user-plus me-2 text-primary"></i>Add New Admin</h5>
      </div>
      <div class="p-4">
        <form method="POST" action="{{ route('admin.admins.store') }}">
          @csrf
          <div class="mb-3">
            <label class="form-label">Full Name</label>
            <input type="text" name="name"
                   class="form-control @error('name') is-invalid @enderror"
                   value="{{ old('name') }}" placeholder="e.g. Jane Doe" required>
            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="mb-3">
            <label class="form-label">Email Address</label>
            <input type="email" name="email"
                   class="form-control @error('email') is-invalid @enderror"
                   value="{{ old('email') }}" placeholder="e.g. jane@admin.com" required>
            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password"
                   class="form-control @error('password') is-invalid @enderror"
                   placeholder="Minimum 6 characters" required>
            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="mb-4">
            <label class="form-label">Confirm Password</label>
            <input type="password" name="password_confirmation"
                   class="form-control" placeholder="Repeat password" required>
          </div>
          <button type="submit" class="btn btn-primary w-100">
            <i class="fas fa-user-shield me-2"></i>Create Admin Account
          </button>
        </form>
      </div>
    </div>
  </div>

  {{-- Admins list --}}
  <div class="col-lg-7">
    <div class="content-card">
      <div class="card-header-custom">
        <h5><i class="fas fa-users-cog me-2 text-primary"></i>All Admin Accounts</h5>
        <span class="badge bg-primary">{{ $admins->count() }} admin(s)</span>
      </div>
      <div class="table-responsive">
        <table class="table table-hover mb-0">
          <thead>
            <tr>
              <th>#</th>
              <th>Name</th>
              <th>Email</th>
              <th>Created</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            @forelse($admins as $admin)
            <tr>
              <td class="text-muted small">{{ $admin->id }}</td>
              <td>
                <div class="fw-semibold small">{{ $admin->name }}</div>
                @if($admin->id === auth()->id())
                  <span class="badge" style="background:#dcfce7;color:#166534;font-size:.68rem;">You</span>
                @endif
              </td>
              <td class="text-muted small">{{ $admin->email }}</td>
              <td class="text-muted small">{{ $admin->created_at->format('d M Y') }}</td>
              <td>
                @if($admin->id !== auth()->id())
                <form method="POST" action="{{ route('admin.admins.destroy', $admin) }}"
                      onsubmit="return confirm('Delete admin {{ $admin->name }}?')">
                  @csrf @method('DELETE')
                  <button type="submit" class="btn btn-sm btn-outline-danger">
                    <i class="fas fa-trash"></i>
                  </button>
                </form>
                @else
                <span class="text-muted small">—</span>
                @endif
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="5" class="text-center text-muted py-4">No admins found.</td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection
