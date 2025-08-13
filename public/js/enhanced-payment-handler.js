// Enhanced Payment Handler for New Midtrans Service
function handlePaymentNew(tagihanId) {
    const paymentAmount = document.getElementById('payment-amount-' + tagihanId).value;
    const paymentMethod = document.getElementById('payment-method-' + tagihanId).value;
    
    // Validate amount
    if (!paymentAmount || paymentAmount <= 0) {
        alert('Silakan masukkan jumlah pembayaran yang valid');
        return;
    }
    
    // Validate payment method
    if (!paymentMethod) {
        alert('Silakan pilih metode pembayaran');
        return;
    }
    
    // Show loading
    const button = event.target;
    const originalText = button.textContent;
    button.textContent = 'Processing...';
    button.disabled = true;
    
    // Call backend to create transaction
    fetch('/midtrans/create-transaction', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            tagihan_id: tagihanId,
            amount: paymentAmount,
            payment_method: paymentMethod
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            if (data.is_mock) {
                // Handle mock payment
                handleMockPaymentNew(data.order_id, button, originalText);
            } else {
                // Handle real Midtrans payment
                if (data.snap_token) {
                    // Use snap.js
                    if (typeof snap === 'undefined') {
                        alert('Midtrans Snap library not loaded. Please refresh the page.');
                        button.textContent = originalText;
                        button.disabled = false;
                        return;
                    }
                    
                    snap.pay(data.snap_token, {
                        onSuccess: function(result) {
                            alert('Pembayaran berhasil!');
                            window.location.reload();
                        },
                        onPending: function(result) {
                            alert('Menunggu pembayaran...');
                            window.location.reload();
                        },
                        onError: function(result) {
                            alert('Pembayaran gagal!');
                            console.error(result);
                        },
                        onClose: function() {
                            console.log('Payment popup closed');
                        }
                    });
                } else if (data.redirect_url) {
                    // Direct redirect for some payment methods
                    window.open(data.redirect_url, '_blank');
                    alert('Jendela pembayaran telah dibuka. Silakan selesaikan pembayaran.');
                } else {
                    alert('Error: No payment method available');
                }
            }
        } else {
            alert('Error: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan dalam memproses pembayaran');
    })
    .finally(() => {
        // Reset button
        button.textContent = originalText;
        button.disabled = false;
    });
}

function handleMockPaymentNew(orderId, button, originalText) {
    const confirmPayment = confirm(
        '🧪 Mode Demo Payment\n\n' +
        'Sistem menggunakan mode demo karena konfigurasi Midtrans.\n\n' +
        'Order ID: ' + orderId + '\n\n' +
        'Klik OK untuk mensimulasikan pembayaran berhasil.'
    );

    if (confirmPayment) {
        // Show processing message
        button.textContent = 'Memproses demo...';
        
        // Complete mock payment
        fetch('/midtrans/complete-mock-payment', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                order_id: orderId
            })
        })
        .then(response => response.json())
        .then(result => {
            if (result.status === 'success') {
                alert('✅ Pembayaran demo berhasil!\n\nPembayaran telah dicatat dalam sistem.');
                setTimeout(() => {
                    location.reload();
                }, 1000);
            } else {
                alert('❌ Gagal mensimulasikan pembayaran: ' + result.message);
                button.textContent = originalText;
                button.disabled = false;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('❌ Terjadi kesalahan saat mensimulasikan pembayaran');
            button.textContent = originalText;
            button.disabled = false;
        });
    } else {
        button.textContent = originalText;
        button.disabled = false;
    }
}

// Format currency display
function formatCurrency(amount) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    }).format(amount);
}

// Validate payment input
function validatePaymentAmount(tagihanId) {
    const input = document.getElementById('payment-amount-' + tagihanId);
    const value = parseFloat(input.value);
    
    if (isNaN(value) || value <= 0) {
        input.style.borderColor = 'red';
        return false;
    } else {
        input.style.borderColor = '';
        return true;
    }
}
