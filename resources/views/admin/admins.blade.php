@extends('layouts.app')
@section('title','Manage Admins')
@section('page-title','Manage Admin Accounts')
@section('content')

@if(!auth()->user()->isSuperAdmin())
<div class="alert alert-warning">
  <i class="fas fa-lock me-2"></i>Only Super Admins can manage admin accounts.
</div>
@else

<div class="row g-4">
  {{-- Add Admin --}}
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
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                   value="{{ old('name') }}" required>
            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="mb-3">
            <label class="form-label">Email Address</label>
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                   value="{{ old('email') }}" required>
            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="mb-3">
            <label class="form-label">Admin Role</label>
            <select name="admin_role" class="form-select @error('admin_role') is-invalid @enderror" required>
              <option value="">— Select role —</option>
              <option value="super_admin">Super Admin (sees all complaints)</option>
              <option value="finance">Finance Admin (Financial complaints only)</option>
              <option value="hr">HR Admin (Staff Conduct complaints only)</option>
              <option value="academic">Academic Admin (Academic complaints only)</option>
              <option value="facilities">Facilities Admin (Facilities complaints only)</option>
              <option value="it">IT Admin (IT / Technology complaints only)</option>
              <option value="accommodation">Accommodation Admin (Accommodation only)</option>
              <option value="other">Other Admin (Other complaints only)</option>
            </select>
            @error('admin_role')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="mb-4">
            <label class="form-label">Confirm Password</label>
            <input type="password" name="password_confirmation" class="form-control" required>
          </div>
          <button type="submit" class="btn btn-primary w-100">
            <i class="fas fa-user-shield me-2"></i>Create Admin Account
          </button>
        </form>
      </div>
    </div>
  </div>

  {{-- Admins List --}}
  <div class="col-lg-7">
    <div class="content-card">
      <div class="card-header-custom">
        <h5><i class="fas fa-users-cog me-2 text-primary"></i>All Admins</h5>
        <span class="badge bg-primary">{{ $admins->count() }}</span>
      </div>
      <div class="table-responsive">
        <table class="table table-hover mb-0">
          <thead>
            <tr><th>Name</th><th>Email</th><th>Role</th><th>Actions</th></tr>
          </thead>
          <tbody>
            @forelse($admins as $admin)
            <tr>
              <td>
                <div class="fw-semibold small">{{ $admin->name }}</div>
                @if($admin->id === auth()->id())
                  <span class="badge" style="background:#dcfce7;color:#166534;font-size:.68rem;">You</span>
                @endif
              </td>
              <td class="text-muted small">{{ $admin->email }}</td>
              <td>
                @php
                  $roleColors = [
                    'Super Admin'      => '#1a3c6e',
                    'Finance'          => '#10b981',
                    'Hr'               => '#f59e0b',
                    'Academic'         => '#3b82f6',
                    'Facilities'       => '#8b5cf6',
                    'It'               => '#ec4899',
                    'Accommodation'    => '#0891b2',
                    'Other'            => '#64748b',
                  ];
                  $label = $admin->adminRoleLabel();
                  $color = $roleColors[$label] ?? '#64748b';
                @endphp
                <span style="background:{{ $color }}20;color:{{ $color }};padding:3px 10px;border-radius:20px;font-size:.72rem;font-weight:600;">
                  {{ $label }}
                </span>
              </td>
              <td>
                @if($admin->id !== auth()->id())
                <div class="d-flex gap-1">
                  {{-- Change role --}}
                  <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                          data-bs-target="#roleModal{{ $admin->id }}" title="Change Role">
                    <i class="fas fa-user-tag"></i>
                  </button>
                  {{-- Delete --}}
                  <form method="POST" action="{{ route('admin.admins.destroy', $admin) }}"
                        onsubmit="return confirm('Delete {{ $admin->name }}?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                  </form>
                </div>

                {{-- Role Modal --}}
                <div class="modal fade" id="roleModal{{ $admin->id }}" tabindex="-1">
                  <div class="modal-dialog"><div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title">Change Role — {{ $admin->name }}</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form method="POST" action="{{ route('admin.admins.update-role', $admin) }}">
                      @csrf @method('PUT')
                      <div class="modal-body">
                        <label class="form-label">Assign Role</label>
                        <select name="admin_role" class="form-select" required>
                          <option value="super_admin" {{ $admin->isSuperAdmin() ? 'selected' : '' }}>Super Admin</option>
                          <option value="finance"       {{ $admin->admin_role === 'finance'       ? 'selected' : '' }}>Finance Admin</option>
                          <option value="hr"            {{ $admin->admin_role === 'hr'            ? 'selected' : '' }}>HR Admin</option>
                          <option value="academic"      {{ $admin->admin_role === 'academic'      ? 'selected' : '' }}>Academic Admin</option>
                          <option value="facilities"    {{ $admin->admin_role === 'facilities'    ? 'selected' : '' }}>Facilities Admin</option>
                          <option value="it"            {{ $admin->admin_role === 'it'            ? 'selected' : '' }}>IT Admin</option>
                          <option value="accommodation" {{ $admin->admin_role === 'accommodation' ? 'selected' : '' }}>Accommodation Admin</option>
                          <option value="other"         {{ $admin->admin_role === 'other'         ? 'selected' : '' }}>Other Admin</option>
                        </select>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Role</button>
                      </div>
                    </form>
                  </div></div>
                </div>
                @else
                <span class="text-muted small">—</span>
                @endif
              </td>
            </tr>
            @empty
            <tr><td colspan="4" class="text-center text-muted py-4">No admins found.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endif
@endsection
