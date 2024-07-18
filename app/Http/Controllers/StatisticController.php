<?php

namespace App\Http\Controllers;

use App\Models\Incident;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StatisticController extends Controller
{
    public function index()
    {
        $page_title = 'statistic';

        return view('statistic.index', compact('page_title'));
    }

    public function getIncidentChartData(Request $request)
    {
        $range = $request->get('range', 'daily');
        $startDate = $request->get('start_date') ? Carbon::parse($request->get('start_date'))->startOfDay() : Carbon::now()->subDays(30)->startOfDay();
        $endDate = $request->get('end_date') ? Carbon::parse($request->get('end_date'))->endOfDay() : Carbon::now()->endOfDay();

        $user = Auth::user();
        $query = Incident::query();

        if ($startDate && $endDate) {
            $query->whereBetween('request_datetime', [$startDate, $endDate]);
        }

        // Adjust query for non-admin users
        if ($user->role !== 'admin') {
            $query->where('responder_id', $user->id);
        }

        switch ($range) {
            case 'monthly':
                $incidents = $query->selectRaw('DATE_FORMAT(request_datetime, "%Y-%m") as date, status, COUNT(*) as count')
                    ->groupBy('date', 'status')
                    ->orderBy('date')
                    ->get();
                break;

            case 'yearly':
                $incidents = $query->selectRaw('YEAR(request_datetime) as date, status, COUNT(*) as count')
                    ->groupBy('date', 'status')
                    ->orderBy('date')
                    ->get();
                break;

            case 'daily':
            default:
                $incidents = $query->selectRaw('DATE(request_datetime) as date, status, COUNT(*) as count')
                    ->groupBy('date', 'status')
                    ->orderBy('date')
                    ->get();
                break;
        }

        $dates = [];
        $requested = [];
        $processed = [];
        $completed = [];

        $currentDate = $startDate->copy();
        while ($currentDate->lte($endDate)) {
            $date = $currentDate->format($range === 'daily' ? 'Y-m-d' : ($range === 'monthly' ? 'Y-m' : 'Y'));
            $dates[] = $date;
            $requested[$date] = 0;
            $processed[$date] = 0;
            $completed[$date] = 0;
            $currentDate->addDay();
        }

        foreach ($incidents as $incident) {
            $date = $incident->date;

            switch ($incident->status) {
                case 'requested':
                    $requested[$date] = $incident->count;
                    break;
                case 'processed':
                    $processed[$date] = $incident->count;
                    break;
                case 'completed':
                    $completed[$date] = $incident->count;
                    break;
            }
        }

        $requestedCounts = array_values($requested);
        $processedCounts = array_values($processed);
        $completedCounts = array_values($completed);

        return response()->json([
            'dates' => $dates,
            'requested' => $requestedCounts,
            'processed' => $processedCounts,
            'completed' => $completedCounts
        ]);
    }

}
