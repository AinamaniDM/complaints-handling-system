<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use Illuminate\Http\Request;

class ComplaintController extends Controller
{
    private function categories(): array
    {
        return ['Academic', 'Accommodation', 'Financial', 'Staff Conduct', 'Facilities', 'IT / Technology', 'Other'];
    }

    // ── User: submit complaint ────────────────────────────────────────────────

    public function create()
    {
        return view('complaints.create', ['categories' => $this->categories()]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category'    => 'required|in:' . implode(',', $this->categories()),
            'description' => 'required|string|min:10|max:2000',
        ]);

        auth()->user()->complaints()->create([
            'category'    => $validated['category'],
            'description' => $validated['description'],
            'status'      => Complaint::STATUS_PENDING,
        ]);

        return redirect()->route('user.dashboard')
            ->with('success', 'Your complaint has been submitted successfully!');
    }

    // ── User: view own complaints ─────────────────────────────────────────────

    public function userDashboard()
    {
        $complaints = auth()->user()->complaints()->latest()->paginate(10);
        $stats = [
            'total'       => auth()->user()->complaints()->count(),
            'pending'     => auth()->user()->complaints()->byStatus(Complaint::STATUS_PENDING)->count(),
            'in_progress' => auth()->user()->complaints()->byStatus(Complaint::STATUS_IN_PROGRESS)->count(),
            'resolved'    => auth()->user()->complaints()->byStatus(Complaint::STATUS_RESOLVED)->count(),
        ];
        return view('dashboard.user', compact('complaints', 'stats'));
    }

    // ── Admin: view all complaints ────────────────────────────────────────────

    public function adminDashboard()
    {
        $stats = [
            'total'       => Complaint::count(),
            'pending'     => Complaint::byStatus(Complaint::STATUS_PENDING)->count(),
            'in_progress' => Complaint::byStatus(Complaint::STATUS_IN_PROGRESS)->count(),
            'resolved'    => Complaint::byStatus(Complaint::STATUS_RESOLVED)->count(),
        ];
        $recent = Complaint::with('user')->latest()->take(5)->get();
        return view('dashboard.admin', compact('stats', 'recent'));
    }

    public function index(Request $request)
    {
        $query = Complaint::with('user')->latest();

        if ($request->filled('search')) {
            $query->search($request->search);
        }
        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }

        $complaints = $query->paginate(10)->withQueryString();
        $stats = [
            'total'       => Complaint::count(),
            'pending'     => Complaint::byStatus(Complaint::STATUS_PENDING)->count(),
            'in_progress' => Complaint::byStatus(Complaint::STATUS_IN_PROGRESS)->count(),
            'resolved'    => Complaint::byStatus(Complaint::STATUS_RESOLVED)->count(),
        ];
        $statuses = Complaint::statuses();

        return view('complaints.index', compact('complaints', 'statuses', 'stats'));
    }

    public function show(Complaint $complaint)
    {
        // Users can only view their own complaints
        if (auth()->user()->isUser() && $complaint->user_id !== auth()->id()) {
            abort(403);
        }
        return view('complaints.show', compact('complaint'));
    }

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
        $complaint->update($validated);
        return redirect()->route('admin.complaints.index')
            ->with('success', 'Complaint status updated successfully!');
    }

    public function destroy(Complaint $complaint)
    {
        $complaint->delete();
        return redirect()->route('user.dashboard')
            ->with('success', 'Complaint deleted.');
    }
}
