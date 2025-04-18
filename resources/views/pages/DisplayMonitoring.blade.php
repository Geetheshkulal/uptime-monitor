@extends('dashboard')
@section('content')

    @push('styles')
        <!-- Toastr CSS -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    @endpush

    
    <!-- Page Heading -->
    <div class="container-fluid">
        <div class="row  p-2">
            <div class="d-flex  flex-md-row justify-content-between align-items-start align-items-md-center w-100">
                <!-- Name and buttons container -->
                <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center w-100 ">

                    <!-- monitor Name -->
                    <div class="mb-2 mb-md-0 mr-md-3">
                        <span class="h4 text-gray-800 font-weight-bold">{{ strtoupper($details->name) }}</span>
                    </div>

                    <!-- Buttons - will wrap under name on small screens -->
                    <div class="d-flex flex-wrap flex-md-nowrap">
                        {{-- edit buttons for monitor --}}
                        <button type="button" class="btn btn-primary mr-2 mb-2" data-toggle="modal" data-target="#editModal"
                            onclick="setEditUrl({{ $details->id }})">
                            <i class="fas fa-pen fa-1x"></i> Edit
                        </button>

                        {{-- delete buttons for monitor --}}
                        <button type="button" class="btn btn-danger mr-2 mb-2" data-toggle="modal"
                            data-target="#deleteModal" onclick="setDeleteUrl({{ $details->id }})">
                            <i class="fas fa-trash fa-1x"></i> Delete
                        </button>

                        {{-- checking the conditions and toggles --}}
                        @if ($details->paused)
                            <button type="button" class="btn btn-warning mr-2 mb-2"
                                onclick="pauseMonitor({{ $details->id }}, this)">
                                <i class="fas fa-play fa-1x"></i> Resume
                            </button>
                        @else
                            <button type="button" class="btn btn-success mr-2 mb-2"
                                onclick="pauseMonitor({{ $details->id }}, this)">
                                <i class="fas fa-pause fa-1x"></i> Pause
                            </button>
                        @endif
                    </div>
                </div>

                {{-- back button --}}
                <div class="ml-md-auto ">
                    <a href="{{ route('monitoring.dashboard') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left mr-1"></i>
                    </a>
                </div>
            </div>

        </div>



        <div class="row">
            <div class="col-xl-12">
                <h5 class="text-gray-800 font-weight-bold mb-3">
                    <span class="text-secondary">URL:</span>
                    <span class="text-primary">{{ $details->url }}</span>
                </h5>
            </div>
        </div>

        <!-- Status & Response Cards -->
        <div class="row d-flex justify-content-center">
            <!-- Current Status Card -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow-lg h-100 py-3">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-2">
                                    Current Status
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800" id="statusElement">
                                    {{ $details->status }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-bolt fa-2x text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Current Response Card -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow-lg h-100 py-3">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-2">
                                    Current Response
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">0</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-check-circle fa-2x text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Average Response Card -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow-lg h-100 py-3">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-2">
                                    Average Response
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800" id="averageResponse">0 ms</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-hourglass-half fa-2x text-warning"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Response Time Graph -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-lg mb-4">
                    <!-- Card Header -->
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-light">
                        <h6 class="m-0 font-weight-bold text-primary">Response Time Graph</h6>
                        <div class="dropdown no-arrow">
                            <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                            </a>
                            <!-- Dropdown content commented out -->
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="card-body">
                        <div class="chart-area">
                            <canvas id="myAreaChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                var ctx = document.getElementById("myAreaChart").getContext('2d');

                var responseTimes = {!! json_encode(array_slice($ChartResponses->pluck('response_time')->toArray(), -20)) !!};
                var timestamps = {!! json_encode(
                    array_slice(
                        $ChartResponses->pluck('created_at')->map(fn($date) => \Carbon\Carbon::parse($date)->format('j/n/Y h:i:s A'))->toArray(),
                        -20,
                    ),
                ) !!};

                var statusElement = document.getElementById('statusElement');

                var myLineChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: timestamps,
                        datasets: [{
                            label: "Response Time (ms)",
                            lineTension: 0.1,
                            backgroundColor: "rgba(78, 115, 223, 0.05)",
                            borderColor: "rgba(78, 115, 223, 1)",
                            pointRadius: 3,
                            pointBackgroundColor: "rgba(78, 115, 223, 1)",
                            pointBorderColor: "rgba(78, 115, 223, 1)",
                            pointHoverRadius: 3,
                            pointHoverBackgroundColor: "rgba(78, 115, 223, 1)",
                            pointHoverBorderColor: "rgba(78, 115, 223, 1)",
                            pointHitRadius: 10,
                            pointBorderWidth: 2,
                            data: responseTimes,
                        }],
                    },
                    options: {
                        maintainAspectRatio: false,
                        layout: {
                            padding: {
                                left: 10,
                                right: 25,
                                top: 25,
                                bottom: 0
                            }
                        },
                        scales: {
                            xAxes: [{
                                time: {
                                    unit: 'date'
                                },
                                gridLines: {
                                    display: true,
                                    drawBorder: true
                                },
                                ticks: {
                                    maxTicksLimit: 10
                                }
                            }],
                            yAxes: [{
                                ticks: {
                                    maxTicksLimit: 5,
                                    padding: 10,
                                    callback: function(value) {
                                        return value + ' ms';
                                    }
                                },
                                gridLines: {
                                    color: "rgb(234, 236, 244)",
                                    zeroLineColor: "rgb(234, 236, 244)",
                                    drawBorder: false,
                                    borderDash: [2],
                                    zeroLineBorderDash: [2]
                                }
                            }],
                        },
                        legend: {
                            display: true
                        },
                        tooltips: {
                            backgroundColor: "rgb(255,255,255)",
                            bodyFontColor: "#858796",
                            titleMarginBottom: 10,
                            titleFontColor: '#6e707e',
                            titleFontSize: 14,
                            borderColor: '#dddfeb',
                            borderWidth: 1,
                            xPadding: 15,
                            yPadding: 15,
                            displayColors: false,
                            intersect: false,
                            mode: 'index',
                            caretPadding: 10,
                            callbacks: {
                                label: function(tooltipItem, chart) {
                                    var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
                                    return datasetLabel + ': ' + tooltipItem.yLabel + ' ms';
                                }
                            }
                        }
                    }
                });

                function updateAverageResponse() {
                    if (responseTimes.length > 0) {
                        let sum = responseTimes.reduce((a, b) => a + b, 0);
                        let average = (sum / responseTimes.length).toFixed(2); // Round to 2 decimal places
                        document.getElementById("averageResponse").textContent = average + " ms";
                        
                    } else {
                        document.getElementById("averageResponse").textContent = "No Data";
                    }
                }

                // Call the function initially
                updateAverageResponse();

                setInterval(function() {
                    $.ajax({
                        url: "{{ route('display.chart.update', [$details->id, $details->type]) }}",
                        type: "GET",
                        dataType: "json",
                        success: function(response) {
                            var maxDataPoints = 20;
                            var responseTimes = response.responses.map(item => item.response_time)
                                .slice(-maxDataPoints);;
                            var timestamps = response.responses.map(item => new Date(item
                                .created_at).toLocaleString("en-IN", {
                                timeZone: "Asia/Kolkata"
                            })).slice(-maxDataPoints);;

                            myLineChart.data.datasets[0].data = responseTimes;
                            myLineChart.data.labels = timestamps;
                            myLineChart.update();

                            statusElement.textContent = response.status;

                            updateAverageResponse();
                        },

                        error: function(xhr, status, error) {
                            console.error("Error fetching data:", error);
                        }
                    });
                }, 10000); // Runs every 3000 milliseconds (3 seconds)

            });
        </script>
    @endpush

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this monitoring data?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <a href="#" id="deleteConfirmButton" class="btn btn-danger">Delete</a>
                </div>
            </div>
        </div>
    </div>

    <!-- edit Confirmation Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Monitoring</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form action="{{ route('monitoring.update', $details->id) }}" id="editForm" method="POST">
                    @csrf
                    @method('POST')
                    {{-- <input type="hidden" name="_method" value="POST">  --}}
                    <input type="hidden" name="type" value="{{ $details->type }}">

                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Friendly name</label>
                            <input id="name" class="form-control" name="name" type="text"
                                placeholder="E.g. Google" value="{{ $details->name }}" required>

                        </div>
                        <div class="mb-3">
                            <label for="url" class="form-label">URL</label>
                            <input id="url" class="form-control" name="url" type="text"
                                placeholder="E.g. https://www.google.com" value="{{ $details->url }}" required>
                            @error('url')
                                <p style="color: red; font-size: 14px;">{{ $message }}</p>
                            @enderror
                        </div>

                        @if ($details->type == 'port')
                            <div class="mb-3">
                                <label for="port" class="form-label">Port</label>
                                <select id="port" class="form-control" name="port" required>
                                    <option value="" disabled>Select Port</option>
                                    <option value="21" {{ $details->port == '21' ? 'selected' : '' }}>FTP - 21
                                    </option>
                                    <option value="22" {{ $details->port == '22' ? 'selected' : '' }}>SSH / SFTP - 22
                                    </option>
                                    <option value="25" {{ $details->port == '25' ? 'selected' : '' }}>SMTP - 25
                                    </option>
                                    <option value="53" {{ $details->port == '53' ? 'selected' : '' }}>DNS - 53
                                    </option>
                                    <option value="80" {{ $details->port == '80' ? 'selected' : '' }}>HTTP - 80
                                    </option>
                                    <option value="110" {{ $details->port == '110' ? 'selected' : '' }}>POP3 - 110
                                    </option>
                                    <option value="143" {{ $details->port == '143' ? 'selected' : '' }}>IMAP - 143
                                    </option>
                                    <option value="443" {{ $details->port == '443' ? 'selected' : '' }}>HTTPS - 443
                                    </option>
                                    <option value="465" {{ $details->port == '465' ? 'selected' : '' }}>SMTP - 465
                                    </option>
                                    <option value="587" {{ $details->port == '587' ? 'selected' : '' }}>SMTP - 587
                                    </option>
                                    <option value="993" {{ $details->port == '993' ? 'selected' : '' }}>IMAP - 993
                                    </option>
                                    <option value="995" {{ $details->port == '995' ? 'selected' : '' }}>POP3 - 995
                                    </option>
                                    <option value="3306" {{ $details->port == '3306' ? 'selected' : '' }}>MySQL - 3306
                                    </option>
                                </select>
                            </div>
                        @endif

                        <!-- Show DNS dropdown if type == 'dns' -->
                        @if ($details->type == 'dns')
                            <div class="mb-3">
                                <label for="dns_resource_type" class="form-label">DNS Resource Type</label>
                                <select id="dns_resource_type" class="form-control" name="dns_resource_type" required>
                                    <option value="A" {{ $details->dns_resource_type == 'A' ? 'selected' : '' }}>
                                        A
                                    </option>
                                    <option value="AAAA" {{ $details->dns_resource_type == 'AAAA' ? 'selected' : '' }}>
                                        AAAA</option>
                                    <option value="CNAME" {{ $details->dns_resource_type == 'CNAME' ? 'selected' : '' }}>
                                        CNAME</option>
                                    <option value="MX" {{ $details->dns_resource_type == 'MX' ? 'selected' : '' }}>MX
                                    </option>
                                    <option value="NS" {{ $details->dns_resource_type == 'NS' ? 'selected' : '' }}>NS
                                    </option>
                                    <option value="SOA" {{ $details->dns_resource_type == 'SOA' ? 'selected' : '' }}>
                                        SOA</option>
                                    <option value="TXT" {{ $details->dns_resource_type == 'TXT' ? 'selected' : '' }}>
                                        TXT</option>
                                    <option value="SRV" {{ $details->dns_resource_type == 'SRV' ? 'selected' : '' }}>
                                        SRV</option>
                                    <option value="DNS_ALL"
                                        {{ $details->dns_resource_type == 'DNS_ALL' ? 'selected' : '' }}>DNS_ALL</option>
                                </select>
                            </div>
                        @endif

                        <div class="mb-3">
                            <label for="retries" class="form-label">Retries</label>
                            <input id="retries" class="form-control" name="retries" type="number"
                                value="{{ $details->retries }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="interval" class="form-label">Interval (in minutes)</label>
                            <input id="interval" class="form-control" name="interval" type="number"
                                value="{{ $details->interval }}" required>
                        </div>

                        <h5 class="card-title">Notification</h5>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input id="email" class="form-control" name="email" type="email"
                                placeholder="example@gmail.com" value="{{ $details->email }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="telegram_id" class="form-label">Telegram Id (Optional)</label>
                            <input id="telegram_id" class="form-control" name="telegram_id" type="text"
                                value="{{ $details->telegram_id }}">
                        </div>
                        <div class="mb-3">
                            <label for="telegram_bot_token" class="form-label">Telegram Bot Token (Optional)</label>
                            <input id="telegram_bot_token" class="form-control" name="telegram_bot_token" type="text"
                                value="{{ $details->telegram_bot_token }}">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" id="editConfirmButton" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
        <script>
            function pauseMonitor(monitorId, button) {
                // Send AJAX request to toggle pause/resume
                fetch(`/monitor/pause/${monitorId}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
                            'Content-Type': 'application/json',
                        },
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Update button classes and icon
                            button.innerHTML = data.paused ?
                                '<i class="fas fa-play fa-1x"></i> Resume' :
                                '<i class="fas fa-pause fa-1x"></i> Pause';

                            // Toggle button classes
                            button.classList.toggle('btn-success', !data.paused);
                            button.classList.toggle('btn-warning', data.paused);

                            // Show success message
                            toastr.success(data.message);
                        } else {
                            toastr.error('Failed to update monitor status.');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        toastr.error('An error occurred.');
                    });
            }
        </script>

        <script>
            function setEditUrl(id) {
                let editForm = document.getElementById('editForm');
                if (editForm) {
                    editForm.action = "/monitoring/edit/" + id; // Set form action dynamically
                } else {
                    console.error("Edit form not found!");
                }
            }
        </script>

        <script>
            function setDeleteUrl(id) {
                let deleteButton = document.getElementById("deleteConfirmButton");
                deleteButton.href = "/monitoring/delete/" + id; // Sets the GET request URL
            }
        </script>

        <script>
            @if (session('success'))
                toastr.success("{{ session('success') }}");
            @endif
        </script>
    @endpush
@endsection
