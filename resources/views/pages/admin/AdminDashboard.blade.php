@extends('dashboard')
@section('content')

@push('styles')
    <style>
        .card-counter {
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        .card-counter:hover {
            transform: translateY(-5px);
            box-shadow: 2px 5px 15px rgba(0, 0, 0, 0.2);
        }
        .card-counter i {
            font-size: 2.5rem;
            opacity: 0.3;
        }
         * {
    border-radius: 0 !important;
}
    </style>
@endpush

<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Admin Dashboard</h1>
    </div>

    <!-- Cards Row -->
    <div class="row">
        <!-- Total Users Card -->
        <div class="col-xl-2 col-md-6 mb-4">
            <div class="card card-counter border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Users</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $total_user_count }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Paid Users Card -->
        <div class="col-xl-2 col-md-6 mb-4">
            <div class="card card-counter border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Paid Users</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $paid_user_count }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-check text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Monitors Card -->
        <div class="col-xl-2 col-md-6 mb-4">
            <div class="card card-counter border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Total Monitors</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $monitor_count }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-server text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Revenue Card -->
        <div class="col-xl-2 col-md-6 mb-4">
            <div class="card card-counter border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Total Revenue</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{$total_revenue}}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Users Card -->
        <div class="col-xl-2 col-md-6 mb-4">
            <div class="card card-counter border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Active Users (30d)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $active_users }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-clock text-danger"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Server Health Card -->
        <div class="col-xl-2 col-md-6 mb-4">
            <div class="card card-counter border-left-secondary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                                Server Health</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800" id="cpuUsage">Loading...</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-heartbeat text-secondary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphs Row -->
    <div class="row">
        <!-- User Growth Chart -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">User Growth (Last 12 Months)</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="userGrowthChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Revenue Growth Chart -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Revenue Growth (Last 12 Months)</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="revenueGrowthChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // User Growth Chart
            const userCtx = document.getElementById('userGrowthChart').getContext('2d');
            const month_labels = @json($month_labels);
            const user_data = @json($user_data);
            const userGrowthChart = new Chart(userCtx, {
                type: 'line',
                data: {
                    labels: month_labels, // e.g., ['Apr', 'May', ..., 'Mar']
                    datasets: [{
                        label: 'New Users',
                        data: user_data,    // e.g., [12, 23, 45, ..., 89]
                        backgroundColor: 'rgba(78, 115, 223, 0.05)',
                        borderColor: 'rgba(78, 115, 223, 1)',
                        pointBackgroundColor: 'rgba(78, 115, 223, 1)',
                        pointBorderColor: '#fff',
                        pointHoverBackgroundColor: '#fff',
                        pointHoverBorderColor: 'rgba(78, 115, 223, 1)',
                        tension: 0.3,
                        fill: true
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return value.toLocaleString();
                                }
                            }
                        }
                    }
                }
            });
        

            // Revenue Growth Chart
            const revenueCtx = document.getElementById('revenueGrowthChart').getContext('2d');
            const revenue_by_month = @json($monthly_revenue);
            const revenueGrowthChart = new Chart(revenueCtx, {
                type: 'bar',
                data: {
                    labels: month_labels,
                    datasets: [{
                        label: 'Revenue ($)',
                        data: revenue_by_month,
                        backgroundColor: 'rgba(54, 185, 204, 0.5)',
                        borderColor: 'rgba(54, 185, 204, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return '$' + value.toLocaleString();
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        function updateCpuUsage() {
            fetch("{{ route('admin.fetch-cpu-usage') }}")
                .then(response => response.json())
                .then(data => {
                    document.getElementById('cpuUsage').textContent = data.cpuPercent + '%';
                })
                .catch(error => console.error('Error fetching CPU usage:', error));
        }

        // Update CPU usage every 1 second
        setInterval(updateCpuUsage, 1000);

        // Initial fetch
        updateCpuUsage();
    });
</script>
@endpush

@endsection