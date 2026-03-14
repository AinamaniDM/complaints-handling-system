<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Complaints System') — UTAMU</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --sidebar-width: 255px;
            --primary: #1a3c6e;
            --primary-light: #2a5298;
            --topbar-height: 58px;
        }
        body { background: #f0f2f5; font-family: 'Segoe UI', sans-serif; }

        #sidebar {
            position: fixed; top: 0; left: 0;
            width: var(--sidebar-width); height: 100vh;
            background: var(--primary);
            display: flex; flex-direction: column;
            z-index: 1040;
        }
        .sidebar-brand {
            padding: 18px 20px; background: rgba(0,0,0,.2);
            display: flex; align-items: center; gap: 10px;
        }
        .sidebar-brand .logo-icon {
            width: 38px; height: 38px; background: white;
            border-radius: 8px; display: flex; align-items: center; justify-content: center;
        }
        .sidebar-brand .logo-icon i { color: var(--primary); font-size: 1.1rem; }
        .sidebar-brand span { color: white; font-weight: 700; font-size: 1rem; line-height: 1.2; }

        .sidebar-nav { flex: 1; padding: 14px 0; overflow-y: auto; }
        .nav-section-title {
            color: rgba(255,255,255,.4); font-size: .66rem;
            font-weight: 700; letter-spacing: 1px; text-transform: uppercase;
            padding: 10px 20px 4px;
        }
        .sidebar-nav .nav-link {
            color: #cdd8e8; padding: 10px 20px;
            display: flex; align-items: center; gap: 12px;
            font-size: .88rem; transition: all .2s; border-left: 3px solid transparent;
        }
        .sidebar-nav .nav-link:hover,
        .sidebar-nav .nav-link.active {
            background: rgba(255,255,255,.1); color: white;
            border-left-color: white;
        }
        .sidebar-nav .nav-link i { width: 18px; text-align: center; opacity: .8; }

        /* Role badge in sidebar */
        .role-badge {
            font-size: .65rem; font-weight: 700; padding: 2px 8px;
            border-radius: 20px; text-transform: uppercase; letter-spacing: .5px;
        }
        .role-badge.admin { background: #f59e0b; color: #1e293b; }
        .role-badge.user  { background: #10b981; color: white; }

        .sidebar-footer {
            padding: 14px 20px; border-top: 1px solid rgba(255,255,255,.1);
        }
        .sidebar-footer .user-info { color: rgba(255,255,255,.7); font-size: .8rem; margin-bottom: 8px; }
        .sidebar-footer .user-info strong { color: white; display: block; font-size: .88rem; }

        #main { margin-left: var(--sidebar-width); min-height: 100vh; }

        #topbar {
            height: var(--topbar-height); background: white;
            border-bottom: 1px solid #e2e8f0;
            display: flex; align-items: center; padding: 0 24px;
            position: sticky; top: 0; z-index: 1030;
        }
        #topbar .page-title { font-weight: 600; font-size: 1rem; color: #1e293b; }

        .page-body { padding: 26px; }

        /* Stat cards */
        .stat-card {
            background: white; border-radius: 12px; padding: 20px;
            display: flex; align-items: center; gap: 16px;
            box-shadow: 0 1px 4px rgba(0,0,0,.07); border-left: 4px solid transparent;
        }
        .stat-card .icon-wrap {
            width: 50px; height: 50px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center; font-size: 1.2rem;
        }
        .stat-card .stat-value { font-size: 1.7rem; font-weight: 700; line-height: 1; }
        .stat-card .stat-label { font-size: .78rem; color: #64748b; margin-top: 2px; }
        .stat-card.total    { border-color: var(--primary); }
        .stat-card.total    .icon-wrap { background: #eff6ff; color: var(--primary); }
        .stat-card.pending  { border-color: #f59e0b; }
        .stat-card.pending  .icon-wrap { background: #fffbeb; color: #f59e0b; }
        .stat-card.progress { border-color: #3b82f6; }
        .stat-card.progress .icon-wrap { background: #eff6ff; color: #3b82f6; }
        .stat-card.resolved { border-color: #10b981; }
        .stat-card.resolved .icon-wrap { background: #ecfdf5; color: #10b981; }

        /* Content card */
        .content-card {
            background: white; border-radius: 12px;
            box-shadow: 0 1px 4px rgba(0,0,0,.07); overflow: hidden;
        }
        .content-card .card-header-custom {
            padding: 16px 22px; border-bottom: 1px solid #f1f5f9;
            display: flex; align-items: center; justify-content: space-between;
        }
        .content-card .card-header-custom h5 {
            font-size: .93rem; font-weight: 600; margin: 0; color: #1e293b;
        }

        /* Table */
        .table th { font-size: .75rem; font-weight: 600; text-transform: uppercase;
                    letter-spacing: .5px; color: #64748b; background: #f8fafc; }
        .table td { vertical-align: middle; font-size: .87rem; color: #334155; }

        /* Status badges */
        .status-badge { padding: 4px 12px; border-radius: 20px; font-size: .73rem; font-weight: 600; }
        .badge-warning  { background: #fef9c3; color: #92400e; }
        .badge-info     { background: #dbeafe; color: #1e40af; }
        .badge-success  { background: #dcfce7; color: #166534; }

        /* Forms */
        .form-label { font-weight: 600; font-size: .85rem; color: #374151; }
        .form-control, .form-select { border: 1px solid #e2e8f0; border-radius: 8px; font-size: .9rem; }
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-light); box-shadow: 0 0 0 3px rgba(42,82,152,.12);
        }
        .btn-primary { background: var(--primary); border-color: var(--primary); }
        .btn-primary:hover { background: var(--primary-light); border-color: var(--primary-light); }

        /* Auth pages */
        .auth-wrapper {
            min-height: 100vh; background: linear-gradient(135deg, #1a3c6e, #2a5298);
            display: flex; align-items: center; justify-content: center; padding: 32px 16px;
        }
        .auth-card {
            background: white; border-radius: 16px; padding: 40px;
            width: 100%; max-width: 480px; box-shadow: 0 20px 60px rgba(0,0,0,.2);
        }
        .auth-card .logo-area { text-align: center; margin-bottom: 28px; }
        .auth-card .badge-org {
            background: var(--primary); color: white; border-radius: 50px;
            padding: 5px 16px; font-size: .75rem; font-weight: 700; display: inline-block; margin-bottom: 10px;
        }
    </style>
</head>
<body>

@auth
<nav id="sidebar">
    <div class="sidebar-brand">
        <div class="logo-icon"><i class="fas fa-clipboard-list"></i></div>
        <span>Complaints<br>System</span>
    </div>

    <div class="sidebar-nav">
        @if(auth()->user()->isAdmin())
            <div class="nav-section-title">Admin Panel</div>
            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
            <a href="{{ route('admin.complaints.index') }}" class="nav-link {{ request()->routeIs('admin.complaints.*') ? 'active' : '' }}">
                <i class="fas fa-list-alt"></i> All Complaints
            </a>
            <div class="nav-section-title">Management</div>
            <a href="{{ route('admin.admins') }}" class="nav-link {{ request()->routeIs('admin.admins*') ? 'active' : '' }}">
                <i class="fas fa-user-shield"></i> Manage Admins
            </a>
        @else
            <div class="nav-section-title">My Account</div>
            <a href="{{ route('user.dashboard') }}" class="nav-link {{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
                <i class="fas fa-home"></i> My Complaints
            </a>
            <a href="{{ route('complaints.create') }}" class="nav-link {{ request()->routeIs('complaints.create') ? 'active' : '' }}">
                <i class="fas fa-plus-circle"></i> Submit Complaint
            </a>
        @endif
    </div>

    <div class="sidebar-footer">
        <div class="user-info">
            <strong>{{ Auth::user()->name }}</strong>
            {{ Auth::user()->email }}
        </div>
        <div class="d-flex align-items-center justify-content-between mb-2">
            <span class="role-badge {{ auth()->user()->role }}">{{ ucfirst(auth()->user()->role) }}</span>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-sm btn-outline-light w-100">
                <i class="fas fa-sign-out-alt me-1"></i> Logout
            </button>
        </form>
    </div>
</nav>

<div id="main">
    <div id="topbar">
        <span class="page-title">@yield('page-title', 'Dashboard')</span>
    </div>
    <div class="page-body">
        @foreach(['success','error'] as $msg)
            @if(session($msg))
            <div class="alert alert-{{ $msg === 'success' ? 'success' : 'danger' }} alert-dismissible fade show mb-4">
                <i class="fas fa-{{ $msg === 'success' ? 'check-circle' : 'exclamation-circle' }} me-2"></i>
                {{ session($msg) }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif
        @endforeach

        @yield('content')
    </div>
</div>

@else
    @yield('content')
@endauth

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>
