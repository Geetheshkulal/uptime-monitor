{{-- @extends('dashboard')
@section('content')
    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">
        @push('styles')
        <style>
            .tablerow:hover{
                background-color: #f0f0f0;
            }
        </style>
        @endpush

        <!-- Main Content -->
        <div id="content">
            <!-- Begin Page Content -->
            <div class="container-fluid">

                <!-- Page Heading -->
             

                <!-- Content Row -->
                <div class="row">
    
                </div>

                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">Incidents</h1>
                </div>

                <!-- Data Table -->
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Status</th>
                                    <th>URL</th>
                                    <th>Root Cause</th>
                                    <th>Start Date</th>
                                    <th>Ends On</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach (range(1, 5) as $num)
                                    <tr class="tablerow">
                                        <td>
                                            <span class="badge badge-success">Up</span>
                                            <span class="badge badge-danger">Down</span>
                                        </td>
                                        <td>https://example.com</td>
                                        <td>Server Crash</td>
                                        <td>2025/03/17 10:30 AM</td>
                                        <td>2025/03/17 03:45 PM</td>
                                        <td>
                                          
                                                
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
        $(document).ready(function() {
            $('#dataTable').DataTable({
                columnDefs: [
                    { targets: [5], searchable: false } // Exclude column index 5 (action button column) from search
                ]
            });
        });

    </script>


    @endpush
@endsection --}}




@extends('dashboard')
@section('content')
    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">
        @push('styles')
        <style>
            .tablerow:hover{
                background-color: #f0f0f0;
            }
        </style>
        @endpush

        <!-- Main Content -->
        <div id="content">
            <!-- Begin Page Content -->
            <div class="container-fluid">

                <!-- Page Heading -->
                {{-- <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">Incidents</h1>   
                </div> --}}
                <!-- Content Row -->
                <div class="row">

                    <!-- Pending Requests Card Example -->

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
                                    <th>Status</th>
                                    <th>URL</th>
                                    <th>Root Cause</th>
                                    <th>Start Date</th>
                                    <th>Ends On</th>
                                    <th style="display: none;">Action</th> 

                                </tr>
                            </thead>
                            <tbody>
                                @foreach (range(1,20) as $num)
                                <tr class="tablerow">
                                    <td>
                                        <span class="badge badge-success">Up</span>
                                        <span class="badge badge-danger">Down</span>
                                    </td>
                                    <td>https://example.com</td>
                                    <td>Server Crash</td>
                                    <td>2025/03/17 10:30 AM</td>
                                    <td>2025/03/17 03:45 PM</td>
                                    <td style="display: none;"></td> 

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




