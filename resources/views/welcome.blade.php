<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>CheckMySite - Website Monitoring Service</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  
  <!-- PWA  -->
  <link rel="manifest" href="{{ asset('manifest.json') }}">
  <meta name="theme-color" content="#6777ef">
  <link rel="apple-touch-icon" href="{{ asset('mainlogo.png') }}">

   <!-- Toastr CSS -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
   
   <!-- Animate.css -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
   
   <!-- Google Fonts -->
   <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>

<style>
  :root {
    --primary-color: #5d75ca;
    --secondary-color: #4e73df;
    --accent-color: #4cc9f0;
    --dark-color: #1a1a2e;
    --light-color: #f8f9fa;
  }
  
  body {
    font-family: 'Poppins', sans-serif;
    overflow-x: hidden;
  }
  
  .gradient-bg {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
  }
  
  .hero-section {
    padding: 180px 0 100px;
    position: relative;
    overflow: hidden;
  }
  
  .hero-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxMDAlIiBoZWlnaHQ9IjEwMCUiPjxkZWZzPjxwYXR0ZXJuIGlkPSJwYXR0ZXJuIiB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHBhdHRlcm5Vbml0cz0idXNlclNwYWNlT25Vc2UiIHBhdHRlcm5UcmFuc2Zvcm09InJvdGF0ZSg0NSkiPjxyZWN0IHdpZHRoPSIyMCIgaGVpZ2h0PSIyMCIgZmlsbD0icmdiYSgyNTUsMjU1LDI1NSwwLjAzKSIvPjwvcGF0dGVybj48L2RlZnM+PHJlY3QgZmlsbD0idXJsKCNwYXR0ZXJuKSIgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIvPjwvc3ZnPg==');
    z-index: 1;
  }
  
  .hero-content {
    position: relative;
    z-index: 2;
  }
  
  .pulse-animation {
    animation: pulse 2s infinite;
  }
  
  @keyframes pulse {
    0% {
      transform: scale(1);
      opacity: 1;
    }
    50% {
      transform: scale(1.05);
      opacity: 0.8;
    }
    100% {
      transform: scale(1);
      opacity: 1;
    }
  }
  
  .feature-icon {
    width: 80px;
    height: 80px;
    margin: 0 auto 1.5rem auto;
    border-radius: 20px;
    display: flex;
    align-items: center ;
    justify-content: center;
    margin-bottom: 1.5rem;
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
    color: white;
    font-size: 2rem;
    box-shadow: #4361ee;
    transition: all 0.3s ease;
  }
  
  .feature-card:hover .feature-icon {
    transform: translateY(-5px);
    box-shadow: #4361ee;
    align-items: center ;
    justify-content: center ;
  }
  
  .testimonial-avatar {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 3px solid var(--primary-color);
  }
  
  .popular-plan {
    position: relative;
    border: 2px solid var(--primary-color);
    border-radius: 15px;
    transform: translateY(-10px);
    box-shadow: 0 20px 40px rgba(67, 97, 238, 0.2);
  }
  
  .popular-badge {
    position: absolute;
    top: -15px;
    left: 50%;
    transform: translateX(-50%);
    background: var(--accent-color);
    color: white;
    padding: 5px 15px;
    border-radius: 20px;
    font-weight: bold;
    font-size: 0.8rem;
    box-shadow: 0 5px 15px rgba(76, 201, 240, 0.4);
  }
  
  .step-circle {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
    font-size: 1.8rem;
    font-weight: bold;
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
    color: white;
    box-shadow: 0 10px 20px rgba(67, 97, 238, 0.3);
  }
  
  .btn {
    border-radius: 50px !important;
    padding: 12px 28px !important;
    font-weight: 600 !important;
    transition: all 0.3s ease !important;
    letter-spacing: 0.5px;
  }
  
  .btn-primary {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
    border: none;
    box-shadow: 0 5px 15px rgba(67, 97, 238, 0.4);
  }
  
  .btn-primary:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(67, 97, 238, 0.6);
  }
  
  .btn-light {
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
  }
  
  .btn-light:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
  }
  
  .card {
    border: none;
    border-radius: 15px;
    overflow: hidden;
    transition: all 0.3s ease;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
  }
  
  .card:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
  }
  
  .navbar {
    padding: 15px 0;
    transition: all 0.3s ease;
  }
  
  .navbar.scrolled {
    background-color: white !important;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
    padding: 10px 0;
  }
  
  .section-title {
    position: relative;
    display: inline-block;
    margin-bottom: 2rem;
  }

  
  .section-title::after {
    content: '';
    position: absolute;
    bottom: -10px;
    left: 50%;
    transform: translateX(-50%);
    width: 60px;
    height: 4px;
    background: linear-gradient(90deg, var(--primary-color) 0%, var(--accent-color) 100%);
    border-radius: 2px;
  }
  
  .floating-shapes {
    position: absolute;
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
    overflow: hidden;
    z-index: 1;
  }
  
  .shape {
    position: absolute;
    opacity: 0.1;
    border-radius: 50%;
  }
  
  .shape-1 {
    width: 300px;
    height: 300px;
    background: var(--accent-color);
    top: -150px;
    right: -150px;
  }
  
  .shape-2 {
    width: 200px;
    height: 200px;
    background: var(--primary-color);
    bottom: -100px;
    left: -100px;
  }
  
  .highlight-text {
    background: linear-gradient(90deg, var(--primary-color) 0%, var(--accent-color) 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    font-weight: 700;
  }
  
  .monitor-illustration {
    position: relative;
    z-index: 2;
  }
  
  .monitor-screen {
    background: #1d1d34;
    border-radius: 10px;
    padding: 15px;
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);
    position: relative;
    overflow: hidden;
  }
  
  .screen-content {
    background: #16213e;
    border-radius: 5px;
    height: 100%;
    padding: 15px;
    position: relative;
    overflow: hidden;
  }
  
  .status-bar {
    display: flex;
    justify-content: space-between;
    margin-bottom: 15px;
  }
  
  .status-item {
    display: flex;
    align-items: center;
  }
  
  .status-indicator {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    margin-right: 8px;
  }
  
  .up {
    background: #4ade80;
  }
  
  .down {
    background: #f87171;
  }
  
  .warning {
    background: #fbbf24;
  }
  
  .chart-placeholder {
    height: 120px;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 5px;
    margin-top: 20px;
    position: relative;
    overflow: hidden;
  }
  
  .chart-line {
    position: absolute;
    height: 2px;
    background: rgba(255, 255, 255, 0.1);
    width: 100%;
  }
  
  .chart-data {
    position: absolute;
    height: 100%;
    width: 100%;
    display: flex;
    align-items: flex-end;
  }
  
  .chart-bar {
    flex: 1;
    background: var(--accent-color);
    margin: 0 2px;
    opacity: 0.7;
    animation: chartGrow 1.5s ease-out forwards;
    transform-origin: bottom;
  }
  
  @keyframes chartGrow {
    from { transform: scaleY(0); }
    to { transform: scaleY(1); }
  }
  
  .monitor-stand {
    width: 100px;
    height: 20px;
    background: #e2e8f0;
    margin: 0 auto;
    position: relative;
    top: -5px;
    border-radius: 0 0 5px 5px;
  }
  
  .monitor-base {
    width: 150px;
    height: 10px;
    background: #cbd5e1;
    margin: 0 auto;
    position: relative;
    top: -5px;
    border-radius: 0 0 5px 5px;
  }
  
  .floating {
    animation: floating 3s ease-in-out infinite;
  }
  
  @keyframes floating {
    0% { transform: translateY(0px); }
    50% { transform: translateY(-15px); }
    100% { transform: translateY(0px); }
  }
  
  .glow {
    filter: drop-shadow(0 0 10px rgba(67, 97, 238, 0.5));
  }
  
  .footer-links a {
    transition: all 0.3s ease;
    display: inline-block;
  }
  
  .footer-links a:hover {
    color: var(--accent-color) !important;
    transform: translateX(5px);
  }
  
  .social-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: rgba(255, 255, 255, 0.1);
    transition: all 0.3s ease;
  }
  
  .social-icon:hover {
    background: var(--accent-color);
    transform: translateY(-5px);
  }


  @keyframes fluctuate {
  0%, 100% {
    transform: scaleY(1);
  }
  25% {
    transform: scaleY(0.8);
  }
  50% {
    transform: scaleY(1.1);
  }
  75% {
    transform: scaleY(0.9);
  }
}

.chart-bar {
  animation: fluctuate 8s ease-in-out infinite;
  transform-origin: bottom;
}
.transition {
    transition: all 0.3s ease;
  }
  .hover-opacity-100:hover {
    opacity: 1 !important;
  }
  .hover-text-primary:hover {
    color: #0d6efd !important;
  }
  .text-muted {
    color: #6c757d !important;
  }
  .bg-opacity-10 {
    opacity: 0.1;
  }
  .fs-8 {
    font-size: 0.7rem;
  }

</style>


@if (session('error'))
<script>
    toastr.error("{{ session('error') }}");
</script>
@endif

<body>
   <!-- Navigation -->
  <nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top shadow-sm">
    <div class="container">
      <a class="navbar-brand text-primary fw-bold d-flex align-items-center" href="/">
        <i class="fas fa-heartbeat me-2"></i>CheckMySite
      </a>
      
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      
      <div class="collapse navbar-collapse" id="navbarNav">
        <!-- Left-aligned navigation items -->
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link" href="#features">Features</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#how-it-works">How It Works</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#pricing">Pricing</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ route('documentation.page') }}">User Docs</a>
          </li>
          {{-- <li class="nav-item">
            <a class="nav-link" href="{{ route('product.documentation') }}">Product Docs</a>
          </li> --}}
          <li class="nav-item">
            <a class="nav-link" href="{{ route('latest.page') }}">Updates</a>
          </li>
        </ul>
        
        <!-- Right-aligned items -->
        <div class="d-flex align-items-center">
          <!-- Feedback button -->
          <button class="btn btn-link text-decoration-none me-3" data-feedbear-button>
            <i class="far fa-comment-dots me-1"></i> Feedback
          </button>
          
          <!-- Auth buttons -->
          @if (Route::has('login'))
            @auth
              @hasrole('superadmin')
                <a href="{{ route('admin.dashboard') }}" class="btn btn-primary btn-sm">Admin Dashboard</a>
              @else
                <a href="{{ route('monitoring.dashboard') }}" class="btn btn-primary btn-sm">Dashboard</a>
              @endhasrole
            @else
            <a href="{{ route('login') }}" class="btn btn-primary btn-sm me-2">
              <i class="fas fa-sign-in-alt me-1"></i> Login
            </a>
            <a href="{{ route('register') }}" class="btn btn-primary btn-sm">
              <i class="fas fa-user-plus me-1"></i> Register
            </a>
            @endauth 
          @endif
        </div>
      </div>
    </div>
  </nav>

  <!-- Hero Section -->
  <section class="gradient-bg hero-section text-white">
    <div class="floating-shapes">
      <div class="shape shape-1"></div>
      <div class="shape shape-2"></div>
    </div>
    <div class="container">
      <div class="row align-items-center hero-content">
        <div class="col-lg-6 text-center text-lg-start mb-5 mb-lg-0">
          <h1 class="text-center text-gradient-primary display-2 fw-black mb-5 animate__animated animate__fadeInDown">
            <span class="d-block fw-semibold mb-2" style="letter-spacing: 1px;">NEVER MISS A WEBSITE</span>
            <span class="d-block fw-semibold mt-3 animate__animated animate__fadeInUp animate__delay-1s">
              <span class="highlight-word"> DOWN AGAIN</span>
            </span>
          </h1>
          {{-- <h1 class="display-4 fw-bold mb-4 animate__animated animate__fadeInDown">Never Miss a Website Downtime</span> Again</h1> --}}
          <p class="lead mb-4 animate__animated animate__fadeIn animate__delay-1s">Get instant alerts when your websites go down. Monitor performance, uptime, and response times with CheckMySite's powerful monitoring tools.</p>
          <div class="d-flex flex-column flex-sm-row justify-content-center justify-content-lg-start gap-3 animate__animated animate__fadeIn animate__delay-2s">
            @if(auth()->check())
                @hasrole('superadmin')
                  <a href="{{ route('admin.dashboard') }}" class="btn btn-light btn-lg fw-bold">
                      <i class="fas fa-play-circle me-2"></i> Start Monitoring
                  </a>
                @else
                  <a href="{{ route('monitoring.dashboard') }}" class="btn btn-light btn-lg fw-bold">
                      <i class="fas fa-play-circle me-2"></i> Start Monitoring
                  </a>
                @endhasrole
            @else
                <a href="{{ route('login') }}" class="btn btn-light btn-lg fw-bold">
                    <i class="fas fa-play-circle me-2"></i> Start Monitoring
                </a>
            @endif
          </div>
        </div>
        <div class="col-lg-6">
          <div class="monitor-illustration animate__animated animate__fadeInRight animate__delay-1s floating">
            <div class="monitor-screen">
              <div class="screen-content">
                <div class="status-bar">
                  <div class="status-item">
                    <div class="status-indicator up"></div>
                    <small>checkmysite.com</small>
                  </div>
                  <div class="status-item">
                    <div class="status-indicator up"></div>
                    <small>example.com</small>
                  </div>
                  <div class="status-item">
                    <div class="status-indicator down"></div>
                    <small>https://www.google.com</small>
                  </div>
                </div>
                <div class="chart-placeholder">
                  <div class="chart-line" style="top: 20%"></div>
                  <div class="chart-line" style="top: 40%"></div>
                  <div class="chart-line" style="top: 60%"></div>
                  <div class="chart-line" style="top: 80%"></div>
                  <div class="chart-data">
                    <div class="chart-bar" style="height: 90%; animation-delay: 0s"></div>
                    <div class="chart-bar" style="height: 85%; animation-delay: 0.5s"></div>
                    <div class="chart-bar" style="height: 78%; animation-delay: 1s"></div>
                    <div class="chart-bar" style="height: 92%; animation-delay: 1.5s"></div>
                    <div class="chart-bar" style="height: 65%; animation-delay: 0.2s"></div>
                    <div class="chart-bar" style="height: 45%; animation-delay: 0.7s"></div>
                    <div class="chart-bar" style="height: 30%; animation-delay: 1.2s"></div>
                    <div class="chart-bar" style="height: 75%; animation-delay: 0.9s"></div>
                  </div>
                </div>
              </div>
            </div>
            <div class="monitor-stand"></div>
            <div class="monitor-base"></div>
          </div>
        </div>
      </div>

    </div>
  </section>

  <!-- Features Section -->
  <section id="features" class="py-5 bg-light">
    <div class="container">
      <div class="text-center mb-5">
        <br>
        <h2 class="fw-bold mb-3 section-title animate__animated animate__fadeIn">Powerful Monitoring Features</h2>
        <p class="text-muted mx-auto" style="max-width: 600px;">Everything you need to keep your websites and services running smoothly.</p>
      </div>
      
      <div class="row g-4">
        <div class="col-md-6 col-lg-4">
          <div class="card h-100 border-light shadow-sm feature-card animate__animated animate__fadeInUp">
            <div class="card-body text-center p-4">
              <div class="feature-icon d-flex justify-content-center">
                <i class="fas fa-clock"></i>
              </div>
              <h3 class="h5 fw-bold">24/7 Monitoring</h3>
              <p class="text-muted">We check your websites every minute from multiple locations around the world.</p>
            </div>
          </div>
        </div>
        
        <div class="col-md-6 col-lg-4">
          <div class="card h-100 border-light shadow-sm feature-card animate__animated animate__fadeInUp animate__delay-1s">
            <div class="card-body text-center p-4">
              <div class="feature-icon">
                <i class="fas fa-bell"></i>
              </div>
              <h3 class="h5 fw-bold">Instant Alerts</h3>
              <p class="text-muted">Get notified via email, SMS, Slack, Discord, or webhook when your sites go down.</p>
            </div>
          </div>
        </div>
        
        <div class="col-md-6 col-lg-4">
          <div class="card h-100 border-light shadow-sm feature-card animate__animated animate__fadeInUp animate__delay-2s">
            <div class="card-body text-center p-4">
              <div class="feature-icon">
                <i class="fas fa-chart-line"></i>
              </div>
              <h3 class="h5 fw-bold">Detailed Analytics</h3>
              <p class="text-muted">Track response times, uptime percentage, and performance metrics over time.</p>
            </div>
          </div>
        </div>
        
        <div class="col-md-6 col-lg-4">
          <div class="card h-100 border-light shadow-sm feature-card animate__animated animate__fadeInUp">
            <div class="card-body text-center p-4">
              <div class="feature-icon">
                <i class="fas fa-exclamation-circle"></i>
              </div>
              <h3 class="h5 fw-bold">Incident Management</h3>
              <p class="text-muted">Track, analyze, and resolve service disruptions with real-time incident reporting.</p> <!-- Updated description -->
            </div>
          </div>
        </div>
        
        <div class="col-md-6 col-lg-4">
          <div class="card h-100 border-light shadow-sm feature-card animate__animated animate__fadeInUp animate__delay-1s">
            <div class="card-body text-center p-4">
              <div class="feature-icon">
                <i class="fas fa-shield-alt"></i>
              </div>
              <h3 class="h5 fw-bold">SSL expiry check</h3>
              <p class="text-muted">Get alerted before your SSL certificates expire to avoid security warnings.</p>
            </div>
          </div>
        </div>
        
        <div class="col-md-6 col-lg-4">
          <div class="card h-100 border-light shadow-sm feature-card animate__animated animate__fadeInUp animate__delay-2s">
            <div class="card-body text-center p-4">
              <div class="feature-icon">
                <i class="fas fa-mobile-alt"></i>
              </div>
              <h3 class="h5 fw-bold">PWA Support</h3>
              <p class="text-muted">Install our app on any device for offline access and native-like performance.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- How It Works Section -->
  <section id="how-it-works" class="py-5">
    <div class="container">
      <div class="text-center mb-5">
        <br><br>
        <h2 class="fw-bold mb-3 section-title animate__animated animate__fadeIn">How CheckMySite Works</h2>
        <p class="text-muted mx-auto animate__animated animate__fadeIn animate__delay-1s" style="max-width: 600px;">Simple setup, powerful results. Get started in less than 2 minutes.</p>
      </div>
      
      <div class="row">
        <div class="col-md-4 text-center mb-4 mb-md-0 animate__animated animate__fadeInLeft">
          <div class="step-circle">1</div>
          <h3 class="h5 fw-bold">Add Your Websites</h3>
          <p class="text-muted">Enter your website URLs and set your preferred check frequency.</p>
        </div>
        
        <div class="col-md-4 text-center mb-4 mb-md-0 animate__animated animate__fadeInUp">
          <div class="step-circle">2</div>
          <h3 class="h5 fw-bold">Configure Alerts</h3>
          <p class="text-muted">Choose how you want to be notified when issues are detected.</p>
        </div>
        
        <div class="col-md-4 text-center animate__animated animate__fadeInRight">
          <div class="step-circle">3</div>
          <h3 class="h5 fw-bold">Relax & Stay Informed</h3>
          <p class="text-muted">We'll monitor your sites 24/7 and alert you if anything goes wrong.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Pricing Section -->
  <section id="pricing" class="py-5 bg-light">
    <div class="container">
      <div class="text-center mb-5">
        <br>
        <h2 class="fw-bold mb-3 section-title animate__animated animate__fadeIn">Simple, Transparent Pricing</h2>
        <p class="text-muted mx-auto animate__animated animate__fadeIn animate__delay-1s" style="max-width: 600px;">Choose the plan that fits your needs. All plans include our core monitoring features.</p>
      </div>
      
      <div class="row justify-content-center g-4">
        <div class="col-lg-4 animate__animated animate__fadeInLeft">
          <div class="card h-100 border-light shadow-sm">
            <div class="card-body p-4 text-center">
              <h3 class="fw-bold mb-2">Basic</h3>
              <div class="text-primary mb-4">
                <span class="display-6 fw-bold">₹0</span>
                <span class="text-muted">/month</span>
              </div>
              <ul style="text-align: left; list-style: none; padding-left: 2cm;">
                <li class="mb-3"><i class="fas fa-check-circle" style="color: #065bef;"></i> Monitor 5 websites</li>
                <li class="mb-3"><i class="fas fa-check-circle" style="color: #065bef;"></i> 5-minute check</li>
                <li class="mb-3"><i class="fas fa-check-circle" style="color: #065bef;"></i> Email alerts</li>
                <li class="mb-3"><i class="fas fa-check-circle" style="color: #065bef;"></i> 1-Month history</li>
                <li class="mb-3"><i class="fas fa-times-circle" style="color: #ea230d;"></i> Telegram alert unavailable</li>
                <li class="mb-3"><i class="fas fa-times-circle" style="color: #ea230d;"></i> SSL expiry check unavailable</li>
                <li class="mb-3">
                  <i class="fas fa-times-circle" style="color: #ea230d;"></i>  Create and manage team members unavailable
                </li>
            </ul>
              @if(auth()->check())
                <a href="{{ route('monitoring.dashboard') }}" class="btn btn-outline-primary d-block">
                  Get Started
                </a>
              @else
                <a href="{{ route('login') }}" class="btn btn-outline-primary d-block">
                  Get Started
                </a>
              @endif
            </div>
          </div>
        </div>
        
        <div class="col-lg-4 animate__animated animate__fadeInUp">
          <div class="card h-100 border-light shadow-sm popular-plan">
            <div class="card-body p-4 text-center">
              @foreach($plans as $plan) 
                <h3 class="fw-bold mb-2">{{ $plan->name }}</h3>
                <div class="text-primary mb-4">
                  <span class="display-6 fw-bold">₹{{ $plan->amount }}</span>
                  <span class="text-muted">/month</span>
                </div>
              @endforeach
               <ul style="text-align: left; list-style: none; padding-left: 2cm;">
                <li class="mb-3"><i class="fas fa-check-circle" style="color: #065bef;"></i>  All basic features</li>
                <li class="mb-3"><i class="fas fa-check-circle" style="color: #065bef;"></i>  Monitor unlimited websites</li>
                <li class="mb-3"><i class="fas fa-check-circle" style="color: #065bef;"></i>  1-minute check</li>
                <li class="mb-3"><i class="fas fa-check-circle" style="color: #065bef;"></i>  Telegram bot notification alert</li>
                <li class="mb-3"><i class="fas fa-check-circle" style="color: #065bef;"></i>  4-Month history</li>
                <li class="mb-3"><i class="fas fa-check-circle" style="color: #065bef;"></i>  SSL expiry check</li>
                <li class="mb-3">
                  <i class="fas fa-check-circle" style="color: #065bef;"></i>  Create and manage team members
                </li>
              </ul>
              <a href="#" class="btn btn-primary d-block">Get Started</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- CTA Section -->
  <section class="py-5 gradient-bg text-white">
    <div class="container">
      <div class="row justify-content-center text-center">
        <div class="col-lg-8">
          <h2 class="display-5 fw-semibold mb-4 animate__animated animate__fadeInDown">Ready to Monitor Your Websites?</h2>
          <p class="lead mb-5 animate__animated animate__fadeIn animate__delay-1s">Join thousands of businesses that trust CheckMySite to keep their websites and services running smoothly.</p>
          <div class="d-flex flex-column flex-sm-row justify-content-center gap-3 animate__animated animate__fadeIn animate__delay-2s">
            <a href="{{ route('login') }}" class="btn btn-light btn-lg fw-bold px-4">
              <i class="fas fa-rocket me-2"></i> Start Your Free Trial
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="bg-dark text-light py-5">
    <div class="container">
      <div class="row gy-4">
        
        <!-- Company Info -->
        <div class="col-lg-3 col-md-6">
          <div class="d-flex align-items-center mb-3">
            <i class="fas fa-heartbeat text-primary me-2 fs-4"></i>
            <h3 class="h5 fw-bold mb-0">CheckMySite</h3>
          </div>
          <p class="mb-3 text-muted">Enterprise-grade website monitoring for businesses of all sizes.</p>
          <div class="d-flex gap-3">
            <a href="#" class="social-icon text-light opacity-75 hover-opacity-100 transition">
              <i class="fab fa-twitter"></i>
            </a>
            <a href="#" class="social-icon text-light opacity-75 hover-opacity-100 transition">
              <i class="fab fa-facebook"></i>
            </a>
            <a href="#" class="social-icon text-light opacity-75 hover-opacity-100 transition">
              <i class="fab fa-linkedin"></i>
            </a>
            <a href="#" class="social-icon text-light opacity-75 hover-opacity-100 transition">
              <i class="fab fa-github"></i>
            </a>
          </div>
        </div>
  
        <!-- Product Links -->
        <div class="col-lg-3 col-md-6">
          <h3 class="h6 fw-bold mb-3 text-uppercase small text-muted">Product</h3>
          <div class="d-flex flex-column gap-2 footer-links">
            <a href="#features" class="text-light text-decoration-none hover-text-primary transition">
              <i class="fas fa-chevron-right me-1 text-primary opacity-50 fs-8"></i> Features
            </a>
            <a href="#pricing" class="text-light text-decoration-none hover-text-primary transition">
              <i class="fas fa-chevron-right me-1 text-primary opacity-50 fs-8"></i> Pricing
            </a>
            <a href="#" class="text-light text-decoration-none hover-text-primary transition">
              <i class="fas fa-chevron-right me-1 text-primary opacity-50 fs-8"></i> Integrations
            </a>
            <a href="#" class="text-light text-decoration-none hover-text-primary transition">
              <i class="fas fa-chevron-right me-1 text-primary opacity-50 fs-8"></i> API
            </a>
            <a href="#" class="text-light text-decoration-none hover-text-primary transition">
              <i class="fas fa-chevron-right me-1 text-primary opacity-50 fs-8"></i> Status
            </a>
          </div>
        </div>
      
        
        <!-- Contact Information - Added to fill empty space -->
        <div class="col-lg-3 col-md-6">
          <h3 class="h6 fw-bold mb-3 text-uppercase small text-muted">Contact Us</h3>
          <div class="d-flex flex-column gap-2">
            <p class="mb-1 d-flex align-items-center">
              <i class="fas fa-envelope text-primary me-2"></i>
              <a href="mailto:checkmysite2025@gmail.com" class="text-light text-decoration-none hover-text-primary transition">checkmysite2025@gmail.com</a>
            </p>
            <p class="mb-1 d-flex align-items-center">
              <i class="fas fa-phone text-primary me-2"></i>
              <a href="tel:+18005551234" class="text-light text-decoration-none hover-text-primary transition">+1 (800) 555-1234</a>
            </p>
            <p class="mb-3 d-flex align-items-center">
              <i class="fas fa-map-marker-alt text-primary me-2"></i>
              <a 
                href="https://www.google.com/maps/place/Mangalore" 
                target="_blank" 
                style="text-decoration: none; color: inherit;"
              >
                <span>Mangalore</span>
              </a>
            </p>
            <div class="mt-2">
              <a href="{{ route('login') }}" class="btn btn-sm btn-outline-light">
                <i class="fas fa-rocket me-2"></i> Start Your Free Trial
              </a>
            </div>
          </div>
        </div>
      </div>
  
      <!-- Footer Divider -->
      <hr class="my-4 bg-opacity-10">
  
      <!-- Copyright & Legal Links -->
      <div class="row align-items-center">
        <div class="col-md-6 text-center text-md-start">
          <p class="mb-md-0 text-muted small">© 2025 CheckMySite. All rights reserved.</p>
        </div>
        <div class="col-md-6 text-center text-md-end">
          <a href="#" class="text-light text-decoration-none me-3 hover-text-primary transition small">Privacy Policy</a>
          <a href="#" class="text-light text-decoration-none hover-text-primary transition small">Terms of Service</a>
          <a href="#" class="text-light text-decoration-none ms-3 hover-text-primary transition small">Cookies Policy</a>
        </div>
      </div>
    </div>
  </footer>


  <!-- Bootstrap JS Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <!-- jQuery and Toastr scripts -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

  <script>
    // Navbar scroll effect
    window.addEventListener('scroll', function() {
      if (window.scrollY > 50) {
        document.querySelector('.navbar').classList.add('scrolled');
      } else {
        document.querySelector('.navbar').classList.remove('scrolled');
      }
    });
    
    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
      anchor.addEventListener('click', function (e) {
        e.preventDefault();
        
        document.querySelector(this.getAttribute('href')).scrollIntoView({
          behavior: 'smooth'
        });
      });
    });
    
    // Animation on scroll
    function animateOnScroll() {
      const elements = document.querySelectorAll('.animate__animated');
      
      elements.forEach(element => {
        const elementPosition = element.getBoundingClientRect().top;
        const screenPosition = window.innerHeight / 1.2;
        
        if (elementPosition < screenPosition) {
          const animationClass = element.classList[1];
          element.classList.add(animationClass);
        }
      });
    }
    
    window.addEventListener('scroll', animateOnScroll);
    window.addEventListener('load', animateOnScroll);
  </script>

  {{-- for feedbear --}}
  <script>
    (function (w, d, s, o, f, js, fjs) { w[o] = w[o] || function () { (w[o].q = w[o].q || []).push(arguments) }; js = d.createElement(s), fjs = d.getElementsByTagName(s)[0]; js.id = o; js.src = f; js.async = 1; fjs.parentNode.insertBefore(js, fjs); }(window, document, 'script', 'FeedBear', 'https://sdk.feedbear.com/widget.js'));
    FeedBear("button", {
    element: document.querySelector("[data-feedbear-button]"),
    project: "check-my-site",
    board: "feature-requests",
    jwt: null // see step 3,
    });
  </script>
</body>
</html>