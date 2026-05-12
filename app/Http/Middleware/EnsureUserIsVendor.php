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
  public function handle(Request $request, Closure $next): Response
  {
    if (!$request->user() || $request->user()->role !== 'vendor') {
      return response()->json([
        'message' => 'Unauthorized. Vendor access required.'
      ], 403);
    }

    return $next($request);
  }
}
