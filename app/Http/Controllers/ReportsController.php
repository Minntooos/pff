<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Resource;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ReportsController extends Controller
{
    public function index()
    {
        $reportDescriptions = [
            'User Activity' => 'Analyze user engagement and interaction patterns over time.',
            'Resource Utilization' => 'Track the usage and popularity of various resources on the platform.',
            'System Performance' => 'Review system metrics, uptime, and performance indicators.',
            'User Growth' => 'Analyze user acquisition and retention trends.',
        ];

        return view('admin.GenerateReports', compact('reportDescriptions'));
    }

    private function getReportDescription($reportType)
    {
        $descriptions = [
            'User Activity' => 'Analyze user engagement and interaction patterns over time.',
            'Resource Utilization' => 'Track the usage and popularity of various resources on the platform.',
            'System Performance' => 'Review system metrics, uptime, and performance indicators.',
            'User Growth' => 'Analyze user acquisition and retention trends.',
        ];

        return $descriptions[$reportType] ?? 'No description available.';
    }

    public function generate(Request $request)
    {
        $reportType = $request->input('report_type');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $data = $this->getReportData($reportType, $startDate, $endDate);

        return response()->json([
            'success' => true,
            'message' => 'Report generated successfully',
            'data' => $data,
        ]);
    }

    public function preview($type)
    {
        $data = $this->getReportData($type, now()->subMonth(), now());

        return view('admin.reports.preview', compact('data', 'type'));
    }

    public function download($type)
    {
        $data = $this->getReportData($type, now()->subMonth(), now());
        $pdf = Pdf::loadView('admin.reports.pdf', compact('data', 'type'));

        return $pdf->download($type.'_report.pdf');
    }

    private function getReportData($type, $startDate, $endDate)
    {
        switch ($type) {
            case 'user-activity':
                return $this->getUserActivityReport($startDate, $endDate);
            case 'resource-utilization':
                return $this->getResourceUtilizationReport($startDate, $endDate);
            case 'system-performance':
                return $this->getSystemPerformanceReport($startDate, $endDate);
            case 'user-growth':
                return $this->getUserGrowthReport($startDate, $endDate);
            default:
                return [];
        }
    }

    private function getUserActivityReport($startDate, $endDate)
    {
        return [
            'total_active_users' => User::whereBetween('last_active_at', [$startDate, $endDate])->count(),
            'average_daily_sessions' => Activity::whereBetween('created_at', [$startDate, $endDate])
                ->groupBy('user_id', 'date')
                ->selectRaw('user_id, DATE(created_at) as date, COUNT(*) as session_count')
                ->get()
                ->avg('session_count'),
            'most_active_users' => User::withCount(['activities' => function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }])->orderByDesc('activities_count')->take(10)->get(),
        ];
    }

    private function getResourceUtilizationReport($startDate, $endDate)
    {
        try {
            $totalResources = Resource::whereBetween('created_at', [$startDate, $endDate])->count();

            $mostViewedResources = Resource::withCount(['activities' => function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate])->where('action', 'view');
            }])->orderByDesc('activities_count')->take(10)->get();

            $resourceTypesDistribution = Resource::whereBetween('created_at', [$startDate, $endDate])
                ->groupBy('type')
                ->selectRaw('type, COUNT(*) as count')
                ->pluck('count', 'type');

            return [
                'total_resources' => $totalResources,
                'most_viewed_resources' => $mostViewedResources,
                'resource_types_distribution' => $resourceTypesDistribution,
            ];
        } catch (\Exception $e) {
            Log::error('Error in getResourceUtilizationReport: '.$e->getMessage());
            Log::error($e->getTraceAsString());
            throw $e;
        }
    }

    private function getSystemPerformanceReport($startDate, $endDate)
    {
        // This is a placeholder. You'd need to implement actual system performance metrics.
        return [
            'average_response_time' => rand(100, 500), // in milliseconds
            'uptime_percentage' => rand(99, 100),
            'error_rate' => rand(0, 2),
        ];
    }

    private function getUserGrowthReport($startDate, $endDate)
    {
        return [
            'new_users' => User::whereBetween('created_at', [$startDate, $endDate])->count(),
            'user_growth_rate' => $this->calculateGrowthRate(User::class, $startDate, $endDate),
            'active_users_trend' => User::whereBetween('last_active_at', [$startDate, $endDate])
                ->groupBy('date')
                ->selectRaw('DATE(last_active_at) as date, COUNT(*) as count')
                ->orderBy('date')
                ->pluck('count', 'date'),
        ];
    }

    private function calculateGrowthRate($model, $startDate, $endDate)
    {
        $startCount = $model::where('created_at', '<', $startDate)->count();
        $endCount = $model::where('created_at', '<=', $endDate)->count();

        return $startCount > 0 ? (($endCount - $startCount) / $startCount) * 100 : 0;
    }
}
