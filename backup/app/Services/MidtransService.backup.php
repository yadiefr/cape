<?php

namespace App\Services;

use App\Models\Siswa;
use App\Models\Tagihan;
use App\Models\Pembayaran;
use App\Models\MidtransTransaction;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class MidtransService
{
    public function __construct()
    {
        // Set Midtrans configuration
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production');
        \Midtrans\Config::$isSanitized = config('midtrans.is_sanitized');
        \Midtrans\Config::$is3ds = config('midtrans.is_3ds');
        
        // Fix SSL certificate issue for local development
        \Midtrans\Config::$curlOptions = [
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0
        ];
    }

    /**
     * Create transaction
     */
    public function createTransaction(Siswa $siswa, Tagihan $tagihan, $amount = null, $preferredMethod = null)
    {
        try {
            // Use provided amount or remaining bill amount
            $paymentAmount = $amount;
            if (!$paymentAmount) {
                $totalPaid = $tagihan->pembayaran()->where('siswa_id', $siswa->id)->sum('jumlah');
                $paymentAmount = $tagihan->nominal - $totalPaid;
            }

            if ($paymentAmount <= 0) {
                throw new \Exception('Tagihan sudah lunas');
            }

            // Generate unique order ID
            $orderId = 'ORDER-' . $siswa->id . '-' . $tagihan->id . '-' . time();

            // Check if server key is configured
            $serverKey = config('midtrans.server_key');
            Log::info('Midtrans Configuration Check', [
                'server_key_exists' => !empty($serverKey),
                'server_key_default' => $serverKey === 'your-server-key',
                'server_key_length' => strlen($serverKey),
                'is_production' => config('midtrans.is_production'),
                'client_key' => config('midtrans.client_key')
            ]);
            
            if (empty($serverKey) || $serverKey === 'your-server-key') {
                // Return mock transaction for testing
                Log::warning('Using mock transaction - Midtrans not configured properly');
                return $this->createMockTransaction($orderId, $siswa, $tagihan, $paymentAmount, $preferredMethod);
            }

            // Prepare transaction details
            $transactionDetails = [
                'order_id' => $orderId,
                'gross_amount' => $paymentAmount,
            ];

            // Prepare item details
            $itemDetails = [
                [
                    'id' => 'tagihan-' . $tagihan->id,
                    'price' => $paymentAmount,
                    'quantity' => 1,
                    'name' => $tagihan->nama_tagihan ?? $tagihan->nama ?? 'Pembayaran Sekolah',
                    'category' => 'Education'
                ]
            ];

            // Prepare customer details
            $customerDetails = [
                'first_name' => $siswa->nama,
                'email' => $siswa->email ?? $siswa->nisn . '@siswa.smk.ac.id',
                'phone' => $siswa->no_telp ?? '08123456789',
                'billing_address' => [
                    'first_name' => $siswa->nama,
                    'phone' => $siswa->no_telp ?? '08123456789',
                    'address' => $siswa->alamat ?? 'Alamat tidak tersedia',
                    'city' => 'Jakarta',
                    'postal_code' => '12345',
                    'country_code' => 'IDN'
                ]
            ];

            // Determine enabled payments based on selected method
            $enabledPayments = [];
            if ($preferredMethod) {
                $enabledPayments = [$preferredMethod];
            } else {
                $enabledPayments = config('midtrans.enabled_payments', [
                    'credit_card', 'bca_va', 'bni_va', 'bri_va', 'permata_va',
                    'other_va', 'gopay', 'qris', 'shopeepay', 'indomaret', 'alfamart'
                ]);
            }

            // Create transaction parameters
            $params = [
                'transaction_details' => $transactionDetails,
                'item_details' => $itemDetails,
                'customer_details' => $customerDetails,
                'enabled_payments' => $enabledPayments,
                'expiry' => [
                    'start_time' => date('Y-m-d H:i:s T'),
                    'unit' => 'day',
                    'duration' => 1
                ],
                'callbacks' => [
                    'finish' => route('midtrans.finish')
                ]
            ];

            // Create transaction
            $snapToken = \Midtrans\Snap::getSnapToken($params);

            // Store transaction in database
            $transaction = MidtransTransaction::create([
                'order_id' => $orderId,
                'siswa_id' => $siswa->id,
                'tagihan_id' => $tagihan->id,
                'gross_amount' => $paymentAmount,
                'transaction_status' => 'pending',
                'snap_token' => $snapToken,
                'payment_data' => json_encode($params)
            ]);

            return [
                'order_id' => $orderId,
                'snap_token' => $snapToken,
                'redirect_url' => null
            ];

        } catch (\Exception $e) {
            Log::error('Midtrans Create Transaction Error: ' . $e->getMessage());
            Log::error('Error trace: ' . $e->getTraceAsString());
            Log::error('Error details', [
                'error_type' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'siswa_id' => $siswa->id,
                'tagihan_id' => $tagihan->id,
                'amount' => $paymentAmount
            ]);
            
            // If real Midtrans fails, fall back to mock
            if (!empty(config('midtrans.server_key')) && config('midtrans.server_key') !== 'your-server-key') {
                Log::info('Falling back to mock payment due to Midtrans error');
                $orderId = 'MOCK-' . $siswa->id . '-' . $tagihan->id . '-' . time();
                return $this->createMockTransaction($orderId, $siswa, $tagihan, $paymentAmount, $preferredMethod);
            }
            
            throw $e;
        }
    }

    /**
     * Create mock transaction for testing
     */
    private function createMockTransaction($orderId, Siswa $siswa, Tagihan $tagihan, $paymentAmount, $preferredMethod = null)
    {
        // Store mock transaction
        $transaction = MidtransTransaction::create([
            'order_id' => $orderId,
            'siswa_id' => $siswa->id,
            'tagihan_id' => $tagihan->id,
            'gross_amount' => $paymentAmount,
            'transaction_status' => 'pending',
            'snap_token' => 'mock_token_' . Str::random(32),
            'payment_data' => json_encode([
                'mock' => true,
                'amount' => $paymentAmount,
                'siswa' => $siswa->nama,
                'tagihan' => $tagihan->nama_tagihan ?? $tagihan->nama,
                'preferred_method' => $preferredMethod
            ])
        ]);

        return [
            'order_id' => $orderId,
            'snap_token' => 'mock_token_' . Str::random(32),
            'redirect_url' => null,
            'is_mock' => true
        ];
    }

    /**
     * Handle Midtrans notification
     */
    public function handleNotification()
    {
        try {
            $notification = new \Midtrans\Notification();
            
            $orderId = $notification->order_id;
            $transactionStatus = $notification->transaction_status;
            $fraudStatus = $notification->fraud_status ?? null;
            $paymentType = $notification->payment_type ?? null;

            Log::info('Midtrans Notification', [
                'order_id' => $orderId,
                'transaction_status' => $transactionStatus,
                'fraud_status' => $fraudStatus,
                'payment_type' => $paymentType
            ]);

            // Find transaction
            $transaction = MidtransTransaction::where('order_id', $orderId)->first();
            if (!$transaction) {
                throw new \Exception('Transaction not found: ' . $orderId);
            }

            // Update transaction status
            $transaction->update([
                'transaction_status' => $transactionStatus,
                'fraud_status' => $fraudStatus,
                'payment_type' => $paymentType,
                'notification_data' => json_encode($notification->getResponse())
            ]);

            // Process payment based on status
            if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
                $this->processSuccessfulPayment($transaction);
            } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire', 'failure'])) {
                $this->processFailedPayment($transaction);
            }

            return [
                'status' => 'success',
                'message' => 'Notification processed'
            ];

        } catch (\Exception $e) {
            Log::error('Midtrans Notification Error: ' . $e->getMessage());
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Process successful payment
     */
    private function processSuccessfulPayment(MidtransTransaction $transaction)
    {
        // Check if payment already processed
        $existingPayment = Pembayaran::where('order_id', $transaction->order_id)->first();
        if ($existingPayment) {
            Log::info('Payment already processed: ' . $transaction->order_id);
            return;
        }

        // Get tagihan info for proper naming
        $tagihan = Tagihan::find($transaction->tagihan_id);
        $tagihanName = $tagihan ? ($tagihan->nama_tagihan ?? $tagihan->nama ?? 'Tagihan') : 'Tagihan';

        // Determine payment method
        $paymentType = $transaction->payment_type ?? 'Midtrans';
        
        // Get preferred method from payment data if available
        $paymentData = json_decode($transaction->payment_data, true);
        $preferredMethod = $paymentData['preferred_method'] ?? null;
        
        if ($paymentType === 'mock_payment') {
            // For mock payments, use the preferred method name or default to Transfer
            if ($preferredMethod) {
                $methodNames = [
                    'qris' => 'QRIS',
                    'gopay' => 'GoPay',
                    'shopeepay' => 'ShopeePay',
                    'bca_va' => 'BCA Virtual Account',
                    'bni_va' => 'BNI Virtual Account',
                    'bri_va' => 'BRI Virtual Account',
                    'permata_va' => 'Permata Virtual Account',
                    'credit_card' => 'Credit Card',
                    'indomaret' => 'Indomaret',
                    'alfamart' => 'Alfamart'
                ];
                $metodePembayaran = $methodNames[$preferredMethod] ?? 'Transfer';
            } else {
                $metodePembayaran = 'Transfer';
            }
        } else {
            $metodePembayaran = 'Online - ' . ucfirst(str_replace('_', ' ', $paymentType));
        }

        // Create payment record
        Pembayaran::create([
            'siswa_id' => $transaction->siswa_id,
            'tagihan_id' => $transaction->tagihan_id,
            'jumlah' => $transaction->gross_amount,
            'tanggal' => now(),
            'metode_pembayaran' => $metodePembayaran,
            'status' => 'lunas',
            'keterangan' => $tagihanName, // Use tagihan name as main description
            'order_id' => $transaction->order_id,
            'admin_id' => null, // System payment
            'bukti_pembayaran' => null
        ]);

        Log::info('Payment processed successfully: ' . $transaction->order_id);
    }

    /**
     * Process failed payment
     */
    private function processFailedPayment(MidtransTransaction $transaction)
    {
        Log::info('Payment failed: ' . $transaction->order_id . ' - Status: ' . $transaction->transaction_status);
        // Additional failed payment processing logic can be added here
    }

    /**
     * Complete mock payment for testing
     */
    public function completeMockPayment($orderId)
    {
        try {
            $transaction = MidtransTransaction::where('order_id', $orderId)->first();
            if (!$transaction) {
                throw new \Exception('Transaction not found');
            }

            // Update transaction status
            $transaction->update([
                'transaction_status' => 'settlement',
                'payment_type' => 'mock_payment'
            ]);

            // Process the payment
            $this->processSuccessfulPayment($transaction);

            return true;

        } catch (\Exception $e) {
            Log::error('Mock Payment Error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get transaction status from Midtrans
     */
    public function getTransactionStatus($orderId)
    {
        try {
            $status = \Midtrans\Transaction::status($orderId);
            return $status;
        } catch (\Exception $e) {
            Log::error('Get Transaction Status Error: ' . $e->getMessage());
            throw $e;
        }
    }
}