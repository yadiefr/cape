<?php

namespace App\Services;

use App\Models\Siswa;
use App\Models\Tagihan;
use App\Models\MidtransTransaction;
use Exception;
use Illuminate\Support\Facades\Log;

class MidtransServiceNew
{
    private $serverKey;
    private $clientKey;
    private $isProduction;
    private $baseUrl;

    public function __construct()
    {
        $this->serverKey = config('services.midtrans.server_key');
        $this->clientKey = config('services.midtrans.client_key');
        $this->isProduction = config('services.midtrans.is_production', false);
        $this->baseUrl = $this->isProduction ? 
            'https://app.midtrans.com' : 
            'https://app.sandbox.midtrans.com';
    }

    public function createTransaction($siswa, $tagihan, $grossAmount)
    {
        try {
            $orderId = 'ORDER-' . time() . '-' . $siswa->id . '-' . $tagihan->id;
            
            $customerEmail = $this->generateCustomerEmail($siswa);
            $customerDisplayName = $this->generateCustomerDisplayName($siswa);
            
            $payload = [
                'transaction_details' => [
                    'order_id' => $orderId,
                    'gross_amount' => (int)$grossAmount
                ],
                'customer_details' => [
                    'first_name' => $customerDisplayName,
                    'email' => $customerEmail,
                    'phone' => $siswa->telepon ?? '081234567890'
                ],
                'item_details' => [
                    [
                        'id' => 'tagihan-' . $tagihan->id,
                        'price' => (int)$grossAmount,
                        'quantity' => 1,
                        'name' => $tagihan->nama_tagihan
                    ]
                ],
                'enabled_payments' => [
                    'credit_card', 'mandiri_clickpay', 'cimb_clicks',
                    'bca_klikbca', 'bca_klikpay', 'bri_epay', 'echannel', 
                    'permata_va', 'bca_va', 'bni_va', 'other_va',
                    'gopay', 'shopeepay', 'qris'
                ]
            ];

            $response = $this->makeApiCall('/snap/v1/transactions', 'POST', $payload);
            
            if (!$response) {
                throw new Exception('Failed to get response from Midtrans API');
            }

            // Store transaction record
            MidtransTransaction::create([
                'siswa_id' => $siswa->id,
                'tagihan_id' => $tagihan->id,
                'order_id' => $orderId,
                'transaction_id' => $response['transaction_id'] ?? null,
                'status' => $response['transaction_status'] ?? 'pending',
                'gross_amount' => $grossAmount,
                'payment_type' => $response['payment_type'] ?? null,
                'transaction_time' => now(),
                'midtrans_response' => json_encode($response)
            ]);

            return [
                'success' => true,
                'token' => $response['token'] ?? null,
                'redirect_url' => $response['redirect_url'] ?? null,
                'order_id' => $orderId,
                'transaction_details' => $response
            ];

        } catch (Exception $e) {
            Log::error('MidtransServiceNew createTransaction error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    private function generateCustomerEmail($siswa)
    {
        // For Midtrans API, we need a valid email format
        $cleanNama = preg_replace('/[^a-zA-Z0-9]/', '', strtolower($siswa->nama_lengkap));
        $cleanKelas = preg_replace('/[^a-zA-Z0-9]/', '', strtolower($siswa->kelas->nama_kelas ?? 'unknown'));
        $nis = $siswa->nis ?? $siswa->nisn ?? '000000';
        
        return $cleanNama . '-' . $cleanKelas . '-' . $nis . '@smkpgrickp.ac.id';
    }
    
    private function generateCustomerDisplayName($siswa)
    {
        // This is for display purposes - clean format "NAMA - KELAS - NIS"
        $cleanNama = preg_replace('/[^a-zA-Z0-9\s]/', '', $siswa->nama_lengkap);
        $cleanNama = preg_replace('/\s+/', ' ', trim($cleanNama));
        $cleanNama = strtoupper($cleanNama);

        $kelasNama = $siswa->kelas ? $siswa->kelas->nama_kelas : 'UNKNOWN';
        $cleanKelas = preg_replace('/[^a-zA-Z0-9\s]/', '', $kelasNama);
        $cleanKelas = preg_replace('/\s+/', ' ', trim($cleanKelas));
        $cleanKelas = strtoupper($cleanKelas);

        $nis = $siswa->nis ?? $siswa->nisn ?? '000000';

        return $cleanNama . ' - ' . $cleanKelas . ' - ' . $nis;
    }

    private function makeApiCall($endpoint, $method = 'GET', $payload = null)
    {
        $url = $this->baseUrl . $endpoint;
        
        $headers = [
            'Accept: application/json',
            'Content-Type: application/json',
            'Authorization: Basic ' . base64_encode($this->serverKey . ':')
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        
        // SSL configuration
        if ($this->isProduction) {
            // For production, use proper SSL verification
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
            // Try to use system's CA bundle first
            curl_setopt($ch, CURLOPT_CAINFO, '');
            // If still having issues, disable SSL verification temporarily
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        } else {
            // Disable SSL verification for sandbox testing
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        }

        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            if ($payload) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
            }
        }

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            throw new Exception('cURL Error: ' . $error);
        }

        if ($httpCode >= 400) {
            $errorResponse = json_decode($response, true);
            $errorMessage = 'API Error (' . $httpCode . '): ';
            
            if ($errorResponse && isset($errorResponse['error_messages'])) {
                $errorMessage .= implode(', ', $errorResponse['error_messages']);
            } else {
                $errorMessage .= $response ?: 'Unknown error';
            }
            
            Log::error('Midtrans API Error: ' . $errorMessage);
            throw new Exception($errorMessage);
        }

        return json_decode($response, true);
    }

    public function handleNotification($notification)
    {
        try {
            $orderId = $notification['order_id'];
            $statusCode = $notification['status_code'];
            $grossAmount = $notification['gross_amount'];
            $signatureKey = $notification['signature_key'];

            // Verify signature
            $hash = hash('sha512', $orderId . $statusCode . $grossAmount . $this->serverKey);
            
            if ($hash !== $signatureKey) {
                throw new Exception('Invalid signature');
            }

            // Find transaction
            $transaction = MidtransTransaction::where('order_id', $orderId)->first();
            
            if (!$transaction) {
                throw new Exception('Transaction not found');
            }

            // Update transaction status
            $transaction->update([
                'status' => $notification['transaction_status'],
                'midtrans_response' => json_encode($notification)
            ]);

            // If successful, create payment record
            if ($notification['transaction_status'] === 'settlement') {
                $this->processSuccessfulPayment($transaction, $notification);
            }

            return ['status' => 'success'];

        } catch (Exception $e) {
            Log::error('MidtransServiceNew handleNotification error: ' . $e->getMessage());
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    private function processSuccessfulPayment($transaction, $notification)
    {
        // Create pembayaran record
        \App\Models\Pembayaran::create([
            'siswa_id' => $transaction->siswa_id,
            'tagihan_id' => $transaction->tagihan_id,
            'jumlah' => $transaction->gross_amount,
            'tanggal_bayar' => now(),
            'metode_pembayaran' => $notification['payment_type'] ?? 'midtrans',
            'status' => 'lunas',
            'keterangan' => 'Pembayaran melalui Midtrans - Order ID: ' . $transaction->order_id
        ]);

        Log::info('Payment processed successfully for order: ' . $transaction->order_id);
    }
}
