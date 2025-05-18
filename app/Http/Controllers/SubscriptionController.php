<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use Illuminate\Http\Request;
use App\Models\Plan;
use App\Models\PlanSubscription;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;


class SubscriptionController extends Controller
{   
    const USERNAME = "AndresContreras";
    const PASSWORD = "K?5a3HCMjX";
    private static $secretKey = "3a878669-bf0e-4b21-a093-674e6befd18c";
    private static $ENDPOINT = "https://veripagos.com/api/bcp";

   
    public static function generarQr($plan,$detalle)
    {   
        $monto = $plan->price;
        $curl = curl_init();
        $user = self::USERNAME;
        $pass = self::PASSWORD;

        $data = [
            "secret_key" => self::$secretKey,
            "monto" => $monto,
            "detalle" => "Inscripcion",
            "data" => [],
            "vigencia" => "30/23:30",
            "uso_unico" => true,
            "detalle" => "Suscripción con el plan: ".$detalle,
        ];
        Log::debug('Data para generar QR: ' . json_encode($data));
        curl_setopt_array($curl, array(
            CURLOPT_URL => self::$ENDPOINT . '/generar-qr',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
            CURLOPT_USERPWD => "$user:$pass",
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        $response = json_decode($response, true);
        return json_decode($response, true);    
    }

    public static function verificarQr($movimiento_id)
    {
        $curl = curl_init();
        $user = self::USERNAME;
        $pass = self::PASSWORD;

        $data = [
            "secret_key" => self::$secretKey,
            "movimiento_id" => $movimiento_id,
        ];
        Log::debug('Data para verificar QR: ' . json_encode($data));
        curl_setopt_array($curl, array(
            CURLOPT_URL => self::$ENDPOINT . '/verificar-estado-qr',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
            CURLOPT_USERPWD => "$user:$pass",
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        return json_decode($response);
    }

    public static function solicitarPago($monto, $tipo, $numeroSoli, $carnet, $complemento, $extension, $fechaExpiracion)
    {
        $curl = curl_init();
        $user = self::USERNAME;
        $pass = self::PASSWORD;
        $data = [
            "secret_key" => self::$secretKey,
            "monto" => $monto,
            "tipo" => $tipo,
            "numero_soli" => $numeroSoli,
            "carnet" => $carnet,
            "complemento" => $complemento,
            "extension" => $extension
        ];

        if ($fechaExpiracion != null) {
            $data["fecha_expiracion_tarjeta"] = $fechaExpiracion;
        }
        curl_setopt_array($curl, array(
            CURLOPT_URL => self::$ENDPOINT . '/solicitar-pago',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
            CURLOPT_USERPWD => "$user:$pass",
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        return json_decode($response);
    }

    public static function confirmarPago($movimiento_id, $otp)
    {
        $curl = curl_init();
        $user = self::USERNAME;
        $pass = self::PASSWORD;

        $data = [
            "secret_key" => self::$secretKey,
            "movimiento_id" => $movimiento_id,
            "otp" => $otp,
        ];
        curl_setopt_array($curl, array(
            CURLOPT_URL => self::$ENDPOINT . '/confirmar-pago',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
            CURLOPT_USERPWD => "$user:$pass",
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        return json_decode($response);
    }

    public static function verificarUsuario($usuario, $password)
    {
        return $usuario == self::USERNAME and $password == self::PASSWORD;
    }

    public static function verifyAndRegisterSubscription($moveId, $planId){
        try {
            $maxAttempts = 2;
            $waitSeconds = 3;
            $startDate = Carbon::now();
            $plan = Plan::find($planId);

            if (!$plan) {
                return ['success' => false, 'message' => 'Plan no encontrado.'];
            }

            $endDate = $startDate->copy()->addDays($plan->duration_days); 

            for ($attempt = 1; $attempt <= $maxAttempts; $attempt++) {
                $response = self::verificarQr($moveId);

                Log::debug('Intento ' . $attempt . ': ' . json_encode($response));
                Log::info('Contenido de respuesta QR:', (array) $response->Data);

                if ($response && isset($response->Codigo) && $response->Codigo === 0) {
                    $status = strtolower($response->Data->estado);

                    // Si el pago es completado, registrar la suscripción
                    if ($status === 'completado') {
                        $planSubscription = PlanSubscription::create([
                            'start_time' => $startDate,
                            'end_time' => $endDate,
                            'status' => 'active',
                            'plan_id' => $plan->id,
                            'user_id' => Auth::id(),
                        ]);

                        return [
                            'success' => true, 
                            'message' => 'Suscripción creada exitosamente.',
                            'planSubscription' => $planSubscription
                        ];
                    }

                    sleep($waitSeconds);
                } else {
                    $message = $response->Mensaje ?? 'Error desconocido en la respuesta';
                    return ['success' => false, 'message' => $message];
                }
            }

            return [
                'success' => false,
                'message' => 'El pago no ha sido confirmado después de varios intentos.',
                'status' => 'pending'
            ];

        } catch (\Exception $e) {
            Log::error('Error al verificar y registrar la suscripción: ' . $e->getMessage());
            return ['success' => false, 'error' => 'Fallo en la verificación: ' . $e->getMessage()];
        }
    }

}
