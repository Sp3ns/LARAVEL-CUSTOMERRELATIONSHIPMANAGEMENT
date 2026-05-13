<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Sales Pipeline Report</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 11px; color: #1f2937; line-height: 1.5; }
        .header { background: linear-gradient(135deg, #059669, #10b981); color: #fff; padding: 28px 32px; }
        .header h1 { font-size: 22px; font-weight: 700; margin-bottom: 4px; }
        .header p { font-size: 11px; opacity: 0.85; }
        .meta { padding: 16px 32px; background: #f9fafb; border-bottom: 1px solid #e5e7eb; font-size: 10px; color: #6b7280; }
        .meta span { margin-right: 24px; }
        .summary { padding: 20px 32px; }
        .summary-grid { width: 100%; }
        .summary-grid td { padding: 14px 16px; text-align: center; border: 1px solid #e5e7eb; }
        .stat-value { font-size: 22px; font-weight: 700; color: #111827; }
        .stat-value.green { color: #059669; }
        .stat-label { font-size: 10px; color: #6b7280; margin-top: 2px; }
        .content { padding: 0 32px 32px; }
        .section-title { font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 10px; }
        table.data { width: 100%; border-collapse: collapse; }
        table.data thead th { background: #f3f4f6; padding: 8px 12px; text-align: left; font-size: 10px; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid #e5e7eb; }
        table.data thead th.right { text-align: right; }
        table.data tbody td { padding: 8px 12px; border-bottom: 1px solid #f3f4f6; font-size: 11px; }
        table.data tbody td.right { text-align: right; }
        table.data tbody tr:nth-child(even) { background: #fafafa; }
        table.data tfoot td { padding: 10px 12px; font-weight: 700; font-size: 12px; background: #f3f4f6; border-top: 2px solid #e5e7eb; }
        table.data tfoot td.right { text-align: right; }
        .footer { padding: 16px 32px; border-top: 1px solid #e5e7eb; font-size: 9px; color: #9ca3af; text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Sales Pipeline Report</h1>
        <p>{{ config('app.name', 'CRM') }} — Expected value by pipeline stage</p>
    </div>

    <div class="meta">
        <span><strong>Generated:</strong> {{ $generatedAt }}</span>
        <span><strong>By:</strong> {{ $generatedBy }}</span>
    </div>

    <div class="summary">
        <table class="summary-grid">
            <tr>
                <td>
                    <div class="stat-value">{{ $pipeline->sum('count') }}</div>
                    <div class="stat-label">Total Leads in Pipeline</div>
                </td>
                <td>
                    <div class="stat-value green">${{ number_format($grandTotal, 2) }}</div>
                    <div class="stat-label">Total Pipeline Value</div>
                </td>
                <td>
                    <div class="stat-value">{{ $pipeline->count() }}</div>
                    <div class="stat-label">Active Stages</div>
                </td>
            </tr>
        </table>
    </div>

    <div class="content">
        <p class="section-title">Pipeline Breakdown</p>
        <table class="data">
            <thead>
                <tr>
                    <th>Stage</th>
                    <th class="right">Leads</th>
                    <th class="right">Total Value</th>
                    <th class="right">% of Pipeline</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pipeline as $stage)
                    <tr>
                        <td>{{ $stage->status }}</td>
                        <td class="right">{{ $stage->count }}</td>
                        <td class="right">${{ number_format($stage->total_value, 2) }}</td>
                        <td class="right">{{ $grandTotal > 0 ? round($stage->total_value / $grandTotal * 100, 1) : 0 }}%</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td>Total</td>
                    <td class="right">{{ $pipeline->sum('count') }}</td>
                    <td class="right">${{ number_format($grandTotal, 2) }}</td>
                    <td class="right">100%</td>
                </tr>
            </tfoot>
        </table>
    </div>

    <div class="footer">
        {{ config('app.name', 'CRM') }} &bull; Sales Pipeline Report &bull; Generated {{ $generatedAt }}
    </div>
</body>
</html>
