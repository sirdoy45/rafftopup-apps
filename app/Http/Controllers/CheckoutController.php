<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Events\NewTransactionEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Midtrans\Snap;
use Illuminate\Support\Facades\Http;
use App\Events\TransactionCreated;
use Illuminate\Support\Facades\Log;
// require_once 'vipayment.class.php';

class VIPayment {
     
    // Endpoint API
    public string $end_point = 'https://vip-reseller.co.id/api';

    // API ID
    protected string $api_id;
    // API Key
    protected string $api_key;
    // Signature
    protected string $signature;

    /**
     * Constructor
     * @param string $api_id
     * @param string $api_key
     */
    public function __construct(
        string $api_id, 
        string $api_key
    ) {
        $this->api_id = $api_id;
        $this->api_key = $api_key;
        $this->signature = md5($api_id . $api_key);
    }

    /**
     * Profile
     * 
     * @return array
     * 
     * @example profile()
     */
    public function profile(): array
    {
        $end_point = $this->end_point . '/profile';

        $params = [
            'key' => $this->api_key,
            'sign' => $this->signature
        ];

        $request = $this->connect($end_point, $params);

        $response = json_decode($request, true);

        // ✅ Tambahkan validasi null
        if (is_null($response)) {
            return [
                'status' => false,
                'message' => 'Gagal menghubungi server. Response kosong atau tidak valid.'
            ];
        }

        if (isset($response['result']) && $response['result'] == false) {
            return [
                'status' => false,
                'message' => $response['message']
            ];
        }

        return [
            'status' => true,
            'data' => $response['data'] ?? [],
            'message' => $response['message'] ?? ''
        ];
    }


    /**
     * Status Order Prepaid
     * @param string $trxid transaction id
     * @param null|int $limit (optional)
     * 
     * @return array
     * 
     * @example status_order_prepaid('1234567890', 1)
     * @example status_order_prepaid('1234567890')
     */
    public function status_order_prepaid(
        string $trxid,
        ?int $limit = null
    ): array 
    {
        $end_point = $this->end_point . '/prepaid';

        $params = [
            'key' => $this->api_key,
            'sign' => $this->signature,
            'type' => 'status',
            'trxid' => $trxid,
            'limit'=> $limit
        ];

        $request = $this->connect($end_point, $params);

        $response = json_decode($request, true);

        if (isset($response['result']) && $response['result'] == false) {
            return [
                'status' => false,
                'message' => $response['message']
            ];
        }

        return [
            'status' => true,
            'data' => $response['data'],
            'message' => $response['message']
        ];
    }

    /**
     * Service Prepaid
     * @param string $filter_type (optional | type, brand)
     * @param null|string $filter_value (optional | pulsa-reguler, telkomsel)
     * 
     * @return array
     * 
     * @example service_prepaid('type', 'pulsa-reguler')
     * @example service_prepaid('brand', 'telkomsel')
     */
    public function service_prepaid(
        ?string $filter_type = null,
        ?string $filter_value = null
    ): array
    {
        $end_point = $this->end_point . '/prepaid';

        $params = [
            'key'=> $this->api_key,
            'sign'=> $this->signature,
            'type'=> 'services',
            'filter_type'=> $filter_type,
            'filter_value'=> $filter_value
        ];

        $request = $this->connect($end_point, $params);

        $response = json_decode($request, true);

        if (isset($response['result']) && $response['result'] == false) {
            return [
                'status'=> false,
                'message'=> $response['message']
            ];
        }

        return [
            'status'=> true,
            'data'=> $response['data'],
            'message'=> $response['message']
        ];
    }

    /**
     * Order Game & Streaming
     * @param string $service service code
     * @param string $data_no target number
     * @param null|string $data_zone (optional)
     * 
     * @return array
     * 
     * @example order_game('GARENA', '1234567890', 'ID')
     * @example order_game('STEAM', '1234567890')
     */
    public function order_game(
        string $service,
        string $data_no,
        ?string $data_zone = null
    ): array 
    {
        $end_point = $this->end_point . '/game-feature';

        $params = [
            'key'=> $this->api_key,
            'sign'=> $this->signature,
            'type'=> 'order',
            'service'=> $service,
            'data_no'=> $data_no,
            'data_zone'=> $data_zone
        ];

        $request = $this->connect($end_point, $params);

        $response = json_decode($request, true);

        if (isset($response['result']) && $response['result'] == false) {
            return [
                'status'=> false,
                'message'=> $response['message']
            ];
        }

        return [
            'status'=> true,
            'data'=> $response['data'],
            'message'=> $response['message']
        ];
    }

    /**
     * Status Order Game & Streaming
     * @param string $trxid
     * @param null|int $limit (optional)
     * 
     * @return array
     * 
     * @example status_order_game('1234567890', 1)
     * @example status_order_game('1234567890')
     */

    public function status_order_game(
        string $trxid,
        ?int $limit = null
    ): array
    {
        $end_point = $this->end_point . '/game-feature';

        $params = [
            'key'=> $this->api_key,
            'sign'=> $this->signature,
            'type'=> 'status',
            'trxid'=> $trxid,
            'limit'=> $limit
        ];

        $request = $this->connect($end_point, $params);

        $response = json_decode($request, true);

        if (isset($response['result']) && $response['result'] == false) {
            return [
                'status'=> false,
                'message'=> $response['message']
            ];
        }

        return [
            'status'=> true,
            'data'=> $response['data'],
            'message'=> $response['message']
        ];
    }

    /**
     * Service Game & Streaming
     * @param string $filter_type (optional | type, brand)
     * @param null|string $filter_value (optional | game, streaming)
     * @param null|string $filter_status (optional | available / empty)
     * 
     * @return array
     * 
     * @example service_game('type', 'game')
     * @example service_game('brand', 'streaming')
     * @example service_game('brand', 'streaming', 'available')
     */

    public function service_game(
        ?string $filter_type = null,
        ?string $filter_value = null,
        ?string $filter_status = null
    ): array
    {
        $end_point = $this->end_point . '/game-feature';

        $params = [
            'key'=> $this->api_key,
            'sign'=> $this->signature,
            'type'=> 'services',
            'filter_type'=> $filter_type,
            'filter_value'=> $filter_value,
            'filter_status'=> $filter_status
        ];

        $request = $this->connect($end_point, $params);

        $response = json_decode($request, true);

        if (isset($response['result']) && $response['result'] == false) {
            return [
                'status'=> false,
                'message'=> $response['message']
            ];
        }

        return [
            'status'=> true,
            'data'=> $response['data'],
            'message'=> $response['message']
        ];
    }

    /**
     * Connect
     * @param string $endpoint
     * @param array $params
     * 
     * @return string|bool
     */
    private function connect(
        string $endpoint, 
        array $params
    ): string | bool 
    {
        $_post = [];
        if (is_array($params)) {
            foreach ($params as $name => $value) {
                $_post[] = $name . '=' . urlencode($value);
            }
        }

        $ch = curl_init($endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        if (is_array($params)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, join('&', $_post));
        }
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)');
        $response = curl_exec($ch);
        if (curl_errno($ch) != 0 && empty($response)) {
            $response = false;
        }
        curl_close($ch);
        return $response;
    }
}



class CheckoutController extends Controller
{
    public function buyForm($slug)
    {
        Log::info('🔍 Mengakses halaman pembelian produk', ['slug' => $slug]);

        $vipayment = new VIPayment(
            config('services.vipreseller.api_id'),
            config('services.vipreseller.api_key')
        );

        $profile = $vipayment->profile();

        if (!is_array($profile)) {
            Log::error('❌ Profile tidak valid atau kosong', ['response' => $profile]);
            return back()->with('error', 'Gagal menghubungi server VIP Reseller. Cek koneksi atau API.');
        }

        $product = Product::where('slug', $slug)->firstOrFail();
        return view('pages.buy', compact('product'));
    }

    public function process(Request $request, $slug)
    {
         if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu untuk melakukan transaksi.');
        }

        $user = Auth::user();
            
        // Konfigurasi Midtrans
        \Midtrans\Config::$serverKey = config('midtrans.serverKey');
        \Midtrans\Config::$isProduction = config('midtrans.isProduction');
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        $product = Product::where('slug', $slug)->firstOrFail();
        $user = Auth::user();

        // Validasi input
        $rules = [
            'customer_phone' => 'required|string|max:15',
        ];

        if ($product->input_type === 'id_game') {
            $rules['game_id'] = 'required|string';
            $rules['server'] = 'required|string';
        } elseif ($product->input_type === 'user_id') {
            $rules['user_id'] = 'required|string';
        } elseif ($product->input_type === 'no_hp') {
            $rules['phone_number'] = 'required|string|max:15';
        }

        $validated = $request->validate($rules);

        // Buat transaksi
        $code = 'GMK-' . mt_rand(10000, 99999) . strtoupper(substr($user->email, 0, 3));
        $detailCode = 'STF-' . mt_rand(10000, 99999) . strtoupper(substr($user->email, 0, 3));
        $tax = 5000;
        $totalPrice = $product->price + $tax;

        $transaction = Transaction::create([
            'users_id' => $user->id,
            'tax_price' => $tax,
            'total_price' => $totalPrice,
            'status' => 'PENDING',
            'code' => $code,
            'payment_method' => null,
            'is_read' => false,
        ]);

        $transactionDetail = TransactionDetail::create([
            'transactions_id' => $transaction->id,
            'products_id' => $product->id,
            'price' => $product->price,
            'delivery_status' => 'PENDING',
            'code' => $detailCode,
            'customer_phone' => $validated['customer_phone'],
            'id_game' => $product->input_type === 'id_game' ? $validated['game_id'] : null,
            'server' => $product->input_type === 'id_game' ? $validated['server'] : null,
            'user_id' => $product->input_type === 'user_id' ? $validated['user_id'] : null,
            'target_phone_number' => $product->input_type === 'no_hp' ? $validated['phone_number'] : null,
            'quantity' => 1,
        ]);

        // Kurangi stok
        $product->decrement('quantity');

        event(new TransactionCreated($transaction));

        // Buat Snap Token
        $midtransParams = [
            'transaction_details' => [
                'order_id' => $code,
                'gross_amount' => $totalPrice,
            ],
            'customer_details' => [
                'first_name' => $user->name,
                'email' => $user->email,
                'phone' => $validated['customer_phone'],
            ],
            'credit_card' => [
                'secure' => true
            ],
            'enabled_payments' => [
                'gopay',
                'shopeepay',
                'bank_transfer',
                'qris',
                'permata_va',
                'bca_va',
                'bni_va',
                'bri_va',
                'indomaret',
                'alfamart'
            ],
            'callbacks' => [
                'finish' => route('payment.success')
            ]
        ];

        $snapToken = Snap::getSnapToken($midtransParams);
        $transaction->snap_token = $snapToken;
        $transaction->save();

        return view('pages.buy_snap', compact('snapToken'));
    }

    public function success(Request $request)
    {
        $orderId = $request->get('order_id');

        if (!$orderId) {
            return redirect()->route('payment.failed')->with('error', 'Order ID tidak ditemukan.');
        }

        $transaction = Transaction::where('code', $orderId)->first();

        if (!$transaction) {
            return redirect()->route('payment.failed')->with('error', 'Transaksi tidak ditemukan.');
        }

        // Konfigurasi Midtrans
        \Midtrans\Config::$serverKey = config('midtrans.serverKey');
        \Midtrans\Config::$isProduction = config('midtrans.isProduction');

        try {
            $status = \Midtrans\Transaction::status($orderId);

            if (is_array($status)) {
                $status = (object) $status;
            }

            // ✅ Jika statusnya settlement atau capture, tampilkan sukses
            if (in_array($status->transaction_status ?? '', ['settlement', 'capture'])) {
                $detail = $transaction->details()->first(); // ambil 1 detail

                return view('pages.payment_succes', compact('transaction'));
            }

            // ✅ Jika tidak, arahkan ke halaman gagal
            return redirect()->route('payment.failed')->with('error', 'Pembayaran tidak berhasil.');

        } catch (\Exception $e) {
            return redirect()->route('payment.failed')->with('error', 'Gagal memverifikasi status pembayaran.');
        }
    }

    public function failed()
    {
        return view('pages.payment_failed');
    }

    public function sendToVipReseller($transaction, $detail)
    {
        if ($detail->delivery_status === 'DELIVERED') {
            Log::info('ℹ️ Produk sudah terkirim sebelumnya, tidak dikirim ulang.', [
                'transaction_id' => $transaction->id,
                'detail_id' => $detail->id
            ]);
            return true;
        }

        $product = $detail->product;

        if (!$product) {
            Log::error('❌ Produk tidak ditemukan dalam detail.', [
                'transaction_id' => $transaction->id,
                'detail_id' => $detail->id
            ]);
            return false;
        }

        $apiKey = config('services.vipreseller.api_key');
        $apiId  = config('services.vipreseller.api_id');
        $sign   = md5($apiId . $apiKey);

        $dataNo = $detail->id_game ?? $detail->user_id ?? $detail->target_phone_number;

        if (empty($dataNo)) {
            Log::error('❌ Data target kosong. Tidak bisa kirim order.', [
                'transaction_id' => $transaction->id,
                'detail_id' => $detail->id
            ]);
            return false;
        }

        $payload = [
            'key'     => $apiKey,
            'sign'    => $sign,
            'type'    => 'order',
            'service' => $product->kode_produk,
            'data_no' => $dataNo,
        ];

        if ($product->input_type === 'id_game') {
            $payload['zone_id'] = $detail->server;
        }

        Log::info('🔁 Mengirim order ke VIP Reseller', $payload);

        $response = Http::asForm()->post(config('services.vipreseller.endpoint'), $payload);

        $result = $response->json();

        Log::info('📩 Respons dari VIP Reseller', is_array($result) ? $result : ['raw' => $response->body()]);

        if (isset($result['data']['status']) && $result['data']['status'] == 1) {
            $detail->delivery_status = 'DELIVERED';
            $detail->save();
            return true;
        }

        Log::warning('❌ Gagal kirim ke VIP Reseller', [
            'payload' => $payload,
            'response' => is_array($result) ? $result : ['raw' => $response->body()]
        ]);

        return false;
    }

}
