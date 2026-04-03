@extends('layouts.app')
@section('title','Complaint #' . $complaint->id)
@section('page-title','Complaint Details')
@section('content')
<div class="row justify-content-center">
  <div class="col-lg-8">

    {{-- Main complaint card --}}
    <div class="content-card mb-4">
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
            <span class="badge bg-light text-dark border">{{ $complaint->category->name }}</span>
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

          {{-- NEW: Attachment preview --}}
          @if($complaint->attachment)
          <div class="col-12">
            <div class="text-muted small fw-semibold text-uppercase mb-2">Attachment</div>
            <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;padding:14px;">
              @if($complaint->isImage())
                <img src="{{ Storage::url($complaint->attachment) }}"
                     class="img-fluid rounded" style="max-height:300px;" alt="Attachment">

              @elseif($complaint->isPdf())
                <div class="d-flex align-items-center gap-3">
                  <i class="fas fa-file-pdf text-danger fs-3"></i>
                  <div>
                    <div class="fw-semibold small">PDF Document</div>
                    <a href="{{ Storage::url($complaint->attachment) }}" target="_blank"
                       class="btn btn-sm btn-outline-danger mt-1">
                      <i class="fas fa-external-link-alt me-1"></i>Open PDF
                    </a>
                  </div>
                </div>

              @elseif($complaint->isAudio())
                <div>
                  <div class="fw-semibold small mb-2">
                    <i class="fas fa-music text-primary me-2"></i>Audio File
                  </div>
                  <audio controls class="w-100">
                    <source src="{{ Storage::url($complaint->attachment) }}">
                    Your browser does not support audio playback.
                  </audio>
                </div>

              @elseif($complaint->isVideo())
                <div>
                  <div class="fw-semibold small mb-2">
                    <i class="fas fa-video text-warning me-2"></i>Video File
                  </div>
                  <video controls class="w-100 rounded" style="max-height:300px;">
                    <source src="{{ Storage::url($complaint->attachment) }}">
                    Your browser does not support video playback.
                  </video>
                </div>
              @endif
            </div>
          </div>
          @endif
        </div>
      </div>
    </div>

    {{-- NEW: Comments & Replies section --}}
    <div class="content-card">
      <div class="card-header-custom">
        <h5>
          <i class="fas fa-comments me-2 text-primary"></i>Comments & Replies
          <span class="badge bg-primary ms-2">{{ $complaint->comments->count() }}</span>
        </h5>
      </div>
      <div class="p-4">

        {{-- Display comments --}}
        @forelse($complaint->comments as $comment)
        <div class="mb-3 p-3 rounded"
             style="{{ $comment->isFromAdmin()
               ? 'background:#eff6ff;border-left:3px solid #3b82f6;'
               : 'background:#f0fdf4;border-left:3px solid #10b981;' }}">
          <div class="d-flex align-items-center gap-2 mb-1">
            <i class="fas fa-{{ $comment->isFromAdmin() ? 'user-shield' : 'user' }}"
               style="color:{{ $comment->isFromAdmin() ? '#3b82f6' : '#10b981' }}"></i>
            <strong class="small">{{ $comment->user->name }}</strong>
            <span class="status-badge {{ $comment->isFromAdmin() ? 'badge-info' : 'badge-success' }}"
                  style="font-size:.65rem;">
              {{ $comment->isFromAdmin() ? 'Admin' : 'User' }}
            </span>
            <span class="text-muted" style="font-size:.74rem;">
              {{ $comment->created_at->format('d M Y, H:i') }}
            </span>
          </div>
          <div style="font-size:.87rem;color:#1e293b;line-height:1.5;">{{ $comment->body }}</div>
        </div>
        @empty
        <p class="text-muted small text-center py-3">
          <i class="fas fa-comment-slash me-2 opacity-50"></i>No comments yet.
        </p>
        @endforelse

        {{-- Reply form --}}
        <div class="mt-4 pt-3 border-top">
          <label class="form-label fw-semibold">
            <i class="fas fa-reply me-2 text-primary"></i>
            {{ auth()->user()->isAdmin() ? 'Reply to this complaint' : 'Reply to admin' }}
          </label>

          @if(auth()->user()->isAdmin())
            <form method="POST" action="{{ route('admin.complaints.comments.store', $complaint) }}">
          @else
            <form method="POST" action="{{ route('complaints.comments.store', $complaint) }}">
          @endif
            @csrf
            <textarea name="body" rows="3"
              class="form-control mb-3 @error('body') is-invalid @enderror"
              placeholder="Write your reply…" required>{{ old('body') }}</textarea>
            @error('body')<div class="invalid-feedback">{{ $message }}</div>@enderror
            <button type="submit" class="btn btn-primary">
              <i class="fas fa-paper-plane me-2"></i>Post Reply
            </button>
          </form>
        </div>

      </div>
    </div>

  </div>
</div>
@endsection
