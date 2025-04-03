@extends('dashboard')
@section('content')
<head>
    @push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
@endpush

</head>
    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

        @push('styles')

        <style>
            .tablerow:hover{
                background-color: #f0f0f0;
            }
            @keyframes heartbeat {
                0% { transform: scale(1); }
                25% { transform: scale(1.2); }
                50% { transform: scale(1); }
                75% { transform: scale(1.2); }
                100% { transform: scale(1); }
            }

            .pulse {
                animation: heartbeat 1s infinite;
            }
    

#dataTable {
    width: 100% !important;
    table-layout: auto;
}

@media (max-width: 992px) {
    .dataTables_wrapper {
        overflow-x: auto;
    }
    .dataTables_scroll {
        display: block; /* Ensure it's visible */
        overflow-x: scroll !important;
        width: 100%;
    }
}

@media (min-width: 993px) {
    .dataTables_wrapper {
        overflow-x: hidden !important; /* Prevents any scrolling */
    }
    .dataTables_scroll {
        display: none !important;
    }
}

    .floating-btn {
        z-index: 1000;
        padding: 10px 20px;
        font-size: 16px;
        border-radius: 50px;
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.2);
        transition: transform 0.2s ease-in-out;
    }

    .floating-btn:hover {
        transform: scale(1.1);
    }

     </style>
    @endpush

 <style>
  @keyframes rgbGradientBorder {
    0% { border-image-source: linear-gradient(45deg, red, yellow, pink, cyan); }
    25% { border-image-source: linear-gradient(45deg, yellow, cyan, yellow, red); }
    50% { border-image-source: linear-gradient(45deg, blue, pink, red, yellow); }
    75% { border-image-source: linear-gradient(45deg, pink, red, yellow, cyan); }
    100% { border-image-source: linear-gradient(45deg, red, yellow, blue, white); }
}

.border-rgb {
    border: 5px solid;
    border-image-slice: 1;
    border-image-source: linear-gradient(45deg, red, yellow, blue, green);
    animation: rgbGradientBorder 3s infinite linear;
    border-radius: 6px;
}

/* Target the default DataTables search bar */
#dataTable_filter input {
    width: 250px !important; 
    height: 40px !important;
    padding: 8px; 
    font-size: 14px; 
    border-radius: 30px; 
    border: 1px solid #ccc; 
    outline: none; 
}


#dataTable_filter input::placeholder {
    color: #999;
    font-style: italic;
}
  </style>

        <!-- Main Content -->
        <div id="content">
            <!-- Begin Page Content -->
            <div class="container-fluid">

                <!-- Page Heading -->
            
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Overview</h1>
                    
                    <!-- Floating Add Button -->
                   @if($totalMonitors>=5 && auth()->user()->status=='free')
                    <a class=" bg-primary btn btn-secondary position-relative rounded-pill border-none" 
                        href="{{ route('premium.page') }}"
                        title="Premium feature - Upgrade to unlock">
                            <i class="fas fa-crown fa-sm me-1" style="color: gold; animation: glow 1.5s infinite alternate;"></i>
                            Add New Monitor
                        </a>
                    @else
                        <a class=" bg-primary btn btn-secondary  position-relative rounded-pill" 
                        href="{{ route('add.monitoring') }}"
                        title="Free Monitors">
                            Add New Monitor
                        </a>
                        @endif
                </div>

                <!-- Content Row -->
                <div class="row">

                    <!-- Earnings (Monthly) Card Example -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-primary shadow h-100 py-2">
                            <div class="card-body">
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
                            <div class="card-body">
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
                            <div class="card-body">
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
                            <div class="card-body">
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

                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">My Monitors</h1>
                </div>
<!-- Premium Alert for Free Users with More Monitors -->
@if(auth()->user()->status === 'free' && $hasMoreMonitors)
    <div class="premium-alert text-center mb-4 p-4" style="
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
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th class=" text-gray-900">Name</th>
                                    <th class=" text-gray-900">Url</th>
                                    <th class=" text-gray-900">Type</th>
                                    <th class=" text-gray-900">Status</th>
                                    <th class=" text-gray-900">Created Date</th>
                                    <th class=" text-gray-900">Last Calls Status</th>
                                    <th class=" text-gray-900">Action</th>
                            
                                </tr>
                            </thead>
                            <tbody>
                               
                            @foreach ($monitors as $key=>$monitor)                           
                            <tr class="tablerow" style="{{ (auth()->user()->status === 'free' && $loop->index >= 5) ? 'background-color: #000000; color: #999;' : '' }}">                                    <td class="sorting_1">{{ $monitor->name }}</td>
                                    <td class="sorting_1">{{ $monitor->url }}</td>
                                    <td>{{ $monitor->type }}{{ $monitor->type === 'port' ? '-' . $monitor->port : '' }}</td>
                                    <td>
                                        @if ($monitor->paused == 1)
                                            <span class="badge" style="background-color: purple; color: white;">Paused</span>
                                        @elseif ($monitor->status === 'up')
                                            <span class="badge badge-success">Up</span>
                                        @else
                                            <span class="badge badge-danger">Down</span>
                                        @endif
                                    </td>
                                    
                                    <td>{{ $monitor->created_at->format('Y-m-d') }}</td>
                                    <!-- Latest Responses (Bars) -->
                                    <td>                    
                                        @if ($monitor->latestResponses->isNotEmpty())  
                                            @foreach ($monitor->latestResponses as $response)
                                                <div style="
                                                    width: 6px; 
                                                    height: 17px; 
                                                    margin: 1px; 
                                                    display: inline-block; 
                                                    background-color: {{ $response->status === 'up' ? '#5cdd8b' : '#ff4d4f' }}; 
                                                    border-radius: 50rem;
                                                "></div>
                                            @endforeach
                                        @else
                                            <!-- Default when no responses are found -->
                                            <div style="
                                                width: 6px; 
                                                height: 17px; 
                                                margin: 1px; 
                                                display: inline-block; 
                                                background-color: #ff4d4f; 
                                                border-radius: 50rem;
                                            "></div>
                                        @endif
                                    </td>

                                    <td>
                                        <a href="{{ route('display.monitoring', ['id'=>$monitor->id, 'type'=>$monitor->type]) }}" class="btn btn-success">
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

    <script>
         var total = document.getElementById('totalMonitors');
         var upCount = document.getElementById('upCount');
         var downCount = document.getElementById('downCount');

         $(document).ready(function() {


            var table = $('#dataTable').DataTable({
                processing: false,
                serverSide: false, // Enable server-side processing
                ajax: {
                    url: '{{ route('monitoring.dashboard.update') }}', // Your API endpoint
                    method: 'GET',
                    dataSrc: 'data' // The key in the JSON response that contains the data
                },
                columns: [
                    { data: 'name', name: 'name' }, // Monitor name
                    { data: 'url', name: 'url' }, // Monitor URL
                    { data: 'type', name: 'type', render: function(data, type, row) {
                        // Append port to type if it exists
                        return data + (row.port ? '-' + row.port : '');
                    }},
                    { data: null, name: 'status', render: function(data) {
                    if (data.paused === 1) {
                            return '<span class="badge" style="background-color: purple; color: white;">Paused</span>';
                         } else if (data.status === 'up') {
                             return '<span class="badge badge-success">Up</span>';
                         } else {
                              return '<span class="badge badge-danger">Down</span>';
                         }
                    }},


                    { data: 'created_at', name: 'created_at', render: function(data) {
                        // Format the created_at date
                        return new Date(data).toISOString().split('T')[0];
                    }},
                    { data: 'latestResponses', name: 'latestResponses', render: function(data) {
                        // Render status bars for latest responses
                        if (data && data.length > 0) {
                            return data.map(response => {
                                return `<div style="width: 6px; height: 17px; margin: 1px; display: inline-block; 
                                        background-color: ${response.status === 'up' ? '#5cdd8b' : '#ff4d4f'}; 
                                        border-radius: 50rem;"></div>`;
                            }).join('');
                        } else {
                            // Default bar when no responses are available
                            return `<div style="width: 6px; height: 17px; margin: 1px; display: inline-block; 
                                    background-color: #ff4d4f; border-radius: 50rem;"></div>`;
                        }
                    }},
                    { data: null, render: function(data, type, row) {
                        // Render the "View" button
                        return `<a href="/monitoring/display/${row.id}/${row.type}" class="btn btn-success">
                                    <i class="fas fa-eye fa-sm"></i> View
                                </a>`;
                    }}
                ],

                columnDefs: [
                    { targets: [5, 6], searchable: false, orderable: false } // Disable search and sorting for status bars and actions
                ]
            });

            // Periodically reload the table data
            setInterval(function() {
                table.ajax.reload(null, false); // Reload data without resetting paging
                $.ajax({
                    url: '{{ route('monitoring.dashboard.update') }}',
                    method: 'GET',
                    success: function(response) {
                        upCount.innerText = response.upCount;
                        downCount.innerText = response.downCount;
                        total.innerText = response.totalMonitors;
                    }
                });
            }, 30000); // Refresh every 30 seconds
        });

    </script>
    @endpush
@endsection

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            @if(session('success'))
                toastr.success("{{ session('success') }}");
            @endif
        });
    </script>
    
    
@endpush
