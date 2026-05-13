<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Follow-Up Report</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Helvetica, Arial, sans-serif; font-size: 11px; color: #1f2937; }
        .header { background: #e11d48; color: #fff; padding: 28px 32px; }
        .header h1 { font-size: 22px; font-weight: 700; }
        .header p { font-size: 11px; opacity: 0.85; }
        .meta { padding: 14px 32px; background: #f9fafb; border-bottom: 1px solid #e5e7eb; font-size: 10px; color: #6b7280; }
        .meta span { margin-right: 20px; }
        .stats { padding: 20px 32px; }
        .stats-grid { width: 100%; }
        .stats-grid td { padding: 12px 16px; text-align: center; border: 1px solid #e5e7eb; }
        .stat-value { font-size: 20px; font-weight: 700; color: #111827; }
        .stat-value.red { color: #dc2626; }
        .stat-label { font-size: 10px; color: #6b7280; margin-top: 2px; }
        .content { padding: 0 32px 32px; }
        .section-title { font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 10px; }
        table.data { width: 100%; border-collapse: collapse; }
        table.data thead th { background: #f3f4f6; padding: 8px 12px; text-align: left; font-size: 10px; font-weight: 600; color: #6b7280; text-transform: uppercase; border-bottom: 2px solid #e5e7eb; }
        table.data tbody td { padding: 8px 12px; border-bottom: 1px solid #f3f4f6; }
        table.data tbody tr:nth-child(even) { background: #fafafa; }
        .overdue-row { background: #fef2f2 !important; }
        .overdue-text { color: #dc2626; font-weight: 600; }
        .footer { padding: 16px 32px; border-top: 1px solid #e5e7eb; font-size: 9px; color: #9ca3af; text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Follow-Up Completion Report</h1>
        <p>{{ config('app.name', 'CRM') }} — Follow-up status and overdue tracking</p>
    </div>
    <div class="meta">
        <span><strong>Generated:</strong> {{ $generatedAt }}</span>
        <span><strong>By:</strong> {{ $generatedBy }}</span>
        <span><strong>Total Records:</strong> {{ $followUps->count() }}</span>
    </div>
    <div class="stats">
        <table class="stats-grid">
            <tr>
                @foreach (\App\Models\FollowUp::STATUSES as $s)
                <td>
                    <div class="stat-value">{{ $statusCounts[$s] ?? 0 }}</div>
                    <div class="stat-label">{{ $s }}</div>
                </td>
                @endforeach
                <td>
                    <div class="stat-value red">{{ $overdue }}</div>
                    <div class="stat-label">Overdue</div>
                </td>
            </tr>
        </table>
    </div>
    <div class="content">
        <p class="section-title">Follow-Up Details</p>
        <table class="data">
            <thead>
                <tr>
                    <th>#</th><th>Title</th><th>Due Date</th><th>Status</th><th>Assigned To</th><th>Linked To</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($followUps as $i => $fu)
                <tr class="{{ $fu->isOverdue() ? 'overdue-row' : '' }}">
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $fu->title }}</td>
                    <td class="{{ $fu->isOverdue() ? 'overdue-text' : '' }}">{{ $fu->due_date->format('M d, Y') }}</td>
                    <td>{{ $fu->status }}</td>
                    <td>{{ $fu->user->name }}</td>
                    <td>@if($fu->customer){{ $fu->customer->full_name }}@elseif($fu->lead){{ $fu->lead->name }}@else — @endif</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="footer">
        {{ config('app.name', 'CRM') }} &bull; Follow-Up Report &bull; Generated {{ $generatedAt }}
    </div>
</body>
</html>
