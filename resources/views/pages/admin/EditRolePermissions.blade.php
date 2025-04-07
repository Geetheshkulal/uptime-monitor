@extends('dashboard')
@section('content')

@push('styles')
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
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="card-title mb-0">
                                Modify Permissions for Role: <strong>{{ $role->name }}</strong>
                            </h4>
                            <a href="{{ route('display.roles') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back
                            </a>
                        </div>

                        <form method="POST" action="{{ route('update.role.permissions', $role->id) }}">
                            @csrf

                            <div class="form-check mb-3">
                                <input type="checkbox" class="form-check-input" id="selectAll">
                                <label class="form-check-label" for="selectAll">
                                    <strong>Select All Permissions</strong>
                                </label>
                            </div>

                            <div class="row">
                                @foreach($permission_groups as $group)
                                    <div class="col-md-4">
                                        <div class="permission-group">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input group-checkbox"
                                                    id="group-{{ $group->group_name }}"
                                                    data-group="{{ $group->group_name }}">
                                                <label class="form-check-label" for="group-{{ $group->group_name }}">
                                                    <strong>{{ ucfirst($group->group_name) }}</strong>
                                                </label>
                                            </div>

                                            <div class="permission-item">
                                                @foreach($groupedPermissions[$group->group_name] as $permission)
                                                    <div class="form-check">
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
                                    </div>
                                @endforeach
                            </div>

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
<script>
$(document).ready(function() {
    // Select all permissions
    $('#selectAll').click(function() {
        $('.permission-checkbox').prop('checked', $(this).prop('checked'));
        $('.group-checkbox').prop('checked', $(this).prop('checked'));
    });

    // Group checkbox functionality
    $('.group-checkbox').click(function() {
        var group = $(this).data('group');
        $(`.permission-checkbox.${group}`).prop('checked', $(this).prop('checked'));
    });

    // Individual permission checkbox functionality
    $('.permission-checkbox').click(function() {
        var group = $(this).attr('class').split(' ').find(c => c !== 'form-check-input' && c !== 'permission-checkbox');
        var allChecked = $(`.permission-checkbox.${group}:checked`).length === $(`.permission-checkbox.${group}`).length;
        $(`#group-${group}`).prop('checked', allChecked);

        // Update select all checkbox
        var allGroupsChecked = $('.group-checkbox:checked').length === $('.group-checkbox').length;
        $('#selectAll').prop('checked', allGroupsChecked);
    });

    // Initialize group checkboxes
    $('.group-checkbox').each(function() {
        var group = $(this).data('group');
        var allChecked = $(`.permission-checkbox.${group}:checked`).length === $(`.permission-checkbox.${group}`).length;
        $(this).prop('checked', allChecked);
    });

    // Initialize select all checkbox
    var allGroupsChecked = $('.group-checkbox:checked').length === $('.group-checkbox').length;
    $('#selectAll').prop('checked', allGroupsChecked);
});
</script>
@endpush
@endsection
