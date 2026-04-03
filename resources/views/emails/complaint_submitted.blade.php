<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
  body { font-family: 'Segoe UI', sans-serif; background: #f0f2f5; margin: 0; padding: 20px; }
  .card { background: white; max-width: 560px; margin: 0 auto; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,.1); }
  .header { background: #1a3c6e; color: white; padding: 28px 32px; }
  .header h1 { margin: 0; font-size: 1.3rem; }
  .header p { margin: 6px 0 0; opacity: .8; font-size: .85rem; }
  .body { padding: 28px 32px; }
  .label { font-size: .75rem; font-weight: 700; text-transform: uppercase; color: #64748b; margin-bottom: 3px; margin-top: 14px; }
  .value { font-size: .9rem; color: #1e293b; }
  .badge { display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: .75rem; font-weight: 600; background: #fef9c3; color: #92400e; }
  .desc { background: #f8fafc; border-radius: 8px; padding: 14px; font-size: .88rem; color: #334155; line-height: 1.6; margin-top: 6px; }
  .footer { background: #f8fafc; padding: 16px 32px; font-size: .78rem; color: #64748b; text-align: center; border-top: 1px solid #e2e8f0; }
</style>
</head>
<body>
<div class="card">
  <div class="header">
    <h1>Complaint Submitted Successfully</h1>
    <p>UTAMU Online Complaints System</p>
  </div>
  <div class="body">
    <p style="color:#334155;font-size:.92rem;">Dear <strong>{{ $complaint->user->name }}</strong>,</p>
    <p style="color:#334155;font-size:.88rem;">Your complaint has been received and is now being reviewed. Below are the details:</p>

    <div class="label">Complaint ID</div>
    <div class="value">#{{ $complaint->id }}</div>

    <div class="label">Category</div>
    <div class="value">{{ $complaint->category->name }}</div>

    <div class="label">Status</div>
    <div class="value"><span class="badge">Pending</span></div>

    <div class="label">Date Submitted</div>
    <div class="value">{{ $complaint->created_at->format('d M Y, H:i') }}</div>

    <div class="label">Description</div>
    <div class="desc">{{ $complaint->description }}</div>

    <p style="margin-top:20px;font-size:.85rem;color:#64748b;">
      You will receive another email when the status of your complaint is updated. Log in to your account to view full details and reply to admin comments.
    </p>
  </div>
  <div class="footer">Universal Technology and Management University (UTAMU) &nbsp;|&nbsp; Online Complaints System</div>
</div>
</body>
</html>
