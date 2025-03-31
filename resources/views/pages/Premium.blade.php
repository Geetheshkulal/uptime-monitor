




























































































































































































































































































































<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upgrade to Premium</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        .ribbon {
            position: absolute;
            top: 10px;
            right: -20px;
            transform: rotate(45deg);
            width: 120px;
            font-size: 0.8rem;
            font-weight: bold;
            padding: 5px 0;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }
        .premium-highlight {
            border: 3px solid gold !important;
            box-shadow: 0px 4px 20px rgba(255, 215, 0, 0.6);
            animation: glow 1.5s infinite alternate;
        }
        @keyframes glow {
            0% { box-shadow: 0px 4px 15px rgba(255, 215, 0, 0.3); }
            100% { box-shadow: 0px 4px 30px rgba(255, 215, 0, 0.7); }
        }
    </style>
</head>
<body>
    <header class="bg-dark py-3">
        <div class="container text-white text-center">
            <h1>Upgrade Your Plan</h1>
        </div>
    </header>
    
    <section id="upgrade" class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold mb-3 text-warning">Upgrade to Premium</h2>
                <p class="text-muted mx-auto" style="max-width: 600px;">
                    Unlock advanced monitoring features and take full control of your website’s uptime.
                </p>
            </div>
            
            <div class="row justify-content-center g-4">
                <!-- Basic Plan (Current Plan) -->
                <div class="col-lg-4">
                    <div class="card h-100 border-secondary shadow-sm position-relative">
                        <div class="ribbon bg-secondary text-white text-center">Your Current Plan</div>
                        <div class="card-body p-4">
                            <h3 class="fw-bold mb-2">Basic</h3>
                            <div class="text-secondary mb-4">
                                <span class="display-6 fw-bold">₹0</span>
                            </div>
                            <ul class="list-unstyled mb-4">
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i> 10 websites</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i> 5-minute checks</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Email alerts</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i> 1-day history</li>
                            </ul>
                            <button class="btn btn-secondary d-block" disabled>Current Plan</button>
                        </div>
                    </div>
                </div>
                
                <!-- Premium Plan (Highlighted) -->
                <div class="col-lg-4">
                    <div class="card h-100 border-warning shadow-lg premium-highlight">
                        <div class="card-body p-4">
                            <h3 class="fw-bold mb-2 text-warning">Premium</h3>
                            <div class="text-warning mb-4">
                                <span class="display-6 fw-bold">₹399</span>
                                <span class="text-muted">/month</span>
                            </div>
                            <ul class="list-unstyled mb-4">
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Unlimited websites</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i> 30-second checks</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i> All alert channels</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i> SSL expiry check</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Custom integrations</li>
                            </ul>
                            <form action="{{ route('store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="name" value="{{ auth()->user()->name }}">
                                <input type="hidden" name="email" value="{{ auth()->user()->email }}">
                                <input type="hidden" name="mobile" value="{{ auth()->user()->phone }}">
                                <input type="hidden" name="amount" value="399">
                                <button type="submit" class="btn btn-warning d-block fw-bold">Upgrade Now</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <footer class="bg-dark text-white text-center py-3">
        <p class="mb-0">&copy; 2025 Uptime Monitor. All rights reserved.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>