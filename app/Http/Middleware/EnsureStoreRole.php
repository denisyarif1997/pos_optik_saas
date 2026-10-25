<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureStoreRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $role = app('activeStore')->role($request->user());

        if (! $role || (! empty($roles) && ! in_array($role, $roles, true))) {
            abort(403, 'Anda tidak punya akses untuk tindakan ini.');
        }

        return $next($request);
    }
}
