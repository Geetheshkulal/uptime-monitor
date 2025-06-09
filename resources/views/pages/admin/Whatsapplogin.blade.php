@extends('dashboard')
@section('content')

<!-- Lottie script -->
<script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>

<div class="container mt-5 text-center">
    <h4 id="title">Scan this QR Code to login to WhatsApp</h4>

    <div id="qr-box">
        <img id="qr-code" src="" alt="QR Code" style="width: 300px; height: auto;" />
    </div>

    <!-- Lottie Animation -->
    <div id="loading-lottie" class="my-4" style="display: none;">
        <lottie-player
            src="https://assets1.lottiefiles.com/packages/lf20_mfdfvyvu.json"
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
                location.reload(); // reload UI
            } else {
                alert("Failed to disconnect.");
            }
        } catch (error) {
            console.error('Disconnect failed:', error);
            alert("An error occurred.");
        }
    }
</script>

@endsection
