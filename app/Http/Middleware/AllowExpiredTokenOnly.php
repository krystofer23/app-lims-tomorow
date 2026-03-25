<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AllowExpiredTokenOnly
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            JWTAuth::parseToken()->authenticate();

            return response()->json([
                'status' => 'error',
                'error' => 'Este endpoint es solo para tokens expirados',
            ], Response::HTTP_FORBIDDEN);
        } catch (TokenExpiredException $e) {
            return $next($request);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'error' => 'Token inválido o no enviado, error: ' . $e->getMessage(),
                'message' => $e->getMessage()
            ], Response::HTTP_UNAUTHORIZED);
        }
    }
}
