@extends('dashboard')

@section('content')
@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<style>
    * {
        border-radius: 0 !important;
    }
    .form-group {
        margin-bottom: 1rem;
        position: relative;
    }
    .form-control {
        display: block;
        width: 100%;
        padding: 0.375rem 0.75rem;
        font-size: 1rem;
        line-height: 1.5;
        color: #495057;
        background-color: #fff;
        background-clip: padding-box;
        border: 1px solid #ced4da;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }
    .password-toggle {
        position: absolute;
        right: 10px;
        top: 40px;
        cursor: pointer;
        color: #6c757d;
    }
    label {
        display: inline-block;
        margin-bottom: 0.5rem;
    }
    .nav-item {
        margin-right: 10px;
    }
    .nav-link.active {
        background-color: #06357f !important;
        color: white !important;
    }
    .dataTables_filter input {
        padding: 5px 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
        margin-bottom: 10px;
    }
    .dataTables_wrapper .dataTables_filter {
        float: right;
        text-align: right;
        margin-bottom: 15px;
    }
    .invalid-feedback {
        display: none;
        width: 100%;
        margin-top: 0.25rem;
        font-size: 80%;
        color: #dc3545;
    }
    .is-invalid {
        border-color: #dc3545;
    }
</style>
@endpush

<div id="content-wrapper" class="d-flex flex-column">
    <div id="content">
        <div class="container-fluid">
            <div x-data="tabHandler()" x-init="initActiveTab()">
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800" 
                        x-text="activeTab === 'customers' ? 'Customers (Total: {{ $customerCount }})' : 'Users (Total: {{ $userCount }})'">
                    </h1>
                    <div x-show="activeTab === 'users'">
                        @can('add.user')
                            <button type="button" class="d-sm-inline-block btn btn-primary shadow-sm mt-4 mt-md-0" data-toggle="modal" data-target="#addUserModal">
                                <i class="fas fa-plus fa-sm text-white-50 mr-1"></i> Add User
                            </button>
                        @endcan
                    </div>
                </div>

                <ul class="nav nav-tabs mb-4">
                    <li class="nav-item">
                        <button class="nav-link btn-primary" :class="{ 'active': activeTab === 'customers' }" @click="activeTab = 'customers'">
                            Customers
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link btn-primary" :class="{ 'active': activeTab === 'users' }" @click="activeTab = 'users'">
                            Users
                        </button>
                    </li>
                </ul>

                <div x-show="activeTab === 'customers'">
                    @include('partials.customers-table', ['users' => $users])
                </div>

                <div x-show="activeTab === 'users'">
                    @include('partials.users-table', ['users' => $users])
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addUserModalLabel">Add New User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
             <form id="addUserForm" action="{{ route('add.user') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Full Name*</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email">Email Address*</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password">Password*</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password"  required>
                                <span class="fas fa-eye password-toggle" id="togglePassword"></span>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="phone">Phone Number</label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="role">User Role*</label>
                                <select class="form-control @error('role') is-invalid @enderror" id="role" name="role">
                                    <option value="">Select Role</option>
                                    @foreach($roles as $role)
                                        @if($role->name != 'user')
                                            <option value="{{ $role->id }}" {{ old('role') == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save User</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script>
function tabHandler() {
    return {
        activeTab: 'customers',
        customersInitialized: false,
        usersInitialized: false,
        initActiveTab() {
            this.$watch('activeTab', (value) => {
                if (value === 'customers' && !this.customersInitialized) {
                    $('#customersTable').DataTable({
                        paging: true,
                        searching: true,
                        ordering: true,
                        info: true,
                        responsive: true,
                        order: [[0, 'asc']],
                        columnDefs: [{ orderable: false, targets: -1 }]
                    });
                    this.customersInitialized = true;
                }
                if (value === 'users' && !this.usersInitialized) {
                    $('#usersTable').DataTable({
                        paging: true,
                        searching: true,
                        ordering: true,
                        info: true,
                        responsive: true,
                        order: [[0, 'asc']],
                        columnDefs: [{ orderable: false, targets: -1 }]
                    });
                    this.usersInitialized = true;
                }
            });

            this.$nextTick(() => {
                if (this.activeTab === 'customers') {
                    $('#customersTable').DataTable({
                        paging: true,
                        searching: true,
                        ordering: true,
                        info: true,
                        responsive: true,
                        order: [[0, 'asc']],
                        columnDefs: [{ orderable: false, targets: -1 }]
                    });
                    this.customersInitialized = true;
                }
            });
        }
    };
}

$(document).ready(function() {
    // Password toggle
    $('#togglePassword').on('click', function() {
        const passwordInput = $('#password');
        const type = passwordInput.attr('type') === 'password' ? 'text' : 'password';
        passwordInput.attr('type', type);
        $(this).toggleClass('fa-eye fa-eye-slash');
    });

    // Form validation
    $('#addUserForm').on('submit', function(e) {
        if (!validateForm()) {
            e.preventDefault();
        }
    });

    // Validate on input change
    $('#addUserForm input, #addUserForm select').on('change', function() {
        validateField($(this));
    });

    // Validate after typing stops for text inputs
    $('#addUserForm input[type="text"], #addUserForm input[type="email"], #addUserForm input[type="password"]').on('input', function() {
        const input = $(this);
        clearTimeout(input.data('typingTimer'));
        input.data('typingTimer', setTimeout(function() {
            validateField(input);
        }, 500));
    });

    function validateForm() {
        let isValid = true;
        
        // Validate each field
        isValid = validateField($('#name')) && isValid;
        isValid = validateField($('#email')) && isValid;
        isValid = validateField($('#password')) && isValid;
        
        // Phone is optional, only validate if value exists
        if ($('#phone').val().trim()) {
            isValid = validateField($('#phone')) && isValid;
        }
        
        isValid = validateField($('#role')) && isValid;
        
        return isValid;
    }

    function validateField(input) {
        let isValid = true;
        let errorMessage = '';
        const inputElement = input[0] || input; // Handle both jQuery object and DOM element
        
        // Clear previous error
        $(input).removeClass('is-invalid');
        let errorElement = $(input).next('.invalid-feedback');
        
        // For password field which has the eye icon
        if ($(input).attr('id') === 'password') {
            errorElement = $(input).nextAll('.invalid-feedback').first();
        }
        
        // Field-specific validation
        switch($(input).attr('id')) {
            case 'name':
                if (!$(input).val().trim()) {
                    errorMessage = 'Name is required';
                    isValid = false;
                }
                break;
                
            case 'email':
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!$(input).val().trim()) {
                    errorMessage = 'Email is required';
                    isValid = false;
                } else if (!emailRegex.test($(input).val())) {
                    errorMessage = 'Please enter a valid email address';
                    isValid = false;
                }
                break;
                
            case 'password':
                const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
                if (!$(input).val().trim()) {
                    errorMessage = 'Password is required';
                    isValid = false;
                } else if (!passwordRegex.test($(input).val())) {
                    errorMessage = 'Password must contain at least one uppercase, one lowercase, one number and one special character';
                    isValid = false;
                }
                break;
                
            case 'phone':
                if ($(input).val().trim() && !/^[0-9]{10}$/.test($(input).val())) {
                    errorMessage = 'Phone number must be exactly 10 digits';
                    isValid = false;
                }
                break;
                
            case 'role':
                if (!$(input).val()) {
                    errorMessage = 'Role is required';
                    isValid = false;
                }
                break;
        }
        
        if (!isValid) {
            $(input).addClass('is-invalid');
            if (errorElement.length) {
                errorElement.text(errorMessage).show();
            } else {
                const errorDiv = $('<div>').addClass('invalid-feedback').text(errorMessage).css('display', 'block');
                
                if ($(input).attr('id') === 'password') {
                    $(input).parent().find('.password-toggle').after(errorDiv);
                } else {
                    $(input).after(errorDiv);
                }
            }
        } else {
            errorElement.hide();
        }
        
        return isValid;
    }
});

@if(session('success'))
$(document).ready(function() {
    toastr.success("{{ session('success') }}");
});
@endif
</script>
@endpush
@endsection