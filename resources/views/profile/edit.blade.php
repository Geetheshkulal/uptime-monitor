@extends('dashboard')
@section('content')

<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

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

@endsection
