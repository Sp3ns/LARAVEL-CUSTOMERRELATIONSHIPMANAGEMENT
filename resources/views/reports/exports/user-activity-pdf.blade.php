<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>User Activity Report</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Helvetica, Arial, sans-serif; font-size: 11px; color: #1f2937; }
        .header { background: #7c3aed; color: #fff; padding: 28px 32px; }
        .header h1 { font-size: 22px; font-weight: 700; }
        .header p { font-size: 11px; opacity: 0.85; }
        .meta { padding: 14px 32px; background: #f9fafb; border-bottom: 1px solid #e5e7eb; font-size: 10px; color: #6b7280; }
        .meta span { margin-right: 20px; }
        .content { padding: 20px 32px; }
        .section-title { font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 10px; }
        table.data { width: 100%; border-collapse: collapse; }
        table.data thead th { background: #f3f4f6; padding: 8px 12px; text-align: left; font-size: 10px; font-weight: 600; color: #6b7280; text-transform: uppercase; border-bottom: 2px solid #e5e7eb; }
        table.data thead th.right { text-align: right; }
        table.data tbody td { padding: 8px 12px; border-bottom: 1px solid #f3f4f6; }
        table.data tbody td.right { text-align: right; font-weight: 600; }
        table.data tbody tr:nth-child(even) { background: #fafafa; }
        .footer { padding: 16px 32px; border-top: 1px solid #e5e7eb; font-size: 9px; color: #9ca3af; text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <h1>User Activity Report</h1>
        <p>{{ config('app.name', 'CRM') }} — Activity counts per team member</p>
    </div>
    <div class="meta">
        <span><strong>Generated:</strong> {{ $generatedAt }}</span>
        <span><strong>By:</strong> {{ $generatedBy }}</span>
        <span><strong>Total Users:</strong> {{ $users->count() }}</span>
    </div>
    <div class="content">
        <p class="section-title">User Breakdown</p>
        <table class="data">
            <thead>
                <tr>
                    <th>#</th><th>User</th><th>Email</th><th>Role</th>
                    <th class="right">Customers</th><th class="right">Leads</th>
                    <th class="right">Activities</th><th class="right">Follow-Ups</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $i => $u)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $u->name }}</td>
                    <td>{{ $u->email }}</td>
                    <td>{{ ucfirst($u->role) }}</td>
                    <td class="right">{{ $u->customers_count }}</td>
                    <td class="right">{{ $u->leads_count }}</td>
                    <td class="right">{{ $u->activities_count }}</td>
                    <td class="right">{{ $u->follow_ups_count }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="footer">
        {{ config('app.name', 'CRM') }} &bull; User Activity Report &bull; Generated {{ $generatedAt }}
    </div>
</body>
</html>
