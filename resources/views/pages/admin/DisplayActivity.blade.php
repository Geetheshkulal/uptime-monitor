
@extends('dashboard')
@section('content')


    @push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.0/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    @endpush

<div id="content-wrapper" class="d-flex flex-column">
    <div id="content">
        <div class="container-fluid">
            <!-- Activity Log Table -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Activity Log</h6>
                    <!-- <select class="js-example-basic-single" name="state">
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->id }}-{{ $user->name }}</option>
                        @endforeach
                    </select> -->
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="activityTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Log Name</th>
                                    <th>Description</th>
                                    {{-- <th>Subject Type</th> --}}
                                    <th>Event</th>
                                    <th>Causer Type</th>
                                    <th>User Id</th>
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
                                    {{-- <td>{{ $log->subject_type }}</td> --}}
                                    <td>{{ $log->event }}</td>
                                    <td>{{ $log->causer_type }}</td>
                                    <td>{{ $log->causer_id }}</td>
                                    <td>{{ $log->causer->name}}</td>
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
        </div>
    </div>
</div>


<!-- Bootstrap 4 Modal -->
<div class="modal fade" id="propertiesModal" tabindex="-1" role="dialog" aria-labelledby="propertiesModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="propertiesModalLabel">Activity Properties</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <pre id="propertiesContent" class="bg-light p-3 border"></pre> <!-- JSON will be displayed here -->
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
        $('#activityTable').DataTable({ 
            "paging": true,
            "searching": true,
            "ordering": true,
            "info": false,
            "order": [[3, "desc"]]
        });

    });
    $(document).ready(function() {
        $('.js-example-basic-single').select2();
    });
</script>

<script>
    function showPropertiesModal(properties) {
        // Format JSON and display inside <pre> tag
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
