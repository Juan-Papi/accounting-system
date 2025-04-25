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

class AuthController extends Controller
{
    use ApiResponder;
    public function login(): JsonResponse
    {
        request()->validate([
            "email" => "required|email",
            "password" => "required|min:6|max:20",
            "device_name" => "required"

        ]);

         // obtiene los usuarios que no tienen el rol de Administrador y Ejecutivo de ventas
        $usuarioCliente = User::select(["id", "name", "password", "email"])
            ->where("email", request("email"))
            ->whereDoesntHave("roles", function ($q) {
                $q->whereIn("name", ["Administrador", "Ejecutivo de ventas"]);
            })
            ->first();


        /* Verificacion si el usuarioCliente existe */
        if (!$usuarioCliente || !Hash::check(request("password"), $usuarioCliente->password)) {
            throw ValidationException::withMessages([
                "email" => [__("Credenciales incorrectas")]
            ]);
        }

        $token = $usuarioCliente->createToken(request("device_name"))->plainTextToken;


        return $this->success(
            __("Bienvenid@"),
            [
                "cliente" => $usuarioCliente->toArray(),

                "token" => $token,
            ]
        );
    }


    //TODO: Funcion para cerrar sesion
    public function logout(): JsonResponse
    {
        //Recuperando el token
        $token = request()->bearerToken();

        /** @var PersonalAccessToken $model */

        $model = Sanctum::$personalAccessTokenModel;

        $accessToken = $model::findToken($token);
        /* si existe el token se eliminara */

        $accessToken->delete();


        return $this->success(
            __("Has cerrado sesion con exito!"),
            data: null,
            code: 204,

        );
    }


    //TODO: PARA EL REGISTRO DEL CLIENTE
    public function signup(): JsonResponse
    {
        request()->validate([
            "name" => "required|min:2|max:60",
            "email" => "required|email|unique:users",
            "password" => "required|min:8|max:20",
            "passwordConfirmation" => "required|same:password|min:8|max:20",
        ]);

        User::create([
            "name" => request("name"),
            "email" => request("email"),
            "password" => bcrypt(request("password")),
            "created_at" => now(),

        ]);

        return $this->success(
            __("Cuenta creada")
        );
    }
}
