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
            'top_queries' => QueryLog::select('sql', DB::raw('COUNT(*) as total'))
                ->groupBy('sql')
                ->orderByDesc('total')
                ->limit(5)
                ->get(),
            'avg_query_time' => QueryLog::avg('duration_ms'),
            'popular_hours' => QueryLog::select(
                DB::raw('HOUR(created_at) as hour'),
                DB::raw('COUNT(*) as total')
            )
                ->groupBy('hour')
                ->orderByDesc('total')
                ->get(),
        ]);

        return response()->json($status);
    }
}
