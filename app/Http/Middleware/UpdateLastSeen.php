<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\OnlineStatusService;
use Symfony\Component\HttpFoundation\Response;

class UpdateLastSeen
{
    protected $onlineStatusService;

    public function __construct(OnlineStatusService $onlineStatusService)
    {
        $this->onlineStatusService = $onlineStatusService;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();

            // Mettre à jour last_seen (throttled à 1 minute)
            $this->onlineStatusService->updateLastSeen($user);
        }

        return $next($request);
    }
}
