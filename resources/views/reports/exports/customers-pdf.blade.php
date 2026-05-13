<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Customer Report</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 11px; color: #1f2937; line-height: 1.5; }
        .header { background: linear-gradient(135deg, #4f46e5, #6366f1); color: #fff; padding: 28px 32px; }
        .header h1 { font-size: 22px; font-weight: 700; margin-bottom: 4px; }
        .header p { font-size: 11px; opacity: 0.85; }
        .meta { display: flex; padding: 16px 32px; background: #f9fafb; border-bottom: 1px solid #e5e7eb; font-size: 10px; color: #6b7280; }
        .meta span { margin-right: 24px; }
        .stats { padding: 20px 32px; }
        .stats-grid { width: 100%; }
        .stats-grid td { padding: 12px 16px; text-align: center; border: 1px solid #e5e7eb; border-radius: 4px; }
        .stat-value { font-size: 22px; font-weight: 700; color: #111827; }
        .stat-value.green { color: #059669; }
        .stat-value.gray { color: #9ca3af; }
        .stat-label { font-size: 10px; color: #6b7280; margin-top: 2px; }
        .content { padding: 0 32px 32px; }
        .section-title { font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 10px; }
        table.data { width: 100%; border-collapse: collapse; }
        table.data thead th { background: #f3f4f6; padding: 8px 12px; text-align: left; font-size: 10px; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid #e5e7eb; }
        table.data tbody td { padding: 8px 12px; border-bottom: 1px solid #f3f4f6; font-size: 11px; }
        table.data tbody tr:nth-child(even) { background: #fafafa; }
        .badge { display: inline-block; padding: 2px 8px; border-radius: 10px; font-size: 10px; font-weight: 600; }
        .badge-active { background: #d1fae5; color: #065f46; }
        .badge-inactive { background: #f3f4f6; color: #6b7280; }
        .footer { padding: 16px 32px; border-top: 1px solid #e5e7eb; font-size: 9px; color: #9ca3af; text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Customer Report</h1>
        <p>{{ config('app.name', 'CRM') }} — Complete customer overview</p>
    </div>

    <div class="meta">
        <span><strong>Generated:</strong> {{ $generatedAt }}</span>
        <span><strong>By:</strong> {{ $generatedBy }}</span>
        <span><strong>Total Records:</strong> {{ $customers->count() }}</span>
    </div>

    <div class="stats">
        <table class="stats-grid">
            <tr>
                <td>
                    <div class="stat-value">{{ $customers->count() }}</div>
                    <div class="stat-label">Total Customers</div>
                </td>
                <td>
                    <div class="stat-value green">{{ $statusCounts['active'] ?? 0 }}</div>
                    <div class="stat-label">Active</div>
                </td>
                <td>
                    <div class="stat-value gray">{{ $statusCounts['inactive'] ?? 0 }}</div>
                    <div class="stat-label">Inactive</div>
                </td>
            </tr>
        </table>
    </div>

    <div class="content">
        <p class="section-title">Customer Details</p>
        <table class="data">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Company</th>
                    <th>Status</th>
                    <th>Assigned To</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($customers as $i => $c)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $c->full_name }}</td>
                        <td>{{ $c->email }}</td>
                        <td>{{ $c->company ?? '—' }}</td>
                        <td><span class="badge {{ $c->status === 'active' ? 'badge-active' : 'badge-inactive' }}">{{ ucfirst($c->status) }}</span></td>
                        <td>{{ $c->assignedUser?->name ?? '—' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="footer">
        {{ config('app.name', 'CRM') }} &bull; Customer Report &bull; Generated {{ $generatedAt }}
    </div>
</body>
</html>
