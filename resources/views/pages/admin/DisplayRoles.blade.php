@extends('dashboard')
@section('content')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
@endpush

<div class="page-content">
    <!-- Page Heading with proper margins -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4 mx-3 mt-3">
        <h1 class="h3 mb-0 text-gray-800">Roles Management</h1>
        @can('add.role')
            <a href="{{ route('add.role') }}" class="d-none d-sm-inline-block btn btn-primary shadow-sm">
                <i class="fas fa-plus fa-sm text-white-50 mr-1"></i> Add Role
            </a>
        @endcan
    </div>

    <!-- Content Row with container margins -->
    <div class="row mx-3">
        <div class="col-12">
            <div class="card shadow mb-4">
                <!-- Card Header with padding -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-white px-4">
                    <h6 class="m-0 font-weight-bold text-primary">All Roles</h6>
                </div>
                
                <!-- Card Body with consistent padding -->
                <div class="card-body px-4 py-4">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="rolesTable" width="100%" cellspacing="0">
                            <thead class="thead-light">
                                <tr>
                                    <th width="5%">#</th>
                                    <th>Role Name</th>
                                    @can('edit.role.permissions')
                                        <th width="25%" class="text-center">Permissions</th>
                                    @endcan

                                    @canany(['edit.role','delete.role'])
                                        <th width="20%" class="text-center">Actions</th>
                                    @endcanany
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($roles as $key => $role)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>
                                        <span class="badge badge-primary py-1 px-2">{{ $role->name }}</span>
                                    </td>
                                    @can('edit.role.permissions')
                                        <td class="text-center">
                                            <a href="{{ route('edit.role.permissions',$role->id) }}" class="btn btn-sm btn-success px-3 py-1">
                                                <i class="fas fa-key mr-1"></i> Manage
                                            </a>
                                        </td>
                                    @endcan

                                    @canany(['edit.role','delete.role'])
                                        <td class="text-center">
                                            @can('edit.role')
                                                <a href="{{ route('edit.role',$role->id) }}" class="btn btn-sm btn-primary px-3 py-1 mr-1">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            @endcan

                                            @can('delete.role')
                                                <a href="{{ route('delete.role',$role->id) }}" class="btn btn-sm btn-danger px-3 py-1" onclick="return confirm('Are you sure?')">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            @endcan
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
        $('#rolesTable').DataTable({
            "paging": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "order": [[0, "asc"]],
            "columnDefs": [
                { "orderable": false, "targets": [2, 3] } // Disable sorting for permissions and action columns
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