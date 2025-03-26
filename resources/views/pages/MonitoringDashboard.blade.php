@extends('dashboard')
@section('content')

<head>
    @push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    @endpush
</head>

<!-- Content Wrapper -->
<div id="content-wrapper" class="d-flex flex-column">
    @push('styles')
    <style>
        :root {
            --primary: #4e73df;
            --success: #1cc88a;
            --danger: #e74a3b;
            --warning: #f6c23e;
            --info: #36b9cc;
            --dark: #5a5c69;
            --light: #f8f9fc;
            --gradient-start: #4e73df;
            --gradient-end: #224abe;
        }
        
        body {
            font-family: 'Nunito', sans-serif;
            background-color: #f5f7fb;
        }
        
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        
        .card-header {
            background-color: transparent;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            padding: 1.25rem 1.5rem;
        }
        
        .card-body {
            padding: 1.5rem;
        }
        
        .tablerow:hover {
            background-color: #f8fafc;
            transform: scale(1.005);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);
        }
        
        @keyframes heartbeat {
            0% { transform: scale(1); }
            25% { transform: scale(1.1); }
            50% { transform: scale(1); }
            75% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }
        
        .pulse {
            animation: heartbeat 1.5s infinite;
        }
        
        .dataTables_wrapper {
            overflow-x: auto !important;
        }
        
        #dataTable {
            width: 100% !important;
            border-collapse: separate;
            border-spacing: 0 8px;
        }
        
        #dataTable thead th {
            background-color: #f8fafc;
            color: #4a5568;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            border: none;
            padding: 12px 15px;
        }
        
        #dataTable tbody tr {
            background-color: white;
            border-radius: 8px;
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.03);
        }
        
        #dataTable tbody td {
            padding: 15px;
            vertical-align: middle;
            border-top: none;
            border-bottom: 1px solid #f1f5f9;
        }
        
        #dataTable tbody tr:first-child td {
            border-top: 1px solid #f1f5f9;
        }
        
        .badge {
            padding: 6px 10px;
            font-weight: 600;
            letter-spacing: 0.5px;
            font-size: 0.75rem;
            border-radius: 50px;
        }
        
        .badge-success {
            background-color: rgba(28, 200, 138, 0.1);
            color: var(--success);
        }
        
        .badge-danger {
            background-color: rgba(231, 74, 59, 0.1);
            color: var(--danger);
        }
        
        .floating-btn {
            z-index: 1000;
            padding: 12px 24px;
            font-size: 16px;
            font-weight: 600;
            border-radius: 50px;
            box-shadow: 0 4px 15px rgba(78, 115, 223, 0.3);
            transition: all 0.3s ease;
            background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end));
            border: none;
            color: white;
            display: inline-flex;
            align-items: center;
        }
        
        .floating-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(78, 115, 223, 0.4);
            color: white;
        }
        
        .floating-btn i {
            margin-right: 8px;
        }
        
        h1, h2, h3, h4, h5, h6 {
            font-weight: 700;
            color: #2d3748;
        }
        
        .text-primary {
            color: var(--primary) !important;
        }
        
        .text-success {
            color: var(--success) !important;
        }
        
        .text-danger {
            color: var(--danger) !important;
        }
        
        .text-warning {
            color: var(--warning) !important;
        }
        
        .text-info {
            color: var(--info) !important;
        }
        
        .border-left-primary {
            border-left: 4px solid var(--primary) !important;
        }
        
        .border-left-success {
            border-left: 4px solid var(--success) !important;
        }
        
        .border-left-danger {
            border-left: 4px solid var(--danger) !important;
        }
        
        .border-left-warning {
            border-left: 4px solid var(--warning) !important;
        }
        
        .border-left-info {
            border-left: 4px solid var(--info) !important;
        }
        
        .btn-success {
            background-color: var(--success);
            border-color: var(--success);
            border-radius: 50px;
            padding: 8px 16px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-success:hover {
            background-color: #17a673;
            border-color: #17a673;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(28, 200, 138, 0.3);
        }
        
        /* RGB Gradient Border Animation */
        @keyframes rgbGradientBorder {
            0% { border-color: #4e73df; }
            25% { border-color: #1cc88a; }
            50% { border-color: #e74a3b; }
            75% { border-color: #f6c23e; }
            100% { border-color: #4e73df; }
        }
        
        .border-rgb {
            border-left: 4px solid;
            animation: rgbGradientBorder 8s infinite linear;
        }
        
        /* Status bars styling */
        .status-bars {
            display: flex;
            align-items: center;
            height: 20px;
        }
        
        .status-bar {
            width: 6px;
            height: 20px;
            margin-right: 3px;
            border-radius: 3px;
            transition: all 0.3s ease;
        }
        
        .status-bar-up {
            background-color: var(--success);
        }
        
        .status-bar-down {
            background-color: var(--danger);
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .card-body {
                padding: 1rem;
            }
            
            #dataTable thead {
                display: none;
            }
            
            #dataTable tbody tr {
                display: block;
                margin-bottom: 15px;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            }
            
            #dataTable tbody td {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 10px 15px;
                border-bottom: 1px solid #f1f5f9;
            }
            
            #dataTable tbody td::before {
                content: attr(data-label);
                font-weight: 600;
                color: #4a5568;
                margin-right: 15px;
            }
        }
    </style>
    @endpush

    <!-- Main Content -->
    <div id="content">
        <!-- Begin Page Content -->
        <div class="container-fluid">
            <!-- Page Heading -->
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">Dashboard Overview</h1>
            </div>

            <!-- Content Row -->
            <div class="row">
                <!-- Total Records Card -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Total Monitors</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800" id="totalMonitors">{{ $totalMonitors }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-list fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Up Count Card -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-rgb shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                        Up</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800" id="upCount">{{ $upCount }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-heart fa-2x pulse" style="color:#1cc88a"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Down Count Card -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-danger shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                        Down</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800" id="downCount">{{ $downCount }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-dizzy fa-2x" style="color:#e74a3b;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Paused Count Card -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-warning shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                        Paused</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">0</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-pause fa-2x" style="color:#f6c23e"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Monitors Section -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">My Monitors</h6>
                    <a href="{{route('add.monitoring')}}" class="btn btn-primary floating-btn">
                        <i class="fas fa-plus-circle"></i> Add New Monitor
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="dataTable" width="100%" cellspacing="0">
                            <thead class="thead-light">
                                <tr>
                                    <th>Name</th>
                                    <th>URL</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Created Date</th>
                                    <th>Last Calls Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($monitors as $monitor)                           
                                <tr class="tablerow">
                                    <td class="font-weight-bold text-dark">{{ $monitor->name }}</td>
                                    <td>
                                        <a href="{{ $monitor->url }}" target="_blank" class="text-primary">
                                            {{ Str::limit($monitor->url, 30) }}
                                        </a>
                                    </td>
                                    <td>
                                        <span class="badge badge-light">
                                            {{ $monitor->type }}{{ $monitor->type === 'port' ? '-' . $monitor->port : '' }}
                                        </span>
                                    </td>
                                    <td>
                                        @if ($monitor)
                                            @if ($monitor->status === 'up')
                                                <span class="badge badge-success">
                                                    <i class="fas fa-check-circle mr-1"></i> Up
                                                </span>
                                            @else
                                                <span class="badge badge-danger">
                                                    <i class="fas fa-times-circle mr-1"></i> Down
                                                </span>
                                            @endif
                                        @else
                                            <span class="badge badge-danger">Down</span>
                                        @endif
                                    </td>
                                    <td>{{ $monitor->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <div class="status-bars">
                                            @if ($monitor->latestResponses->isNotEmpty())  
                                                @foreach ($monitor->latestResponses as $response)
                                                    <div class="status-bar {{ $response->status === 'up' ? 'status-bar-up' : 'status-bar-down' }}"></div>
                                                @endforeach
                                            @else
                                                <div class="status-bar status-bar-down"></div>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <a href="{{ route('display.monitoring', ['id'=>$monitor->id, 'type'=>$monitor->type]) }}" class="btn btn-success btn-sm">
                                            <i class="fas fa-eye mr-1"></i> View
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- End of Main Content -->
    </div>
    <!-- End of Content Wrapper -->
</div>

<!-- Scripts -->
@push('scripts')
<script src="{{ asset('frontend/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('frontend/assets/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
<script src="{{ asset('frontend/assets/vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('frontend/assets/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('frontend/assets/js/sb-admin-2.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
    var total = document.getElementById('totalMonitors');
    var upCount = document.getElementById('upCount');
    var downCount = document.getElementById('downCount');
    
    $(document).ready(function() {
        var table = $('#dataTable').DataTable({
            processing: false,
            serverSide: true,
            ajax: {
                url: '{{ route('monitoring.dashboard.update') }}',
                method: 'GET',
                dataSrc: 'data'
            },
            columns: [
                { 
                    data: 'name', 
                    name: 'name',
                    render: function(data, type, row) {
                        return '<span class="font-weight-bold text-dark">' + data + '</span>';
                    }
                },
                { 
                    data: 'url', 
                    name: 'url',
                    render: function(data) {
                        return '<a href="' + data + '" target="_blank" class="text-primary">' + 
                               (data.length > 30 ? data.substring(0, 30) + '...' : data) + 
                               '</a>';
                    }
                },
                { 
                    data: 'type', 
                    name: 'type', 
                    render: function(data, type, row) {
                        return '<span class="badge badge-light">' + 
                               data + (row.port ? '-' + row.port : '') + 
                               '</span>';
                    }
                },
                { 
                    data: 'status', 
                    name: 'status', 
                    render: function(data) {
                        if (data === 'up') {
                            return '<span class="badge badge-success">' +
                                   '<i class="fas fa-check-circle mr-1"></i> Up</span>';
                        } else {
                            return '<span class="badge badge-danger">' +
                                   '<i class="fas fa-times-circle mr-1"></i> Down</span>';
                        }
                    }
                },
                { 
                    data: 'created_at', 
                    name: 'created_at', 
                    render: function(data) {
                        var date = new Date(data);
                        return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
                    }
                },
                { 
                    data: 'latestResponses', 
                    name: 'latestResponses', 
                    render: function(data) {
                        var bars = '';
                        if (data && data.length > 0) {
                            data.forEach(function(response) {
                                bars += '<div class="status-bar ' + 
                                       (response.status === 'up' ? 'status-bar-up' : 'status-bar-down') + 
                                       '"></div>';
                            });
                        } else {
                            bars = '<div class="status-bar status-bar-down"></div>';
                        }
                        return '<div class="status-bars">' + bars + '</div>';
                    }
                },
                { 
                    data: null, 
                    render: function(data, type, row) {
                        return '<a href="/monitoring/display/' + row.id + '/' + row.type + 
                               '" class="btn btn-success btn-sm">' +
                               '<i class="fas fa-eye mr-1"></i> View</a>';
                    }
                }
            ],
            columnDefs: [
                { targets: [5, 6], searchable: false, orderable: false }
            ],
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search monitors...",
                lengthMenu: "Show _MENU_ monitors per page",
                zeroRecords: "No monitors found",
                info: "Showing _START_ to _END_ of _TOTAL_ monitors",
                infoEmpty: "No monitors available",
                infoFiltered: "(filtered from _MAX_ total monitors)"
            },
            responsive: true
        });

        // Periodically reload the table data
        setInterval(function() {
            table.ajax.reload(null, false);
            $.ajax({
                url: '{{ route('monitoring.dashboard.update') }}',
                method: 'GET',
                success: function(response) {
                    upCount.innerText = response.upCount;
                    downCount.innerText = response.downCount;
                    total.innerText = response.totalMonitors;
                }
            });
        }, 30000);
    });
    
    // Toast notifications
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