<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\Category;
use App\Models\Comment;
use App\Mail\ComplaintSubmitted;
use App\Mail\StatusUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class ComplaintController extends Controller
{
    // ── User: submit complaint ────────────────────────────────────────────────
    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('complaints.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string|min:10|max:2000',
            'attachment'  => 'nullable|file|max:20480|mimes:jpg,jpeg,png,gif,pdf,mp3,wav,mp4,mov,avi',
        ]);

        $data = [
            'category_id' => $validated['category_id'],
            'description' => $validated['description'],
            'status'      => Complaint::STATUS_PENDING,
        ];

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $path = $file->store('attachments', 'public');
            $mime = $file->getMimeType();
            $data['attachment'] = $path;
            $data['attachment_type'] = match(true) {
                str_starts_with($mime, 'image/')  => 'image',
                str_starts_with($mime, 'audio/')  => 'audio',
                str_starts_with($mime, 'video/')  => 'video',
                $mime === 'application/pdf'        => 'pdf',
                default                            => 'file',
            };
        }

        $complaint = auth()->user()->complaints()->create($data);

        try {
            Mail::to(auth()->user()->email)->send(new ComplaintSubmitted($complaint));
        } catch (\Exception $e) {}

        return redirect()->route('user.dashboard')
            ->with('success', 'Your complaint has been submitted successfully!');
    }

    // ── User: own complaints dashboard ───────────────────────────────────────
    public function userDashboard()
    {
        $complaints = auth()->user()->complaints()->with('category')->latest()->paginate(10);
        $stats = [
            'total'       => auth()->user()->complaints()->count(),
            'pending'     => auth()->user()->complaints()->byStatus(Complaint::STATUS_PENDING)->count(),
            'in_progress' => auth()->user()->complaints()->byStatus(Complaint::STATUS_IN_PROGRESS)->count(),
            'resolved'    => auth()->user()->complaints()->byStatus(Complaint::STATUS_RESOLVED)->count(),
        ];
        return view('dashboard.user', compact('complaints', 'stats'));
    }

    // ── Admin: dashboard ──────────────────────────────────────────────────────
    public function adminDashboard()
    {
        // Filter based on admin sub-role
        $query = $this->adminComplaintsQuery();

        $stats = [
            'total'       => (clone $query)->count(),
            'pending'     => (clone $query)->byStatus(Complaint::STATUS_PENDING)->count(),
            'in_progress' => (clone $query)->byStatus(Complaint::STATUS_IN_PROGRESS)->count(),
            'resolved'    => (clone $query)->byStatus(Complaint::STATUS_RESOLVED)->count(),
        ];
        $recent = (clone $query)->with(['user', 'category'])->latest()->take(5)->get();

        return view('dashboard.admin', compact('stats', 'recent'));
    }

    // ── Admin: all complaints ─────────────────────────────────────────────────
    public function index(Request $request)
    {
        $query = $this->adminComplaintsQuery()->with(['user', 'category']);

        if ($request->filled('search'))   { $query->search($request->search); }
        if ($request->filled('status'))   { $query->byStatus($request->status); }
        if ($request->filled('category')) { $query->where('category_id', $request->category); }

        $baseQuery = $this->adminComplaintsQuery();
        $stats = [
            'total'       => (clone $baseQuery)->count(),
            'pending'     => (clone $baseQuery)->byStatus(Complaint::STATUS_PENDING)->count(),
            'in_progress' => (clone $baseQuery)->byStatus(Complaint::STATUS_IN_PROGRESS)->count(),
            'resolved'    => (clone $baseQuery)->byStatus(Complaint::STATUS_RESOLVED)->count(),
        ];

        $complaints = $query->latest()->paginate(10)->withQueryString();
        $statuses   = Complaint::statuses();

        // Category admins only see their own category in the filter
        $categories = auth()->user()->isSuperAdmin()
            ? Category::orderBy('name')->get()
            : Category::where('name', auth()->user()->adminCategory())->get();

        return view('complaints.index', compact('complaints', 'statuses', 'stats', 'categories'));
    }

    // ── Show complaint ────────────────────────────────────────────────────────
    public function show(Complaint $complaint)
    {
        if (auth()->user()->isUser() && $complaint->user_id !== auth()->id()) {
            abort(403);
        }
        // Category admin can only see complaints in their category
        if (auth()->user()->isAdmin() && !auth()->user()->isSuperAdmin()) {
            $adminCategory = auth()->user()->adminCategory();
            if ($complaint->category?->name !== $adminCategory) {
                abort(403, 'You can only view complaints in your assigned category.');
            }
        }
        $complaint->load(['user', 'category', 'comments.user']);
        return view('complaints.show', compact('complaint'));
    }

    // ── Admin: edit status ────────────────────────────────────────────────────
    public function edit(Complaint $complaint)
    {
        $statuses = Complaint::statuses();
        return view('complaints.edit', compact('complaint', 'statuses'));
    }

    public function update(Request $request, Complaint $complaint)
    {
        $validated = $request->validate([
            'status' => 'required|in:' . implode(',', Complaint::statuses()),
        ]);
        $oldStatus = $complaint->status;
        $complaint->update($validated);

        if ($oldStatus !== $complaint->status) {
            try { Mail::to($complaint->user->email)->send(new StatusUpdated($complaint)); } catch (\Exception $e) {}
        }

        return redirect()->route('admin.complaints.index')
            ->with('success', 'Complaint status updated successfully!');
    }

    // ── User: delete own complaint ────────────────────────────────────────────
    public function destroy(Complaint $complaint)
    {
        if ($complaint->user_id !== auth()->id()) abort(403);
        if ($complaint->attachment) {
            Storage::disk('public')->delete($complaint->attachment);
        }
        $complaint->delete();
        return redirect()->route('user.dashboard')
            ->with('success', 'Complaint deleted successfully.');
    }

    // ── Comments (with optional attachment) ───────────────────────────────────
    public function storeComment(Request $request, Complaint $complaint)
    {
        if (auth()->user()->isUser() && $complaint->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'body'       => 'required|string|min:2|max:1000',
            'attachment' => 'nullable|file|max:20480|mimes:jpg,jpeg,png,gif,pdf,mp3,wav,mp4,mov,avi',
        ]);

        $data = [
            'user_id' => auth()->id(),
            'body'    => $request->body,
        ];

        // Handle comment attachment
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $path = $file->store('comment-attachments', 'public');
            $mime = $file->getMimeType();
            $data['attachment'] = $path;
            $data['attachment_type'] = match(true) {
                str_starts_with($mime, 'image/')  => 'image',
                str_starts_with($mime, 'audio/')  => 'audio',
                str_starts_with($mime, 'video/')  => 'video',
                $mime === 'application/pdf'        => 'pdf',
                default                            => 'file',
            };
        }

        $complaint->comments()->create($data);

        return back()->with('success', 'Reply posted successfully.');
    }

    // ── Export CSV ────────────────────────────────────────────────────────────
    public function exportCsv()
    {
        $complaints = $this->adminComplaintsQuery()->with(['user', 'category'])->latest()->get();
        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename=complaints_' . now()->format('Ymd_His') . '.csv',
        ];
        $callback = function () use ($complaints) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'User', 'Email', 'Category', 'Description', 'Status', 'Date']);
            foreach ($complaints as $c) {
                fputcsv($file, [
                    $c->id, $c->user->name, $c->user->email,
                    $c->category?->name, $c->description,
                    $c->status, $c->created_at->format('d M Y'),
                ]);
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }

    // ── Export PDF ────────────────────────────────────────────────────────────
    public function exportPdf()
    {
        $complaints = $this->adminComplaintsQuery()->with(['user', 'category'])->latest()->get();
        $pdf = Pdf::loadView('complaints.pdf', compact('complaints'))->setPaper('a4', 'landscape');
        return $pdf->download('complaints_' . now()->format('Ymd_His') . '.pdf');
    }

    // ── Helper: base complaints query filtered by admin role ──────────────────
    private function adminComplaintsQuery()
    {
        $query = Complaint::query();

        if (!auth()->user()->isSuperAdmin()) {
            $categoryName = auth()->user()->adminCategory();
            if ($categoryName) {
                $query->whereHas('category', fn($q) => $q->where('name', $categoryName));
            }
        }

        return $query;
    }
}
