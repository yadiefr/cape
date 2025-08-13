// Simple Payment Handler - Clean Version
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
