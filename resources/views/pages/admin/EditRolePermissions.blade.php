@extends('dashboard')
@section('content')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<style>
    .form-check-label {
        text-transform: capitalize;
    }
    .permission-group {
        padding-bottom: 15px;
        margin-bottom: 15px;
    }
    .permission-item {
        margin-left: 20px;
    }
</style>
@endpush

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 ">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="card-title mb-0">
                                Modify Permissions for Role: <strong>{{ $role->name }}</strong>
                            </h4>
                            <a href="{{ route('display.roles') }}" class="btn btn-primary">
                                <i class="fas fa-arrow-left"></i> Back
                            </a>
                        </div>

                        <form method="POST" action="{{ route('update.role.permissions', $role->id) }}">
                            @csrf
                
                            <!-- Select All Checkbox -->
                            <div class="form-check mb-3">
                                <input type="checkbox" class="form-check-input" id="selectAll">
                                <label class="form-check-label" for="selectAll">
                                    <strong>Select All Permissions</strong>
                                </label>
                            </div>
                
                            <!-- Permission Groups -->
                            @foreach($permission_groups as $group)
                                <div class="permission-group">
                                    <!-- Group Checkbox -->
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input group-checkbox"
                                            id="group-{{ $group->group_name }}"
                                            data-group="{{ $group->group_name }}">
                                        <label class="form-check-label" for="group-{{ $group->group_name }}">
                                            <strong>{{ ucfirst($group->group_name) }}</strong>
                                        </label>
                                    </div>
                
                                    <!-- Individual Permissions -->
                                    <div>
                                        @foreach($groupedPermissions[$group->group_name] as $permission)
                                            <div class="form-check form-check-inline">
                                                <input type="checkbox" class="form-check-input permission-checkbox {{ $group->group_name }}"
                                                    name="permission[]"
                                                    id="permission-{{ $permission->id }}"
                                                    value="{{ $permission->id }}"
                                                    {{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="permission-{{ $permission->id }}">
                                                    {{ $permission->name }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                
                            <!-- Form Actions -->
                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary">Update Permissions</button>
                                <a href="{{ route('display.roles') }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Toastr JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
     toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };

    // Show toastr messages from session
    @if(Session::has('success'))
        toastr.success("{{ Session::get('success') }}");
    @endif

    @if(Session::has('error'))
        toastr.error("{{ Session::get('error') }}");
    @endif

    $(document).ready(function() {
        // Select all permissions
        $('#selectAll').click(function() {
            const checked = $(this).prop('checked');
            $('.permission-checkbox').prop('checked', checked);
            $('.group-checkbox').prop('checked', checked);
        });
    
        // Group checkbox functionality
        $('.group-checkbox').click(function() {
            var group = $(this).data('group');
            $('.permission-checkbox.' + group).prop('checked', $(this).prop('checked'));
    
            // Update "Select All" checkbox
            $('#selectAll').prop('checked', $('.group-checkbox:checked').length === $('.group-checkbox').length);
        });
    
        // Individual permission checkbox functionality
        $('.permission-checkbox').click(function() {
            var classes = $(this).attr('class').split(' ');
            var group = classes.find(c => c !== 'form-check-input' && c !== 'permission-checkbox');
    
            var total = $('.permission-checkbox.' + group).length;
            var checked = $('.permission-checkbox.' + group + ':checked').length;
    
            $('#group-' + group).prop('checked', total === checked);
    
            // Update "Select All" checkbox
            var allGroupsChecked = $('.group-checkbox:checked').length === $('.group-checkbox').length;
            $('#selectAll').prop('checked', allGroupsChecked);
        });
    
        // Initialize group checkboxes on load
        $('.group-checkbox').each(function() {
            var group = $(this).data('group');
            var total = $('.permission-checkbox.' + group).length;
            var checked = $('.permission-checkbox.' + group + ':checked').length;
            $(this).prop('checked', total === checked);
        });
    
        // Initialize "Select All" checkbox on load
        var allGroupsChecked = $('.group-checkbox:checked').length === $('.group-checkbox').length;
        $('#selectAll').prop('checked', allGroupsChecked);
    });
    </script>
    
@endpush
@endsection