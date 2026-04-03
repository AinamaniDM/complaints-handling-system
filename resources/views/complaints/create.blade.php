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
        {{-- UPDATED: enctype for file upload --}}
        <form method="POST" action="{{ route('complaints.store') }}" enctype="multipart/form-data">
          @csrf

          {{-- UPDATED: category now loaded from DB --}}
          <div class="mb-3">
            <label class="form-label">Complaint Category</label>
            <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
              <option value="">— Select a category —</option>
              @foreach($categories as $cat)
              <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                {{ $cat->name }}
              </option>
              @endforeach
            </select>
            @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>

          <div class="mb-3">
            <label class="form-label">Complaint Description</label>
            <textarea name="description" rows="5"
              class="form-control @error('description') is-invalid @enderror"
              placeholder="Describe your complaint in detail (minimum 10 characters)…"
              required>{{ old('description') }}</textarea>
            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>

          {{-- NEW: file attachment --}}
          <div class="mb-4">
            <label class="form-label">
              Attachment <span class="text-muted fw-normal">(optional)</span>
            </label>
            <input type="file" name="attachment"
              class="form-control @error('attachment') is-invalid @enderror"
              accept=".jpg,.jpeg,.png,.gif,.pdf,.mp3,.wav,.mp4,.mov,.avi">
            <div class="form-text text-muted">
              <i class="fas fa-info-circle me-1"></i>
              Supported: Images (JPG, PNG, GIF), PDF, Audio (MP3, WAV), Video (MP4, MOV, AVI) — Max 20MB
            </div>
            @error('attachment')<div class="invalid-feedback">{{ $message }}</div>@enderror
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
