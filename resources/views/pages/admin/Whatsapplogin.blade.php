@extends('dashboard')
@section('content')

<div class="container mt-5 text-center">
    <h4>Scan this QR Code to login to WhatsApp</h4>
    <div id="qr-box">
        <img id="qr-code" src="" alt="QR Code" style="width: 300px; height: auto;" />
    </div>
</div>
<div id="wa-status" class="mb-2 font-weight-bold">Checking WhatsApp...</div>

<script>
    let lastHash = null;

    async function fetchQrCode() {
        const response = await fetch("{{ route('admin.whatsapp.fetchQr') }}");
        const data = await response.json();
        const base64 = data.qr;

        if (base64) {
            const currentHash = await hashString(base64);

            if (currentHash !== lastHash) {
                document.getElementById('qr-code').src = base64;
                lastHash = currentHash;
            }
        }
    }

    async function hashString(input) {
        const msgUint8 = new TextEncoder().encode(input);
        const hashBuffer = await crypto.subtle.digest('SHA-256', msgUint8);
        const hashArray = Array.from(new Uint8Array(hashBuffer));
        return hashArray.map(b => b.toString(16).padStart(2, '0')).join('');
    }

    setInterval(fetchQrCode, 5000);
    fetchQrCode();
</script>
<script>
    async function checkWhatsAppConnection() {
        const res = await fetch("{{ route('admin.whatsapp.liveStatus') }}");
        const data = await res.json();

        const el = document.getElementById('wa-status');

        if (data.status === 'connected') {
            el.textContent = '✅ WhatsApp Connected';
            el.style.color = 'green';
        } else if (data.status === 'not_connected') {
            el.textContent = '❌ WhatsApp Not Connected';
            el.style.color = 'red';
        } else {
            el.textContent = '⚠️ Unable to check WhatsApp';
            el.style.color = 'gray';
        }
    }

    checkWhatsAppConnection();
</script>
@endsection