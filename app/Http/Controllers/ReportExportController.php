<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\FollowUp;
use App\Models\Lead;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportExportController extends Controller
{
    /* ================================================================
     *  CUSTOMERS
     * ================================================================ */

    public function customersCsv(Request $request): StreamedResponse
    {
        $customers = $this->customersQuery($request)->get();

        return $this->streamCsv(
            'customers_report.csv',
            ['Name', 'Email', 'Company', 'Status', 'Assigned To'],
            $customers->map(fn ($c) => [
                $c->full_name,
                $c->email,
                $c->company ?? '—',
                $c->status,
                $c->assignedUser?->name ?? '—',
            ])->toArray()
        );
    }

    public function customersPdf(Request $request)
    {
        $customers = $this->customersQuery($request)->get();

        $statusCounts = Customer::query()
            ->when($request->user()->isSales(), fn ($q) => $q->where('assigned_user_id', $request->user()->id))
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        $pdf = Pdf::loadView('reports.exports.customers-pdf', [
            'customers'    => $customers,
            'statusCounts' => $statusCounts,
            'generatedAt'  => now()->format('M d, Y — h:i A'),
            'generatedBy'  => $request->user()->name,
        ])->setPaper('a4', 'landscape');

        return $pdf->download('customers_report.pdf');
    }

    /* ================================================================
     *  LEADS
     * ================================================================ */

    public function leadsCsv(Request $request): StreamedResponse
    {
        $leads = $this->leadsQuery($request)->get();

        return $this->streamCsv(
            'leads_report.csv',
            ['Name', 'Email', 'Status', 'Priority', 'Expected Value', 'Source', 'Assigned To'],
            $leads->map(fn ($l) => [
                $l->name,
                $l->email,
                $l->status,
                $l->priority,
                $l->expected_value ? number_format($l->expected_value, 2) : '—',
                $l->source ?? '—',
                $l->assignedUser?->name ?? '—',
            ])->toArray()
        );
    }

    public function leadsPdf(Request $request)
    {
        $query = Lead::query();
        if ($request->user()->isSales()) {
            $query->where('assigned_user_id', $request->user()->id);
        }

        $statusCounts   = (clone $query)->selectRaw('status, COUNT(*) as count')->groupBy('status')->pluck('count', 'status');
        $priorityCounts = (clone $query)->selectRaw('priority, COUNT(*) as count')->groupBy('priority')->pluck('count', 'priority');
        $leads          = (clone $query)->with('assignedUser')->latest()->get();

        $pdf = Pdf::loadView('reports.exports.leads-pdf', [
            'leads'          => $leads,
            'statusCounts'   => $statusCounts,
            'priorityCounts' => $priorityCounts,
            'generatedAt'    => now()->format('M d, Y — h:i A'),
            'generatedBy'    => $request->user()->name,
        ])->setPaper('a4', 'landscape');

        return $pdf->download('leads_report.pdf');
    }

    /* ================================================================
     *  PIPELINE
     * ================================================================ */

    public function pipelineCsv(Request $request): StreamedResponse
    {
        $pipeline = $this->pipelineQuery($request);
        $grandTotal = $pipeline->sum('total_value');

        return $this->streamCsv(
            'pipeline_report.csv',
            ['Stage', 'Leads', 'Total Value', '% of Pipeline'],
            $pipeline->map(fn ($s) => [
                $s->status,
                $s->count,
                number_format($s->total_value, 2),
                $grandTotal > 0 ? round($s->total_value / $grandTotal * 100, 1) . '%' : '0%',
            ])->toArray()
        );
    }

    public function pipelinePdf(Request $request)
    {
        $pipeline   = $this->pipelineQuery($request);
        $grandTotal = $pipeline->sum('total_value');

        $pdf = Pdf::loadView('reports.exports.pipeline-pdf', [
            'pipeline'    => $pipeline,
            'grandTotal'  => $grandTotal,
            'generatedAt' => now()->format('M d, Y — h:i A'),
            'generatedBy' => $request->user()->name,
        ])->setPaper('a4', 'landscape');

        return $pdf->download('pipeline_report.pdf');
    }

    /* ================================================================
     *  USER ACTIVITY
     * ================================================================ */

    public function userActivityCsv(Request $request): StreamedResponse
    {
        $users = $this->userActivityQuery($request);

        return $this->streamCsv(
            'user_activity_report.csv',
            ['User', 'Email', 'Role', 'Customers', 'Leads', 'Activities', 'Follow-Ups'],
            $users->map(fn ($u) => [
                $u->name,
                $u->email,
                ucfirst($u->role),
                $u->customers_count,
                $u->leads_count,
                $u->activities_count,
                $u->follow_ups_count,
            ])->toArray()
        );
    }

    public function userActivityPdf(Request $request)
    {
        $users = $this->userActivityQuery($request);

        $pdf = Pdf::loadView('reports.exports.user-activity-pdf', [
            'users'       => $users,
            'generatedAt' => now()->format('M d, Y — h:i A'),
            'generatedBy' => $request->user()->name,
        ])->setPaper('a4', 'landscape');

        return $pdf->download('user_activity_report.pdf');
    }

    /* ================================================================
     *  FOLLOW-UPS
     * ================================================================ */

    public function followUpsCsv(Request $request): StreamedResponse
    {
        $followUps = $this->followUpsQuery($request)->get();

        return $this->streamCsv(
            'follow_ups_report.csv',
            ['Title', 'Due Date', 'Status', 'Assigned To', 'Linked To', 'Overdue'],
            $followUps->map(fn ($fu) => [
                $fu->title,
                $fu->due_date->format('Y-m-d'),
                $fu->status,
                $fu->user->name,
                $fu->customer ? $fu->customer->full_name : ($fu->lead ? $fu->lead->name : '—'),
                $fu->isOverdue() ? 'Yes' : 'No',
            ])->toArray()
        );
    }

    public function followUpsPdf(Request $request)
    {
        $query = FollowUp::query();
        if ($request->user()->isSales()) {
            $query->where('user_id', $request->user()->id);
        }

        $statusCounts = (clone $query)->selectRaw('status, COUNT(*) as count')->groupBy('status')->pluck('count', 'status');
        $overdue      = (clone $query)->where('status', '!=', 'Completed')->where('due_date', '<', now()->toDateString())->count();
        $followUps    = (clone $query)->with(['user', 'customer', 'lead'])->orderBy('due_date')->get();

        $pdf = Pdf::loadView('reports.exports.follow-ups-pdf', [
            'followUps'    => $followUps,
            'statusCounts' => $statusCounts,
            'overdue'      => $overdue,
            'generatedAt'  => now()->format('M d, Y — h:i A'),
            'generatedBy'  => $request->user()->name,
        ])->setPaper('a4', 'landscape');

        return $pdf->download('follow_ups_report.pdf');
    }

    /* ================================================================
     *  SHARED QUERIES (mirrors ReportController logic)
     * ================================================================ */

    private function customersQuery(Request $request)
    {
        $query = Customer::with('assignedUser');
        if ($request->user()->isSales()) {
            $query->where('assigned_user_id', $request->user()->id);
        }
        return $query->latest();
    }

    private function leadsQuery(Request $request)
    {
        $query = Lead::with('assignedUser');
        if ($request->user()->isSales()) {
            $query->where('assigned_user_id', $request->user()->id);
        }
        return $query->latest();
    }

    private function pipelineQuery(Request $request)
    {
        $query = Lead::query();
        if ($request->user()->isSales()) {
            $query->where('assigned_user_id', $request->user()->id);
        }
        return $query->selectRaw('status, COUNT(*) as count, COALESCE(SUM(expected_value),0) as total_value')
            ->groupBy('status')
            ->orderByRaw("FIELD(status,'New','Contacted','Qualified','Proposal Sent','Negotiation','Won','Lost')")
            ->get();
    }

    private function userActivityQuery(Request $request)
    {
        $query = User::withCount('activities', 'customers', 'leads', 'followUps');
        if ($request->user()->isSales()) {
            $query->where('id', $request->user()->id);
        }
        return $query->orderBy('name')->get();
    }

    private function followUpsQuery(Request $request)
    {
        $query = FollowUp::with(['user', 'customer', 'lead']);
        if ($request->user()->isSales()) {
            $query->where('user_id', $request->user()->id);
        }
        return $query->orderBy('due_date');
    }

    /* ================================================================
     *  CSV HELPER
     * ================================================================ */

    private function streamCsv(string $filename, array $headers, array $rows): StreamedResponse
    {
        return response()->streamDownload(function () use ($headers, $rows) {
            $handle = fopen('php://output', 'w');

            // UTF-8 BOM for proper Excel encoding
            fwrite($handle, "\xEF\xBB\xBF");

            fputcsv($handle, $headers);

            foreach ($rows as $row) {
                fputcsv($handle, $row);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
