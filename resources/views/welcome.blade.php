<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>UptimeGuard - Website Monitoring Service</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <script src="https://cdn.tailwindcss.com"></script>

  <style>
    .gradient-bg {
      background: linear-gradient(90deg, #3b82f6 0%, #2563eb 100%);
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
  </style>
</head>
<body class="font-sans antialiased text-gray-800">
  <!-- Navigation -->
  <nav class="bg-white shadow-md py-4 fixed w-full z-10">
    <div class="container mx-auto px-6 flex items-center justify-between">
      <div class="flex items-center">
        <span class="text-blue-600 text-2xl font-bold"><i class="fas fa-heartbeat mr-2"></i>UptimeGuard</span>
      </div>
      <div class="hidden md:flex items-center space-x-8">
        <a href="#features" class="text-gray-600 hover:text-blue-600 transition">Features</a>
        <a href="#how-it-works" class="text-gray-600 hover:text-blue-600 transition">How It Works</a>
        <a href="#pricing" class="text-gray-600 hover:text-blue-600 transition">Pricing</a>
        <a href="#testimonials" class="text-gray-600 hover:text-blue-600 transition">Testimonials</a>
      </div>
      <div class="flex items-center space-x-4">
        <a href="#" class="text-gray-600 hover:text-blue-600 transition">Login</a>
        <a href="#" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">Sign Up Free</a>
      </div>
    </div>
  </nav>

  <!-- Hero Section -->
  <section class="pt-32 pb-20 gradient-bg">
    <div class="container mx-auto px-6">
      <div class="flex flex-col-reverse lg:flex-row items-center">
        <div class="w-full lg:w-1/2 text-center lg:text-left text-white">
          <h1 class="text-4xl lg:text-5xl font-bold leading-tight mb-6">Never Miss a Website Downtime Again</h1>
          <p class="text-xl mb-8">Get instant alerts when your websites go down. Monitor performance, uptime, and response times with UptimeGuard's powerful monitoring tools.</p>
          <div class="flex flex-col sm:flex-row justify-center lg:justify-start space-y-4 sm:space-y-0 sm:space-x-4">
            <a href="#" class="bg-white text-blue-600 font-bold px-8 py-3 rounded-lg hover:bg-gray-100 transition">Start Monitoring</a>
            <a href="#" class="border-2 border-white text-white px-8 py-3 rounded-lg hover:bg-white hover:text-blue-600 transition">Watch Demo</a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Features Section -->
  <section id="features" class="py-20 bg-white">
    <div class="container mx-auto px-6">
      <div class="text-center mb-16">
        <h2 class="text-3xl font-bold mb-4">Powerful Monitoring Features</h2>
        <p class="text-gray-600 max-w-2xl mx-auto">Everything you need to keep your websites and services running smoothly, all in one place.</p>
      </div>
      
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
        <div class="p-6 border border-gray-200 rounded-lg hover:shadow-lg transition">
          <div class="rounded-full bg-blue-100 w-16 h-16 flex items-center justify-center mb-4">
            <i class="fas fa-clock text-blue-600 text-2xl"></i>
          </div>
          <h3 class="text-xl font-bold mb-2">24/7 Monitoring</h3>
          <p class="text-gray-600">We check your websites every minute from multiple locations around the world.</p>
        </div>
        
        <div class="p-6 border border-gray-200 rounded-lg hover:shadow-lg transition">
          <div class="rounded-full bg-blue-100 w-16 h-16 flex items-center justify-center mb-4">
            <i class="fas fa-bell text-blue-600 text-2xl"></i>
          </div>
          <h3 class="text-xl font-bold mb-2">Instant Alerts</h3>
          <p class="text-gray-600">Get notified via email, SMS, Slack, Discord, or webhook when your sites go down.</p>
        </div>
        
        <div class="p-6 border border-gray-200 rounded-lg hover:shadow-lg transition">
          <div class="rounded-full bg-blue-100 w-16 h-16 flex items-center justify-center mb-4">
            <i class="fas fa-chart-line text-blue-600 text-2xl"></i>
          </div>
          <h3 class="text-xl font-bold mb-2">Detailed Analytics</h3>
          <p class="text-gray-600">Track response times, uptime percentage, and performance metrics over time.</p>
        </div>
        
        <div class="p-6 border border-gray-200 rounded-lg hover:shadow-lg transition">
          <div class="rounded-full bg-blue-100 w-16 h-16 flex items-center justify-center mb-4">
            <i class="fas fa-globe text-blue-600 text-2xl"></i>
          </div>
          <h3 class="text-xl font-bold mb-2">Global Monitoring</h3>
          <p class="text-gray-600">Check from multiple regions to ensure your site is accessible everywhere.</p>
        </div>
        
        <div class="p-6 border border-gray-200 rounded-lg hover:shadow-lg transition">
          <div class="rounded-full bg-blue-100 w-16 h-16 flex items-center justify-center mb-4">
            <i class="fas fa-shield-alt text-blue-600 text-2xl"></i>
          </div>
          <h3 class="text-xl font-bold mb-2">SSL Monitoring</h3>
          <p class="text-gray-600">Get alerted before your SSL certificates expire to avoid security warnings.</p>
        </div>
        
        <div class="p-6 border border-gray-200 rounded-lg hover:shadow-lg transition">
          <div class="rounded-full bg-blue-100 w-16 h-16 flex items-center justify-center mb-4">
            <i class="fas fa-code text-blue-600 text-2xl"></i>
          </div>
          <h3 class="text-xl font-bold mb-2">API Access</h3>
          <p class="text-gray-600">Integrate our monitoring data directly into your applications and dashboards.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- How It Works Section -->
  <section id="how-it-works" class="py-20 bg-gray-50">
    <div class="container mx-auto px-6">
      <div class="text-center mb-16">
        <h2 class="text-3xl font-bold mb-4">How UptimeGuard Works</h2>
        <p class="text-gray-600 max-w-2xl mx-auto">Simple setup, powerful results. Get started in less than 2 minutes.</p>
      </div>
      
      <div class="flex flex-col md:flex-row justify-between items-center">
        <div class="w-full md:w-1/3 text-center px-4 mb-10 md:mb-0">
          <div class="rounded-full bg-blue-600 text-white w-16 h-16 flex items-center justify-center mx-auto mb-4 text-2xl font-bold">1</div>
          <h3 class="text-xl font-bold mb-2">Add Your Websites</h3>
          <p class="text-gray-600">Enter your website URLs and set your preferred check frequency.</p>
        </div>
        
        <div class="w-full md:w-1/3 text-center px-4 mb-10 md:mb-0">
          <div class="rounded-full bg-blue-600 text-white w-16 h-16 flex items-center justify-center mx-auto mb-4 text-2xl font-bold">2</div>
          <h3 class="text-xl font-bold mb-2">Configure Alerts</h3>
          <p class="text-gray-600">Choose how you want to be notified when issues are detected.</p>
        </div>
        
        <div class="w-full md:w-1/3 text-center px-4">
          <div class="rounded-full bg-blue-600 text-white w-16 h-16 flex items-center justify-center mx-auto mb-4 text-2xl font-bold">3</div>
          <h3 class="text-xl font-bold mb-2">Relax & Stay Informed</h3>
          <p class="text-gray-600">We'll monitor your sites 24/7 and alert you if anything goes wrong.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Stats Section -->
  <section class="py-16 gradient-bg text-white">
    <div class="container mx-auto px-6">
      <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
        <div>
          <div class="text-4xl font-bold mb-2">99.9%</div>
          <div class="text-blue-200">Uptime Guarantee</div>
        </div>
        <div>
          <div class="text-4xl font-bold mb-2">60s</div>
          <div class="text-blue-200">Check Interval</div>
        </div>
        <div>
          <div class="text-4xl font-bold mb-2">5,000+</div>
          <div class="text-blue-200">Happy Customers</div>
        </div>
        <div>
          <div class="text-4xl font-bold mb-2">1M+</div>
          <div class="text-blue-200">Websites Monitored</div>
        </div>
      </div>
    </div>
  </section>

  <!-- Pricing Section -->
  <section id="pricing" class="py-20 bg-white">
    <div class="container mx-auto px-6">
      <div class="text-center mb-16">
        <h2 class="text-3xl font-bold mb-4">Simple, Transparent Pricing</h2>
        <p class="text-gray-600 max-w-2xl mx-auto">Choose the plan that fits your needs. All plans include our core monitoring features.</p>
      </div>
      
      <div class="flex flex-col lg:flex-row justify-center space-y-8 lg:space-y-0 lg:space-x-8">
        <div class="w-full lg:w-1/3 border border-gray-200 rounded-lg p-8 hover:shadow-lg transition">
          <h3 class="text-2xl font-bold mb-2">Starter</h3>
          <div class="text-blue-600 text-4xl font-bold mb-6">$9<span class="text-lg font-normal text-gray-600">/month</span></div>
          <ul class="mb-8 space-y-3">
            <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> 10 websites</li>
            <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> 5-minute checks</li>
            <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> Email alerts</li>
            <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> 7-day history</li>
          </ul>
          <a href="#" class="block text-center bg-blue-600 text-white font-bold py-3 rounded-lg hover:bg-blue-700 transition">Get Started</a>
        </div>
        
        <div class="w-full lg:w-1/3 border-2 border-blue-600 rounded-lg p-8 relative shadow-lg">
          <div class="absolute top-0 inset-x-0 -mt-4 flex justify-center">
            <span class="bg-blue-600 text-white text-sm font-bold py-1 px-4 rounded-full">MOST POPULAR</span>
          </div>
          <h3 class="text-2xl font-bold mb-2">Professional</h3>
          <div class="text-blue-600 text-4xl font-bold mb-6">$29<span class="text-lg font-normal text-gray-600">/month</span></div>
          <ul class="mb-8 space-y-3">
            <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> 50 websites</li>
            <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> 1-minute checks</li>
            <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> Email, SMS & Slack alerts</li>
            <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> 30-day history</li>
            <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> API access</li>
          </ul>
          <a href="#" class="block text-center bg-blue-600 text-white font-bold py-3 rounded-lg hover:bg-blue-700 transition">Get Started</a>
        </div>
        
        <div class="w-full lg:w-1/3 border border-gray-200 rounded-lg p-8 hover:shadow-lg transition">
          <h3 class="text-2xl font-bold mb-2">Enterprise</h3>
          <div class="text-blue-600 text-4xl font-bold mb-6">$99<span class="text-lg font-normal text-gray-600">/month</span></div>
          <ul class="mb-8 space-y-3">
            <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> Unlimited websites</li>
            <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> 30-second checks</li>
            <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> All alert channels</li>
            <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> 1-year history</li>
            <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> Priority support</li>
            <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> Custom integrations</li>
          </ul>
          <a href="#" class="block text-center bg-blue-600 text-white font-bold py-3 rounded-lg hover:bg-blue-700 transition">Get Started</a>
        </div>
      </div>
    </div>
  </section>

  <!-- Testimonials Section -->
  <section id="testimonials" class="py-20 bg-gray-50">
    <div class="container mx-auto px-6">
      <div class="text-center mb-16">
        <h2 class="text-3xl font-bold mb-4">What Our Customers Say</h2>
        <p class="text-gray-600 max-w-2xl mx-auto">Thousands of businesses trust UptimeGuard to keep their websites running smoothly.</p>
      </div>
      
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <div class="bg-white p-6 rounded-lg shadow-md">
          <div class="flex items-center mb-4">
            <div class="h-12 w-12 rounded-full bg-blue-100 flex items-center justify-center">
              <span class="text-blue-600 font-bold text-xl">J</span>
            </div>
            <div class="ml-4">
              <h4 class="font-bold">Jason Miller</h4>
              <p class="text-sm text-gray-600">CTO, TechStart Inc.</p>
            </div>
          </div>
          <div class="text-yellow-400 flex mb-3">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
          </div>
          <p class="text-gray-600">"UptimeGuard has been a game-changer for our business. We were alerted to an outage within seconds and resolved it before most of our customers even noticed. The detailed reporting helps us optimize our infrastructure."</p>
        </div>
        
        <div class="bg-white p-6 rounded-lg shadow-md">
          <div class="flex items-center mb-4">
            <div class="h-12 w-12 rounded-full bg-blue-100 flex items-center justify-center">
              <span class="text-blue-600 font-bold text-xl">S</span>
            </div>
            <div class="ml-4">
              <h4 class="font-bold">Sarah Johnson</h4>
              <p class="text-sm text-gray-600">Owner, Johnson E-commerce</p>
            </div>
          </div>
          <div class="text-yellow-400 flex mb-3">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
          </div>
          <p class="text-gray-600">"As a small business owner, I don't have time to constantly check if my online store is up. UptimeGuard gives me peace of mind knowing I'll be alerted immediately if there's an issue with my website."</p>
        </div>
        
        <div class="bg-white p-6 rounded-lg shadow-md">
          <div class="flex items-center mb-4">
            <div class="h-12 w-12 rounded-full bg-blue-100 flex items-center justify-center">
              <span class="text-blue-600 font-bold text-xl">M</span>
            </div>
            <div class="ml-4">
              <h4 class="font-bold">Michael Chang</h4>
              <p class="text-sm text-gray-600">IT Director, Global Solutions</p>
            </div>
          </div>
          <div class="text-yellow-400 flex mb-3">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star-half-alt"></i>
          </div>
          <p class="text-gray-600">"We monitor over 200 websites across multiple regions with UptimeGuard. The global monitoring network gives us confidence that our services are accessible to customers worldwide. Great value for the price."</p>
        </div>
      </div>
    </div>
  </section>

  <!-- FAQ Section -->
  <section class="py-20 bg-white">
    <div class="container mx-auto px-6">
      <div class="text-center mb-16">
        <h2 class="text-3xl font-bold mb-4">Frequently Asked Questions</h2>
        <p class="text-gray-600 max-w-2xl mx-auto">Find answers to common questions about our uptime monitoring service.</p>
      </div>
      
      <div class="max-w-3xl mx-auto">
        <div class="mb-6">
          <h3 class="text-xl font-bold mb-2">How often do you check my websites?</h3>
          <p class="text-gray-600">Depending on your plan, we check your websites as frequently as every 30 seconds. Our Professional plan includes 1-minute checks, while our Enterprise plan offers 30-second monitoring intervals.</p>
        </div>
        
        <div class="mb-6">
          <h3 class="text-xl font-bold mb-2">What happens when my site goes down?</h3>
          <p class="text-gray-600">We'll immediately verify the outage from multiple locations to prevent false positives. Once confirmed, we'll send you alerts through your configured notification channels. We'll continue monitoring and notify you when your site is back up.</p>
        </div>
        
        <div class="mb-6">
          <h3 class="text-xl font-bold mb-2">Can I monitor internal services or APIs?</h3>
          <p class="text-gray-600">Yes! In addition to monitoring public websites, you can monitor internal services, APIs, and endpoints. We support HTTP/HTTPS, TCP/UDP, ICMP, and custom request monitoring.</p>
        </div>
        
        <div class="mb-6">
          <h3 class="text-xl font-bold mb-2">Do you offer a free trial?</h3>
          <p class="text-gray-600">Yes, we offer a 14-day free trial on all plans with no credit card required. You can try out all features before making a decision.</p>
        </div>
        
        <div>
          <h3 class="text-xl font-bold mb-2">How do I get started?</h3>
          <p class="text-gray-600">Simply sign up for an account, add your websites or services, configure your alert preferences, and you're all set! The setup process takes less than 2 minutes.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- CTA Section -->
  <section class="py-20 gradient-bg">
    <div class="container mx-auto px-6 text-center">
      <h2 class="text-3xl md:text-4xl font-bold text-white mb-8">Ready to Monitor Your Websites?</h2>
      <p class="text-xl text-blue-100 mb-10 max-w-2xl mx-auto">Join thousands of businesses that trust UptimeGuard to keep their websites and services running smoothly.</p>
      <div class="flex flex-col sm:flex-row justify-center space-y-4 sm:space-y-0 sm:space-x-4">
        <a href="#" class="bg-white text-blue-600 font-bold px-8 py-4 rounded-lg hover:bg-gray-100 transition text-lg">Start Your Free Trial</a>
        <a href="#" class="border-2 border-white text-white px-8 py-4 rounded-lg hover:bg-white hover:text-blue-600 transition text-lg">Schedule a Demo</a>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="bg-gray-800 text-gray-300 py-12">
    <div class="container mx-auto px-6">
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-8">
        <div>
          <h3 class="text-xl font-bold text-white mb-4">UptimeGuard</h3>
          <p class="mb-4">Enterprise-grade website monitoring for businesses of all sizes.</p>
          <div class="flex space-x-4">
            <a href="#" class="text-gray-300 hover:text-white transition"><i class="fab fa-twitter"></i></a>
            <a href="#" class="text-gray-300 hover:text-white transition"><i class="fab fa-facebook"></i></a>
            <a href="#" class="text-gray-300 hover:text-white transition"><i class="fab fa-linkedin"></i></a>
            <a href="#" class="text-gray-300 hover:text-white transition"><i class="fab fa-github"></i></a>
          </div>
        </div>
        
        <div>
          <h3 class="text-lg font-bold text-white mb-4">Product</h3>
          <ul class="space-y-2">
            <li><a href="#" class="text-gray-300 hover:text-white transition">Features</a></li>
            <li><a href="#" class="text-gray-300 hover:text-white transition">Pricing</a></li>
            <li><a href="#" class="text-gray-300 hover:text-white transition">Integrations</a></li>
            <li><a href="#" class="text-gray-300 hover:text-white transition">API</a></li>
            <li><a href="#" class="text-gray-300 hover:text-white transition">Status</a></li>
          </ul>
        </div>
        
        <div>
          <h3 class="text-lg font-bold text-white mb-4">Resources</h3>
          <ul class="space-y-2">
            <li><a href="#" class="text-gray-300 hover:text-white transition">Documentation</a></li>
            <li><a href="#" class="text-gray-300 hover:text-white transition">Blog</a></li>
            <li><a href="#" class="text-gray-300 hover:text-white transition">Knowledge Base</a></li>
            <li><a href="#" class="text-gray-300 hover:text-white transition">Uptime Calculator</a></li>
            <li><a href="#" class="text-gray-300 hover:text-white transition">Community</a></li>
          </ul>
        </div>
        
        <div>
          <h3 class="text-lg font-bold text-white mb-4">Company</h3>
          <ul class="space-y-2">
            <li><a href="#" class="text-gray-300 hover:text-white transition">About Us</a></li>
            <li><a href="#" class="text-gray-300 hover:text-white transition">Careers</a></li>
            <li><a href="#" class="text-gray-300 hover:text-white transition">Contact</a></li>
            <li><a href="#" class="text-gray-300 hover:text-white transition">Privacy Policy</a></li>
            <li><a href="#" class="text-gray-300 hover:text-white transition">Terms of Service</a></li>
          </ul>
        </div>
      </div>
      
      <div class="border-t border-gray-700 pt-8">
        <div class="flex flex-col md:flex-row justify-between items-center">
          <p>© 2025 UptimeGuard. All rights reserved.</p>
          <div class="mt-4 md:mt-0">
            <a href="#" class="text-blue-400 hover:text-blue-300 transition">Privacy Policy</a>
            <span class="mx-2">|</span>
            <a href="#" class="text-blue-400 hover:text-blue-300 transition">Terms of Service</a>
          </div>
        </div>
      </div>
    </div>
  </footer>

  <!-- Mobile Menu Toggle (Hidden in this version) -->
  <div class="fixed bottom-6 right-6 z-50 md:hidden">
    <button class="bg-blue-600 text-white rounded-full w-14 h-14 flex items-center justify-center shadow-lg">
      <i class="fas fa-bars text-xl"></i>
    </button>
  </div>
</body>
</html>