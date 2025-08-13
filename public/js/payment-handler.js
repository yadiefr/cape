// Enhanced Payment Handler for Midtrans with Mock Support
function handlePayment(siswaId, tagihanId, amount, button, paymentMethod) {
    try {
        const originalText = button.innerHTML;
        
        // Debug CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        console.log('CSRF meta tag found:', !!csrfToken);
        console.log('CSRF token value:', csrfToken ? csrfToken.getAttribute('content') : 'NOT FOUND');
        
        if (!csrfToken) {
            alert('CSRF token tidak ditemukan. Silakan refresh halaman.');
            return;
        }
        
        // Disable button and show loading
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
        button.disabled = true;

        console.log('Sending payment request with:', {
            siswa_id: siswaId,
            tagihan_id: tagihanId,
            amount: amount,
            payment_method: paymentMethod
        });

        performPaymentRequest(siswaId, tagihanId, amount, paymentMethod, csrfToken, button, originalText);
        
    } catch (error) {
        console.error('Outer error in handlePayment:', error);
        alert('❌ Unexpected error: ' + error.message);
        // Reset button if error occurs
        if (button) {
            button.innerHTML = button.getAttribute('data-original-text') || 'Bayar';
            button.disabled = false;
        }
    }
}

function performPaymentRequest(siswaId, tagihanId, amount, paymentMethod, csrfToken, button, originalText) {

    fetch('/midtrans/create-payment', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken.getAttribute('content')
        },
        body: JSON.stringify({
            siswa_id: siswaId,
            tagihan_id: tagihanId,
            amount: amount,
            payment_method: paymentMethod
        })
    })
    .then(async response => {
        console.log('Raw response:', response);
        console.log('Response status:', response.status);
        console.log('Response ok:', response.ok);
        
        // Get response text first
        let responseText;
        try {
            responseText = await response.text();
            console.log('Response text:', responseText);
        } catch (textError) {
            console.error('Failed to get response text:', textError);
            throw new Error('Failed to read response from server');
        }
        
        // Handle non-OK responses
        if (!response.ok) {
            console.log('Error response text:', responseText);
            if (response.status === 419) {
                throw new Error('CSRF token mismatch. Silakan refresh halaman.');
            } else if (response.status === 401) {
                throw new Error('Silakan login terlebih dahulu.');
            } else if (response.status === 403) {
                throw new Error('Anda tidak memiliki akses untuk melakukan pembayaran ini.');
            } else {
                throw new Error(`HTTP error! status: ${response.status}. Response: ${responseText}`);
            }
        }
        
        // Parse JSON
        let data;
        try {
            data = JSON.parse(responseText);
            console.log('Parsed JSON data:', data);
            return data;
        } catch (parseError) {
            console.error('JSON parsing error:', parseError);
            console.error('Response text that failed to parse:', responseText);
            throw new Error('Invalid JSON response from server');
        }
    })
    .then(data => {
        // Reset button
        button.innerHTML = originalText;
        button.disabled = false;
        
        console.log('Response data:', data);
        
        if (data.status === 'success') {
            console.log('Success response received');
            console.log('data.data:', data.data);
            
            // Check if response has the required data
            if (data.data && data.data.success) {
                console.log('data.data.success is true');
                console.log('Token:', data.data.token);
                console.log('Redirect URL:', data.data.redirect_url);
                
                // Check if this is a mock payment or real payment
                const hasToken = data.data.token && data.data.token.length > 0;
                const hasRedirectUrl = data.data.redirect_url && data.data.redirect_url.length > 0;
                
                console.log('hasToken:', hasToken);
                console.log('hasRedirectUrl:', hasRedirectUrl);
                
                if (hasToken && hasRedirectUrl) {
                    // Real payment - redirect to Midtrans
                    console.log('Attempting to handle real payment');
                    handleRealPayment({
                        token: data.data.token,
                        redirect_url: data.data.redirect_url,
                        order_id: data.data.order_id
                    });
                } else {
                    // Mock or failed payment
                    console.log('Failed payment - missing token or redirect URL');
                    alert('Gagal mendapatkan token pembayaran dari Midtrans\n\nToken: ' + (data.data.token || 'Missing') + '\nRedirect URL: ' + (data.data.redirect_url || 'Missing'));
                }
            } else {
                console.log('data.data is missing or data.data.success is false');
                console.log('data.data exists:', !!data.data);
                if (data.data) {
                    console.log('data.data.success:', data.data.success);
                    console.log('data.data.error:', data.data.error);
                }
                alert('Gagal membuat transaksi: ' + (data.data && data.data.error ? data.data.error : 'Response structure invalid'));
            }
        } else {
            console.log('Status is not success:', data.status);
            alert('Gagal membuat transaksi: ' + data.message);
        }
    })
    .catch(error => {
        // Reset button
        button.innerHTML = originalText;
        button.disabled = false;
        
        console.error('=== PAYMENT ERROR DEBUG ===');
        console.error('Error object:', error);
        console.error('Error message:', error.message);
        console.error('Error name:', error.name);
        console.error('Error stack:', error.stack);
        console.error('Error constructor:', error.constructor.name);
        
        let errorMessage = 'Terjadi kesalahan jaringan. Silakan coba lagi.';
        
        if (error && error.message) {
            errorMessage = error.message;
        } else if (typeof error === 'string') {
            errorMessage = error;
        }
        
        alert('❌ Error: ' + errorMessage);
    });
}

function handleMockPayment(paymentData) {
    // Show mock payment dialog
    const confirmPayment = confirm(
        '🧪 Mode Demo Payment\n\n' +
        'Sistem sedang dalam mode demo karena ada masalah koneksi ke Midtrans.\n\n' +
        'Order ID: ' + paymentData.order_id + '\n' +
        'Jumlah: Rp ' + new Intl.NumberFormat('id-ID').format(paymentData.amount) + '\n\n' +
        'Klik OK untuk mensimulasikan pembayaran berhasil.'
    );

    if (confirmPayment) {
        // Show processing message
        const processingMsg = document.createElement('div');
        processingMsg.innerHTML = '<div style="position:fixed;top:50%;left:50%;transform:translate(-50%,-50%);background:rgba(0,0,0,0.8);color:white;padding:20px;border-radius:10px;z-index:10000;"><i class="fas fa-spinner fa-spin"></i> Memproses pembayaran demo...</div>';
        document.body.appendChild(processingMsg);

        // Complete mock payment
        fetch('/midtrans/complete-mock-payment', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                order_id: paymentData.order_id
            })
        })
        .then(response => response.json())
        .then(result => {
            document.body.removeChild(processingMsg);
            
            if (result.status === 'success') {
                alert('✅ Pembayaran demo berhasil!\n\nPembayaran telah dicatat dalam sistem.\nHalaman akan di-refresh untuk melihat perubahan.');
                setTimeout(() => {
                    location.reload();
                }, 1000);
            } else {
                alert('❌ Gagal mensimulasikan pembayaran: ' + result.message);
            }
        })
        .catch(error => {
            document.body.removeChild(processingMsg);
            console.error('Error:', error);
            alert('❌ Terjadi kesalahan saat mensimulasikan pembayaran');
        });
    }
}

function handleRealPayment(paymentData) {
    // Real Midtrans payment
    console.log('Payment data received:', paymentData);
    
    // Check if we have redirect URL (for Snap)
    if (paymentData.redirect_url) {
        // Redirect to Midtrans Snap page
        window.location.href = paymentData.redirect_url;
        return;
    }
    
    // Fallback: try using Snap JS if token is available
    if (paymentData.token) {
        if (typeof snap === 'undefined') {
            alert('Midtrans Snap library not loaded. Redirecting to payment page...');
            if (paymentData.redirect_url) {
                window.location.href = paymentData.redirect_url;
            }
            return;
        }

        snap.pay(paymentData.token, {
            onSuccess: function(result) {
                alert('✅ Pembayaran berhasil!\n\nTransaksi ID: ' + (result.transaction_id || paymentData.order_id));
                setTimeout(() => {
                    location.reload();
                }, 1000);
            },
            onPending: function(result) {
                alert('⏳ Pembayaran pending.\n\nSilakan selesaikan pembayaran Anda.\nHalaman akan di-refresh.');
                setTimeout(() => {
                    location.reload();
                }, 1000);
            },
            onError: function(result) {
                alert('❌ Pembayaran gagal!\n\n' + (result.status_message || 'Terjadi kesalahan sistem'));
                console.error('Payment Error:', result);
            },
            onClose: function() {
                console.log('Payment popup closed');
                // Optional: refresh page to check payment status
                setTimeout(() => {
                    location.reload();
                }, 500);
            }
        });
    } else {
        alert('❌ Token pembayaran tidak valid');
    }
}
