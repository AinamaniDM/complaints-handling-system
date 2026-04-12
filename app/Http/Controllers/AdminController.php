<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function index()
    {
        // Only super admin can manage admins
        if (!auth()->user()->isSuperAdmin()) {
            abort(403, 'Only super admins can manage admin accounts.');
        }
        $admins    = User::where('role', User::ROLE_ADMIN)->latest()->get();
        $adminRoles = User::ADMIN_ROLES;
        return view('admin.admins', compact('admins', 'adminRoles'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->isSuperAdmin()) abort(403);

        $validated = $request->validate([
            'name'       => 'required|string|min:3|max:100',
            'email'      => 'required|email|unique:users,email',
            'password'   => 'required|string|min:6|confirmed',
            'admin_role' => 'required|in:' . implode(',', array_keys(User::ADMIN_ROLES)),
        ]);

        User::create([
            'name'       => $validated['name'],
            'email'      => $validated['email'],
            'password'   => Hash::make($validated['password']),
            'role'       => User::ROLE_ADMIN,
            'admin_role' => $validated['admin_role'] === 'super_admin' ? null : $validated['admin_role'],
        ]);

        return redirect()->route('admin.admins')
            ->with('success', 'Admin account created successfully!');
    }

    // Update admin role assignment
    public function updateRole(Request $request, User $user)
    {
        if (!auth()->user()->isSuperAdmin()) abort(403);
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot change your own role.');
        }

        $request->validate([
            'admin_role' => 'required|in:' . implode(',', array_keys(User::ADMIN_ROLES)),
        ]);

        $user->update([
            'admin_role' => $request->admin_role === 'super_admin' ? null : $request->admin_role,
        ]);

        return redirect()->route('admin.admins')
            ->with('success', 'Admin role updated successfully.');
    }

    public function destroy(User $user)
    {
        if (!auth()->user()->isSuperAdmin()) abort(403);
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }
        $user->delete();
        return redirect()->route('admin.admins')->with('success', 'Admin account deleted.');
    }
}
