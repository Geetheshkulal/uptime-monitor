@extends('dashboard')
@section('content')
<head>
    @push('styles')
    <!-- Include external CSS libraries -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        /* CSS variables for easy color management */
        @media (max-width: 768px) {
        .table-responsive {
            overflow-x: auto;
        }

        #dataTable {
            width: auto; /* Allow table to expand beyond viewport */
            min-width: 768px; /* Or a reasonable minimum width to prevent extreme squishing */
        }

        /* Adjust font sizes for better readability on smaller screens */
        #dataTable th,
        #dataTable td {
            font-size: 0.8rem;
            padding: 0.75rem 0.5rem; /* Reduce padding slightly */
            white-space: nowrap; /* Prevent text wrapping in columns */
        }

        .stat-card .stat-value {
            font-size: 1.5rem; /* Adjust stat card value font size */
        }

        .btn-gradient,
        .btn-premium,
        .btn-sm {
            padding: 0.6rem 1rem;
            font-size: 0.85rem;
        }
    }

    /* Further adjustments for even smaller screens */
    @media (max-width: 576px) {
        /* Hide less critical columns on smaller screens */
        #dataTable th:nth-child(5), /* Created Date */
        #dataTable td:nth-child(5),
        #dataTable th:nth-child(6), /* Last Calls Status */
        #dataTable td:nth-child(6) {
            display: none;
        }

        .stat-card .stat-value {
            font-size: 1.2rem;
        }

        .btn-gradient,
        .btn-premium,
        .btn-sm {
            padding: 0.5rem 0.8rem;
            font-size: 0.8rem;
        }
    }

    /* Adjustments for very small screens (e.g., narrow phones) */
    @media (max-width: 400px) {
        .h3 {
            font-size: 1.5rem; /* Reduce header size */
        }

        .stat-card .stat-label {
            font-size: 0.75rem;
        }

        .stat-card .stat-icon {
            font-size: 1.5rem;
        }
    }
        :root {
            --primary: #4361ee;
            --primary-dark: #3a0ca3;
            --secondary: #3f37c9;
            --success: #4cc9f0;
            --danger: #f72585;
            --warning: #f8961e;
            --info: #4895ef;
            --light: #f8f9fa;
            --dark: #212529;
            --purple: #7209b7;
            --cyan: #00b4d8;
        }

        /* Base styling */
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f7fa;
        }

        /* Card styling */
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            overflow: hidden;
        }

        /* Card hover effect */
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 20px rgba(0, 0, 0, 0.1);
        }

        /* Card header styling */
        .card-header {
            background: white;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            font-weight: 600;
            padding: 1.25rem 1.5rem;
        }

        /* Gradient button styling */
        .btn-gradient {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border: none;
            border-radius: 8px;
            color: white;
            font-weight: 500;
            padding: 10px 24px;
            text-transform: none;
            letter-spacing: 0.5px;
            box-shadow: 0 4px 6px rgba(67, 97, 238, 0.3);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        /* Button hover effect */
        .btn-gradient:hover {
            background: linear-gradient(135deg, var(--primary-dark), var(--secondary));
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(67, 97, 238, 0.3);
            color: white;
        }

        /* Button active state */
        .btn-gradient:active {
            transform: translateY(0);
        }

        /* Premium button styling with shine effect */
        .btn-premium {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(245, 158, 11, 0.3);
        }

        /* Premium button hover */
        .btn-premium:hover {
            background: linear-gradient(135deg, #e67e22, #d35400);
        }

        /* Shine animation for premium button */
        .btn-premium::before {
            content: "";
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(
                to bottom right,
                rgba(255, 255, 255, 0) 45%,
                rgba(255, 255, 255, 0.3) 50%,
                rgba(255, 255, 255, 0) 55%
            );
            transform: rotate(30deg);
            animation: shine 3s infinite;
        }

        /* Status badge styling */
        .badge {
            padding: 6px 12px;
            font-weight: 500;
            border-radius: 20px;
            font-size: 0.75rem;
        }

        /* Success status badge */
        .badge-success {
            background-color: rgba(76, 201, 240, 0.1);
            color: #4cc9f0;
        }

        /* Danger status badge */
        .badge-danger {
            background-color: rgba(247, 37, 133, 0.1);
            color: #f72585;
        }

        /* Paused status badge */
        .badge-paused {
            background-color: rgba(114, 9, 183, 0.1);
            color: #7209b7;
        }

        /* Table styling */
        #dataTable {
            border-collapse: separate;
            border-spacing: 0 8px;
        }

        /* Table header styling */
        #dataTable thead th {
            border: none;
            background-color: #f8fafc;
            color: #64748b;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
        }

        /* Table row styling */
        #dataTable tbody tr {
            background-color: white;
            transition: all 0.3s ease;
            border-radius: 8px;
        }

        /* Table row hover effect */
        #dataTable tbody tr:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            transform: translateY(-2px);
        }

        /* Status indicator dots */
        .status-indicator {
            display: inline-block;
            width: 6px;
            height: 17px;
            margin: 1px;
            border-radius: 50rem;
        }

        /* Up status indicator */
        .status-up {
            background-color: #4cc9f0;
        }

        /* Down status indicator */
        .status-down {
            background-color: #f72585;
        }

        /* Pulse animation for up status */
        .pulse {
            animation: heartbeat 1.5s infinite;
            transform-origin: center;
        }

        /* Premium alert styling */
        .premium-alert {
            background: linear-gradient(135deg, #4361ee, #3a0ca3);
            color: white;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(67, 97, 238, 0.3);
            overflow: hidden;
            position: relative;
        }

        /* Stats card styling */
        .stat-card {
            transition: all 0.3s ease;
        }

        /* Stats card hover effect */
        .stat-card:hover {
            transform: translateY(-5px);
        }

        /* Stats value styling */
        .stat-card .stat-value {
            font-size: 1.75rem;
            font-weight: 600;
            color: var(--dark);
        }

        /* Stats label styling */
        .stat-card .stat-label {
            font-size: 0.875rem;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Stats icon styling */
        .stat-card .stat-icon {
            font-size: 2rem;
            opacity: 0.8;
        }

        /* Responsive adjustments for smaller screens */
        @media (max-width: 768px) {
            .stat-card .stat-value {
                font-size: 1.5rem;
            }
            
            .btn-gradient, .btn-premium {
                padding: 8px 16px;
                font-size: 0.875rem;
            }
        }
    </style>
    @endpush

    <!-- Animation library -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">

    <!-- Intro.js tour library -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intro.js/7.2.0/introjs.min.css"/>
</head>

<!-- Main content wrapper -->
<div id="content-wrapper" class="d-flex flex-column">
    <div id="content">
        <div class="container-fluid">
            <!-- Header Section -->
            <div data-aos="fade-up" class="d-flex align-items-center justify-content-between mb-4">
                <div>
                    <h1 class="h3 mb-1 text-gray-800">Overview</h1>
                </div>
                
                <!-- Add Monitor button - changes based on user status -->
                @if($totalMonitors>=5 && auth()->user()->status=='free')
                <a class="bg-primary btn btn-secondary position-relative border-none AddMonitor" 
                    href="{{ route('premium.page') }}"
                    title="Premium feature - Upgrade to unlock">
                    <i class="fas fa-crown fa-sm me-1" style="color: gold; animation: glow 1.5s infinite alternate;"></i>
                    Add New Monitor
                </a>
                @else
                <a class="bg-primary btn btn-secondary position-relative AddMonitor" 
                    href="{{ route('add.monitoring') }}"
                    title="Free Monitors">
                    Add New Monitor
                </a>
                @endif
            </div>

            <!-- Stats Cards Row -->
            <div data-aos="fade-up" class="row">
                <!-- Total Records Card -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card stat-card h-100">
                        <div class="card-body first">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="stat-label">Total Records</div>
                                    <div class="stat-value" id="totalMonitors">{{ $totalMonitors }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-list stat-icon text-primary"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Up Count Card -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card stat-card h-100">
                        <div class="card-body second">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="stat-label">Up</div>
                                    <div class="stat-value" id="upCount">{{ $upCount }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-heart stat-icon pulse" style="color:#4cc9f0"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Down Count Card -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card stat-card h-100">
                        <div class="card-body third">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="stat-label">Down</div>
                                    <div class="stat-value" id="downCount">{{ $downCount }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-dizzy stat-icon" style="color:#f72585"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Paused Count Card -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card stat-card h-100">
                        <div class="card-body fourth">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="stat-label">Paused</div>
                                    <div class="stat-value" id="pausedCount">{{ $pausedCount }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-pause stat-icon" style="color:#7209b7"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- My Monitors Section -->
            <div data-aos="fade-up" class="d-flex align-items-center justify-content-between mb-4">
                <div>
                    <h1 class="h3 mb-1 text-gray-800">My Monitors</h1>
                </div>
            </div>

            <!-- Premium upgrade alert for free users with many monitors -->
            @if(auth()->user()->status === 'free' && $hasMoreMonitors)
            <div data-aos="fade-up" class="premium-alert text-center mb-4 p-4">
                <h2 class="mb-3" style="font-weight: 700; text-shadow: 1px 1px 2px rgba(0,0,0,0.1);">
                    Unlock All Your Monitors!
                </h2>
                <p class="mb-4" style="font-size: 16px; font-weight: 400;">
                    Upgrade to the premium plan to monitor all your services with advanced features.
                </p>
                <a href="{{ route('premium.page') }}" class="btn btn-light btn-lg px-4 py-2" style="
                    font-weight: 600;
                    border-radius: 30px;
                    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                ">
                    <i class="fas fa-crown fa-sm me-1"></i>
                    Upgrade Now
                </a>
            </div>
            @endif

            <!-- Data Table -->
            <div data-aos="fade-up" class="card shadow mb-4">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table" id="dataTable" width="100%" cellspacing="0">
                            <thead>
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
                                <!-- Loop through monitors and display each one -->
                                @foreach ($monitors as $key=>$monitor)                           
                                <tr style="{{ (auth()->user()->status === 'free' && $loop->index >= 5) ? 'opacity: 0.7;' : '' }}">
                                    <td>
                                        {{ $monitor->name }}
                                    </td>
                                    <td>{{ $monitor->url }}</td>
                                    <td>
                                        <span class="badge badge-light">
                                            {{ $monitor->type }}{{ $monitor->type === 'port' ? '-' . $monitor->port : '' }}
                                        </span>
                                    </td>
                                    <td>
                                        <!-- Display status badge based on monitor state -->
                                        @if ($monitor->paused == 1)
                                        <span class="badge" style="background-color:purple; color: white;">Paused</span>
                                        @elseif ($monitor->status === 'up')
                                        <span class="badge badge-success">Up</span>
                                        @else
                                        <span class="badge badge-danger">Down</span>
                                        @endif
                                    </td>
                                    <td>{{ $monitor->created_at->format('M d, Y') }}</td>
                                    <td>                    
                                        <!-- Show status indicators for recent checks -->
                                        @if ($monitor->latestResponses->isNotEmpty())  
                                            @foreach ($monitor->latestResponses as $response)
                                                <div class="status-indicator {{ $response->status === 'up' ? 'status-up' : 'status-down' }}"></div>
                                            @endforeach
                                        @else
                                            <div class="status-indicator status-down"></div>
                                        @endif
                                    </td>
                                    <td>
                                        <!-- View button for monitor details -->
                                        <a href="{{ route('display.monitoring', ['id'=>$monitor->id, 'type'=>$monitor->type]) }}" 
                                           class="btn btn-sm btn-light view">
                                            <i class="fas fa-eye fa-sm mr-1"></i> View
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
    </div>
</div>

<!-- Initialize animation library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
<script>
    AOS.init({
        duration: 800,         // Animation duration
        easing: 'ease-in-out', // Smooth easing
        once: true,           // Only animate once
        offset: 50            // Offset from top before triggering
    });
</script>

@push('scripts')
<!-- Include required JavaScript libraries -->
<script src="{{ asset('frontend/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('frontend/assets/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
<script src="{{ asset('frontend/assets/vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('frontend/assets/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('frontend/assets/js/sb-admin-2.min.js') }}"></script>

<script>
    $(document).ready(function() {
        // Initialize DataTable with custom settings
        var table = $('#dataTable').DataTable({
            "paging": true,      // Enable pagination
            "searching": true,    // Enable search
            "ordering": true,     // Enable sorting
            "info": true,         // Show info text
            "order": [[4, "desc"]], // Default sort by Created Date (newest first)
            "columnDefs": [
                { "orderable": false, "targets": [5, 6] }, // Disable sorting for status bars and actions
                { "className": "text-center", "targets": [3, 5] } // Center align status columns
            ],
            "language": {         // Customize text
                "search": "_INPUT_",
                "searchPlaceholder": "Search monitors...",
                "lengthMenu": "Show _MENU_ entries",
                "info": "Showing _START_ to _END_ of _TOTAL_ entries",
                "paginate": {
                    "previous": "<i class='fas fa-chevron-left'></i>",
                    "next": "<i class='fas fa-chevron-right'></i>"
                }
            }
        });

        // Auto-refresh data every 30 seconds
        setInterval(function() {
            $.ajax({
                url: '{{ route('monitoring.dashboard.update') }}',
                method: 'GET',
                success: function(response) {
                    // Update counter values
                    $('#upCount').text(response.upCount);
                    $('#downCount').text(response.downCount);
                    $('#totalMonitors').text(response.totalMonitors);
                    $('#pausedCount').text(response.pausedCount);
                }
            });
        }, 30000); // 30 seconds interval
    });
</script>
@endpush

@push('scripts')
<script>
    // Show success message if any
    document.addEventListener("DOMContentLoaded", function() {
        @if(session('success'))
            toastr.success("{{ session('success') }}", "Success", {
                closeButton: true,
                progressBar: true,
                positionClass: "toast-bottom-right",
                timeOut: 5000
            });
        @endif
    });
</script>
@endpush

<!-- Initialize interactive tour -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/intro.js/7.2.0/intro.min.js"></script>

<script>
    // Configure and start the interactive tour
    introJs().setOptions({
        steps:[{
            title:'Welcome to Check My Site!',
            intro:'Let me show you around your monitoring dashboard.'
        },
        {
         element:document.querySelector('.AddMonitor'),
         intro:'Click here to add new monitors to track your websites, servers, or APIs.'
       },
       {
         element:document.querySelector('.first'),
         intro:'This shows your total number of monitors. Keep track of how many services you\'re watching.'
       },
       {
         element:document.querySelector('.second'),
         intro:'Healthy services appear here. Green is good!'
       },
       {
         element:document.querySelector('.third'),
         intro:'Any down services will show up here so you can take quick action.'
       },
       {
         element:document.querySelector('.fourth'),
         intro:'Paused monitors won\'t be checked until you resume them.'
       },
       {
         element:document.querySelector('#dataTable'),
         intro:'Here\'s your complete list of monitors with quick status overviews.'
       },
       {
         element:document.querySelector('.view'),
         intro:'Click "View" to see detailed statistics and response history for each monitor.'
       }
      ],
      dontShowAgain:false,     // Show the tour every time
      showProgress: true,      // Show progress bar
      showBullets: false,      // Hide bullet points
      nextLabel: 'Next →',     // Custom next button text
      prevLabel: '← Back',     // Custom back button text
      doneLabel: 'Let\'s Go!', // Custom done button text
      tooltipClass: 'custom-introjs', // Custom CSS class
      highlightClass: 'custom-highlight' // Custom highlight class
    }).start(); // Start the tour
</script>

@endsection