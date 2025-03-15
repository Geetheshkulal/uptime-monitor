<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CheckMySite - Website Monitoring Service</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <style>
    .gradient-bg {
      background: linear-gradient(90deg, #0d6efd 0%, #0a58ca 100%);
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
    .hero-section {
      padding-top: 120px;
      padding-bottom: 80px;
    }
    .feature-icon {
      width: 64px;
      height: 64px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 1rem;
    }
    .testimonial-avatar {
      width: 48px;
      height: 48px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .popular-plan {
      position: relative;
      border: 2px solid #0d6efd;
      border-radius: 0.375rem;
    }
    .popular-badge {
      position: absolute;
      top: -12px;
      left: 50%;
      transform: translateX(-50%);
    }
    .step-circle {
      width: 64px;
      height: 64px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 1rem;
      font-size: 1.5rem;
      font-weight: bold;
    }
  </style>
</head>
<body>
  <!-- Navigation -->
  <!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top shadow-sm">
  <div class="container">
    <a class="navbar-brand text-primary fw-bold" href="#">
      <i class="fas fa-heartbeat me-2"></i>CheckMySite
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-expanded="false" aria-controls="navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto">
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
          <a class="nav-link" href="#testimonials">Testimonials</a>
        </li>
      </ul>
      @if (Route::has('login'))      
      <div class="d-flex">
      @auth 
      <a href="{{ url('/dashboard') }}" class="btn btn-primary">Dashboard</a>
      @else  
      <a href="{{ route('login') }}" class="btn btn-link text-secondary me-3">Login</a>
      @if (Route::has('register'))  
      <a href="{{ route('register') }}" class="btn btn-primary">Register</a>
      @endif
      @endauth
      </div>
      @endif
    </div>
  </div>
</nav>

  <!-- Hero Section -->
  <section class="gradient-bg hero-section text-white">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-6 text-center text-lg-start">
          <h1 class="display-4 fw-bold mb-4">Never Miss a Website Downtime Again</h1>
          <p class="lead mb-4">Get instant alerts when your websites go down. Monitor performance, uptime, and response times with CheckMySite's powerful monitoring tools.</p>
          <div class="d-flex flex-column flex-sm-row justify-content-center justify-content-lg-start gap-3">
            <a href="#" class="btn btn-light btn-lg text-primary fw-bold">Start Monitoring</a>
            <a href="#" class="btn btn-outline-light btn-lg">Watch Demo</a>
          </div>
        </div>
        <div class="col-lg-6">
          <!-- Hero image would go here -->
        </div>
      </div>
    </div>
  </section>

  <!-- Features Section -->
  <section id="features" class="py-5">
    <div class="container">
      <div class="text-center mb-5">
        <h2 class="fw-bold mb-3">Powerful Monitoring Features</h2>
        <p class="text-muted mx-auto" style="max-width: 600px;">Everything you need to keep your websites and services running smoothly, all in one place.</p>
      </div>
      
      <div class="row g-4">
        <div class="col-md-6 col-lg-4">
          <div class="card h-100 border-light shadow-sm">
            <div class="card-body">
              <div class="feature-icon bg-primary bg-opacity-10">
                <i class="fas fa-clock text-primary fs-4"></i>
              </div>
              <h3 class="h5 fw-bold">24/7 Monitoring</h3>
              <p class="text-muted">We check your websites every minute from multiple locations around the world.</p>
            </div>
          </div>
        </div>
        
        <div class="col-md-6 col-lg-4">
          <div class="card h-100 border-light shadow-sm">
            <div class="card-body">
              <div class="feature-icon bg-primary bg-opacity-10">
                <i class="fas fa-bell text-primary fs-4"></i>
              </div>
              <h3 class="h5 fw-bold">Instant Alerts</h3>
              <p class="text-muted">Get notified via email, SMS, Slack, Discord, or webhook when your sites go down.</p>
            </div>
          </div>
        </div>
        
        <div class="col-md-6 col-lg-4">
          <div class="card h-100 border-light shadow-sm">
            <div class="card-body">
              <div class="feature-icon bg-primary bg-opacity-10">
                <i class="fas fa-chart-line text-primary fs-4"></i>
              </div>
              <h3 class="h5 fw-bold">Detailed Analytics</h3>
              <p class="text-muted">Track response times, uptime percentage, and performance metrics over time.</p>
            </div>
          </div>
        </div>
        
        <div class="col-md-6 col-lg-4">
          <div class="card h-100 border-light shadow-sm">
            <div class="card-body">
              <div class="feature-icon bg-primary bg-opacity-10">
                <i class="fas fa-globe text-primary fs-4"></i>
              </div>
              <h3 class="h5 fw-bold">Global Monitoring</h3>
              <p class="text-muted">Check from multiple regions to ensure your site is accessible everywhere.</p>
            </div>
          </div>
        </div>
        
        <div class="col-md-6 col-lg-4">
          <div class="card h-100 border-light shadow-sm">
            <div class="card-body">
              <div class="feature-icon bg-primary bg-opacity-10">
                <i class="fas fa-shield-alt text-primary fs-4"></i>
              </div>
              <h3 class="h5 fw-bold">SSL Monitoring</h3>
              <p class="text-muted">Get alerted before your SSL certificates expire to avoid security warnings.</p>
            </div>
          </div>
        </div>
        
        <div class="col-md-6 col-lg-4">
          <div class="card h-100 border-light shadow-sm">
            <div class="card-body">
              <div class="feature-icon bg-primary bg-opacity-10">
                <i class="fas fa-code text-primary fs-4"></i>
              </div>
              <h3 class="h5 fw-bold">API Access</h3>
              <p class="text-muted">Integrate our monitoring data directly into your applications and dashboards.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- How It Works Section -->
  <section id="how-it-works" class="py-5 bg-light">
    <div class="container">
      <div class="text-center mb-5">
        <h2 class="fw-bold mb-3">How CheckMySite Works</h2>
        <p class="text-muted mx-auto" style="max-width: 600px;">Simple setup, powerful results. Get started in less than 2 minutes.</p>
      </div>
      
      <div class="row">
        <div class="col-md-4 text-center mb-4 mb-md-0">
          <div class="step-circle bg-primary text-white">1</div>
          <h3 class="h5 fw-bold">Add Your Websites</h3>
          <p class="text-muted">Enter your website URLs and set your preferred check frequency.</p>
        </div>
        
        <div class="col-md-4 text-center mb-4 mb-md-0">
          <div class="step-circle bg-primary text-white">2</div>
          <h3 class="h5 fw-bold">Configure Alerts</h3>
          <p class="text-muted">Choose how you want to be notified when issues are detected.</p>
        </div>
        
        <div class="col-md-4 text-center">
          <div class="step-circle bg-primary text-white">3</div>
          <h3 class="h5 fw-bold">Relax & Stay Informed</h3>
          <p class="text-muted">We'll monitor your sites 24/7 and alert you if anything goes wrong.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Stats Section -->
  <section class="py-5 gradient-bg text-white">
    <div class="container">
      <div class="row text-center">
        <div class="col-6 col-md-3 mb-3 mb-md-0">
          <h3 class="display-6 fw-bold mb-1">99.9%</h3>
          <p class="text-light">Uptime Guarantee</p>
        </div>
        <div class="col-6 col-md-3 mb-3 mb-md-0">
          <h3 class="display-6 fw-bold mb-1">60s</h3>
          <p class="text-light">Check Interval</p>
        </div>
        <div class="col-6 col-md-3">
          <h3 class="display-6 fw-bold mb-1">5,000+</h3>
          <p class="text-light">Happy Customers</p>
        </div>
        <div class="col-6 col-md-3">
          <h3 class="display-6 fw-bold mb-1">1M+</h3>
          <p class="text-light">Websites Monitored</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Pricing Section -->
  <section id="pricing" class="py-5">
    <div class="container">
      <div class="text-center mb-5">
        <h2 class="fw-bold mb-3">Simple, Transparent Pricing</h2>
        <p class="text-muted mx-auto" style="max-width: 600px;">Choose the plan that fits your needs. All plans include our core monitoring features.</p>
      </div>
      
      <div class="row justify-content-center g-4">
        <div class="col-lg-4">
          <div class="card h-100 border-light shadow-sm">
            <div class="card-body p-4">
              <h3 class="fw-bold mb-2">Starter</h3>
              <div class="text-primary mb-4">
                <span class="display-6 fw-bold">$9</span>
                <span class="text-muted">/month</span>
              </div>
              <ul class="list-unstyled mb-4">
                <li class="mb-2"><i class="fas fa-check text-success me-2"></i> 10 websites</li>
                <li class="mb-2"><i class="fas fa-check text-success me-2"></i> 5-minute checks</li>
                <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Email alerts</li>
                <li class="mb-2"><i class="fas fa-check text-success me-2"></i> 7-day history</li>
              </ul>
              <a href="#" class="btn btn-primary d-block">Get Started</a>
            </div>
          </div>
        </div>
        
        <div class="col-lg-4">
          <div class="card h-100 popular-plan shadow">
            <span class="badge bg-primary popular-badge px-3 py-2 rounded-pill">MOST POPULAR</span>
            <div class="card-body p-4">
              <h3 class="fw-bold mb-2">Professional</h3>
              <div class="text-primary mb-4">
                <span class="display-6 fw-bold">$29</span>
                <span class="text-muted">/month</span>
              </div>
              <ul class="list-unstyled mb-4">
                <li class="mb-2"><i class="fas fa-check text-success me-2"></i> 50 websites</li>
                <li class="mb-2"><i class="fas fa-check text-success me-2"></i> 1-minute checks</li>
                <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Email, SMS & Slack alerts</li>
                <li class="mb-2"><i class="fas fa-check text-success me-2"></i> 30-day history</li>
                <li class="mb-2"><i class="fas fa-check text-success me-2"></i> API access</li>
              </ul>
              <a href="#" class="btn btn-primary d-block">Get Started</a>
            </div>
          </div>
        </div>
        
        <div class="col-lg-4">
          <div class="card h-100 border-light shadow-sm">
            <div class="card-body p-4">
            <h3 class="fw-bold mb-2">Enterprise</h3>
              <div class="text-primary mb-4">
                <span class="display-6 fw-bold">$99</span>
                <span class="text-muted">/month</span>
              </div>
              <ul class="list-unstyled mb-4">
                <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Unlimited websites</li>
                <li class="mb-2"><i class="fas fa-check text-success me-2"></i> 30-second checks</li>
                <li class="mb-2"><i class="fas fa-check text-success me-2"></i> All alert channels</li>
                <li class="mb-2"><i class="fas fa-check text-success me-2"></i> 1-year history</li>
                <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Priority support</li>
                <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Custom integrations</li>
              </ul>
              <a href="#" class="btn btn-primary d-block">Get Started</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Testimonials Section -->
  <section id="testimonials" class="py-5 bg-light">
    <div class="container">
      <div class="text-center mb-5">
        <h2 class="fw-bold mb-3">What Our Customers Say</h2>
        <p class="text-muted mx-auto" style="max-width: 600px;">Thousands of businesses trust CheckMySite to keep their websites running smoothly.</p>
      </div>
      
      <div class="row g-4">
        <div class="col-md-6 col-lg-4">
          <div class="card h-100 border-0 shadow-sm">
            <div class="card-body p-4">
              <div class="d-flex align-items-center mb-3">
                <div class="testimonial-avatar bg-primary bg-opacity-10">
                  <span class="text-primary fw-bold">J</span>
                </div>
                <div class="ms-3">
                  <h4 class="h6 fw-bold mb-0">Jason Miller</h4>
                  <p class="small text-muted mb-0">CTO, TechStart Inc.</p>
                </div>
              </div>
              <div class="text-warning mb-3">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
              </div>
              <p class="text-muted">"CheckMySite has been a game-changer for our business. We were alerted to an outage within seconds and resolved it before most of our customers even noticed. The detailed reporting helps us optimize our infrastructure."</p>
            </div>
          </div>
        </div>
        
        <div class="col-md-6 col-lg-4">
          <div class="card h-100 border-0 shadow-sm">
            <div class="card-body p-4">
              <div class="d-flex align-items-center mb-3">
                <div class="testimonial-avatar bg-primary bg-opacity-10">
                  <span class="text-primary fw-bold">S</span>
                </div>
                <div class="ms-3">
                  <h4 class="h6 fw-bold mb-0">Sarah Johnson</h4>
                  <p class="small text-muted mb-0">Owner, Johnson E-commerce</p>
                </div>
              </div>
              <div class="text-warning mb-3">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
              </div>
              <p class="text-muted">"As a small business owner, I don't have time to constantly check if my online store is up. CheckMySite gives me peace of mind knowing I'll be alerted immediately if there's an issue with my website."</p>
            </div>
          </div>
        </div>
        
        <div class="col-md-6 col-lg-4">
          <div class="card h-100 border-0 shadow-sm">
            <div class="card-body p-4">
              <div class="d-flex align-items-center mb-3">
                <div class="testimonial-avatar bg-primary bg-opacity-10">
                  <span class="text-primary fw-bold">M</span>
                </div>
                <div class="ms-3">
                  <h4 class="h6 fw-bold mb-0">Michael Chang</h4>
                  <p class="small text-muted mb-0">IT Director, Global Solutions</p>
                </div>
              </div>
              <div class="text-warning mb-3">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star-half-alt"></i>
              </div>
              <p class="text-muted">"We monitor over 200 websites across multiple regions with CheckMySite. The global monitoring network gives us confidence that our services are accessible to customers worldwide. Great value for the price."</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- FAQ Section -->
  <section class="py-5">
    <div class="container">
      <div class="text-center mb-5">
        <h2 class="fw-bold mb-3">Frequently Asked Questions</h2>
        <p class="text-muted mx-auto" style="max-width: 600px;">Find answers to common questions about our uptime monitoring service.</p>
      </div>
      
      <div class="row justify-content-center">
        <div class="col-lg-8">
          <div class="accordion" id="faqAccordion">
            <div class="accordion-item">
              <h2 class="accordion-header" id="headingOne">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                  How often do you check my websites?
                </button>
              </h2>
              <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#faqAccordion">
                <div class="accordion-body">
                  Depending on your plan, we check your websites as frequently as every 30 seconds. Our Professional plan includes 1-minute checks, while our Enterprise plan offers 30-second monitoring intervals.
                </div>
              </div>
            </div>
            
            <div class="accordion-item">
              <h2 class="accordion-header" id="headingTwo">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                  What happens when my site goes down?
                </button>
              </h2>
              <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#faqAccordion">
                <div class="accordion-body">
                  We'll immediately verify the outage from multiple locations to prevent false positives. Once confirmed, we'll send you alerts through your configured notification channels. We'll continue monitoring and notify you when your site is back up.
                </div>
              </div>
            </div>
            
            <div class="accordion-item">
              <h2 class="accordion-header" id="headingThree">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                  Can I monitor internal services or APIs?
                </button>
              </h2>
              <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#faqAccordion">
                <div class="accordion-body">
                  Yes! In addition to monitoring public websites, you can monitor internal services, APIs, and endpoints. We support HTTP/HTTPS, TCP/UDP, ICMP, and custom request monitoring.
                </div>
              </div>
            </div>
            
            <div class="accordion-item">
              <h2 class="accordion-header" id="headingFour">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                  Do you offer a free trial?
                </button>
              </h2>
              <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#faqAccordion">
                <div class="accordion-body">
                  Yes, we offer a 14-day free trial on all plans with no credit card required. You can try out all features before making a decision.
                </div>
              </div>
            </div>
            
            <div class="accordion-item">
              <h2 class="accordion-header" id="headingFive">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                  How do I get started?
                </button>
              </h2>
              <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive" data-bs-parent="#faqAccordion">
                <div class="accordion-body">
                  Simply sign up for an account, add your websites or services, configure your alert preferences, and you're all set! The setup process takes less than 2 minutes.
                </div>
              </div>
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
          <h2 class="display-5 fw-bold mb-4">Ready to Monitor Your Websites?</h2>
          <p class="lead mb-5">Join thousands of businesses that trust CheckMySite to keep their websites and services running smoothly.</p>
          <div class="d-flex flex-column flex-sm-row justify-content-center gap-3">
            <a href="#" class="btn btn-light btn-lg text-primary fw-bold px-4">Start Your Free Trial</a>
            <a href="#" class="btn btn-outline-light btn-lg px-4">Schedule a Demo</a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="bg-dark text-light py-5">
    <div class="container">
      <div class="row gy-4">
        <div class="col-lg-3 col-md-6">
          <h3 class="h5 fw-bold mb-3">CheckMySite</h3>
          <p class="mb-3">Enterprise-grade website monitoring for businesses of all sizes.</p>
          <div class="d-flex gap-3">
            <a href="#" class="text-light"><i class="fab fa-twitter"></i></a>
            <a href="#" class="text-light"><i class="fab fa-facebook"></i></a>
            <a href="#" class="text-light"><i class="fab fa-linkedin"></i></a>
            <a href="#" class="text-light"><i class="fab fa-github"></i></a>
          </div>
        </div>
        
        <div class="col-lg-3 col-md-6">
          <h3 class="h5 fw-bold mb-3">Product</h3>
          <ul class="list-unstyled">
            <li class="mb-2"><a href="#" class="text-light text-decoration-none">Features</a></li>
            <li class="mb-2"><a href="#" class="text-light text-decoration-none">Pricing</a></li>
            <li class="mb-2"><a href="#" class="text-light text-decoration-none">Integrations</a></li>
            <li class="mb-2"><a href="#" class="text-light text-decoration-none">API</a></li>
            <li class="mb-2"><a href="#" class="text-light text-decoration-none">Status</a></li>
          </ul>
        </div>
        
        <div class="col-lg-3 col-md-6">
          <h3 class="h5 fw-bold mb-3">Resources</h3>
          <ul class="list-unstyled">
            <li class="mb-2"><a href="#" class="text-light text-decoration-none">Documentation</a></li>
            <li class="mb-2"><a href="#" class="text-light text-decoration-none">Blog</a></li>
            <li class="mb-2"><a href="#" class="text-light text-decoration-none">Knowledge Base</a></li>
            <li class="mb-2"><a href="#" class="text-light text-decoration-none">Uptime Calculator</a></li>
            <li class="mb-2"><a href="#" class="text-light text-decoration-none">Community</a></li>
          </ul>
        </div>
        
        <div class="col-lg-3 col-md-6">
          <h3 class="h5 fw-bold mb-3">Company</h3>
          <ul class="list-unstyled">
            <li class="mb-2"><a href="#" class="text-light text-decoration-none">About Us</a></li>
            <li class="mb-2"><a href="#" class="text-light text-decoration-none">Careers</a></li>
            <li class="mb-2"><a href="#" class="text-light text-decoration-none">Contact</a></li>
            <li class="mb-2"><a href="#" class="text-light text-decoration-none">Privacy Policy</a></li>
            <li class="mb-2"><a href="#" class="text-light text-decoration-none">Terms of Service</a></li>
          </ul>
        </div>
      </div>
      
      <hr class="my-4 bg-secondary">
      
      <div class="row align-items-center">
        <div class="col-md-6 text-center text-md-start">
          <p class="mb-md-0">© 2025 CheckMySite. All rights reserved.</p>
        </div>
        <div class="col-md-6 text-center text-md-end">
          <a href="#" class="text-light text-decoration-none me-3">Privacy Policy</a>
          <a href="#" class="text-light text-decoration-none">Terms of Service</a>
        </div>
      </div>
    </div>
  </footer>
  <!-- Bootstrap JS Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>