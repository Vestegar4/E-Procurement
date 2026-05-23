<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsVendor
{
  /**
   * Handle an incoming request.
   *
   * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
   */
  public function handle(Request $request, Closure $next)
  {
    if (!auth()->check() || auth()->user()->role !== 'vendor') {
      // Kalau request dari web, redirect — bukan return JSON
      if ($request->expectsJson()) {
        return response()->json(['message' => 'Unauthorized. Vendor access required.'], 403);
      }

      return redirect('/login')->withErrors(['email' => 'Akses ditolak.']);
    }

    return $next($request);
  }
}
