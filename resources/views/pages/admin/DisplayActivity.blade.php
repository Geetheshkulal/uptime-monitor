@extends('dashboard')
@section('content')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    
    <style>
        .select2-container--default .select2-selection--single {
            height: 38px;
            border: 1px solid #d1d3e2;
            border-radius: 0.35rem;
            padding: 6px 12px;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 24px;
        }
        .filter-container {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
        }
        .filter-container label {
            margin-bottom: 0;
            font-weight: 600;
            color: #6e707e;
        }
        * {
            border-radius: 0 !important;
        }
        .dataTables_processing {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 200px;
            margin-left: -100px;
            margin-top: -26px;
            text-align: center;
            padding: 1em 0;
            background: rgba(255, 255, 255, 0.9);
            border: 1px solid #ddd;
        }

        .select2-results__options {
            max-height: 200px; /* Adjust this value as needed */
            overflow-y: auto;
        }



    </style>
@endpush


<div class="page-content">
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Activity Log</h1>
        </div>
        
        <!-- Activity Log Table -->
        <div class="card shadow mb-4">
            <div class="card-body px-4 py-4">
                <div class="filter-container">
                    <label for="userFilter">Filter by User:</label>
                    <select class="js-example-basic-single form-control" id="userFilter" style="width: 300px;">
                        <option value="">All Users</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }} | {{$user->email}} | {{$user->phone}}</option>
                        @endforeach
                    </select>

                    {{-- seperate search for name email phone --}}
                    
                    {{-- <div class="row">
                        <div class="col-md-4">
                            <input type="text" id="searchName" class="form-control" placeholder="Search by Name">
                        </div>
                        <div class="col-md-4">
                            <input type="text" id="searchEmail" class="form-control" placeholder="Search by Email">
                        </div>
                        <div class="col-md-4">
                            <input type="text" id="searchPhone" class="form-control" placeholder="Search by Phone">
                        </div>
                    </div> --}}
                </div>
                
                <div class="table-responsive">
                    <table class="table table-bordered" id="activityTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Log Name</th>
                                <th>Description</th>
                                <th>Event</th>
                                <th>User Name</th>
                                <th>Date</th>
                                <th>Properties</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data will be loaded via AJAX -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Bootstrap 4 Modal -->
<div class="modal fade" id="propertiesModal" tabindex="-1" role="dialog" aria-labelledby="propertiesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="propertiesModalLabel">Activity Properties</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <pre id="propertiesContent" class="bg-light p-3 border rounded" style="max-height: 500px; overflow: auto;"></pre>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>




 @push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.0/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> 


<script>
    $(document).ready(function() {
        // Initialize DataTable with server-side processing
        var table = $('#activityTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('activity.logs.ajax') }}",
                type: "GET",
                data: function(d) {
                    d.user_filter = $('#userFilter').val();

                    d.search_name = $('#searchName').val();
                    d.search_email = $('#searchEmail').val();
                    d.search_phone = $('#searchPhone').val();
                },
                error: function(xhr, error, thrown) {
                    console.error('DataTables AJAX error:', error);
                    alert('Error loading data. Please try again.');
                }
            },
            columns: [
                { data: 'id', name: 'id', orderable: true },
                { data: 'log_name', name: 'log_name', orderable: true },
                { data: 'description', name: 'description', orderable: true },
                { data: 'event', name: 'event', orderable: true },
                { data: 'causer_name', name: 'causer_name', orderable: true },
                { data: 'created_at', name: 'created_at', orderable: true},
                { 
                    data: 'properties_button', 
                    name: 'properties', 
                    orderable: false, 
                    searchable: false 
                }
            ],
            order: [[5, "desc"]], // Order by date column (created_at) descending
            pageLength: 25,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            language: {
                processing: '<div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div> Loading...',
                search: "Search:",
                lengthMenu: "Show _MENU_ entries",
                info: "Showing _START_ to _END_ of _TOTAL_ entries",
                infoEmpty: "Showing 0 to 0 of 0 entries",
                infoFiltered: "(filtered from _MAX_ total entries)",
                paginate: {
                    first: "First",
                    last: "Last",
                    next: "Next",
                    previous: "Previous"
                }
            },
            dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
                 '<"row"<"col-sm-12"tr>>' +
                 '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
            responsive: true
        });

        
        $('.js-example-basic-single').select2({
            placeholder: "Select a user",
            allowClear: true
        });

     
        $('#userFilter').change(function() {
            table.ajax.reload();
        });

        $('#searchName, #searchEmail, #searchPhone').on('keyup', function() {
            table.ajax.reload();
        });

        // Handle DataTable errors
        table.on('error.dt', function(e, settings, techNote, message) {
            console.error('DataTable error: ', message);
        });
    });

    function showPropertiesModal(properties) {
        
        document.getElementById("propertiesContent").textContent = JSON.stringify(properties, null, 4);
        $('#propertiesModal').modal('show');
    }
</script>

@if(session('success'))
<script>
    toastr.success("{{ session('success') }}");
</script>
@endif
@endpush 

@endsection