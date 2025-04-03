@extends('dashboard')
@section('content')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
@endpush

<div class="page-content">
    <!-- Page Heading with proper margins -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4 mx-3 mt-3">
        <h1 class="h3 mb-0 text-gray-800">Permissions Management</h1>
        <a href="{{ route('add.permission') }}" class="d-none d-sm-inline-block btn btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50 mr-1"></i> Add Permission
        </a>
    </div>

    <!-- Content Row with container margins -->
    <div class="row mx-3">
        <div class="col-12">
            <div class="card shadow mb-4">
                <!-- Card Header with padding -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-white px-4">
                    <h6 class="m-0 font-weight-bold text-primary">All Permissions</h6>
                </div>
                
                <!-- Card Body with consistent padding -->
                <div class="card-body px-4 py-4">
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th width="5%" class="pl-3">#</th>
                                    <th class="pl-3">Permission Name</th>
                                    <th class="pl-3">Group Name</th>
                                    <th width="20%" class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($permissions as $key => $permission)
                                <tr>
                                    <td class="pl-3">{{ $permissions->firstItem() + $key }}</td>
                                    <td class="pl-3">{{ $permission->name }}</td>
                                    <td class="pl-3">{{ $permission->group_name }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('edit.permission',$permission->id) }}" 
                                           class="btn btn-sm btn-primary px-3 py-1 mr-1">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{route('delete.permission',$permission->id)}}" 
                                           class="btn btn-sm btn-danger px-3 py-1" 
                                           onclick="return confirm('Are you sure?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4">No permissions found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination with proper spacing -->
                    @if($permissions->hasPages())
                    <div class="mt-4 pt-3">
                        {{ $permissions->links() }}
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