@extends('dashboard')
@section('content')

<div class="server-health">
    <h2>Server Health Metrics</h2>
    <ul>
        <li id="cpu-load">CPU Load: Loading...</li>
        <li id="memory-usage">Memory Usage: Loading...</li>
        <li id="disk-space">Disk Space: Loading...</li>
    </ul>
</div>

<script>
    function fetchServerHealth() {
        fetch('{{ route('server.health') }}')
            .then(response => response.json())
            .then(data => {
                document.getElementById('cpu-load').innerText = `CPU Load: ${data.cpu_load}`;
                document.getElementById('memory-usage').innerText = `Memory Usage: ${(data.memory_usage / 1024 / 1024).toFixed(2)} MB`;
                document.getElementById('disk-space').innerText = `Disk Space: ${(data.disk_free_space / 1024 / 1024 / 1024).toFixed(2)} GB / ${(data.disk_total_space / 1024 / 1024 / 1024).toFixed(2)} GB`;
            })
            .catch(error => {
                console.error('Error fetching server health:', error);
            });
    }

    // Fetch server health metrics every 5 seconds
    setInterval(fetchServerHealth, 5000);
    fetchServerHealth(); // Initial fetch
</script>

@endsection