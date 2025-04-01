@extends('dashboard')
@section('content')
  
<div class="row">

@if (auth()->user()->status === 'paid')
<div class="col-xl-3 mx-4 col-md-6 mb-4">
    <div class="card border-left-primary shadow h-100 py-2">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-2">
                        current plans</div>
                    <div class="h6 mb-3 font-weight-bold text-gray-600" id="totalMonitors">Status : {{$subscription->status}}</div>
                    <div class="h6 mb-3 font-weight-bold text-gray-600" id="totalMonitors">Amount : {{$subscription->amount}}</div>
                    <div class="h6 mb-0 font-weight-bold text-gray-600" id="totalMonitors">Payment Type : {{ strtoupper($subscription->payment_type) }}</div>  
                </div>
            </div>
        </div>
    </div>
</div>
@elseif(auth()->user()->status === 'free')
<div class="col-xl-3 mx-4 col-md-6 mb-4">
    <div class="card border-left-primary shadow h-100 py-2">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-2">
                        You don't have any current plan</div>
                        <a class="rounded btn btn-primary mt-3 full" href="{{route('premium.page')}}">Upgrade plan</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endif


@if (auth()->user()->status === 'paid')

<div class="col-xl-3 mx-4 col-md-6 mb-4">
    <div class="card border-left-primary shadow h-100 py-2">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                        Start Date</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800" id="totalMonitors"> {{ \Carbon\Carbon::parse($subscription->start_date)->format('d F Y') }}</div>
                    <div class="text-xs font-weight-bold text-primary text-uppercase mt-3 mb-1">
                        End Date</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800" id="totalMonitors"> {{ \Carbon\Carbon::parse($subscription->end_date)->format('d F Y') }}</div>
                </div>
                <div class="col-auto">
                    <i class="fas fa-calendar-alt fa-2x text-gray-300"></i>
                </div>
            </div>
        </div>
    </div>
</div>

@endif

</div>

    <!-- Scripts -->
    @push('scripts')
    <script src="{{ asset('frontend/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/sb-admin-2.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    
   
    @endpush
@endsection