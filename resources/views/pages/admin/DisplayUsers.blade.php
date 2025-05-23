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
        background-color: #4e73df !important;
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
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email">Email Address*</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password">Password*</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                                <span class="fas fa-eye password-toggle" id="togglePassword"></span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="phone">Phone Number</label>
                                <input type="text" class="form-control" id="phone" name="phone">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="role">User Role*</label>
                                <select class="form-control" id="role" name="role" required>
                                    <option value="">Select Role</option>
                                    @foreach($roles as $role)
                                            @if($role->name != 'user')
                                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                                            @endif
                                        @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="status">Account Status*</label>
                                <select class="form-control" id="status" name="status" required>
                                    <option value="free">Free</option>
                                    <option value="paid">Paid</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="premium_end_date">Premium End Date</label>
                                <input type="date" class="form-control" id="premium_end_date" name="premium_end_date">
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

// Password toggle (if element exists)
document.addEventListener('DOMContentLoaded', function () {
    const togglePassword = document.getElementById('togglePassword');
    if (togglePassword) {
        togglePassword.addEventListener('click', function () {
            const passwordInput = document.getElementById('password');
            if (passwordInput) {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                this.classList.toggle('fa-eye');
                this.classList.toggle('fa-eye-slash');
            }
        });
    }
});
</script>

@if(session('success'))
<script>
    toastr.success("{{ session('success') }}");
</script>
@endif
@endpush
@endsection
