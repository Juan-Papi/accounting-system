<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;


trait ApiResponder
{
    /**
     * Return o success Json response.
     *
     * @param string $message
     * @param int $code
     * @param $data
     * @return JsonResponse
     */
    //TODO: Funciones para la respuesta del servidor , con sus respectivo estado
    protected function success(string $message, $data = null, int $code = 200): JsonResponse
    {
        return response()->json([
            "status" => "Success",
            "message" => $message,
            "data" => $data,

        ], $code);
    }

    /**
     * Return o error Json response.
     *
     * @param string $message
     * @param int $code
     * @param null $data
     * @return JsonResponse
     */
    // Funcion por si el servidor da un error en la respuesta
    protected function error(string $message, $data = null, int $code = 400): JsonResponse
    {
        return response()->json([
            "status" => "Error",
            "message" => $message,
            "data" => $data,

        ], $code);
    }
}
