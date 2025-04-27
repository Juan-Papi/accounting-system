<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\ApiResponder;
use Laravel\Sanctum\Sanctum;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    use ApiResponder;

    public function login(Request $request): JsonResponse
    {
        try {
            Log::info("Iniciando proceso de login");

            // Validar los datos de entrada
            $validated = $request->validate([
                "email" => "required|email",
                "password" => "required|min:6|max:20",
            ]);

            DB::beginTransaction();

            try {
                // Obtener el usuario por email
                $usuario = User::where("email", $validated["email"])->first();

                // Verificar si el usuario existe y la contraseña es correcta
                if (!$usuario || !Hash::check($validated["password"], $usuario->password)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Credenciales incorrectas'
                    ], 401);
                }

                // Verificar si el usuario tiene el rol de Gerente
                if (!$usuario->hasRole('Gerente')) {
                    return response()->json([
                        'success' => false,
                        'message' => 'No tienes permisos para acceder al sistema'
                    ], 403);
                }

                // Generar token
                $token = $usuario->createToken('auth_token')->plainTextToken;

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Bienvenido',
                    'data' => [
                        'user' => $usuario->only(['id', 'name', 'email']),
                        'token' => $token,
                    ]
                ], 200);
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error("Error en la base de datos: " . $e->getMessage());

                return response()->json([
                    'success' => false,
                    'message' => 'Error de conexión a la base de datos',
                    'error' => config('app.debug') ? $e->getMessage() : 'Problema al procesar la solicitud'
                ], 500);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning("Error de validación: " . json_encode($e->errors()));

            return response()->json([
                'success' => false,
                'message' => 'Datos de entrada inválidos',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error("Error general: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Ha ocurrido un error inesperado',
                'error' => config('app.debug') ? $e->getMessage() : 'Error interno del servidor'
            ], 500);
        }
    }

    public function logout(Request $request): JsonResponse
    {
        try {
            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'success' => true,
                'message' => 'Sesión cerrada correctamente'
            ], 200);
        } catch (\Exception $e) {
            Log::error("Error al cerrar sesión: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error al cerrar sesión',
                'error' => config('app.debug') ? $e->getMessage() : 'Error interno del servidor'
            ], 500);
        }
    }

    public function checkAuthStatus(Request $request): JsonResponse
    {
        try {
            // Obtener el usuario autenticado
            $usuario = $request->user();

            // Verificar si el usuario tiene el rol requerido
            if (!$usuario->hasRole('Gerente')) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tienes permisos para acceder al sistema'
                ], 403);
            }

            // Generar un nuevo token
            $newToken = $usuario->createToken('auth_token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Sesión verificada correctamente',
                'data' => [
                    'user' => $usuario->only(['id', 'name', 'email']),
                    'token' => $newToken,
                ]
            ], 200);
        } catch (\Exception $e) {
            Log::error("Error al verificar estado de autenticación: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error al verificar la sesión',
                'error' => config('app.debug') ? $e->getMessage() : 'Error interno del servidor'
            ], 500);
        }
    }
}
