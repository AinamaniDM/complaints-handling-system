@extends('layouts.app')
@section('title','Categories')
@section('page-title','Manage Categories')
@section('content')
<div class="row g-4">

  {{-- Add Category --}}
  <div class="col-lg-4">
    <div class="content-card">
      <div class="card-header-custom">
        <h5><i class="fas fa-plus me-2 text-primary"></i>Add New Category</h5>
      </div>
      <div class="p-4">
        <form method="POST" action="{{ route('admin.categories.store') }}">
          @csrf
          <div class="mb-3">
            <label class="form-label">Category Name</label>
            <input type="text" name="name"
                   class="form-control @error('name') is-invalid @enderror"
                   value="{{ old('name') }}" placeholder="e.g. Academic" required>
            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="mb-4">
            <label class="form-label">
              Description <span class="text-muted fw-normal">(optional)</span>
            </label>
            <textarea name="description" rows="2" class="form-control"
                      placeholder="Brief description of this category">{{ old('description') }}</textarea>
          </div>
          <button type="submit" class="btn btn-primary w-100">
            <i class="fas fa-plus me-2"></i>Create Category
          </button>
        </form>
      </div>
    </div>
  </div>

  {{-- Categories List --}}
  <div class="col-lg-8">
    <div class="content-card">
      <div class="card-header-custom">
        <h5><i class="fas fa-tags me-2 text-primary"></i>All Categories</h5>
        <span class="badge bg-primary">{{ $categories->count() }} categories</span>
      </div>
      <div class="table-responsive">
        <table class="table table-hover mb-0">
          <thead>
            <tr>
              <th>#</th>
              <th>Name</th>
              <th>Description</th>
              <th>Complaints</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse($categories as $cat)
            <tr>
              <td class="text-muted small">{{ $cat->id }}</td>
              <td class="fw-semibold">{{ $cat->name }}</td>
              <td class="text-muted small">{{ $cat->description ?? '—' }}</td>
              <td>
                <span class="badge bg-light text-dark border">{{ $cat->complaints_count }}</span>
              </td>
              <td>
                <div class="d-flex gap-1">
                  <button class="btn btn-sm btn-outline-primary"
                          data-bs-toggle="modal"
                          data-bs-target="#editCat{{ $cat->id }}">
                    <i class="fas fa-edit"></i>
                  </button>
                  @if($cat->complaints_count == 0)
                  <form method="POST" action="{{ route('admin.categories.destroy', $cat) }}"
                        onsubmit="return confirm('Delete {{ $cat->name }}?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger">
                      <i class="fas fa-trash"></i>
                    </button>
                  </form>
                  @else
                  <button class="btn btn-sm btn-outline-secondary" disabled
                          title="Cannot delete — has {{ $cat->complaints_count }} complaint(s)">
                    <i class="fas fa-trash"></i>
                  </button>
                  @endif
                </div>
              </td>
            </tr>

            {{-- Edit Modal --}}
            <div class="modal fade" id="editCat{{ $cat->id }}" tabindex="-1">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title">Edit Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                  </div>
                  <form method="POST" action="{{ route('admin.categories.update', $cat) }}">
                    @csrf @method('PUT')
                    <div class="modal-body">
                      <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control"
                               value="{{ $cat->name }}" required>
                      </div>
                      <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" rows="2"
                                  class="form-control">{{ $cat->description }}</textarea>
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-outline-secondary"
                              data-bs-dismiss="modal">Cancel</button>
                      <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>

            @empty
            <tr>
              <td colspan="5" class="text-center text-muted py-4">No categories yet.</td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

</div>
@endsection
