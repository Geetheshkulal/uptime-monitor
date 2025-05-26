@extends('dashboard')
@section('content')

@push('styles')
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css"></head>
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

            <div x-data="{
                tab: localStorage.getItem('profile_tab') || 'profile',
                setTab(value) {
                    this.tab = value;
                    localStorage.setItem('profile_tab', value);
                }
            }">
                <!-- Tabs -->
                <ul class="nav nav-tabs mb-4 ">
                    <!-- Profile Tab -->
                    <li class="nav-item btn-primary">
                        <button class="nav-link btn-primary"
                                :class="{ 'active': tab === 'profile' }"
                                @click="setTab('profile')">Profile</button>
                    </li>

                    <!-- Password Tab -->
                    <li class="nav-item">
                        <button class="nav-link btn-primary"
                                :class="{ 'active': tab === 'password' }"
                                @click="setTab('password')">Password</button>
                    </li>

                    <!-- Billing Tab -->
                    @hasrole('user')
                    <li class="nav-item">
                        <button class="nav-link btn-primary"
                                :class="{ 'active': tab === 'billing' }"
                                @click="setTab('billing')">Billing Details</button>
                    </li>
                    @endhasrole

                </ul>

                <!-- Sections -->
                <div x-show="tab === 'profile'">
                    @include('profile.partials.update-profile-information-form')
                </div>
                <div x-show="tab === 'password'" class="mt-4">
                    @include('profile.partials.update-password-form')
                </div>
                <div  x-show="tab === 'billing'" class="mt-4">
                    @include('profile.partials.billing-details-form')   
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
@push('scripts')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @if(session('success'))
        <script>
            toastr.success("{{ session('success') }}");
        </script>
    @endif

    @if(session('error'))
        <script>
            toastr.error("{{ session('error') }}");
        </script>
    @endif
@endpush
@endsection
