@extends('dashboard')
@section('content')

<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
{{-- 
<div class="row">

<div class="col">
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Profile Management</h1>
    </div>

    <div x-data="{ tab: 'profile' }">
        <!-- Tabs -->
        <div class="nav nav-tabs mb-4">
            <button class="nav-link" :class="{ 'active': tab === 'profile' }" @click="tab = 'profile'">Profile</button>
            <button class="nav-link" :class="{ 'active': tab === 'password' }" @click="tab = 'password'">Password</button>
            <button class="nav-link text-danger" :class="{ 'active': tab === 'delete' }" @click="tab = 'delete'">Delete</button>
        </div>

        <!-- Sections -->
        <div x-show="tab === 'profile'">
            @include('profile.partials.update-profile-information-form')
        </div>
        <div x-show="tab === 'password'" class="mt-4">
            @include('profile.partials.update-password-form')
        </div>
        <div x-show="tab === 'delete'" class="mt-4">
            @include('profile.partials.delete-user-form')
        </div>
    </div>
</div>

</div>

<div class="col">
    <div class="d-sm-flex">
        <h1 class="h3 mb-0 text-gray-800">Current IP</h1>
    </div>
</div>

</div> --}}

<div class="container-fluid">
    <div class="row d-flex flex-column-reverse flex-lg-row">
        <div class="col-lg-8 col-md-12">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">Profile Management</h1>
            </div>

            <div x-data="{ tab: 'profile' }">
                <!-- Tabs -->
                <ul class="nav nav-tabs mb-4">
                    <li class="nav-item">
                        <button class="nav-link" :class="{ 'active': tab === 'profile' }" @click="tab = 'profile'">Profile</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" :class="{ 'active': tab === 'password' }" @click="tab = 'password'">Password</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link text-danger" :class="{ 'active': tab === 'delete' }" @click="tab = 'delete'">Delete</button>
                    </li>
                </ul>

                <!-- Sections -->
                <div x-show="tab === 'profile'">
                    @include('profile.partials.update-profile-information-form')
                </div>
                <div x-show="tab === 'password'" class="mt-4">
                    @include('profile.partials.update-password-form')
                </div>
                <div x-show="tab === 'delete'" class="mt-4">
                    @include('profile.partials.delete-user-form')
                </div>
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

@endsection
