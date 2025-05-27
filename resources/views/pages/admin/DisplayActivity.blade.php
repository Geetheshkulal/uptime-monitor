@extends('dashboard')
@section('content')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.0/css/bootstrap.min.css"> --}}
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
                                <option value="{{ $user->id }}">{{ $user->name }} (ID: {{ $user->id }})</option>
                            @endforeach
                        </select>
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
                                @foreach($logs as $log)
                                <tr>
                                    <td>{{ $log->id }}</td>
                                    <td>{{ $log->log_name }}</td>
                                    <td>{{ $log->description }}</td>
                                    <td>{{ $log->event }}</td>
                                    <td>{{ $log->causer?->name }}</td>
                                    <td>{{ $log->created_at->format('d M Y, h:i A') }}</td>
                                    <td>
                                        <button class="btn btn-success btn-sm" onclick="showPropertiesModal({{ json_encode($log->properties) }})">
                                            View
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            
</div></div>

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
     
        var table = $('#activityTable').DataTable({ 
            "paging": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "order": [[5, "desc"]],
            "columnDefs": [
                { "searchable": false, "targets": [6] } 
            ]
            
        });

        
        $('.js-example-basic-single').select2({
            placeholder: "Select a user",
            allowClear: true
        });

     
        $('#userFilter').change(function() {
            var userId = $(this).val();
            table.column(5).search(userId).draw();
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