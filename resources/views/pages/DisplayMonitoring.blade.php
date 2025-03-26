@extends('dashboard')
@section('content')

@push('styles')
<style>
    :root {
        --primary: #4e73df;
        --primary-light: rgba(78, 115, 223, 0.1);
        --success: #1cc88a;
        --success-light: rgba(28, 200, 138, 0.1);
        --warning: #f6c23e;
        --warning-light: rgba(246, 194, 62, 0.1);
        --danger: #e74a3b;
        --danger-light: rgba(231, 74, 59, 0.1);
        --dark: #2d3748;
        --light: #f8f9fc;
        --gray: #6c757d;
    }
    
    body {
        font-family: 'Nunito', sans-serif;
        background-color: #f5f7fb;
    }
    
    .card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        overflow: hidden;
    }
    
    .card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }
    
    .card-header {
        background-color: white;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        padding: 1.25rem 1.5rem;
        font-weight: 600;
    }
    
    .card-body {
        padding: 1.5rem;
    }
    
    .btn {
        border-radius: 8px;
        font-weight: 600;
        padding: 0.5rem 1.25rem;
        transition: all 0.3s ease;
        letter-spacing: 0.5px;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, var(--primary), #3a56c7);
        border: none;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(78, 115, 223, 0.3);
    }
    
    .btn-danger {
        background: linear-gradient(135deg, var(--danger), #c23321);
        border: none;
    }
    
    .btn-danger:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(231, 74, 59, 0.3);
    }
    
    .btn-secondary {
        background-color: #edf2f7;
        color: var(--dark);
        border: none;
    }
    
    .btn-secondary:hover {
        background-color: #e2e8f0;
        color: var(--dark);
    }
    
    .badge {
        padding: 6px 12px;
        font-weight: 600;
        border-radius: 50px;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
    }
    
    .badge-success {
        background-color: var(--success-light);
        color: var(--success);
    }
    
    .badge-danger {
        background-color: var(--danger-light);
        color: var(--danger);
    }
    
    .badge-warning {
        background-color: var(--warning-light);
        color: var(--warning);
    }
    
    .badge-primary {
        background-color: var(--primary-light);
        color: var(--primary);
    }
    
    .status-card {
        border-left: 4px solid;
        transition: all 0.3s ease;
    }
    
    .status-card:hover {
        transform: translateY(-5px);
    }
    
    .status-card-primary {
        border-left-color: var(--primary);
    }
    
    .status-card-success {
        border-left-color: var(--success);
    }
    
    .status-card-warning {
        border-left-color: var(--warning);
    }
    
    .chart-container {
        position: relative;
        height: 300px;
        width: 100%;
    }
    
    .modal-content {
        border-radius: 12px;
        border: none;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
    }
    
    .modal-header {
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    }
    
    .modal-footer {
        border-top: 1px solid rgba(0, 0, 0, 0.05);
    }
    
    .form-control {
        border-radius: 8px;
        padding: 0.75rem 1rem;
        border: 1px solid #e2e8f0;
        transition: all 0.3s ease;
    }
    
    .form-control:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
    }
    
    .form-label {
        font-weight: 600;
        color: var(--dark);
        margin-bottom: 0.5rem;
    }
    
    .text-url {
        color: var(--primary);
        font-weight: 500;
        word-break: break-all;
    }
    
    .text-url:hover {
        text-decoration: underline;
        color: #3a56c7;
    }
    
    .monitor-name {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--dark);
        letter-spacing: 0.5px;
    }
    
    @media (max-width: 768px) {
        .monitor-name {
            font-size: 1.25rem;
        }
        
        .btn {
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
        }
        
        .card-body {
            padding: 1rem;
        }
    }
    
    /* Animation for status change */
    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }
    
    .status-pulse {
        animation: pulse 1.5s infinite;
    }
</style>
@endpush

<!-- Main Content -->
<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between">
                <div class="d-flex align-items-center mb-3 mb-md-0">
                    <h1 class="monitor-name mr-3">{{ $details->name }}</h1>
                    <span class="badge badge-primary align-self-center">
                        {{ strtoupper($details->type) }}{{ $details->type === 'port' ? '-' . $details->port : '' }}
                    </span>
                </div>
                <div class="d-flex">
                    <button type="button" class="btn btn-primary mx-2" data-toggle="modal" data-target="#editModal" 
                        onclick="setEditUrl({{ $details->id }})">
                        <i class="fas fa-pen mr-2"></i> Edit
                    </button>
                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteModal" 
                        onclick="setDeleteUrl({{ $details->id }})">
                        <i class="fas fa-trash mr-2"></i> Delete
                    </button>
                    <button onclick="window.print()" class="btn btn-secondary ml-2 d-none d-md-inline-block">
                        <i class="fas fa-download mr-2"></i> Generate Report
                    </button>
                </div>
            </div>
            
            <!-- URL Display -->
            <div class="mt-3">
                <h5 class="font-weight-bold text-gray-700 mb-1">
                    <span class="text-muted">URL:</span> 
                    <a href="{{ $details->url }}" target="_blank" class="text-url">{{ $details->url }}</a>
                </h5>
            </div>
        </div>
    </div>

    <!-- Status Cards -->
    <div class="row">
        <!-- Current Status Card -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card status-card status-card-primary h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-3">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-2">
                                Current Status
                            </div>
                            <div class="h4 mb-0 font-weight-bold text-gray-800 d-flex align-items-center">
                                <span id="statusElement" class="status-pulse">
                                    {{ ucfirst($details->status) }}
                                </span>
                                @if($details->status === 'up')
                                    <i class="fas fa-check-circle ml-2 text-success"></i>
                                @else
                                    <i class="fas fa-times-circle ml-2 text-danger"></i>
                                @endif
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-bolt fa-2x text-primary opacity-25"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Current Response Card -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card status-card status-card-success h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-3">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-2">
                                Current Response
                            </div>
                            <div class="h4 mb-0 font-weight-bold text-gray-800">0 ms</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-stopwatch fa-2x text-success opacity-25"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Average Response Card -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card status-card status-card-warning h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-3">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-2">
                                Average Response
                            </div>
                            <div class="h4 mb-0 font-weight-bold text-gray-800" id="averageResponse">0 ms</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-line fa-2x text-warning opacity-25"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Response Time Graph -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-white">
                    <h6 class="m-0 font-weight-bold text-primary">Response Time History</h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                    </div>
                </div>
                
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="myAreaChart"></canvas>
                    </div>
                </div>  
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Initialize Chart
        var ctx = document.getElementById("myAreaChart").getContext('2d');
        var responseTimes = {!! json_encode(array_slice($ChartResponses->pluck('response_time')->toArray(), -20)) !!};
        var timestamps = {!! json_encode(array_slice($ChartResponses->pluck('created_at')
            ->map(fn($date) => \Carbon\Carbon::parse($date)->format('j/n/Y h:i A'))
            ->toArray(), -20)) !!};

        var myLineChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: timestamps,
                datasets: [{
                    label: "Response Time (ms)",
                    lineTension: 0.3,
                    backgroundColor: "rgba(78, 115, 223, 0.05)",
                    borderColor: "rgba(78, 115, 223, 1)",
                    pointRadius: 3,
                    pointBackgroundColor: "rgba(78, 115, 223, 1)",
                    pointBorderColor: "rgba(78, 115, 223, 1)",
                    pointHoverRadius: 5,
                    pointHoverBackgroundColor: "rgba(78, 115, 223, 1)",
                    pointHoverBorderColor: "rgba(78, 115, 223, 1)",
                    pointHitRadius: 10,
                    pointBorderWidth: 2,
                    data: responseTimes,
                }],
            },
            options: {
                maintainAspectRatio: false,
                responsive: true,
                layout: {
                    padding: { left: 10, right: 25, top: 25, bottom: 0 }
                },
                scales: {
                    x: {
                        grid: {
                            display: true,
                            drawBorder: true
                        },
                        ticks: {
                            maxTicksLimit: 10
                        }
                    },
                    y: {
                        ticks: {
                            maxTicksLimit: 5,
                            padding: 10,
                            callback: function(value) {
                                return value + ' ms';
                            }
                        },
                        grid: {
                            color: "rgb(234, 236, 244)",
                            drawBorder: false,
                            borderDash: [2],
                            zeroLineBorderDash: [2]
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            padding: 20
                        }
                    },
                    tooltip: {
                        backgroundColor: "rgb(255,255,255)",
                        bodyColor: "#858796",
                        titleMarginBottom: 10,
                        titleColor: '#6e707e',
                        titleFontSize: 14,
                        borderColor: '#dddfeb',
                        borderWidth: 1,
                        padding: 15,
                        intersect: false,
                        mode: 'index',
                        caretPadding: 10,
                        callbacks: {
                            label: function(context) {
                                var label = context.dataset.label || '';
                                return label + ': ' + context.parsed.y + ' ms';
                            }
                        }
                    }
                }
            }
        });

        // Calculate and display average response time
        function updateAverageResponse() {
            if (responseTimes.length > 0) {
                let sum = responseTimes.reduce((a, b) => a + b, 0);
                let average = (sum / responseTimes.length).toFixed(2);
                document.getElementById("averageResponse").textContent = average + " ms";
            } else {
                document.getElementById("averageResponse").textContent = "No Data";
            }
        }

        // Initial calculation
        updateAverageResponse();

        // Auto-refresh data
        setInterval(function () {
            $.ajax({
                url: "{{ route('display.chart.update',[$details->id,$details->type]) }}",
                type: "GET",
                dataType: "json",
                success: function (response) {
                    var maxDataPoints = 20;
                    var responseTimes = response.map(item => item.response_time).slice(-maxDataPoints);
                    var timestamps = response.map(item => new Date(item.created_at).toLocaleString("en-IN", { 
                        timeZone: "Asia/Kolkata",
                        day: 'numeric',
                        month: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    })).slice(-maxDataPoints);

                    // Update chart data
                    myLineChart.data.datasets[0].data = responseTimes;
                    myLineChart.data.labels = timestamps;
                    myLineChart.update();

                    // Update status if changed
                    if (response.length > 0) {
                        var latestStatus = response[response.length - 1].status;
                        var statusElement = document.getElementById('statusElement');
                        if (statusElement.textContent.trim().toLowerCase() !== latestStatus) {
                            statusElement.textContent = latestStatus.charAt(0).toUpperCase() + latestStatus.slice(1);
                            
                            // Add pulse animation
                            statusElement.classList.add('status-pulse');
                            setTimeout(() => {
                                statusElement.classList.remove('status-pulse');
                            }, 3000);
                        }
                    }

                    updateAverageResponse();
                },
                error: function (xhr, status, error) {
                    console.error("Error fetching data:", error);
                }
            });
        }, 30000); // Refresh every 30 seconds
    });

    // Set edit form URL
    function setEditUrl(id) {
        let editForm = document.getElementById('editForm');
        if (editForm) {
            editForm.action = "/monitoring/edit/"+id;
        } else {
            console.error("Edit form not found!");
        }
    }

    // Set delete URL
    function setDeleteUrl(id) {
        let deleteButton = document.getElementById("deleteConfirmButton");
        deleteButton.href = "/monitoring/delete/" + id;
    }

    // Toast notifications
    @if(session('success'))
        toastr.success("{{ session('success') }}", "Success", {
            closeButton: true,
            progressBar: true,
            positionClass: "toast-top-right",
            timeOut: 5000
        });
    @endif
</script>
@endpush

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title font-weight-bold" id="deleteModalLabel">Confirm Deletion</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <p>Are you sure you want to delete this monitor? This action cannot be undone.</p>
          <p class="text-muted"><small>All monitoring data and history will be permanently removed.</small></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <a href="#" id="deleteConfirmButton" class="btn btn-danger">Delete Monitor</a>
        </div>
      </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title font-weight-bold" id="editModalLabel">Edit Monitor Settings</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <form action="{{ route('monitoring.update', $details->id)}}" id="editForm" method="POST">
            @csrf
            <input type="hidden" name="_method" value="POST"> 
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="name" class="form-label">Friendly Name</label>
                            <input id="name" class="form-control" name="name" type="text" 
                                   placeholder="E.g. Google" value="{{$details->name}}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="url" class="form-label">URL</label>
                            <input id="url" class="form-control" name="url" type="text" 
                                   placeholder="E.g. https://www.google.com" value="{{$details->url}}" required>
                        </div>
                    </div>
                </div>

                @if($details->type == 'port')
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="port" class="form-label">Port</label>
                            <select id="port" class="form-control" name="port" required>
                                <option value="" disabled>Select Port</option>
                                <option value="21" {{ $details->port == '21' ? 'selected' : '' }}>FTP - 21</option>
                                <option value="22" {{ $details->port == '22' ? 'selected' : '' }}>SSH / SFTP - 22</option>
                                <option value="25" {{ $details->port == '25' ? 'selected' : '' }}>SMTP - 25</option>
                                <option value="53" {{ $details->port == '53' ? 'selected' : '' }}>DNS - 53</option>
                                <option value="80" {{ $details->port == '80' ? 'selected' : '' }}>HTTP - 80</option>
                                <option value="110" {{ $details->port == '110' ? 'selected' : '' }}>POP3 - 110</option>
                                <option value="143" {{ $details->port == '143' ? 'selected' : '' }}>IMAP - 143</option>
                                <option value="443" {{ $details->port == '443' ? 'selected' : '' }}>HTTPS - 443</option>
                                <option value="443" {{ $details->port == '465' ? 'selected' : '' }}>SMTP - 465</option>
                                <option value="443" {{ $details->port == '587' ? 'selected' : '' }}>SMTP - 587</option>
                                <option value="143" {{ $details->port == '993' ? 'selected' : '' }}>IMAP - 993</option>
                                <option value="110" {{ $details->port == '995' ? 'selected' : '' }}>POP3 - 995</option>
                                <option value="3306" {{ $details->port == '3306' ? 'selected' : '' }}>MySQL - 3306</option>
                            </select>
                        </div>
                    </div>
                </div>
                @endif

                @if($details->type == 'dns')
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="dns_resource_type" class="form-label">DNS Resource Type</label>
                            <select id="dns_resource_type" class="form-control" name="dns_resource_type" required>
                                <option value="A" {{ $details->dns_resource_type == 'A' ? 'selected' : '' }}>A</option>
                                <option value="AAAA" {{ $details->dns_resource_type == 'AAAA' ? 'selected' : '' }}>AAAA</option>
                                <option value="CNAME" {{ $details->dns_resource_type == 'CNAME' ? 'selected' : '' }}>CNAME</option>
                                <option value="MX" {{ $details->dns_resource_type == 'MX' ? 'selected' : '' }}>MX</option>
                                <option value="NS" {{ $details->dns_resource_type == 'NS' ? 'selected' : '' }}>NS</option>
                                <option value="SOA" {{ $details->dns_resource_type == 'SOA' ? 'selected' : '' }}>SOA</option>
                                <option value="TXT" {{ $details->dns_resource_type == 'TXT' ? 'selected' : '' }}>TXT</option>
                                <option value="SRV" {{ $details->dns_resource_type == 'SRV' ? 'selected' : '' }}>SRV</option>
                                <option value="DNS_ALL" {{ $details->dns_resource_type == 'DNS_ALL' ? 'selected' : '' }}>DNS_ALL</option>
                            </select>
                        </div>
                    </div>
                </div>
                @endif

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="retries" class="form-label">Retries</label>
                            <input id="retries" class="form-control" name="retries" type="number" 
                                   min="1" max="5" value="{{$details->retries ?? 3}}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="interval" class="form-label">Interval (minutes)</label>
                            <input id="interval" class="form-control" name="interval" type="number" 
                                   min="1" max="60" value="{{$details->interval ?? 1}}" required>
                        </div>
                    </div>
                </div>

                <h5 class="font-weight-bold mt-4 mb-3">Notification Settings</h5>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input id="email" class="form-control" name="email" type="email" 
                                   placeholder="example@gmail.com" value="{{$details->email}}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="telegram_id" class="form-label">Telegram ID (Optional)</label>
                            <input id="telegram_id" class="form-control" name="telegram_id" type="text"  
                                   value="{{$details->telegram_id}}">
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="telegram_bot_token" class="form-label">Telegram Bot Token (Optional)</label>
                            <input id="telegram_bot_token" class="form-control" name="telegram_bot_token" 
                                   type="text" value="{{$details->telegram_bot_token}}">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="submit" id="editConfirmButton" class="btn btn-primary">
                    <i class="fas fa-save mr-2"></i> Save Changes
                </button>
            </div>
        </form>
      </div>
    </div>
</div>

@endsection