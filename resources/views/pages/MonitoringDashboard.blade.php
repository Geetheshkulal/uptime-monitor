@extends('dashboard')
@section('content')
<head>
    @push('styles')
    <!-- External CSS Libraries -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intro.js/7.2.0/introjs.min.css"/>

    <style>
        /* ========== GLOBAL STYLES ========== */
        :root {
            --primary: #4e73df;
            --primary-light: #e3f2fd;
            --success: #1cc88a;
            --danger: #e74a3b;
            --warning: #f6c23e;
            --info: #36b9cc;
            --gray: #858796;
            --light-gray: #f8f9fc;
            --dark-gray: #5a5c69;
        }

        body {
            font-family: 'Nunito', -apple-system, BlinkMacSystemFont, sans-serif;
            color: var(--dark-gray);
        }

        .card {
            border: none;
            border-radius: 0.35rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            transition: all 0.2s ease;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 0.5rem 1.5rem rgba(58, 59, 69, 0.2);
        }

        .card-header {
            background-color: #f8f9fc;
            border-bottom: 1px solid #e3e6f0;
            font-weight: 600;
            padding: 1rem 1.35rem;
        }

        /* ========== STATUS INDICATORS ========== */
        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.35rem 0.65rem;
            border-radius: 0.35rem;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: capitalize;
        }

        .badge-up {
            background-color: rgba(28, 200, 138, 0.1);
            color: var(--success);
        }

        .badge-down {
            background-color: rgba(231, 74, 59, 0.1);
            color: var(--danger);
        }

        .badge-paused {
            background-color: rgba(54, 185, 204, 0.1);
            color: var(--info);
        }

         .status-dot {
            width: 6px;
            height: 17px;
            margin: 1px;
            border-radius: 50rem;
            margin-right: 0.35rem;
        } 

        /* ========== BUTTONS ========== */
        .btn {
            border-radius: 0.35rem;
            font-weight: 600;
            padding: 0.375rem 0.75rem;
            font-size: 1rem;
            transition: all 0.2s;
        }

        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
        }

        .btn-primary:hover {
            background-color: #2e59d9;
            border-color: #2653d4;
        }

        .btn-view {
            background-color: rgba(28, 200, 138, 0.1);
            color: var(--success);
            border: none;
        }

        .btn-view:hover {
            background-color: rgba(28, 200, 138, 0.2);
        }

        /* ========== TABLE STYLES ========== */
        .table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            height: 100%;
        }


        .table thead th {
            border: none;
            font-weight: 700;
            color: var(--gray);
            padding: 1rem;
            background: #f8f9fc;
        }

        .table td {
            padding: 1rem;
            vertical-align: middle;
            border-top: 1px solid #e3e6f0;
        }

        /* ========== UTILITY CLASSES ========== */
        .text-primary {
            color: var(--primary) !important;
        }

        .font-600 {
            font-weight: 600;
        }

        .disabled-row {
            background-color: var(--light-gray);
            color: var(--gray);
        }

        .disabled-row a {
            color: var(--gray) !important;
            pointer-events: none;
        }

        /* ========== ANIMATIONS ========== */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .fade-in {
            animation: fadeIn 0.3s ease forwards;
        }

        .pulse {
            animation: heartbeat 1.5s infinite;
        }

        @keyframes heartbeat {
            0% { transform: scale(1); }
            14% { transform: scale(1.1); }
            28% { transform: scale(1); }
            42% { transform: scale(1.1); }
            70% { transform: scale(1); }
        }

        /* ========== INTROJS TOUR ========== */
        .introjs-tooltip {
            background-color: white;
            color: var(--dark-gray);
            font-family: 'Nunito', sans-serif;
            border-radius: 0.35rem;
            box-shadow: 0 0.5rem 1.5rem rgba(58, 59, 69, 0.2);
        }

        .introjs-tooltip-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--primary);
        }

        .introjs-button {
            background-color: var(--primary);
            border-radius: 0.25rem;
            font-weight: 600;
        }

        .introjs-button:hover {
            background-color: #2e59d9;
        }
    </style>
</head>

<!-- Main Content -->
<div id="content-wrapper" class="d-flex flex-column">
    <div id="content">
        <div class="container-fluid">
            <!-- Page Header -->
            <div data-aos="fade-up" class="d-flex align-items-center justify-content-between mb-4 fade-in">
                <h1 class="h3 mb-0 text-gray-800 font-600">Overview</h1>
                
                @if($totalMonitors>=5 && auth()->user()->status=='free')
                <a class="btn btn-primary AddMonitor" href="{{ route('premium.page') }}">
                    <i class="fas fa-crown mr-2"></i>Upgrade Plan
                </a>
                @else
                <a class="btn btn-primary AddMonitor" href="{{ route('add.monitoring') }}">
                    <i class="fas fa-plus mr-2"></i>Add Monitor
                </a>
                @endif
            </div>

            <!-- Status Cards -->
            <div data-aos="fade-up" class="row mb-4 fade-in">
                <!-- Total Monitors -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body first">
                            <div class="d-flex align-items-center">
                                <div class="mr-3">
                                    <div class="text-xs font-weight-bold text-gray-500 text-uppercase mb-1">Total Monitors</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800" id="totalMonitors">{{ $totalMonitors }}</div>
                                </div>
                                <div class="ml-auto">
                                    <i class="fas fa-list-alt fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Up Count -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body second">
                            <div class="d-flex align-items-center">
                                <div class="mr-3">
                                    <div class="text-xs font-weight-bold text-gray-500 text-uppercase mb-1">Up</div>
                                    <div class="h5 mb-0 font-weight-bold text-success" id="upCount">{{ $upCount }}</div>
                                </div>
                                <div class="ml-auto">
                                    <i class="fas fa-heart fa-2x pulse" style="color:#63E6BE"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Down Count -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body third">
                            <div class="d-flex align-items-center">
                                <div class="mr-3">
                                    <div class="text-xs font-weight-bold text-gray-500 text-uppercase mb-1">Down</div>
                                    <div class="h5 mb-0 font-weight-bold text-danger" id="downCount">{{ $downCount }}</div>
                                </div>
                                <div class="ml-auto">
                                    <i class="fas fa-times-circle fa-2x text-danger"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Paused Count -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body fourth">
                            <div class="d-flex align-items-center">
                                <div class="mr-3">
                                    <div class="text-xs font-weight-bold text-gray-500 text-uppercase mb-1">Paused</div>
                                    <div class="h5 mb-0 font-weight-bold text-info" id="pausedCount">{{ $pausedCount }}</div>
                                </div>
                                <div class="ml-auto">
                                    <i class="fas fa-pause-circle fa-2x text-info"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Monitors Section -->
            <div class="fade-in">
                <div data-aos="fade-up" class="d-flex align-items-center justify-content-between mb-3">
                    <h1 class="h3 mb-0 font-600">My Monitors</h1>
                </div>

                @if(auth()->user()->status === 'free' && $hasMoreMonitors)
                <div data-aos="fade-up" class="card bg-primary text-white mb-4">
                    <div class="card-body py-3">
                        <div class="d-flex align-items-center">
                            <div class="mr-3">
                                <i class="fas fa-crown fa-2x"></i>
                            </div>
                            <div>
                                <h4 class="mb-1">Upgrade to Premium</h4>
                                <p class="mb-0">Monitor all your services without limitations</p>
                            </div>
                            <div class="ml-auto">
                                <a href="{{ route('premium.page') }}" class="btn btn-light">
                                    Upgrade Now
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Monitors Table -->
                <div data-aos="fade-up" class="card mb-4 px-4">
                    <br>
                    <div class="card-body p-0">
                        <div class="table-responsive"style="min-width: 100%;">
                            <table class="table mb-0" id="dataTable">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Endpoint</th>
                                        <th>Type</th>
                                        <th>Status</th>
                                        <th>Created</th>
                                        <th>History</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($monitors as $key=>$monitor)                           
                                    <tr class="{{ (auth()->user()->status === 'free' && $loop->index >= 5) ? 'disabled-row' : '' }}">
                                        <td class="font-600">{{ $monitor->name }}</td>
                                        <td>
                                            <a href="{{ $monitor->url }}" target="_blank" class="text-primary">
                                                {{ Str::limit($monitor->url, 30) }}
                                            </a>
                                        </td>
                                        <td>{{ ucfirst($monitor->type) }}{{ $monitor->type === 'port' ? ':'.$monitor->port : '' }}</td>
                                        <td>
                                            @if ($monitor->paused == 1)
                                            <span class="status-badge badge-paused">
                                                <span style="background: var(--info);"></span>
                                                Paused
                                            </span>
                                            @elseif ($monitor->status === 'up')
                                            <span class="status-badge badge-up">
                                                <span class="status-dot" style="background: var(--success);"></span>
                                                Up
                                            </span>
                                            @else
                                            <span class="status-badge badge-down">
                                                <span class="status-dot" style="background: var(--danger);"></span>
                                                Down
                                            </span>
                                            @endif
                                        </td>
                                        <td>{{ $monitor->created_at->format('M d, Y') }}</td>
                                        <td>                    
                                            @if ($monitor->latestResponses->isNotEmpty())  
                                                @foreach ($monitor->latestResponses as $response)
                                                <span class="status-dot d-inline-block mr-1" 
                                                      style="background: {{ $response->status === 'up' ? 'var(--success)' : 'var(--danger)' }};"></span>
                                                @endforeach
                                            @else
                                                <span class="status-dot d-inline-block" style="background: var(--danger);"></span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('display.monitoring', ['id'=>$monitor->id, 'type'=>$monitor->type]) }}" 
                                               class="btn btn-sm btn-view">
                                                <i class="fas fa-eye mr-1"></i>View
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <br>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<!-- Required Scripts -->
<script src="{{ asset('frontend/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('frontend/assets/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
<script src="{{ asset('frontend/assets/vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('frontend/assets/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('frontend/assets/js/sb-admin-2.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/intro.js/7.2.0/intro.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>

<script>
    // Initialize animations
    AOS.init({
        duration: 400,
        easing: 'ease-out',
        once: true
    });

    // Initialize DataTable
    $(document).ready(function() {
        $('#dataTable').DataTable({
            "paging": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "order": [[4, "desc"]],
            "language": {
                "search": "_INPUT_",
                "searchPlaceholder": "Search monitors...",
                "lengthMenu": "Show _MENU_",
                "info": "Showing _START_ to _END_ of _TOTAL_"
            }
        });

        // Update status counts
        function updateStatusCounts() {
            $.ajax({
                url: '{{ route('monitoring.dashboard.update') }}',
                method: 'GET',
                success: function(response) {
                    $('#upCount').text(response.upCount);
                    $('#downCount').text(response.downCount);
                    $('#totalMonitors').text(response.totalMonitors);
                    $('#pausedCount').text(response.pausedCount);
                }
            });
        }

        // Update every 30 seconds
        setInterval(updateStatusCounts, 30000);
    });

    // Initialize tour(tool tip)
        introJs().setOptions({
            steps:[{
            title:'Check My Site',
            intro:'Welcome to check my site! Lets take a quick tour'
        },
        {
         element:document.querySelector('.profile'),
         intro:'Access your profile settings and account information here.'
       },
        {
         element:document.querySelector('.AddMonitor'),
         intro:'click here to add new monitor'
       },
       {
         element:document.querySelector('.incident'),
         intro:'View and manage incident reports related to your monitored services.'
       },
       {
         element:document.querySelector('.plan'),
         intro:'Explore and manage your current subscription plan or upgrade to premium.'
       },
       {
         element:document.querySelector('.ssl'),
         intro:'Check the SSL certificate expiry status of your domains here.'
       },
       {
         element:document.querySelector('.first'),
         intro:'This shows the total number of monitors you have configured.'
       },
       {
         element:document.querySelector('.second'),
         intro:'Displays the number of services that are currently operational.'
       },
       {
         element:document.querySelector('.third'),
         intro:'Displays the number of services that are currently down.'
       },
       {
         element:document.querySelector('.fourth'),
         intro:'Shows the number of monitors that are currently paused.'
       }
      ],
            dontShowAgain: true,
            nextLabel: 'Next',
            prevLabel: 'Back',
            doneLabel: 'Finish'
        }).start();

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