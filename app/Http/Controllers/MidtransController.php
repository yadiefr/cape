<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Tagihan;
use App\Models\MidtransTransaction;
use App\Services\MidtransServiceNew;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MidtransController extends Controller
{
    protected $midtransService;

    public function __construct(MidtransServiceNew $midtransService)
    {
        $this->midtransService = $midtransService;
    }

    /**
     * Create payment transaction
     */
    public function createPayment(Request $request)
    {
        try {
            $request->validate([
                'siswa_id' => 'required|exists:siswa,id',
                'tagihan_id' => 'required|exists:tagihan,id',
                'amount' => 'nullable|numeric|min:1000'
            ]);

            $siswa = Siswa::findOrFail($request->siswa_id);
            $tagihan = Tagihan::findOrFail($request->tagihan_id);
            $amount = $request->amount;
            $paymentMethod = null; // Tidak menggunakan payment method karena akan dipilih di Midtrans

            // Check if user can pay for this siswa
            if (Auth::guard('siswa')->check()) {
                // Siswa can only pay for themselves
                if (Auth::guard('siswa')->id() != $siswa->id) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Anda hanya bisa melakukan pembayaran untuk diri sendiri'
                    ], 403);
                }
            } elseif (!Auth::guard('admin')->check() && !Auth::guard('guru')->check()) {
                return response()->json([
                    'status' => 'error', 
                    'message' => 'Unauthorized'
                ], 401);
            }

            // Check if Midtrans is properly configured
            $serverKey = config('services.midtrans.server_key');
            if (empty($serverKey) || $serverKey === 'your-server-key') {
                // Return demo response for testing
                return response()->json([
                    'status' => 'error',
                    'message' => 'Midtrans belum dikonfigurasi. Silakan set MIDTRANS_SERVER_KEY, MIDTRANS_CLIENT_KEY, dan MIDTRANS_MERCHANT_ID di file .env'
                ], 422);
            }

            $result = $this->midtransService->createTransaction($siswa, $tagihan, $amount);

            return response()->json([
                'status' => 'success',
                'data' => $result,
                'message' => 'Transaction created successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Midtrans Payment Error: ' . $e->getMessage());
            Log::error('Midtrans Payment Stack: ' . $e->getTraceAsString());
            
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal membuat transaksi pembayaran: ' . $e->getMessage(),
                'debug' => config('app.debug') ? $e->getTraceAsString() : null
            ], 500);
        }
    }

    /**
     * Handle notification from Midtrans
     */
    public function notification(Request $request)
    {
        try {
            $result = $this->midtransService->handleNotification($request->all());
            
            Log::info('Midtrans Notification: ', $result);

            if ($result['status'] == 'success') {
                return response('OK', 200);
            } else {
                return response('Failed', 400);
            }

        } catch (\Exception $e) {
            Log::error('Midtrans Notification Error: ' . $e->getMessage());
            return response('Error', 500);
        }
    }

    /**
     * Handle finish callback from Midtrans
     */
    public function finish(Request $request)
    {
        $orderId = $request->order_id;
        $transactionStatus = $request->transaction_status;
        
        // Redirect based on transaction status
        if (in_array($transactionStatus, ['capture', 'settlement'])) {
            return redirect()->route('siswa.keuangan')->with('success', 
                'Pembayaran berhasil! Transaksi dengan ID: ' . $orderId);
        } elseif ($transactionStatus == 'pending') {
            return redirect()->route('siswa.keuangan')->with('info', 
                'Pembayaran pending. Silakan selesaikan pembayaran Anda.');
        } else {
            return redirect()->route('siswa.keuangan')->with('error', 
                'Pembayaran gagal atau dibatalkan.');
        }
    }

    /**
     * Complete mock payment for testing
     */
    public function completeMockPayment(Request $request)
    {
        try {
            $request->validate([
                'order_id' => 'required|string'
            ]);

            $orderId = $request->order_id;
            
            // For now, just return success for testing
            // In real implementation, this should update transaction status
            $transaction = MidtransTransaction::where('order_id', $orderId)->first();
            
            if (!$transaction) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Transaction not found'
                ], 404);
            }
            
            // Update transaction as settled for mock payment
            $transaction->update([
                'status' => 'settlement',
                'transaction_status' => 'settlement'
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Mock payment completed successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Mock Payment Error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to complete mock payment: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check transaction status
     */
    public function status($orderId)
    {
        try {
            $transaction = MidtransTransaction::where('order_id', $orderId)->first();
            
            if (!$transaction) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Transaction not found'
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'data' => [
                    'order_id' => $transaction->order_id,
                    'transaction_status' => $transaction->transaction_status,
                    'payment_type' => $transaction->payment_type,
                    'gross_amount' => $transaction->gross_amount,
                    'transaction_time' => $transaction->transaction_time,
                    'settlement_time' => $transaction->settlement_time
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to get transaction status'
            ], 500);
        }
    }
}
