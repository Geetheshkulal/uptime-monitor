@extends('dashboard')
@section('content')

@push('styles')
    <style>
        .nav-item{
            margin-right: 10px;
        }
    </style>
@endpush
<div class="container-fluid">
    <div class="row d-flex flex-column-reverse flex-lg-row">
        <div class="col-lg-8 col-md-12">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">Profile Management</h1>
            </div>

            <div x-data="{ tab: 'profile' }">
                <!-- Tabs -->
                <ul class="nav nav-tabs mb-4 ">
                    <li class="nav-item btn-primary">
                        <button class="nav-link btn-primary" :class="{ 'active': tab === 'profile' }" @click="tab = 'profile'">Profile</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link btn-primary" :class="{ 'active': tab === 'password' }" @click="tab = 'password'">Password</button>
                    </li>
                    @if(!auth()->user()->hasRole('superadmin'))
                        <li class="nav-item">
                            <button class="nav-link btn-primary text-danger" :class="{ 'active': tab === 'delete' }" @click="tab = 'delete'">Delete</button>
                        </li>
                    @endif
                </ul>

                <!-- Sections -->
                <div x-show="tab === 'profile'">
                    @include('profile.partials.update-profile-information-form')
                </div>
                <div x-show="tab === 'password'" class="mt-4">
                    @include('profile.partials.update-password-form')
                </div>
                @if(!auth()->user()->hasRole('superadmin'))
                    <div x-show="tab === 'delete'" class="mt-4">
                        @include('profile.partials.delete-user-form')
                    </div>
                @endif
            </div>
        </div>
        
        <div class="col-lg-4 col-md-12 mb-4">
            <div class="card shadow p-3">
                <h1 class="h5 mb-0 text-gray-800">Last login IP address</h1>
                <br>
                <h4 class="h5 mb-0 text-gray-900">{{$user->last_login_ip}}</h4>
            </div>
        </div>
    </div>
</div>
@push('scripts')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endpush
@endsection
