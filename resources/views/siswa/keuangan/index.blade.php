@extends('layouts.siswa')

@section('title', 'Informasi Keuangan')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Informasi Keuangan</h1>

    <!-- Summary Cards - Mobile First 2x2 Layout -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 lg:gap-6 mb-6">
        <!-- Total Tagihan -->
        <div class="bg-purple-50 rounded-lg p-3 sm:p-4 lg:p-6 border border-purple-100 hover:shadow-md transition-shadow">
            <div class="flex flex-col lg:flex-row lg:items-center">
                <div class="p-2 sm:p-3 rounded-full bg-purple-100 text-purple-600 mb-2 lg:mb-0 self-center lg:self-auto">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 lg:w-6 lg:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div class="lg:ml-4 text-center lg:text-left">
                    <h3 class="text-purple-800 text-xs sm:text-sm font-medium mb-1">Total Tagihan</h3>
                    <p class="text-sm sm:text-lg lg:text-2xl font-bold text-purple-900">Rp {{ number_format($totalTagihan, 0, ',', '.') }}</p>
                    <p class="text-xs text-purple-600 mt-1">TA 2025/2026</p>
                </div>
            </div>
        </div>

        <!-- Total Dibayar -->
        <div class="bg-green-50 rounded-lg p-3 sm:p-4 lg:p-6 border border-green-100 hover:shadow-md transition-shadow">
            <div class="flex flex-col lg:flex-row lg:items-center">
                <div class="p-2 sm:p-3 rounded-full bg-green-100 text-green-600 mb-2 lg:mb-0 self-center lg:self-auto">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 lg:w-6 lg:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="lg:ml-4 text-center lg:text-left">
                    <h3 class="text-green-800 text-xs sm:text-sm font-medium mb-1">Telah Dibayar</h3>
                    <p class="text-sm sm:text-lg lg:text-2xl font-bold text-green-900">Rp {{ number_format($totalTelahDibayar, 0, ',', '.') }}</p>
                    <p class="text-xs text-green-600 mt-1">TA 2025/2026</p>
                </div>
            </div>
        </div>

        <!-- Sisa Tunggakan -->
        <div class="bg-red-50 rounded-lg p-3 sm:p-4 lg:p-6 border border-red-100 hover:shadow-md transition-shadow">
            <div class="flex flex-col lg:flex-row lg:items-center">
                <div class="p-2 sm:p-3 rounded-full bg-red-100 text-red-600 mb-2 lg:mb-0 self-center lg:self-auto">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 lg:w-6 lg:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="lg:ml-4 text-center lg:text-left">
                    <h3 class="text-red-800 text-xs sm:text-sm font-medium mb-1">Total Tunggakan</h3>
                    <p class="text-sm sm:text-lg lg:text-2xl font-bold text-red-900">Rp {{ number_format($totalTunggakan, 0, ',', '.') }}</p>
                    <p class="text-xs text-red-600 mt-1">TA 2025/2026</p>
                </div>
            </div>
        </div>

        <!-- Status Pembayaran -->
        <div class="bg-blue-50 rounded-lg p-3 sm:p-4 lg:p-6 border border-blue-100 hover:shadow-md transition-shadow">
            <div class="flex flex-col lg:flex-row lg:items-center">
                <div class="p-2 sm:p-3 rounded-full bg-blue-100 text-blue-600 mb-2 lg:mb-0 self-center lg:self-auto">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 lg:w-6 lg:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="lg:ml-4 text-center lg:text-left">
                    <h3 class="text-blue-800 text-xs sm:text-sm font-medium mb-1">Status</h3>
                    <p class="text-sm sm:text-lg lg:text-2xl font-bold text-blue-900">{{ $totalTunggakan <= 0 ? 'Lunas' : 'Belum Lunas' }}</p>
                    <p class="text-xs text-blue-600 mt-1">{{ date('d/m/Y') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Rincian Tagihan -->
    <div class="bg-white rounded-lg shadow-sm mb-6">
        <div class="p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Rincian Tagihan</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Tagihan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nominal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Dibayar</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sisa</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($detailTagihan as $tagihan)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $tagihan['nama'] }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    Rp {{ number_format($tagihan['nominal'], 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    Rp {{ number_format($tagihan['total_dibayar'], 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    Rp {{ number_format($tagihan['sisa'], 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <span class="px-2 py-1 text-xs leading-5 font-semibold rounded-full 
                                        {{ $tagihan['status'] === 'Lunas' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $tagihan['status'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm font-medium">
                                    @if($tagihan['status'] !== 'Lunas' && $tagihan['sisa'] > 0)
                                        <div class="flex flex-col space-y-2">
                                            <div class="flex items-center space-x-2">
                                                <input 
                                                    type="number" 
                                                    id="amount-{{ $tagihan['id'] }}"
                                                    class="w-24 px-2 py-1 text-xs border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                    min="1000"
                                                    max="{{ $tagihan['sisa'] }}"
                                                    placeholder="Nominal">
                                                <button 
                                                    class="btn-bayar-online inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                                    data-siswa-id="{{ auth('siswa')->id() }}"
                                                    data-tagihan-id="{{ $tagihan['id'] }}"
                                                    data-max-amount="{{ $tagihan['sisa'] }}">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                                    </svg>
                                                    Bayar
                                                </button>
                                            </div>
                                            <small class="text-gray-500 text-xs">
                                                Min: Rp 1.000 | Max: Rp {{ number_format($tagihan['sisa'], 0, ',', '.') }}
                                            </small>
                                        </div>
                                    @else
                                        <span class="text-green-600 text-xs">
                                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            Lunas
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                    Tidak ada tagihan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Riwayat Pembayaran -->
    <div class="bg-white rounded-lg shadow-sm">
        <div class="p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Riwayat Pembayaran</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal & Jam</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Tagihan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Metode Pembayaran</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($allPembayaran as $pembayaran)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <div>{{ \Carbon\Carbon::parse($pembayaran->tanggal)->format('d/m/Y') }}</div>
                                    <small class="text-gray-500">{{ \Carbon\Carbon::parse($pembayaran->tanggal)->format('H:i:s') }}</small>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ $pembayaran->keterangan ?? ($pembayaran->tagihan ? ($pembayaran->tagihan->nama_tagihan ?? $pembayaran->tagihan->nama) : 'Pembayaran') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $pembayaran->metode_pembayaran ?? 'Manual' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs leading-5 font-semibold rounded-full 
                                        {{ $pembayaran->status === 'lunas' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                        {{ ucfirst($pembayaran->status ?? 'Lunas') }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                    Belum ada pembayaran
                                </td>
                            </tr>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Midtrans Snap Integration --}}
<script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>

<!-- Payment Handler Script -->
<script>
// Payment Handler Function
function handlePayment(siswaId, tagihanId, amount, button, paymentMethod) {
    console.log('=== PAYMENT HANDLER STARTED ===');
    console.log('Parameters:', { siswaId, tagihanId, amount, paymentMethod });
    
    // Store original button state
    const originalText = button.innerHTML;
    const originalDisabled = button.disabled;
    
    try {
        // Check CSRF token
        const csrfMeta = document.querySelector('meta[name="csrf-token"]');
        if (!csrfMeta) {
            alert('Error: CSRF token not found. Please refresh the page.');
            return;
        }
        
        const csrfToken = csrfMeta.getAttribute('content');
        console.log('CSRF token found:', !!csrfToken);
        
        // Update button to loading state
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
        button.disabled = true;
        
        // Prepare request data
        const requestData = {
            siswa_id: siswaId,
            tagihan_id: tagihanId,
            amount: amount,
            payment_method: paymentMethod
        };
        
        console.log('Sending request:', requestData);
        
        // Make the request
        makePaymentRequest(requestData, csrfToken)
            .then(result => {
                console.log('Payment request successful:', result);
                handlePaymentSuccess(result);
            })
            .catch(error => {
                console.error('Payment request failed:', error);
                handlePaymentError(error);
            })
            .finally(() => {
                // Always reset button
                button.innerHTML = originalText;
                button.disabled = originalDisabled;
            });
            
    } catch (error) {
        console.error('Error in handlePayment:', error);
        button.innerHTML = originalText;
        button.disabled = originalDisabled;
        alert('Unexpected error: ' + error.message);
    }
}

async function makePaymentRequest(data, csrfToken) {
    console.log('=== MAKING PAYMENT REQUEST ===');
    
    try {
        const response = await fetch('/midtrans/create-payment', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify(data)
        });
        
        console.log('Response received:', {
            status: response.status,
            statusText: response.statusText,
            ok: response.ok
        });
        
        // Get response text
        const responseText = await response.text();
        console.log('Response text:', responseText);
        
        // Handle HTTP errors
        if (!response.ok) {
            const errorMsg = getErrorMessage(response.status, responseText);
            throw new Error(errorMsg);
        }
        
        // Parse JSON
        let jsonData;
        try {
            jsonData = JSON.parse(responseText);
        } catch (parseError) {
            console.error('JSON parse error:', parseError);
            throw new Error('Invalid response format from server');
        }
        
        console.log('Parsed JSON:', jsonData);
        return jsonData;
        
    } catch (error) {
        console.error('Request error:', error);
        throw error;
    }
}

function getErrorMessage(status, responseText) {
    switch (status) {
        case 419:
            return 'CSRF token mismatch. Please refresh the page.';
        case 401:
            return 'Please login first.';
        case 403:
            return 'You do not have permission to make this payment.';
        case 422:
            return 'Invalid data provided.';
        case 500:
            return 'Server error occurred.';
        default:
            return `HTTP ${status} error. Response: ${responseText}`;
    }
}

function handlePaymentSuccess(response) {
    console.log('=== HANDLING SUCCESS RESPONSE ===');
    console.log('Response:', response);
    
    if (response.status !== 'success') {
        throw new Error('Response status is not success: ' + (response.message || 'Unknown error'));
    }
    
    if (!response.data) {
        throw new Error('No data in response');
    }
    
    const data = response.data;
    console.log('Payment data:', data);
    
    // Check if we have the required fields
    if (!data.success) {
        throw new Error('Payment creation failed: ' + (data.error || 'Unknown error'));
    }
    
    if (!data.token || !data.redirect_url) {
        throw new Error('Missing token or redirect URL in response');
    }
    
    // Redirect to Midtrans
    console.log('Redirecting to Midtrans:', data.redirect_url);
    window.location.href = data.redirect_url;
}

function handlePaymentError(error) {
    console.log('=== HANDLING ERROR ===');
    console.error('Error:', error);
    
    const message = error.message || 'Unknown error occurred';
    alert('Payment failed: ' + message);
}

// DOM Content Loaded Event Handler
document.addEventListener('DOMContentLoaded', function() {
    // Handle payment button clicks
    document.querySelectorAll('.btn-bayar-online').forEach(button => {
        button.addEventListener('click', function() {
            const siswaId = this.dataset.siswaId;
            const tagihanId = this.dataset.tagihanId;
            const maxAmount = this.dataset.maxAmount;
            
            // Get amount from input field
            const amountInput = document.getElementById('amount-' + tagihanId);
            const amount = parseInt(amountInput.value);
            
            // Validate amount
            if (!amount || amount < 1000) {
                alert('Minimal pembayaran Rp 1.000');
                return;
            }
            
            if (amount > parseInt(maxAmount)) {
                alert('Nominal tidak boleh melebihi sisa tagihan: Rp ' + parseInt(maxAmount).toLocaleString('id-ID'));
                return;
            }
            
            handlePayment(siswaId, tagihanId, amount, this, null);
        });
    });

    // Add real-time validation for amount inputs
    document.querySelectorAll('input[id^="amount-"]').forEach(input => {
        input.addEventListener('input', function() {
            const value = parseInt(this.value);
            const max = parseInt(this.getAttribute('max'));
            const min = parseInt(this.getAttribute('min'));
            
            if (value > max) {
                this.value = max;
            }
            if (value < min && this.value !== '') {
                this.style.borderColor = '#ef4444';
            } else {
                this.style.borderColor = '#d1d5db';
            }
        });
    });

    // Auto refresh payment status every 30 seconds for pending payments
    const urlParams = new URLSearchParams(window.location.search);
    const checkStatus = urlParams.get('check_status');
    
    if (checkStatus) {
        // Remove the parameter from URL
        window.history.replaceState({}, document.title, window.location.pathname);
        
        // Refresh page to show updated payment status
        setTimeout(() => {
            location.reload();
        }, 2000);
    }
});
</script>
@endsection