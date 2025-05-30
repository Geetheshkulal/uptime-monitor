@extends('dashboard')
@section('content')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
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
button.btn-light {
    box-shadow: none !important; /* Remove shadow */
    border: none !important; /* Remove border */
    background-color: transparent !important; /* Make background transparent */
    color:blue !important; /* Change text color */
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
                            <div class="h5 mb-0 font-weight-bold text-gray-800">₹ {{$total_revenue}}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-rupee-sign text-warning"></i>
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
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{$cpuPercent}}%</div>
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
    <h6 class="m-0 font-weight-bold text-primary">User Growth</h6>
   <div class="dropdown">
    <button class="btn btn-sm btn-light" type="button" id="userGrowthMenu" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="fas fa-ellipsis-v"></i>
    </button>
    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userGrowthMenu">
        <li><a class="dropdown-item" href="#" data-value="12">Last 12 Months</a></li>
        <li><a class="dropdown-item" href="#" data-value="6">Last 6 Months</a></li>
        <li><a class="dropdown-item" href="#" data-value="3">Last 3 Months</a></li>
    </ul>
</div>
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
    <h6 class="m-0 font-weight-bold text-primary">Revenue Growth</h6>
   <div class="dropdown">
    <button class="btn btn-sm btn-light " type="button" id="revenueGrowthMenu" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="fas fa-ellipsis-v"></i>
    </button>
    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="revenueGrowthMenu">
        <li><a class="dropdown-item" href="#" data-value="12">Last 12 Months</a></li>
        <li><a class="dropdown-item" href="#" data-value="6">Last 6 Months</a></li>
        <li><a class="dropdown-item" href="#" data-value="3">Last 3 Months</a></li>
    </ul>
</div>
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
    document.addEventListener("DOMContentLoaded", function () {
    const userCtx = document.getElementById('userGrowthChart').getContext('2d');
    const month_labels = @json($month_labels); // Original labels
    const user_data = @json($user_data);       // Original data

    let filteredLabels = [...month_labels]; // Clone original labels
    let filteredData = [...user_data];      // Clone original data

    const userGrowthChart = new Chart(userCtx, {
        type: 'line',
        data: {
            labels: filteredLabels,
            datasets: [{
                label: 'New Users',
                data: filteredData,
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
                        stepSize: 1,
                        callback: function (value) {
                            return value.toLocaleString();
                        }
                    }
                }
            }
        }
    });

    // Filter Logic for User Growth Chart
    const userGrowthMenuItems = document.querySelectorAll('#userGrowthMenu + .dropdown-menu .dropdown-item');
    userGrowthMenuItems.forEach(item => {
        item.addEventListener('click', function (e) {
            e.preventDefault();
            const selectedValue = parseInt(this.getAttribute('data-value')); // Get selected timeline (e.g., 12, 6, 3)

            // Filter data based on the selected timeline
            const startIndex = Math.max(filteredLabels.length - selectedValue, 0);
            const newLabels = month_labels.slice(startIndex);
            const newData = user_data.slice(startIndex);

            // Update the chart
            userGrowthChart.data.labels = newLabels;
            userGrowthChart.data.datasets[0].data = newData;
            userGrowthChart.update();
        });
    });
});
document.addEventListener("DOMContentLoaded", function () {
    const revenueCtx = document.getElementById('revenueGrowthChart').getContext('2d');
    const month_labels = @json($month_labels); // Original labels
    const revenue_data = @json($monthly_revenue); // Original revenue data

    let filteredLabels = [...month_labels]; // Clone original labels
    let filteredData = [...revenue_data];   // Clone original data

    const revenueGrowthChart = new Chart(revenueCtx, {
        type: 'bar',
        data: {
            labels: filteredLabels,
            datasets: [{
                label: 'Revenue (₹)',
                data: filteredData,
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
                        stepSize: 100,
                        callback: function (value) {
                            return '₹' + value.toLocaleString();
                        }
                    }
                }
            }
        }
    });

    // Filter Logic for Revenue Growth Chart
    const revenueGrowthMenuItems = document.querySelectorAll('#revenueGrowthMenu + .dropdown-menu .dropdown-item');
    revenueGrowthMenuItems.forEach(item => {
        item.addEventListener('click', function (e) {
            e.preventDefault();
            const selectedValue = parseInt(this.getAttribute('data-value')); // Get selected timeline (e.g., 12, 6, 3)

            // Filter data based on the selected timeline
            const startIndex = Math.max(filteredLabels.length - selectedValue, 0);
            const newLabels = month_labels.slice(startIndex);
            const newData = revenue_data.slice(startIndex);

            // Update the chart
            revenueGrowthChart.data.labels = newLabels;
            revenueGrowthChart.data.datasets[0].data = newData;
            revenueGrowthChart.update();
        });
    });
});
// Show success message if exists
    document.addEventListener("DOMContentLoaded", function() {
        @if(session('success'))
            toastr.success("{{ session('success') }}", "Success", {
                closeButton: true,
                progressBar: true,
                positionClass: "toast-top-right",
                timeOut: 5000
            });
        @endif
    });
    </script>
@endpush

@endsection