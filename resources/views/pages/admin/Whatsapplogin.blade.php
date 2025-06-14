@extends('dashboard')
@section('content')

<!-- Lottie & FontAwesome -->
<script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet"/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

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
        background: lightgreen;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(37, 211, 102, 0.3);
    }
    
    .instructions {
        margin-top: 1.5rem;
        padding: 1rem;
        background: #f0f8ff;
        border-left: 4px solid #25D366;
        border-radius: 0 4px 4px 0;
        text-align: left;
    }
    #profile-photo{
        border-radius:50% !important;
    }
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .spin{
        animation: spin 0.5s linear infinite;
        display: inline-block;
    }
</style>



<div class="whatsapp-container">

    <h3 class="mb-4">
        <i class="fab fa-whatsapp text-success"></i> WhatsApp Connection
    </h3>
    
    <div id="status-indicator" class="status-indicator bg-secondary text-white">
        <i class="fas fa-circle-notch fa-spin"></i> Checking WhatsApp Status...
    </div>
    
    <button id="connect-whatsapp-btn" class="btn btn-whatsapp mt-3">
        <i class="fas fa-plug"></i> Connect WhatsApp
    </button>

  

    <!-- QR Code Container -->
    <div id="qr-box" class="qr-container" style="display: none;">
        <h5 class="mb-3">Scan this QR code with your phone</h5>
        <span id="qr-code-container">
            
        </span>
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
        {{-- profile image --}}

    <div id="profile-photo-wrapper" class="mb-3">
        <img id="profile-photo" src="" alt="Profile Photo"  style="width: 100px; height: 100px; object-fit: cover; display: none;">
    </div>

    <p id="wa-username" class="mt-2 fw-bold" style="display: none;"></p>

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
        <button id="disconnect-btn" class="btn btn-danger" style="display: none;" onclick="disconnectWhatsApp()">
            <i class="fas fa-power-off"></i> Disconnect WhatsApp
        </button>
        <button id="refresh-btn" class="btn btn-outline-primary" onclick="location.reload()">
            <i class="fas fa-sync-alt"></i> Refresh Status
        </button>
        <button id="retry-whatsapp-btn" class="btn btn-whatsapp mt-3" onclick="retryWhatsApp()">
            <i class="fas fa-sync-alt" id="retry-spinner-icon"></i> Retry
        </button>
    </div>
</div>

<!-- Optional Bootstrap Modal (for confirmation) -->
<!-- Disconnect Confirmation Modal -->
<div class="modal fade" id="disconnectConfirmModal" tabindex="-1" role="dialog" aria-labelledby="disconnectModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header text-black">
          <h5 class="modal-title" id="disconnectModalLabel">Disconnect WhatsApp</h5>
          <button type="button" class="close text-black" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          Are you sure you want to disconnect WhatsApp?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-danger" id="confirmDisconnectBtn">Disconnect</button>
        </div>
      </div>
    </div>
  </div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>


<script>

    let lastHash = null;
    let connecting=false;

    async function fetchQrCode() {
        try {
            const response = await fetch("{{ route('admin.whatsapp.fetchQr') }}");
            const data = await response.json();

            if (data.qr) {
                const currentHash = await hashString(data.qr);
                if (currentHash !== lastHash) {
                    document.getElementById('qr-code-container').innerHTML = `<img id="qr-code" class="qr-code" src="${data.qr}" alt="Waiting for QR code...">`;
                    lastHash = currentHash;

                    document.getElementById('connect-whatsapp-btn').disabled = true;

                    if(connecting){
                        document.getElementById('loading-lottie').style.display = 'none';
                        connecting=false;
                    }
                }
            }else{
                document.getElementById('qr-code-container').innerHTML = "<span class='text-muted'>Waiting for QR code...</span>";
            }

            updateStatus(data.status || 'pending');
        } catch (error) {
            console.error('Error fetching QR:', error);
            updateStatus('error');
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
        const retryBtn = document.getElementById('retry-whatsapp-btn');
        const refreshBtn = document.getElementById('refresh-btn');
        const profilePhoto = document.getElementById('profile-photo');
        const userName = document.getElementById('wa-username');
        

        

        // Reset all displays first
        qrBox.style.display = 'none';
        loadingLottie.style.display = 'none';
        connectedStatus.style.display = 'none';
        errorStatus.style.display = 'none';
        statusIndicator.style.display = 'none';

        switch (status) {
            case 'connected':
                statusIndicator.innerHTML = '<i class="fas fa-check-circle"></i> CONNECTED';
                statusIndicator.className = 'status-indicator bg-success text-white';
                connectedStatus.style.display = 'block';
                disconnectBtn.style.display = 'inline-block';
                connectBtn.style.display = 'none';
                retryBtn.style.display='none';

                const profileImageUrl = "{{ route('admin.whatsapp.profileImage') }}?t=" + new Date().getTime();

                if(profileImageUrl)
                {
                    profilePhoto.src = profileImageUrl;
                    profilePhoto.style.display = 'inline-block';

                }else{
                    profilePhoto.style.display = 'none';
                }

                break;

            case 'loading':
                statusIndicator.innerHTML = '<i class="fas fa-sync-alt fa-spin"></i> CONNECTING';
                statusIndicator.className = 'status-indicator bg-primary text-white';
                loadingLottie.style.display = 'block';
                disconnectBtn.style.display = 'none';
                connectBtn.style.display = 'none';
                retryBtn.style.display='none';

                break;

            case 'disconnected':
                statusIndicator.innerHTML = '<i class="fas fa-times-circle"></i> DISCONNECTED';
                statusIndicator.className = 'status-indicator bg-dark text-white';
                errorStatus.style.display = 'block';
                retryBtn.style.display='inline-block';
                connectBtn.style.display = 'none';
                connectBtn.disabled = false;
                disconnectBtn.style.display = 'none';
                refreshBtn.style.display = 'none';
                break;

            case 'pending':

                // qrBox.style.display = 'block';
                // disconnectBtn.style.display = 'none';
                // connectBtn.style.display = 'inline-block';
                qrBox.style.display = 'block';
                disconnectBtn.style.display = 'none';
                retryBtn.style.display='none';

                // 🛠 FIX: Only show connect button if not already connecting
                if (!connecting) {
                    connectBtn.style.display = 'inline-block';
                    loadingLottie.style.display = 'none';
                } else {
                    connectBtn.style.display = 'none';
                    loadingLottie.style.display = 'block';
                    statusIndicator.style.display = 'block';
                    statusIndicator.innerHTML = '<i class="fas fa-sync-alt fa-spin"></i> CONNECTING';
                    statusIndicator.className = 'status-indicator bg-primary text-white';
                }
                break;

            default: 
                statusIndicator.innerHTML = '<i class="fas fa-exclamation-triangle"></i> CONNECTION ERROR';
                statusIndicator.className = 'status-indicator bg-danger text-white';
                errorStatus.style.display = 'block';
                disconnectBtn.style.display = 'none';
                retryBtn.style.display='none';

        }
    }

    async function hashString(input) {
        const msgUint8 = new TextEncoder().encode(input);
        const hashBuffer = await crypto.subtle.digest('SHA-256', msgUint8);
        const hashArray = Array.from(new Uint8Array(hashBuffer));
        return hashArray.map(b => b.toString(16).padStart(2, '0')).join('');
    }

    async function disconnectWhatsApp() {

         const modal = new bootstrap.Modal(document.getElementById('disconnectConfirmModal'));
            modal.show();

        document.getElementById('confirmDisconnectBtn').onclick = async function () {
            modal.hide();
        
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
                toastr.success("Disconnected from WhatsApp successfully.");
                setTimeout(() => location.reload(), 1500);
                // document.getElementById('connect-whatsapp-btn').disabled = false;
            } else {
                toastr.error("Failed to disconnect: " + (data.message || 'Unknown error'));
            }
        } catch (error) {
            console.error('Disconnect failed:', error);
            toastr.error("An error occurred while disconnecting.");
        }
    }
}

async function retryWhatsApp(){
    const retrySpinnerIcon = document.getElementById('retry-spinner-icon');
    const retryBtn = document.getElementById('retry-whatsapp-btn');

    retryBtn.disabled = true;
    retrySpinnerIcon.classList.add('spin');

    try{
        const res = await fetch("{{ route('admin.whatsapp.retry') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            });


            const data = await res.json();
            if (data.success) {
               
            } else {
                toastr.error("Failed to reconnect: " + (data.message || 'Unknown error'));
            }

    }catch(error){
        console.error('Disconnect failed:', error);
        toastr.error("An error occurred while disconnecting.");

    }


    retrySpinnerIcon.classList.remove('spin');
    retryBtn.disabled = false;
    window.location.reload();

}

    // Check status every 2 seconds
    setInterval(fetchQrCode, 2000);
    // Initial check
    fetchQrCode();

</script>
 <script>
    document.getElementById('connect-whatsapp-btn').addEventListener('click', async () => {
        const connectBtn = document.getElementById('connect-whatsapp-btn');
        const loadingLottie = document.getElementById('loading-lottie');
        const statusIndicator=document.getElementById('status-indicator');

        connecting=true;
        connectBtn.style.display = 'none';
        loadingLottie.style.display = 'block';
        statusIndicator.style.display = 'block';
        statusIndicator.innerHTML = '<i class="fas fa-sync-alt fa-spin"></i> CONNECTING';
        statusIndicator.className = 'status-indicator bg-primary text-white';

        try {
            const res = await fetch("{{ route('admin.whatsapp.triggerLogin') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            });

            const data = await res.json();
            if (data.success) {
                // alert("Login process started.");
                console.log("Login process started.");
            } else {
                alert("Failed to start: " + (data.message || "Unknown error"));
                connecting=false;
                connectBtn.style.display = 'inline-block';
                loadingLottie.style.display = 'none';
            }
        } catch (e) {
            console.error("Trigger error:", e);
            alert("Something went wrong while starting WhatsApp login.");

            connecting=false;
            connectBtn.style.display = 'inline-block';
            loadingLottie.style.display = 'none';
            
        }
    });

</script>
@endsection


{{-- for backup --}}

{{-- @extends('dashboard')
@section('content')


<script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>

<div class="container mt-5 text-center">
    <h4 id="title">Scan this QR Code to login to WhatsApp</h4>

    <div id="qr-box">
        <img id="qr-code" src="" alt="QR Code" style="width: 300px; height: auto;" />
    </div>

  
    <div id="loading-lottie" class="my-4" style="display: none;">
        <lottie-player
            src="{{ asset('animations/loading.json') }}"
            background="transparent"
            speed="1"
            style="width: 150px; height: 150px; margin: auto;"
            loop
            autoplay>
        </lottie-player>
        <div class="text-muted">Logging in to WhatsApp...</div>
    </div>

    <div class="mt-3">
        <button id="disconnect-btn" class="btn btn-danger" style="display: none;" onclick="disconnectWhatsApp()">Disconnect</button>
    </div>
</div>

<div id="wa-status" class="mb-2 font-weight-bold">Checking WhatsApp...</div>

<script>
    let lastHash = null;

    async function fetchQrCode() {
        try {
            const response = await fetch("{{ route('admin.whatsapp.fetchQr') }}");
            const data = await response.json();

            if (data.qr) {
                const currentHash = await hashString(data.qr);

                if (currentHash !== lastHash) {
                    document.getElementById('qr-code').src = data.qr;
                    lastHash = currentHash;
                }
            }

            updateStatus(data.status || 'pending');
        } catch (error) {
            console.error('Error fetching QR:', error);
            updateStatus('error');
        }
    }

    function updateStatus(status) {
        const statusBox = document.getElementById('wa-status');
        const qrBox = document.getElementById('qr-box');
        const title = document.getElementById('title');
        const lottie = document.getElementById('loading-lottie');
        const disconnectBtn = document.getElementById('disconnect-btn');

        if (status === 'connected') {
            statusBox.innerText = '✅ WhatsApp is connected';
            statusBox.className = 'text-success font-weight-bold';
            disconnectBtn.style.display = 'inline-block';
            qrBox.style.display = 'none';
            title.style.display = 'none';
            lottie.style.display = 'none';
        } else if (status === 'loading') {
            statusBox.innerText = '⏳ Logging in to WhatsApp...';
            statusBox.className = 'text-primary font-weight-bold';
            qrBox.style.display = 'none';
            title.style.display = 'none';
            lottie.style.display = 'block';
            disconnectBtn.style.display = 'none';
        } else if (status === 'pending') {
            statusBox.innerText = '⏳ Waiting for WhatsApp login...';
            statusBox.className = 'text-warning font-weight-bold';
            qrBox.style.display = 'block';
            title.style.display = 'block';
            lottie.style.display = 'none';
            disconnectBtn.style.display = 'none';
        } else {
            statusBox.innerText = '❌ Error connecting to WhatsApp';
            statusBox.className = 'text-danger font-weight-bold';
            qrBox.style.display = 'block';
            title.style.display = 'block';
            lottie.style.display = 'none';
            disconnectBtn.style.display = 'none';
        }
    }

    async function hashString(input) {
        const msgUint8 = new TextEncoder().encode(input);
        const hashBuffer = await crypto.subtle.digest('SHA-256', msgUint8);
        const hashArray = Array.from(new Uint8Array(hashBuffer));
        return hashArray.map(b => b.toString(16).padStart(2, '0')).join('');
    }

    setInterval(fetchQrCode, 3000);
    fetchQrCode();
</script>

<script>
    async function disconnectWhatsApp() {
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
                alert("Disconnected from WhatsApp.");
                location.reload(); 
            } else {
                alert("Failed to disconnect.");
            }
        } catch (error) {
            console.error('Disconnect failed:', error);
            alert("An error occurred.");
        }
    }
</script>

@endsection --}}
