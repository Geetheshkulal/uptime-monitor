@extends('dashboard')

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="alert alert-warning border border-warning rounded shadow-sm">
                    <h4 class="alert-heading">Premium Plan Not Active</h4>
                    <p>
                        Your account currently doesn't have access to premium features.
                        Please contact your parent user to subscribe to the premium plan.
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
