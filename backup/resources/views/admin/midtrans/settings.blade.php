@extends('layouts.admin')

@section('title', 'Konfigurasi Payment Gateway')

@section('main-content')
<div class="bg-white rounded-xl shadow-md p-6 mb-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Konfigurasi Payment Gateway</h1>
        <div class="flex items-center space-x-2">
            <span class="text-sm text-gray-500">Status:</span>
            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ config('midtrans.is_production') ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                {{ config('midtrans.is_production') ? 'Production' : 'Sandbox' }}
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Midtrans Settings -->
        <div class="bg-gray-50 rounded-lg p-6">
            <div class="flex items-center mb-4">
                <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-medium text-gray-900">Midtrans Configuration</h3>
                    <p class="text-sm text-gray-500">Payment gateway settings</p>
                </div>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Merchant ID</label>
                    <div class="mt-1 p-3 bg-white border border-gray-300 rounded-md text-sm text-gray-900">
                        {{ config('midtrans.merchant_id') ?: 'Not configured' }}
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Client Key</label>
                    <div class="mt-1 p-3 bg-white border border-gray-300 rounded-md text-sm text-gray-900">
                        {{ config('midtrans.client_key') ? str_repeat('*', 20) . substr(config('midtrans.client_key'), -4) : 'Not configured' }}
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Server Key</label>
                    <div class="mt-1 p-3 bg-white border border-gray-300 rounded-md text-sm text-gray-900">
                        {{ config('midtrans.server_key') ? str_repeat('*', 20) . substr(config('midtrans.server_key'), -4) : 'Not configured' }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Methods -->
        <div class="bg-gray-50 rounded-lg p-6">
            <div class="flex items-center mb-4">
                <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-medium text-gray-900">Payment Methods</h3>
                    <p class="text-sm text-gray-500">Available payment options</p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                @foreach(config('midtrans.snap.enabled_payments', []) as $method)
                    <div class="flex items-center p-2 bg-white rounded border border-gray-200">
                        <div class="w-2 h-2 bg-green-400 rounded-full mr-2"></div>
                        <span class="text-sm text-gray-700 capitalize">{{ str_replace('_', ' ', $method) }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Instructions -->
        <div class="md:col-span-2 bg-blue-50 rounded-lg p-6">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800">Setup Instructions</h3>
                    <div class="mt-2 text-sm text-blue-700">
                        <p class="mb-2">To configure Midtrans payment gateway:</p>
                        <ol class="list-decimal list-inside space-y-1 ml-4">
                            <li>Create account at <a href="https://midtrans.com" target="_blank" class="text-blue-600 hover:text-blue-800 underline">midtrans.com</a></li>
                            <li>Get your Merchant ID, Client Key, and Server Key from Midtrans Dashboard</li>
                            <li>Update your <code class="bg-blue-100 px-1 rounded">.env</code> file with:</li>
                        </ol>
                        <div class="mt-3 p-3 bg-blue-100 rounded text-xs font-mono">
                            MIDTRANS_MERCHANT_ID=your-merchant-id<br>
                            MIDTRANS_CLIENT_KEY=your-client-key<br>
                            MIDTRANS_SERVER_KEY=your-server-key<br>
                            MIDTRANS_IS_PRODUCTION=false
                        </div>
                        <p class="mt-3">Set notification URL in Midtrans Dashboard to: <code class="bg-blue-100 px-1 rounded">{{ route('midtrans.notification') }}</code></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Transaction History -->
        <div class="md:col-span-2">
            <div class="bg-white border border-gray-200 rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Recent Transactions</h3>
                </div>
                <div class="px-6 py-4">
                    @php
                        $recentTransactions = \App\Models\MidtransTransaction::with(['siswa', 'tagihan'])
                            ->orderBy('created_at', 'desc')
                            ->limit(5)
                            ->get();
                    @endphp
                    
                    @if($recentTransactions->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Order ID</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Student</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach($recentTransactions as $transaction)
                                        <tr>
                                            <td class="px-3 py-2 text-sm font-mono text-gray-900">{{ $transaction->order_id }}</td>
                                            <td class="px-3 py-2 text-sm text-gray-900">{{ $transaction->siswa->nama_lengkap ?? 'N/A' }}</td>
                                            <td class="px-3 py-2 text-sm text-gray-900">Rp {{ number_format($transaction->gross_amount, 0, ',', '.') }}</td>
                                            <td class="px-3 py-2 text-sm">
                                                <span class="px-2 py-1 text-xs rounded-full {{ 
                                                    $transaction->isSuccess() ? 'bg-green-100 text-green-800' : 
                                                    ($transaction->isPending() ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') 
                                                }}">
                                                    {{ ucfirst($transaction->transaction_status) }}
                                                </span>
                                            </td>
                                            <td class="px-3 py-2 text-sm text-gray-500">{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-8">No transactions yet</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
