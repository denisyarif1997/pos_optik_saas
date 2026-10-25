<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureActiveStore
{
    public function handle(Request $request, Closure $next): Response
    {
        $activeStore = app('activeStore');

        if (! $activeStore->check($request->user())) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Akun Anda belum terhubung ke toko.'], 403);
            }

            return redirect()->route('stores.create')
                ->with('error', 'Lengkapi profil toko Anda untuk mulai memakai aplikasi.');
        }

        return $next($request);
    }
}
