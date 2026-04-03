<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
  body { font-family: 'Segoe UI', sans-serif; background: #f0f2f5; margin: 0; padding: 20px; }
  .card { background: white; max-width: 560px; margin: 0 auto; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,.1); }
  .header { background: #1a3c6e; color: white; padding: 28px 32px; }
  .header h1 { margin: 0; font-size: 1.3rem; }
  .body { padding: 28px 32px; }
  .label { font-size: .75rem; font-weight: 700; text-transform: uppercase; color: #64748b; margin-bottom: 3px; margin-top: 14px; }
  .value { font-size: .9rem; color: #1e293b; }
  .pending   { display:inline-block; padding:4px 12px; border-radius:20px; font-size:.78rem; font-weight:600; background:#fef9c3; color:#92400e; }
  .progress  { display:inline-block; padding:4px 12px; border-radius:20px; font-size:.78rem; font-weight:600; background:#dbeafe; color:#1e40af; }
  .resolved  { display:inline-block; padding:4px 12px; border-radius:20px; font-size:.78rem; font-weight:600; background:#dcfce7; color:#166534; }
  .footer { background: #f8fafc; padding: 16px 32px; font-size: .78rem; color: #64748b; text-align: center; border-top: 1px solid #e2e8f0; }
</style>
</head>
<body>
<div class="card">
  <div class="header"><h1>Complaint Status Updated</h1></div>
  <div class="body">
    <p style="color:#334155;font-size:.92rem;">Dear <strong>{{ $complaint->user->name }}</strong>,</p>
    <p style="color:#334155;font-size:.88rem;">
      The status of your complaint <strong>#{{ $complaint->id }}</strong> has been updated.
    </p>

    <div class="label">New Status</div>
    <div class="value">
      @if($complaint->status === 'Pending')
        <span class="pending">Pending</span>
      @elseif($complaint->status === 'In Progress')
        <span class="progress">In Progress</span>
      @else
        <span class="resolved">Resolved</span>
      @endif
    </div>

    <div class="label">Category</div>
    <div class="value">{{ $complaint->category->name }}</div>

    <div class="label">Description</div>
    <div style="background:#f8fafc;border-radius:8px;padding:12px;font-size:.86rem;color:#334155;margin-top:6px;">
      {{ Str::limit($complaint->description, 200) }}
    </div>

    <p style="margin-top:20px;font-size:.84rem;color:#64748b;">
      Log in to your account to view the full details and reply to any admin comments.
    </p>
  </div>
  <div class="footer">Uganda Technology and Management University (UTAMU) &nbsp;|&nbsp; Online Complaints System</div>
</div>
</body>
</html>
