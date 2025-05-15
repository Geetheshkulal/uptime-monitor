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
            <div class="col-md-12 ">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="card-title mb-0">
                                Modify Permissions for User: <strong>{{ $user->name }}</strong>
                            </h4>
                            <a href="{{ route('display.sub.users') }}" class="btn btn-primary">
                                <i class="fas fa-arrow-left"></i> Back
                            </a>
                        </div>

                        <form method="POST" action="{{ route('update.sub.user.permissions', $user->id) }}">
                            @csrf

                            <!-- Select All -->
                            <div class="form-check mb-3">
                                <input type="checkbox" class="form-check-input" id="selectAll">
                                <label class="form-check-label" for="selectAll">
                                    <strong>Select All Permissions</strong>
                                </label>
                            </div>

                            <!-- Permission Groups -->
                            @foreach($permission_groups as $group)
                                <div class="permission-group">
                                    <!-- Group checkbox -->
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input group-checkbox"
                                            id="group-{{ $group->group_name }}"
                                            data-group="{{ $group->group_name }}">
                                        <label class="form-check-label" for="group-{{ $group->group_name }}">
                                            <strong>{{ ucfirst($group->group_name) }}</strong>
                                        </label>
                                    </div>

                                    <!-- Individual permissions -->
                                    <div>
                                        @foreach($groupedPermissions[$group->group_name] as $permission)
                                            <div class="form-check form-check-inline">
                                                <input type="checkbox" class="form-check-input permission-checkbox {{ $group->group_name }}"
                                                    name="permission[]"
                                                    id="permission-{{ $permission->id }}"
                                                    value="{{ $permission->name}}"
                                                    {{ $user->hasDirectPermission($permission->name) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="permission-{{ $permission->id }}">
                                                    {{ $permission->name }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach

                            <!-- Submit -->
                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary">Update Permissions</button>
                                <a href="{{ route('display.sub.users') }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
{{-- Same jQuery select all/group logic from your original --}}
<script>
    $(document).ready(function() {
        $('#selectAll').click(function() {
            const checked = $(this).prop('checked');
            $('.permission-checkbox').prop('checked', checked);
            $('.group-checkbox').prop('checked', checked);
        });

        $('.group-checkbox').click(function() {
            var group = $(this).data('group');
            $('.permission-checkbox.' + group).prop('checked', $(this).prop('checked'));
            $('#selectAll').prop('checked', $('.group-checkbox:checked').length === $('.group-checkbox').length);
        });

        $('.permission-checkbox').click(function() {
            var classes = $(this).attr('class').split(' ');
            var group = classes.find(c => c !== 'form-check-input' && c !== 'permission-checkbox');

            var total = $('.permission-checkbox.' + group).length;
            var checked = $('.permission-checkbox.' + group + ':checked').length;

            $('#group-' + group).prop('checked', total === checked);

            var allGroupsChecked = $('.group-checkbox:checked').length === $('.group-checkbox').length;
            $('#selectAll').prop('checked', allGroupsChecked);
        });

        $('.group-checkbox').each(function() {
            var group = $(this).data('group');
            var total = $('.permission-checkbox.' + group).length;
            var checked = $('.permission-checkbox.' + group + ':checked').length;
            $(this).prop('checked', total === checked);
        });

        var allGroupsChecked = $('.group-checkbox:checked').length === $('.group-checkbox').length;
        $('#selectAll').prop('checked', allGroupsChecked);
    });
</script>
@endpush

@endsection
