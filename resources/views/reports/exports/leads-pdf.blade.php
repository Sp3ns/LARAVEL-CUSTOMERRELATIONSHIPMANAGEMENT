<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Lead Status Report</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 11px; color: #1f2937; line-height: 1.5; }
        .header { background: linear-gradient(135deg, #d97706, #f59e0b); color: #fff; padding: 28px 32px; }
        .header h1 { font-size: 22px; font-weight: 700; margin-bottom: 4px; }
        .header p { font-size: 11px; opacity: 0.85; }
        .meta { padding: 16px 32px; background: #f9fafb; border-bottom: 1px solid #e5e7eb; font-size: 10px; color: #6b7280; }
        .meta span { margin-right: 24px; }
        .stats { padding: 20px 32px; }
        .stats h3 { font-size: 12px; font-weight: 600; color: #374151; margin-bottom: 8px; }
        .stats-row { width: 100%; margin-bottom: 16px; }
        .stats-row td { padding: 10px 14px; text-align: center; border: 1px solid #e5e7eb; }
        .stat-value { font-size: 18px; font-weight: 700; color: #111827; }
        .stat-label { font-size: 10px; color: #6b7280; margin-top: 2px; }
        .content { padding: 0 32px 32px; }
        .section-title { font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 10px; }
        table.data { width: 100%; border-collapse: collapse; }
        table.data thead th { background: #f3f4f6; padding: 8px 12px; text-align: left; font-size: 10px; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid #e5e7eb; }
        table.data tbody td { padding: 8px 12px; border-bottom: 1px solid #f3f4f6; font-size: 11px; }
        table.data tbody tr:nth-child(even) { background: #fafafa; }
        .badge { display: inline-block; padding: 2px 8px; border-radius: 10px; font-size: 10px; font-weight: 600; }
        .footer { padding: 16px 32px; border-top: 1px solid #e5e7eb; font-size: 9px; color: #9ca3af; text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Lead Status Report</h1>
        <p>{{ config('app.name', 'CRM') }} — Lead distribution by status and priority</p>
    </div>

    <div class="meta">
        <span><strong>Generated:</strong> {{ $generatedAt }}</span>
        <span><strong>By:</strong> {{ $generatedBy }}</span>
        <span><strong>Total Leads:</strong> {{ $leads->count() }}</span>
    </div>

    <div class="stats">
        <h3>By Status</h3>
        <table class="stats-row">
            <tr>
                @foreach (\App\Models\Lead::STATUSES as $s)
                    <td>
                        <div class="stat-value">{{ $statusCounts[$s] ?? 0 }}</div>
                        <div class="stat-label">{{ $s }}</div>
                    </td>
                @endforeach
            </tr>
        </table>

        <h3>By Priority</h3>
        <table class="stats-row">
            <tr>
                @foreach (\App\Models\Lead::PRIORITIES as $p)
                    <td>
                        <div class="stat-value">{{ $priorityCounts[$p] ?? 0 }}</div>
                        <div class="stat-label">{{ $p }}</div>
                    </td>
                @endforeach
            </tr>
        </table>
    </div>

    <div class="content">
        <p class="section-title">Lead Details</p>
        <table class="data">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Priority</th>
                    <th>Expected Value</th>
                    <th>Assigned To</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($leads as $i => $l)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $l->name }}</td>
                        <td>{{ $l->email }}</td>
                        <td>{{ $l->status }}</td>
                        <td>{{ $l->priority }}</td>
                        <td>{{ $l->expected_value ? '$' . number_format($l->expected_value, 2) : '—' }}</td>
                        <td>{{ $l->assignedUser?->name ?? '—' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="footer">
        {{ config('app.name', 'CRM') }} &bull; Lead Status Report &bull; Generated {{ $generatedAt }}
    </div>
</body>
</html>
