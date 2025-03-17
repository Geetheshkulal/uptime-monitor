@extends('dashboard')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
            <div class="card shadow-lg border-0 rounded">
                <div class="card-body p-4">
                    <h2 class="text-center fw-bold mb-4">
                        <i class="fas fa-lock text-primary"></i> SSL Certificate Expiry Check
                    </h2>

                    @if(session('success'))
                        <div class="alert alert-success d-flex align-items-center">
                            <i class="fas fa-check-circle me-2"></i> 
                            <span>{{ session('success') }}</span>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger d-flex align-items-center">
                            <i class="fas fa-exclamation-triangle me-2"></i> 
                            <span>{{ session('error') }}</span>
                        </div>
                    @endif

                    <form action="{{ route('ssl.check.domain') }}" method="POST" class="mt-3">
                        @csrf
                        <div class="mb-3">
                            <label for="domain" class="form-label fw-semibold">
                                <i class="fas fa-globe"></i> Enter Website URL:
                            </label>
                            <input type="url" id="domain" name="domain" class="form-control form-control-lg"
                                placeholder="https://example.com" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg w-100">
                            <i class="fas fa-search"></i> Check SSL Expiry
                        </button>
                    </form>

                    @if(session('ssl_details'))
                        <div class="card mt-4 shadow-sm border-0">
                            <div class="card-body">
                                <h5 class="text-center fw-bold mb-3">
                                    <i class="fas fa-info-circle text-info"></i> SSL Certificate Details
                                </h5>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item">
                                        <strong>🌍 Domain:</strong> {{ session('ssl_details')['domain'] }}
                                    </li>
                                    <li class="list-group-item">
                                        <strong>🏆 Issuer:</strong> {{ session('ssl_details')['issuer'] }}
                                    </li>
                                    <li class="list-group-item">
                                        <strong>📅 Valid From:</strong> {{ session('ssl_details')['valid_from'] }}
                                    </li>
                                    <li class="list-group-item">
                                        <strong>⏳ Valid To:</strong> 
                                        <span class="badge 
                                            {{ session('ssl_details')['days_remaining'] < 10 ? 'bg-danger' : 'bg-success' }}">
                                            {{ session('ssl_details')['valid_to'] }} 
                                            ({{ session('ssl_details')['days_remaining'] }} days left)
                                        </span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
