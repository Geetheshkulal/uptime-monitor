@extends('dashboard')
@section('content')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
@endpush

<div class="page-content">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4 mx-4 mt-4">
        <h1 class="h3 mb-0 text-gray-800">Roles Management</h1>
        <a href="{{ route('add.role') }}" class="d-none d-sm-inline-block btn btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50 mr-1"></i> Add Role
        </a>
    </div>

    <!-- Content Row -->
    <div class="row mx-3">
        <div class="col-12">
            <div class="card shadow mb-4">
                <!-- Card Header -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-white px-4">
                    <h6 class="m-0 font-weight-bold text-primary">All Roles ({{ $roles->total() }})</h6>
                </div>
                
                <!-- Card Body -->
                <div class="card-body px-4 py-4">
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th width="5%" class="pl-3">#</th>
                                    <th class="pl-3">Role Name</th>
                                    <th width="25%" class="text-center">Permissions</th>
                                    <th width="20%" class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($roles as $key => $role)
                                <tr>
                                    <td class="pl-3">{{ $roles->firstItem() + $key }}</td>
                                    <td class="pl-3">
                                        <span class="badge badge-primary py-1 px-2">{{ $role->name }}</span>
                                    </td>
                                    <td class="text-center">
                                        <a href="#" class="btn btn-sm btn-success px-3 py-1">
                                            <i class="fas fa-key mr-1"></i> Manage
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('edit.role',$role->id) }}" class="btn btn-sm btn-primary px-3 py-1 mr-2">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{ route('delete.role',$role->id) }}" class="btn btn-sm btn-danger px-3 py-1" onclick="return confirm('Are you sure?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4">No roles found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    @if($roles->hasPages())
                    <div class="mt-4 pt-3">
                        {{ $roles->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
    @if(Session::has('success'))
        toastr.success("{{ Session::get('success') }}");
    @endif
    
    @if(Session::has('error'))
        toastr.error("{{ Session::get('error') }}");
    @endif
</script>
@endpush

@endsection