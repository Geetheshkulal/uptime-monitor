@extends('dashboard')
@section('content')
<head>
    @push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">

    <style>
        /* Add this to your existing styles */
        .pulse {
            animation: heartbeat 1.5s infinite;
            transform-origin: center;
        }
    
        @keyframes heartbeat {
            0% {
                transform: scale(1);
            }
            14% {
                transform: scale(1.3);
            }
            28% {
                transform: scale(1);
            }
            42% {
                transform: scale(1.3);
            }
            70% {
                transform: scale(1);
            }
        }
    </style> 
    @endpush

   {{-- for animation --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">

    {{-- for intro js tour --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intro.js/7.2.0/introjs.min.css"/>

</head>
<style>
  
.introjs-tooltip {
  background-color: white;  /* Dark gray */
  color: #0b38e9;             /* White text */
  font-family: 'Poppins', sans-serif;
  font-size: 14px;
  border-radius: 10px;
  box-shadow: 0 4px 12px rgba(55, 73, 241, 0.967);
}

/* Tooltip Title */
.introjs-tooltip-title {
  font-size: 18px;
  font-weight: 600;
  color: rgb(5, 36, 55); 
}

/* Buttons */
.introjs-button {
  background-color: #3b82f6; /* Blue */
  color: white;
  border-radius: 8px;
  padding: 6px 16px;
  font-size: 13px;
  transition: background-color 0.3s;
  border: none;
}

.introjs-button:hover {
  background-color: #2563eb; /* Darker blue */
}

/* Highlighted Element Border */
.introjs-helperLayer {
  border-radius: 10px;
  border: none;
}

/* Progress bar */
.introjs-progressbar {
  background-color: #22d3ee; /* Cyan */
}

.introjs-bullets li > a {
  background-color: #facc15;
}

.introjs-bullets li > a.active {
  background-color: #22c55e;
}

    </style>

<div id="content-wrapper" class="d-flex flex-column">
    <div id="content">
        <div class="container-fluid">
            <div data-aos="fade-up" class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">Overview</h1>
                
                @if($totalMonitors>=5 && auth()->user()->status=='free')
                <a class="bg-primary btn btn-secondary position-relative border-none" 
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
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body first">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Total Records</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800" id="totalMonitors">{{ $totalMonitors }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-list fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Earnings (Monthly) Card Example -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-rgb shadow h-100 py-2">
                    {{-- <div class="card border-left-success shadow h-100 py-2"> --}}
                        <div class="card-body second">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                        Up</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800" id="upCount" >{{ $upCount }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-heart fa-2x pulse" style="color:#63E6BE"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Earnings (Monthly) Card Example -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-info shadow h-100 py-2">
                        <div class="card-body third">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                        Down
                                    </div>
                                    <div class="row no-gutters align-items-center">
                                        <div class="col-auto">
                                            <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800" id="downCount">{{ $downCount }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-dizzy fa-2x" style="color:#bb072b;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pending Requests Card Example -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-warning shadow h-100 py-2">
                        <div class="card-body fourth">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                        Pause
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800" id="pausedCount">{{ $pausedCount }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-pause fa-2x " style="color:#b197fc"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div data-aos="fade-up" class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">My Monitors</h1>
            </div>

            @if(auth()->user()->status === 'free' && $hasMoreMonitors)
            <div data-aos="fade-up" class="premium-alert text-center mb-4 p-4" style="
                background: linear-gradient(180deg, blue,lightblue);
                color: white;
                border-radius: 12px;
                box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
                animation: pulseGlow 1.5s infinite alternate;
            ">
                <h2 style="font-weight: bold; text-shadow: 1px 1px 2px rgba(0,0,0,0.2);">
                    Unlock All Your Monitors!
                </h2>
                <p style="font-size: 16px; font-weight: 500;">
                    Upgrade to the premium plan to monitor all your services.
                </p>
                <a href="{{ route('premium.page') }}" class="btn btn-light text-dark px-4 py-2" style="
                    font-size: 18px;
                    font-weight: bold;
                    border-radius: 30px;
                    box-shadow: 2px 2px 6px rgba(0, 0, 0, 0.2);
                ">
                    <i class="fas fa-crown fa-sm me-1" style="color: gold; animation: glow 1.5s infinite alternate;"></i>
                    Upgrade Now
                </a>
            </div>
        
            <style>
                @keyframes pulseGlow {
                    0% { box-shadow: 0px 0px 10px rgba(255, 204, 0, 0.6); }
                    100% { box-shadow: 0px 0px 20px rgba(255, 204, 0, 1); }
                }
            </style>
        @endif

            <!-- Data Table -->
            <div data-aos="fade-up" class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-white">
                    <h6 class="m-0 font-weight-bold text-primary">All Monitors</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
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
                                @foreach ($monitors as $key=>$monitor)                           
                                <tr style="{{ (auth()->user()->status === 'free' && $loop->index >= 5) ? 'background-color: #f9f9f9; color: #999;' : '' }}">
                                    <td>{{ $monitor->name }}</td>
                                    <td>{{ $monitor->url }}</td>
                                    <td>{{ $monitor->type }}{{ $monitor->type === 'port' ? '-' . $monitor->port : '' }}</td>
                                    <td>
                                        @if ($monitor->paused == 1)
                                        <span class="badge" style="background-color:purple; color: white;">Paused</span>
                                    @elseif ($monitor->status === 'up')
                                        <span class="badge badge-success">Up</span>
                                    @else
                                        <span class="badge badge-danger">Down</span>
                                    @endif
                                    </td>
                                    <td>{{ $monitor->created_at->format('Y-m-d') }}</td>
                                    <td>                    
                                        @if ($monitor->latestResponses->isNotEmpty())  
                                            @foreach ($monitor->latestResponses as $response)
                                                <div style="width: 6px; height: 17px; margin: 1px; display: inline-block; background-color: {{ $response->status === 'up' ? '#5cdd8b' : '#ff4d4f' }}; border-radius: 50rem;"></div>
                                            @endforeach
                                        @else
                                            <div style="width: 6px; height: 17px; margin: 1px; display: inline-block; background-color: #ff4d4f; border-radius: 50rem;"></div>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('display.monitoring', ['id'=>$monitor->id, 'type'=>$monitor->type]) }}" class="btn btn-sm btn-success view">
                                            <i class="fas fa-eye fa-sm"></i> View
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

<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
<script>
    AOS.init({
        duration: 1000,
        easing: 'ease-in-out',
        once: true
    });
</script>

@push('scripts')
<script src="{{ asset('frontend/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('frontend/assets/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
<script src="{{ asset('frontend/assets/vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('frontend/assets/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('frontend/assets/js/sb-admin-2.min.js') }}"></script>

<script>
    $(document).ready(function() {
        var table = $('#dataTable').DataTable({
            "paging": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "order": [[4, "desc"]], // Default sort by Created Date
            "columnDefs": [
                { "orderable": false, "targets": [5, 6] } // Disable sorting for status bars and actions
            ]
        });

        // Periodically update data
        setInterval(function() {
            $.ajax({
                url: '{{ route('monitoring.dashboard.update') }}',
                method: 'GET',
                success: function(response) {
                    $('#upCount').text(response.upCount);
                    $('#downCount').text(response.downCount);
                    $('#totalMonitors').text(response.totalMonitors);
                    $('#pausedCount').text(response.pausedCount);
                    
                    // You could also reload the table here if needed
                    // table.ajax.reload(null, false);
                }
            });
        }, 30000); // Refresh every 30 seconds
    });
</script>
@endpush

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        @if(session('success'))
            toastr.success("{{ session('success') }}");
        @endif
    });
</script>
@endpush

<script src="https://cdnjs.cloudflare.com/ajax/libs/intro.js/7.2.0/intro.min.js"></script>

<script>
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
       },
       {
         element:document.querySelector('.view'),
         intro:'Click here to view, edit, update, or delete your monitors.'
       }
      ],
      dontShowAgain:true,
      showProgress: true,
    showBullets: false,
  nextLabel: 'Next →',
  prevLabel: '← Back',
  doneLabel: 'Let’s go!'
    }).start();
</script>

@endsection