<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        try {
            $query = Report::with(['reporter', 'reported', 'product'])->latest();

            $filter = $request->get('filter', 'open');
            if ($filter !== 'all') {
                $query->where('status', $filter);
            }

            if ($search = $request->get('search')) {
                $query->where(function ($q) use ($search) {
                    $q->whereHas('reported', fn($q) => $q->where('name', 'like', "%{$search}%"))
                      ->orWhere('reason', 'like', "%{$search}%");
                });
            }

            $reports = $query->paginate(20)->withQueryString();

            $counts = [
                'all'       => Report::count(),
                'open'      => Report::where('status', 'open')->count(),
                'reviewed'  => Report::where('status', 'reviewed')->count(),
                'actioned'  => Report::where('status', 'actioned')->count(),
                'dismissed' => Report::where('status', 'dismissed')->count(),
            ];
        } catch (\Exception $e) {
            $reports = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 20);
            $counts  = ['all' => 0, 'open' => 0, 'reviewed' => 0, 'actioned' => 0, 'dismissed' => 0];
        }

        return view('reports.index', compact('reports', 'counts'));
    }

    public function dismiss(Report $report): RedirectResponse
    {
        $report->update(['status' => 'dismissed']);
        return back()->with('success', 'Report dismissed.');
    }

    public function action(Request $request, Report $report): RedirectResponse
    {
        $report->update([
            'status'      => 'actioned',
            'admin_notes' => $request->input('notes'),
        ]);

        if ($request->boolean('suspend_user')) {
            $report->reported->update(['is_suspended' => true]);
        }

        return back()->with('success', 'Action taken on report.');
    }
}
