@extends('dashboard')
@section('content')

{{-- <div class="d-sm-flex align-items-center justify-content-between mb-4">
  
    <h1 class="h3 mb-0 text-gray-800">DISPLAY</h1>
    <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
            class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
</div> --}}

<!-- Page Heading -->
<!-- Page Heading -->
<div class="container-fluid">
    <div class="row">
        <div class="col-xl-12">
            <div class="d-sm-flex align-items-center justify-content-between mb-3">
                <h1 class="h3 mb-0 text-gray-800 font-weight-bold">{{$details->name}}</h1>
                <div onclick="window.print()" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                    <i class="fas fa-download fa-sm text-white-50"></i> Generate Report
                </div>
            </div>
        </div>
    </div>

    <!-- URL Display -->
    <div class="row">
        <div class="col-xl-12">
            <h5 class="text-gray-800 font-weight-bold mb-3">
                <span class="text-secondary">URL:</span> 
                <span class="text-primary">{{$details->url}}</span>
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
                                {{$details->status}}
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
                            <div class="h5 mb-0 font-weight-bold text-gray-800">0</div>
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


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        var ctx = document.getElementById("myAreaChart").getContext('2d');

        var responseTimes = {!! json_encode(array_slice($ChartResponses->pluck('response_time')->toArray(), -20)) !!};
        var timestamps = {!! json_encode(array_slice($ChartResponses->pluck('created_at')
            ->map(fn($date) => \Carbon\Carbon::parse($date)->format('j/n/Y h:i:s A'))
            ->toArray(), -20)) !!};



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
                plugins: {
                    decimation: {
                        enabled: true,
                        algorithm: 'lttb', // 'lttb' (Largest Triangle Three Buckets) maintains trends
                        samples: 1// Displays only 50 points while preserving trends
                    }
                },
                maintainAspectRatio: false,
                layout: {
                    padding: { left: 10, right: 25, top: 25, bottom: 0 }
                },
                scales: {
                    xAxes: [{
                        time: { unit: 'date' },
                        gridLines: { display: false, drawBorder: false },
                        ticks: { maxTicksLimit: 7 }
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
                legend: { display: false },
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

        setInterval(function () {
            $.ajax({
                url: "{{ route('display.chart.update',[$details->id,$details->type]) }}", // Replace with your API endpoint
                type: "GET",
                dataType: "json",
                success: function (response) {
                    var maxDataPoints = 20;
                    var responseTimes = response.map(item => item.response_time).slice(-maxDataPoints);;
                    var timestamps = response.map(item => new Date(item.created_at).toLocaleString("en-IN", { timeZone: "Asia/Kolkata" })).slice(-maxDataPoints);;

                    myLineChart.data.datasets[0].data = responseTimes;
                    myLineChart.data.labels = timestamps;
                    myLineChart.update();
                },

                error: function (xhr, status, error) {
                    console.error("Error fetching data:", error);
                }
            });
        }, 5000); // Runs every 5000 milliseconds (5 seconds)

    });
</script>

@endsection