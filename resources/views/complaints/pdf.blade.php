<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
  body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #1e293b; }
  h1 { color: #1a3c6e; font-size: 16px; margin-bottom: 4px; }
  .meta { color: #64748b; font-size: 10px; margin-bottom: 16px; }
  table { width: 100%; border-collapse: collapse; }
  th { background: #1a3c6e; color: white; padding: 8px 10px; text-align: left; font-size: 10px; text-transform: uppercase; letter-spacing: .5px; }
  td { padding: 7px 10px; border-bottom: 1px solid #e2e8f0; font-size: 10.5px; }
  tr:nth-child(even) td { background: #f8fafc; }
  .pending   { background:#fef9c3; color:#92400e; padding:2px 8px; border-radius:10px; font-size:9px; font-weight:bold; }
  .progress  { background:#dbeafe; color:#1e40af; padding:2px 8px; border-radius:10px; font-size:9px; font-weight:bold; }
  .resolved  { background:#dcfce7; color:#166534; padding:2px 8px; border-radius:10px; font-size:9px; font-weight:bold; }
</style>
</head>
<body>
<h1>UTAMU Complaints Handling System Report</h1>
<div class="meta">
  Generated: {{ now()->format('d M Y, H:i') }}
  &nbsp;|&nbsp; Total complaints: {{ $complaints->count() }}
  &nbsp;|&nbsp; Universal Technology and Management University (UTAMU)
</div>
<table>
  <thead>
    <tr>
      <th>#</th>
      <th>User</th>
      <th>Email</th>
      <th>Category</th>
      <th>Description</th>
      <th>Status</th>
      <th>Date</th>
    </tr>
  </thead>
  <tbody>
    @foreach($complaints as $c)
    <tr>
      <td>{{ $c->id }}</td>
      <td>{{ $c->user->name }}</td>
      <td>{{ $c->user->email }}</td>
      <td>{{ $c->category->name }}</td>
      <td>{{ Str::limit($c->description, 60) }}</td>
      <td>
        @if($c->status === 'Pending')   <span class="pending">Pending</span>
        @elseif($c->status === 'In Progress') <span class="progress">In Progress</span>
        @else <span class="resolved">Resolved</span>
        @endif
      </td>
      <td>{{ $c->created_at->format('d M Y') }}</td>
    </tr>
    @endforeach
  </tbody>
</table>
</body>
</html>
