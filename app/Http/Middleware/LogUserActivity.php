<?php

namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use App\Models\UserActivityLog; // Create this model
use Carbon\Carbon;

class LogUserActivity
{
    public function handle(Request $request, Closure $next)
    {
        // Store the start time
        $request->start = microtime(true);

        // Pass the request further down the pipeline
        $response = $next($request);

        // Calculate duration when the response is sent
        $duration = microtime(true) - $request->start;

        // Log user activity (for authenticated users or guests)
        UserActivityLog::create([
            'user_id' => auth()->check() ? auth()->id() : null,
            'url' => substr(request()->fullUrl(), 0, 254),
            'method' => $request->method(),
            'accessed_at' => Carbon::now(),
            'duration' => round($duration, 2), // Duration in seconds
            'user_agent' => $request->header('User-Agent'),
            'ip_address' => $request->ip(),
        ]);

        return $response;
    }
}

