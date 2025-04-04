@extends('dashboard')
@section('content')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
@endpush

<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">User Details</h1>
        <div>
            <a href="{{ route('display.users') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Users
            </a>
        </div>
    </div>

    <!-- User Details Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">User Information</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tr>
                                <th width="30%">Name</th>
                                <td>{{ $user->name }}</td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>{{ $user->email }}</td>
                            </tr>
                            <tr>
                                <th>Phone</th>
                                <td>{{ $user->phone ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>
                                    <span class="badge badge-{{ $user->status === 'active' ? 'success' : ($user->status === 'paid' ? 'warning' : 'secondary') }}">
                                        {{ ucfirst($user->status) }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Roles</th>
                                <td>
                                    @foreach($user->roles as $role)
                                        <span class="badge badge-info mr-1">{{ $role->name }}</span>
                                    @endforeach
                                </td>
                            </tr>
                            <tr>
                                <th>Premium End Date</th>
                                <td>{{ $user->premium_end_date ? \Carbon\Carbon::parse($user->premium_end_date)->format('M d, Y') : 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Registered On</th>
                                <td>{{ $user->created_at->format('M d, Y') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="col-md-4">
                    <!-- User Actions -->
                    @canany(['edit.user','delete.user'])
                        <div class="card mb-4">
                            <div class="card-header">
                                <h6 class="m-0 font-weight-bold text-primary">Actions</h6>
                            </div>
                            <div class="card-body text-center">
                                @can('edit.user')
                                    <a href="{{ route('edit.user', $user->id) }}" class="btn btn-primary btn-block mb-3">
                                        <i class="fas fa-edit"></i> Edit User
                                    </a>
                                @endcan
                                
                                @can('delete.user')
                                    <form action="{{ route('delete.user', $user->id) }}" method="POST" class="d-inline-block w-100">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-block" onclick="return confirm('Are you sure you want to delete this user?')">
                                            <i class="fas fa-trash"></i> Delete User
                                        </button>
                                    </form>
                                @endcan
                            </div>
                        </div>
                    @endcanany
                    
                    <!-- Additional Info (optional) -->
                    <div class="card">
                        <div class="card-header">
                            <h6 class="m-0 font-weight-bold text-primary">Account Info</h6>
                        </div>
                        <div class="card-body">
                            <p><strong>Last Updated:</strong> {{ $user->updated_at->diffForHumans() }}</p>
                            <p><strong>Email Verified:</strong> 
                                @if($user->email_verified_at)
                                    <span class="text-success">Yes ({{ $user->email_verified_at->format('M d, Y') }})</span>
                                @else
                                    <span class="text-danger">No</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- User Monitors Section -->
    @can('see.monitors')
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">User Monitors</h6>
                <span class="badge badge-primary">{{ $user->monitors->count() }} Monitors</span>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="monitorsTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>URL</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Created Date</th>
                                @can('see.monitor.details')
                                    <th>Action</th>
                                @endcan
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($user->monitors as $monitor)
                            <tr>
                                <td>{{ $monitor->name }}</td>
                                <td>{{ $monitor->url }}</td>
                                <td>{{ $monitor->type }}{{ $monitor->type === 'port' ? '-' . $monitor->port : '' }}</td>
                                <td>
                                    @if ($monitor->status === 'up')
                                        <span class="badge badge-success">Up</span>
                                    @else
                                        <span class="badge badge-danger">Down</span>
                                    @endif
                                </td>
                                <td>{{ $monitor->created_at->format('Y-m-d') }}</td>
                                @can('see.monitor.details')
                                    <td>
                                        <a href="{{ route('display.monitoring', ['id' => $monitor->id, 'type' => $monitor->type]) }}" 
                                        class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                    </td>
                                @endcan
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endcan
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        $('#monitorsTable').DataTable({
            "pageLength": 10,
            "order": [[4, "desc"]]
        });
    });
</script>
@endpush
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