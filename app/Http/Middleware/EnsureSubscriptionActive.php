<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSubscriptionActive
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->routeIs('billing.*') || $request->routeIs('stores.create') || $request->routeIs('logout')) {
            return $next($request);
        }

        $store = app('activeStore')->get();
        if (! $store) {
            return $next($request);
        }

        $subscription = $store->currentSubscription();
        if ($subscription && ! $subscription->isActive()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Langganan toko Anda telah berakhir.'], 402);
            }

            return redirect()->route('billing.index')
                ->with('error', 'Langganan toko Anda telah berakhir. Silakan perbarui paket.');
        }

        return $next($request);
    }
}
