<?php

namespace App\Http\Controllers\tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthApiController extends Controller
{
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $credentials = $request->only(['email', 'password']);

            if (! $token = Auth::attempt($credentials)) {
                return $this->sendError('Unauthorized');
            }

            $user = Auth::user();

            return $this->sendResponse([
                'user' => $user,
                'access_token' => $token,
                'token_type' => 'bearer'
            ], 'Sesión iniciada...');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function me(): JsonResponse
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return $this->sendError('No hay usuario atenticado');
            }

            return $this->sendResponse([
                'user' => $user
            ], 'Enviando datos del usuario');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function refresh(Request $request)
    {
        try {
            $token = JWTAuth::getToken();

            if (!$token) {
                return response()->json([
                    'error' => 'Token no enviado'
                ], 401);
            }

            $newToken = JWTAuth::setToken($token)->refresh();

            return response()->json([
                'access_token' => $newToken,
                'token_type' => 'bearer',
            ]);
        } catch (TokenExpiredException $e) {
            return  response()->json(['error' => 'Token expirado y no se puede refrescar'], 401);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al refrescar el token', 'message' => $e->getMessage()], 500);
        }
    }

    public function logout()
    {
        try {
            Auth::logout();
            return $this->sendSuccess('Sesión cerrada exitosa.');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }
}
