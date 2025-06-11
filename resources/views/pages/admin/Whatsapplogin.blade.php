@extends('dashboard')
@section('content')

<!-- Lottie & FontAwesome -->
<script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
    .whatsapp-container {
        max-width: 500px;
        margin: 2rem auto;
        padding: 2rem;
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        text-align: center;
    }
    
    .qr-container {
        padding: 1.5rem;
        background: #f8f9fa;
        border-radius: 8px;
        margin: 1.5rem 0;
        border: 1px dashed #ddd;
    }
    
    .qr-code {
        width: 280px;
        height: 280px;
        margin: 0 auto;
        padding: 10px;
        background: white;
        border-radius: 4px;
    }
    
    .status-indicator {
        font-size: 1.1rem;
        padding: 0.5rem 1rem;
        border-radius: 50px;
        display: inline-block;
        margin-bottom: 1.5rem;
    }
    
    .btn-whatsapp {
        background: #25D366;
        color: white;
        padding: 0.6rem 1.5rem;
        border-radius: 50px !important;
        font-weight: 600;
        border: none;
        transition: all 0.3s;
    }
    
    .btn-whatsapp:hover {
        background: #128C7E;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(37, 211, 102, 0.3);
    }
    
    .btn-whatsapp:disabled {
        background: #cccccc;
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }
    
    .instructions {
        margin-top: 1.5rem;
        padding: 1rem;
        background: #f0f8ff;
        border-left: 4px solid #25D366;
        border-radius: 0 4px 4px 0;
        text-align: left;
    }
    
    .loading-spinner {
        display: inline-block;
        margin-right: 8px;
    }
</style>

<div class="whatsapp-container">
    <h3 class="mb-4">
        <i class="fab fa-whatsapp text-success"></i> WhatsApp Connection
    </h3>
    
    <div id="status-indicator" class="status-indicator bg-secondary text-white">
        <i class="fas fa-circle-notch fa-spin loading-spinner"></i> Checking WhatsApp Status...
    </div>
    
    <button id="connect-whatsapp-btn" class="btn btn-whatsapp mt-3" style="display: none;">
        <i class="fas fa-plug"></i> Connect WhatsApp
    </button>

    <!-- QR Code Container -->
    <div id="qr-box" class="qr-container" style="display: none;">
        <h5 class="mb-3">Scan this QR code with your phone</h5>
        <div id="qr-code-container">
            <div class="d-flex justify-content-center">
                <div class="spinner-border" role="status">
                    <span class="sr-only">Loading QR code...</span>
                </div>
            </div>
        </div>
        <p class="text-muted mt-2">Open WhatsApp > Settings > Linked Devices > Link a Device</p>
    </div>
    
    <!-- Loading Animation -->
    <div id="loading-lottie" style="display: none;">
        <lottie-player
            src="{{ asset('animations/loading.json') }}"
            background="transparent"
            speed="1"
            style="width: 180px; height: 180px; margin: 0 auto;"
            loop
            autoplay>
        </lottie-player>
        <div class="text-primary mt-2">Connecting to WhatsApp...</div>
    </div>
    
    <!-- Connected Status -->
    <div id="connected-status" class="text-center" style="display: none;">
        <div class="mb-3">
            <i class="fas fa-check-circle text-success" style="font-size: 3rem;"></i>
        </div>
        <h4 class="text-success mb-3">Successfully Connected!</h4>
        <div class="instructions">
            <p><strong>WhatsApp is now connected</strong> and ready to use.</p>
            <p class="mb-0">To switch accounts, please disconnect first.</p>
        </div>
    </div>
    
    <!-- Error Status -->
    <div id="error-status" class="text-center" style="display: none;">
        <div class="mb-3">
            <i class="fas fa-exclamation-triangle text-danger" style="font-size: 3rem;"></i>
        </div>
        <h4 class="text-danger mb-3">Connection Error</h4>
        <p>Failed to connect to WhatsApp. Please try again.</p>
    </div>
    
    <!-- Action Buttons -->
    <div class="mt-4">
        <button id="disconnect-btn" class="btn btn-danger" style="display: none;">
            <i class="fas fa-power-off"></i> Disconnect WhatsApp
        </button>
        <button id="refresh-btn" class="btn btn-outline-primary">
            <i class="fas fa-sync-alt"></i> Refresh Status
        </button>
    </div>
</div>

@push('scripts')
<script>
    let lastHash = null;
    let statusCheckInterval = null;
    let isConnecting = false;

    // Initialize the page
    document.addEventListener('DOMContentLoaded', function() {
        // Check the initial status immediately
        checkWhatsAppStatus();
        
        // Set up event listeners
        document.getElementById('connect-whatsapp-btn').addEventListener('click', startWhatsAppConnection);
        document.getElementById('disconnect-btn').addEventListener('click', disconnectWhatsApp);
        document.getElementById('refresh-btn').addEventListener('click', function() {
            location.reload();
        });
        
        // Start periodic status checking
        statusCheckInterval = setInterval(checkWhatsAppStatus, 3000);
    });

    async function checkWhatsAppStatus() {
        try {
            const response = await fetch("{{ route('admin.whatsapp.fetchQr') }}");
            const data = await response.json();
            
            if (data.status === 'connected') {
                updateStatus('connected');
                isConnecting = false;
            } else if (data.status === 'pending') {
                // If we have a QR code, show it
                if (data.qr) {
                    updateStatus('pending');
                    displayQrCode(data.qr);
                } else {
                    updateStatus('disconnected');
                }
            } else {
                updateStatus('disconnected');
                isConnecting = false;
            }
        } catch (error) {
            console.error('Error checking status:', error);
            updateStatus('error');
            isConnecting = false;
        }
    }

    function displayQrCode(qrData) {
        const currentHash = hashString(qrData);
        if (currentHash !== lastHash) {
            document.getElementById('qr-code-container').innerHTML = `<img class="qr-code" src="${qrData}" alt="WhatsApp QR Code">`;
            lastHash = currentHash;
        }
    }

    async function startWhatsAppConnection() {
        const connectBtn = document.getElementById('connect-whatsapp-btn');
        
        // Disable the button and show loading state
        connectBtn.disabled = true;
        connectBtn.innerHTML = '<i class="fas fa-spinner fa-spin loading-spinner"></i> Connecting...';
        isConnecting = true;
        
        updateStatus('loading');
        
        try {
            const res = await fetch("{{ route('admin.whatsapp.triggerLogin') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            });

            const data = await res.json();
            if (!data.success) {
                alert("Failed to start: " + (data.message || "Unknown error"));
                updateStatus('error');
                
                // Re-enable the button
                connectBtn.disabled = false;
                connectBtn.innerHTML = '<i class="fas fa-plug"></i> Connect WhatsApp';
                isConnecting = false;
            }
        } catch (e) {
            console.error("Trigger error:", e);
            alert("Something went wrong while starting WhatsApp login.");
            updateStatus('error');
            
            // Re-enable the button
            connectBtn.disabled = false;
            connectBtn.innerHTML = '<i class="fas fa-plug"></i> Connect WhatsApp';
            isConnecting = false;
        }
    }

    function updateStatus(status) {
        const statusIndicator = document.getElementById('status-indicator');
        const qrBox = document.getElementById('qr-box');
        const loadingLottie = document.getElementById('loading-lottie');
        const connectedStatus = document.getElementById('connected-status');
        const errorStatus = document.getElementById('error-status');
        const disconnectBtn = document.getElementById('disconnect-btn');
        const connectBtn = document.getElementById('connect-whatsapp-btn');

        // Reset all displays first
        qrBox.style.display = 'none';
        loadingLottie.style.display = 'none';
        connectedStatus.style.display = 'none';
        errorStatus.style.display = 'none';
        statusIndicator.style.display = 'block';

        switch (status) {
            case 'connected':
                statusIndicator.innerHTML = '<i class="fas fa-check-circle"></i> CONNECTED';
                statusIndicator.className = 'status-indicator bg-success text-white';
                connectedStatus.style.display = 'block';
                disconnectBtn.style.display = 'inline-block';
                connectBtn.style.display = 'none';
                break;

            case 'loading':
                statusIndicator.innerHTML = '<i class="fas fa-sync-alt fa-spin loading-spinner"></i> CONNECTING';
                statusIndicator.className = 'status-indicator bg-primary text-white';
                loadingLottie.style.display = 'block';
                disconnectBtn.style.display = 'none';
                connectBtn.style.display = 'none';
                break;

            case 'pending':
                statusIndicator.innerHTML = '<i class="fas fa-qrcode"></i> WAITING FOR QR CODE';
                statusIndicator.className = 'status-indicator bg-warning text-white';
                qrBox.style.display = 'block';
                disconnectBtn.style.display = 'none';
                connectBtn.style.display = 'none';
                break;

            case 'disconnected':
                statusIndicator.innerHTML = '<i class="fas fa-plug"></i> DISCONNECTED';
                statusIndicator.className = 'status-indicator bg-secondary text-white';
                disconnectBtn.style.display = 'none';
                connectBtn.style.display = 'inline-block';
                connectBtn.disabled = false;
                connectBtn.innerHTML = '<i class="fas fa-plug"></i> Connect WhatsApp';
                break;

            default: // error
                statusIndicator.innerHTML = '<i class="fas fa-exclamation-triangle"></i> CONNECTION ERROR';
                statusIndicator.className = 'status-indicator bg-danger text-white';
                errorStatus.style.display = 'block';
                disconnectBtn.style.display = 'none';
                connectBtn.style.display = 'inline-block';
                connectBtn.disabled = false;
                connectBtn.innerHTML = '<i class="fas fa-plug"></i> Connect WhatsApp';
        }
    }

    async function hashString(input) {
        const msgUint8 = new TextEncoder().encode(input);
        const hashBuffer = await crypto.subtle.digest('SHA-256', msgUint8);
        const hashArray = Array.from(new Uint8Array(hashBuffer));
        return hashArray.map(b => b.toString(16).padStart(2, '0')).join('');
    }

    async function disconnectWhatsApp() {
        if (!confirm('Are you sure you want to disconnect WhatsApp?')) return;
        
        try {
            const res = await fetch("{{ route('admin.whatsapp.disconnect') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            });
            const data = await res.json();
            if (data.success) {
                alert("Disconnected from WhatsApp successfully.");
                updateStatus('disconnected');
                location.reload();
            } else {
                alert("Failed to disconnect: " + (data.message || 'Unknown error'));
            }
        } catch (error) {
            console.error('Disconnect failed:', error);
            alert("An error occurred while disconnecting.");
        }
    }
</script>
@endpush
@endsection