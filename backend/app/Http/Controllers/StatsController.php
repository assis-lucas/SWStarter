<?php

namespace App\Http\Controllers;

use App\Models\QueryLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class StatsController extends Controller
{
    public function __invoke(): JsonResponse
    {
        $status = Cache::remember('query_stats', now()->addMinutes(5), fn () => [
            'top_queries' => QueryLog::select('sql', DB::raw('COUNT(*) as total'), DB::raw('AVG(duration_ms) as avg_time'))
                ->groupBy('sql')
                ->orderByDesc('total')
                ->limit(5)
                ->get()
                ->map(fn ($q) => [
                    'sql' => $q->sql,
                    'total' => $q->total,
                    'avg_time' => round($q->avg_time, 2),
                ]),
            'avg_query_time' => QueryLog::avg('duration_ms') ? round(QueryLog::avg('duration_ms'), 2) : null,
            'popular_hours' => QueryLog::select(
                DB::raw('HOUR(created_at) as hour'),
                DB::raw('COUNT(*) as total'),
                DB::raw('AVG(duration_ms) as avg_time')
            )
                ->groupBy('hour')
                ->orderBy('hour')
                ->get()
                ->map(fn ($h) => [
                    'hour' => $h->hour,
                    'total' => $h->total,
                    'avg_time' => round($h->avg_time, 2),
                ]),
            'summary' => [
                'total_queries' => QueryLog::count(),
                'fastest_query' => round(QueryLog::min('duration_ms'), 2),
                'slowest_query' => round(QueryLog::max('duration_ms'), 2),
                'total_query_time' => round(QueryLog::sum('duration_ms') / 1000, 2),
            ],
            'slowest_queries' => QueryLog::select('sql', DB::raw('MAX(duration_ms) as max_time'), DB::raw('COUNT(*) as count'))
                ->groupBy('sql')
                ->orderByDesc('max_time')
                ->limit(5)
                ->get()
                ->map(fn ($q) => [
                    'sql' => $q->sql,
                    'max_time' => round($q->max_time, 2),
                    'count' => $q->count,
                ]),
            'query_performance' => [
                'fast' => QueryLog::where('duration_ms', '<', 50)->count(),
                'medium' => QueryLog::whereBetween('duration_ms', [50, 200])->count(),
                'slow' => QueryLog::where('duration_ms', '>', 200)->count(),
            ],
            'recent_activity' => QueryLog::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as total')
            )
                ->where('created_at', '>=', now()->subDays(7))
                ->groupBy('date')
                ->orderBy('date')
                ->get()
                ->map(fn ($d) => [
                    'date' => $d->date,
                    'total' => $d->total,
                ]),
        ]);

        return response()->json($status);
    }
}
