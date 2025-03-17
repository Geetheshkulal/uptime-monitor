{{-- <x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

        </div>
    </div>
</x-app-layout> --}}





@extends('dashboard')
@section('content')
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <div class="container-fluid">
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800 text-center">Profile Management</h1>
                </div>

                <div class="d-flex flex-column align-items-center">
                    
                    <!-- Profile Update Card -->
                    <div class="col-lg-6 mb-4">
                        <div class="card shadow-lg rounded-4 border-light">
                            <div class="card-header py-3 bg-primary text-white rounded-top text-uppercase fw-bold">
                                <h6 class="m-0">Update Profile Information</h6>
                            </div>
                            <div class="card-body p-5 bg-light-subtle">
                                @include('profile.partials.update-profile-information-form')
                            </div>
                        </div>
                    </div>

                    <!-- Password Update Card -->
                    <div class="col-lg-6 mb-4">
                        <div class="card shadow-lg rounded-4 border-light">
                            <div class="card-header py-3 bg-primary text-white rounded-top text-uppercase fw-bold">
                                <h6 class="m-0">Update Password</h6>
                            </div>
                            <div class="card-body p-5 bg-light-subtle">
                                @include('profile.partials.update-password-form')
                            </div>
                        </div>
                    </div>

                    <!-- Delete Account Card -->
                    <div class="col-lg-6 mb-4">
                        <div class="card shadow-lg rounded-4 border-light">
                            <div class="card-header py-3 bg-danger text-white rounded-top text-uppercase fw-bold">
                                <h6 class="m-0">Delete Account</h6>
                            </div>
                            <div class="card-body p-5 bg-light-subtle">
                                @include('profile.partials.delete-user-form')
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
@endsection

