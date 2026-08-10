<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Cek apakah pengguna login dan apakah role ada dalam daftar yang diizinkan
        if (!$request->user() || !in_array($request->user()->role, $roles, true)) {
            abort(403, 'Unauthorized action.');
        }

        // Lanjutkan request jika role cocok
        return $next($request);
    }
}
