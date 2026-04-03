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
    // ── User: show submission form ────────────────────────────────────────────
    public function create()
    {
        // UPDATED: load categories from DB instead of hardcoded array
        $categories = Category::orderBy('name')->get();
        return view('complaints.create', compact('categories'));
    }

    // ── User: store new complaint ─────────────────────────────────────────────
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string|min:10|max:2000',
            // NEW: file attachment validation
            'attachment'  => 'nullable|file|max:20480|mimes:jpg,jpeg,png,gif,pdf,mp3,wav,mp4,mov,avi',
        ]);

        $data = [
            'category_id' => $validated['category_id'],
            'description' => $validated['description'],
            'status'      => Complaint::STATUS_PENDING,
        ];

        // NEW: handle file upload
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

        // NEW: send email notification
        try {
            Mail::to(auth()->user()->email)->send(new ComplaintSubmitted($complaint));
        } catch (\Exception $e) {
            // Don't fail if mail not configured
        }

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
        $stats = [
            'total'       => Complaint::count(),
            'pending'     => Complaint::byStatus(Complaint::STATUS_PENDING)->count(),
            'in_progress' => Complaint::byStatus(Complaint::STATUS_IN_PROGRESS)->count(),
            'resolved'    => Complaint::byStatus(Complaint::STATUS_RESOLVED)->count(),
        ];
        $recent = Complaint::with(['user', 'category'])->latest()->take(5)->get();
        return view('dashboard.admin', compact('stats', 'recent'));
    }

    // ── Admin: all complaints with search/filter ──────────────────────────────
    public function index(Request $request)
    {
        $query = Complaint::with(['user', 'category'])->latest();

        if ($request->filled('search'))   { $query->search($request->search); }
        if ($request->filled('status'))   { $query->byStatus($request->status); }
        // NEW: filter by category
        if ($request->filled('category')) { $query->where('category_id', $request->category); }

        $complaints = $query->paginate(10)->withQueryString();
        $stats = [
            'total'       => Complaint::count(),
            'pending'     => Complaint::byStatus(Complaint::STATUS_PENDING)->count(),
            'in_progress' => Complaint::byStatus(Complaint::STATUS_IN_PROGRESS)->count(),
            'resolved'    => Complaint::byStatus(Complaint::STATUS_RESOLVED)->count(),
        ];
        $statuses   = Complaint::statuses();
        // NEW: pass categories for filter dropdown
        $categories = Category::orderBy('name')->get();

        return view('complaints.index', compact('complaints', 'statuses', 'stats', 'categories'));
    }

    // ── Show complaint detail (shared) ────────────────────────────────────────
    public function show(Complaint $complaint)
    {
        if (auth()->user()->isUser() && $complaint->user_id !== auth()->id()) {
            abort(403);
        }
        // NEW: eager load category and comments
        $complaint->load(['user', 'category', 'comments.user']);
        return view('complaints.show', compact('complaint'));
    }

    // ── Admin: edit status ────────────────────────────────────────────────────
    public function edit(Complaint $complaint)
    {
        $statuses = Complaint::statuses();
        return view('complaints.edit', compact('complaint', 'statuses'));
    }

    // ── Admin: update status ──────────────────────────────────────────────────
    public function update(Request $request, Complaint $complaint)
    {
        $validated = $request->validate([
            'status' => 'required|in:' . implode(',', Complaint::statuses()),
        ]);

        $oldStatus = $complaint->status;
        $complaint->update($validated);

        // NEW: send email if status changed
        if ($oldStatus !== $complaint->status) {
            try {
                Mail::to($complaint->user->email)->send(new StatusUpdated($complaint));
            } catch (\Exception $e) {}
        }

        return redirect()->route('admin.complaints.index')
            ->with('success', 'Complaint status updated successfully!');
    }

    // ── User: delete own complaint ────────────────────────────────────────────
    public function destroy(Complaint $complaint)
    {
        if ($complaint->user_id !== auth()->id()) {
            abort(403);
        }
        // NEW: delete attachment file if exists
        if ($complaint->attachment) {
            Storage::disk('public')->delete($complaint->attachment);
        }
        $complaint->delete();
        return redirect()->route('user.dashboard')
            ->with('success', 'Complaint deleted successfully.');
    }

    // ── NEW: store comment (admin or user) ────────────────────────────────────
    public function storeComment(Request $request, Complaint $complaint)
    {
        if (auth()->user()->isUser() && $complaint->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate(['body' => 'required|string|min:2|max:1000']);

        $complaint->comments()->create([
            'user_id' => auth()->id(),
            'body'    => $request->body,
        ]);

        return back()->with('success', 'Reply posted successfully.');
    }

    // ── NEW: export as CSV ────────────────────────────────────────────────────
    public function exportCsv()
    {
        $complaints = Complaint::with(['user', 'category'])->latest()->get();

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
                    $c->category->name, $c->description,
                    $c->status, $c->created_at->format('d M Y'),
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // ── NEW: export as PDF ────────────────────────────────────────────────────
    public function exportPdf()
    {
        $complaints = Complaint::with(['user', 'category'])->latest()->get();
        $pdf = Pdf::loadView('complaints.pdf', compact('complaints'))->setPaper('a4', 'landscape');
        return $pdf->download('complaints_' . now()->format('Ymd_His') . '.pdf');
    }
}
