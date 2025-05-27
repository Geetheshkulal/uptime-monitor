@extends('dashboard')
@section('content')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
<style>
    * {
        border-radius: 0 !important;
    }
</style>
@endpush
<div id="content-wrapper" class="d-flex flex-column">
<div class="page-content">
       <div class="container-fluid">
    <!-- Page Heading with proper margins -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Permissions Management</h1>
        @can('add.permission')
            <a href="{{ route('add.permission') }}" class="d-sm-inline-block btn btn-primary shadow-sm mt-2 mt-md-0">
                <i class="fas fa-plus fa-sm text-white-50 mr-1"></i> Add Permission
            </a>
        @endcan
    </div>

    <!-- Content Row with container margins -->
            <div class="card shadow mb-4">
                <!-- Card Body with consistent padding -->
                <div class="card-body px-4 py-4">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="permissionsTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Permission Name</th>
                                    <th>Group Name</th>
                                    @canany(['edit.permission','delete.permission'])
                                        <th>Actions</th>
                                    @endcanany
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($permissions as $key => $permission)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $permission->name }}</td>
                                    <td>{{ $permission->group_name }}</td>
                                    @canany(['edit.permission','delete.permission'])
                                        <td>
                                            @if($permission->type==='system')
                                                <span>System Permission</span>
                                            @elseif($permission->type==='custom')
                                                @can('edit.permission')
                                                    <a href="{{ route('edit.permission',$permission->id) }}" 
                                                    class="btn btn-sm btn-primary px-3 py-1 mr-1">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                @endcan
                                                
                                                @can('delete.permission')
                                                    <a href="{{route('delete.permission',$permission->id)}}" 
                                                    class="btn btn-sm btn-danger px-3 py-1" 
                                                    onclick="return confirm('Are you sure?')">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                @endcan
                                            @endif
                                        </td>
                                    @endcanany
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
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
<script>
    $(document).ready(function() {
        $('#permissionsTable').DataTable({
            "paging": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "order": [[0, "asc"]],
            "columnDefs": [
                { "orderable": false, "targets": [3] } // Disable sorting for action column
            ]
        });
    });

    @if(Session::has('success'))
        toastr.success("{{ Session::get('success') }}");
    @endif
    
    @if(Session::has('error'))
        toastr.error("{{ Session::get('error') }}");
    @endif
</script>
@endpush
@endsection