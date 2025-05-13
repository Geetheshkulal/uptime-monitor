@extends('dashboard')
@section('content')

<style>
    * {
    border-radius: 0 !important;
}
</style>
<div class="page-content">
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0">Edit Permission</h4>
                    <a href="{{ route('display.permissions') }}" class="btn btn-primary">
                        <i class="fas fa-arrow-left mr-1"></i> Back
                    </a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6 mx-auto">
                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="{{ route('update.permission', $permission->id) }}">
                            @csrf
                            @method('PUT')
                            
                            <div class="mb-4">
                                <label for="name" class="form-label">Permission Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name', $permission->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="group_name" class="form-label">Group Name</label>
                                <select class="form-select @error('group_name') is-invalid @enderror" 
                                        id="group_name" name="group_name" required>
                                    <option value="user" {{ $permission->group_name == 'user' ? 'selected' : '' }}>User</option>
                                    <option value="role" {{ $permission->group_name == 'role' ? 'selected' : '' }}>Role</option>
                                    <option value="permission" {{ $permission->group_name == 'permission' ? 'selected' : '' }}>Permission</option>
                                    <option value="monitor" {{ $permission->group_name == 'monitor' ? 'selected' : '' }}>Monitor</option>
                                </select>
                                @error('group_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="text-end mt-4">
                                <button type="submit" class="btn btn-primary px-4 py-2">
                                    <i class="fas fa-save mr-1"></i> Update Permission
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection