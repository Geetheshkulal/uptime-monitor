@extends('dashboard')
@section('content')
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

        </style>
        @endpush

        <!-- Main Content -->
        <div id="content">
            <!-- Begin Page Content -->
            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">Overview</h1>
                    <a href="{{route("add.monitoring")}}" class="btn btn-primary">
                        <i class="fas fa-plus-circle"></i> Add New Monitor
                    </a>
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
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalMonitors }}</div>
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
                        <div class="card border-left-success shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                            Up</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $upCount }}</div>
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
                                                <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">{{ $downCount }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-skull-crossbones fa-2x" style="color:#bb072b;"></i>
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
                                            Pause</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">18</div>
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

                <!-- Data Table -->
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th class=" text-gray-900">Url</th>
                                     <th class=" text-gray-900">Type</th>
                                    <th class=" text-gray-900">Status</th>
                                    <th class=" text-gray-900">Created Date</th>
                                    <th class=" text-gray-900">Message</th>
                                    <th class=" text-gray-900">Last Calls Status</th>
                                    <th class=" text-gray-900">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                               
                            @foreach ($monitors as $monitor)                           
                                <tr class="tablerow">
                                    <td class="sorting_1">{{ $monitor->url }}</td>
                                    <td>{{ $monitor->type }}{{ $monitor->type === 'port' ? '-' . $monitor->port : '' }}</td>
                                    <td>
                                        @if ($monitor)
                                            @if ($monitor->status === 'up')
                                                <span class="badge badge-success">Up</span>
                                            @else
                                                <span class="badge badge-danger">Down</span>
                                            @endif
                                        @else
                                            <span class="badge badge-danger">Down</span>
                                        @endif
                                    </td>
                                    <td>{{ $monitor->created_at->format('Y-m-d') }}</td>
                                    <td>N/A</td>

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

                                <!-- Add more rows as needed -->
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
        $(document).ready(function() {
            $('#dataTable').DataTable({
                columnDefs: [
                    { targets: [5], searchable: false } // Exclude column index 5 (action button column) from search
                ]
            });
        });

    </script>


    @endpush
@endsection