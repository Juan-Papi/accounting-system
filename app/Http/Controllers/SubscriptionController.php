<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use Illuminate\Http\Request;
use App\Models\Plan;
use Illuminate\Support\Facades\Http;


class SubscriptionController extends Controller
{
   
    public static function obtenerQr($selectedPlan){           
        $secretKey = env('VERIPAGOS_SECRET_KEY');
        $apiVeriPagosQr = env('VERIPAGOS_API_GENERATE_QR');

        try {
            $response = Http::withBasicAuth($secretKey, '')
            ->post($apiVeriPagosQr, [
                'secret_key' => $secretKey,
                'monto' => $selectedPlan->price,
            ]);

            $data = $response->json();

            if ($data['Codigo'] === 0 && isset($data['Data']['qr'])) {
                return 'data:image/png;base64,' . $data['Data']['qr'];
            } else {
                return $data;
            }

        } catch (\Exception $e) {
            \Log::error('Error al generar QR VeriPagos: ' . $e->getMessage());

            return null;

        }
    }
}
