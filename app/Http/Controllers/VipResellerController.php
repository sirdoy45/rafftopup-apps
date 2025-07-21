<?php


namespace App\Http\Controllers;
// require_once 'vipayment.class.php';

use Illuminate\Support\Facades\Http;
use vipayment;

class VipResellerController extends Controller
{
    
//    public $vipayment = new VIPayment('API_ID', 'API_KEY');

    public function getGameServices()
    {
        
        
        $url = env('VIP_ENDPOINT_GAME') . '?' . http_build_query([
            'key' => env('VIP_API_KEY'),
            'sign' => env('VIP_SIGN'),
            'type' => 'services'
        ]);

        $response = Http::get($url);

        return response()->json($response->json());
    }

    public function getPulsaServices()
    {
        $response = Http::get(env('VIP_ENDPOINT_PULSA'), [
            'key' => env('VIP_API_KEY'),
            'sign' => env('VIP_SIGN'),
            'type' => 'layanan'
        ]);

        return response()->json($response->json());
    }
}

