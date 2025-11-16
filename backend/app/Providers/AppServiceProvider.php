<?php

namespace App\Providers;

use App\Models\QueryLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use Str;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        Model::shouldBeStrict(!app()->isProduction());
    }

    public function boot(): void
    {
        DB::listen(function ($query) {
            if (Str::contains($query->sql, ['query_logs']) || !DB::getSchemaBuilder()->hasTable('query_logs')) {
                return;
            }

            QueryLog::create([
                'sql' => $query->sql,
                'bindings' => json_encode($query->bindings),
                'duration_ms' => $query->time,
                'full_query' => vsprintf(str_replace('?', '%s', $query->sql), collect($query->bindings)
                    ->map(fn ($binding) => is_numeric($binding) ? $binding : "'{$binding}'")
                    ->toArray()),
            ]);
        });
    }
}
