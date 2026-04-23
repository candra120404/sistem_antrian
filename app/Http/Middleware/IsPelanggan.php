<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware IsPelanggan — memastikan hanya user berperan pelanggan yang bisa akses.
 */
class IsPelanggan
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! auth()->check() || auth()->user()->isAdmin()) {
            abort(403, 'Akses ditolak.');
        }

        return $next($request);
    }
}
