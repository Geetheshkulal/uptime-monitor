<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>UP_TIME Monitoring System - Documentation</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    :root {
      --primary-color: #4e73df;;
      --primary-dark: #0a58ca;
      --accent-color: #ffc107;
      --white: #ffffff;
      --sidebar-width: 280px;
    }
    
    
    body {
      display: flex;
      min-height: 100vh;
      margin: 0;
      font-family: 'Segoe UI', Roboto, 'Helvetica Neue', sans-serif;
      background-color: #f8f9fa;
      color: #333;
      position: sticky;
      top: 0;
      height: 100vh;
    }

 /* Sidebar Container */
.sidebar {
    width: var(--sidebar-width);
    background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
    color: white;
    padding: 20px 0;
    box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
    position: fixed;
    height: 100vh;
    z-index: 10;
    overflow-y: auto;
    top: 0;
    left: 0;
}

/* Content Area */
.content {
    margin-left: var(--sidebar-width); /* Offset content to the right of the sidebar */
    flex: 1;
    padding: 40px;
    overflow-y: auto;
    position: relative;
    background-color: #f8f9fa;
    min-height: 100vh;
}


/* Sidebar Header */
.sidebar-header {
    padding: 0 25px 20px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    text-align: center;
}

.sidebar-header h4 {
    font-weight: 600;
    margin: 0;
    font-size: 1.25rem;
    color: white;
}

.sidebar-header a {
    text-decoration: none;
    color: white;
}

/* Navigation Menu */
.nav-menu {
    padding: 20px 0;
}

.nav-menu a {
    color: rgba(255, 255, 255, 0.9);
    text-decoration: none;
    padding: 12px 25px;
    display: flex;
    align-items: center;
    font-size: 15px;
    transition: all 0.3s ease;
    border-left: 4px solid transparent;
    margin: 2px 0;
}

.nav-menu a i {
    margin-right: 12px;
    width: 20px;
    text-align: center;
}

.nav-menu a:hover {
    background-color: rgba(255, 255, 255, 0.1);
    border-left: 4px solid var(--white);
    color: white;
}

.nav-menu a.active {
    background-color: rgba(255, 255, 255, 0.15);
    border-left: 4px solid var(--white);
    color: white;
}

/* Premium Badge */
.badge-premium {
    background-color: rgba(255, 193, 7, 0.2);
    color: gold;
    font-size: 0.7rem;
    font-weight: 600;
    padding: 3px 8px;
    border-radius: 4px;
    margin-left: 8px;
    text-transform: uppercase;
}

/* Back Button */
.back-btn {
    margin: 20px 25px;
    display: flex;
    align-items: center;
    background-color: var(--primary-color);
    color: white;
    border: none;
    padding: 10px 15px;
    border-radius: 4px;
    font-size: 14px;
    text-decoration: none;
    transition: all 0.3s ease;
}

.back-btn:hover {
    background-color: var(--primary-dark);
    transform: translateY(-1px);
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.back-btn i {
    margin-right: 8px;
}

 

    .tab-content {
      display: none;
      animation: fadeIn 0.4s ease-out;
    }

    .tab-content.active {
      display: block;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(10px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .content-header {
      margin-bottom: 30px;
      padding-bottom: 15px;
      border-bottom: 1px solid #e0e0e0;
    }

    h2 {
      color: var(--primary-color);
      font-weight: 600;
      margin: 0;
    }

    .content p {
      color: #555;
      line-height: 1.7;
      margin-bottom: 20px;
    }

    .content ul, .content ol {
      padding-left: 20px;
      margin-bottom: 20px;
    }

    .content li {
      margin-bottom: 10px;
      line-height: 1.6;
    }

    .card {
      border: none;
      border-radius: 8px;
      box-shadow: 0 2px 15px rgba(0,0,0,0.05);
      transition: transform 0.3s, box-shadow 0.3s;
      margin-bottom: 25px;
    }

    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }

    .card-header {
      border-radius: 8px 8px 0 0 !important;
      font-weight: 600;
    }

    .img-preview {
      border-radius: 8px;
      box-shadow: 0 3px 15px rgba(0,0,0,0.1);
      border: 1px solid #e0e0e0;
      transition: transform 0.3s;
    }

    .img-preview:hover {
      transform: scale(1.02);
    }

    .feature-icon {
      font-size: 1.5rem;
      color: var(--primary-color);
      margin-right: 10px;
    }

    .highlight {
      background-color: #f8f9fa;
      padding: 30px;
      border-radius: 8px;
      border-left: 4px solid var(--primary-color);
      margin: 20px 0;
    }

    .highlight pre {
      margin: 0;
      color: #333;
    }

    @media (max-width: 992px) {
      body {
        flex-direction: column;
      }
    .sidebar {
        width: 100%;
        max-width: 100%;
        padding: 15px 0;
        position: relative;
        height: auto;
    }

    .sidebar-header {
        padding: 0 15px 10px;
    }

    .nav-menu {
        display: flex;
        overflow-x: auto;
        padding: 10px 0 !important;
        white-space: nowrap;
    }

    .nav-menu a {
        padding: 8px 15px;
        display: inline-block;
        border-left: none;
        border-bottom: 3px solid transparent;
    }

    .nav-menu a:hover,
    .nav-menu a.active {
        border-left: none;
        border-bottom: 3px solid var(--accent-color);
    }

    .back-btn {
        margin: 10px auto;
        width: 90%;
        text-align: center;
    }
     
      .content {
        margin-left: 0; /* Remove offset for smaller screens */
        padding: 30px 20px;
    }
      
    }
  </style>
</head>
<body>

  <!-- Sidebar Navigation -->
 <div class="sidebar">
    <div class="sidebar-header">
        <a>
            <h4><i class="fas fa-heartbeat me-2"></i>CheckMySite</h4>
        </a>
    </div>
    <div class="nav-menu">
        <a href="#" onclick="showTab('tab1')" class="active">
            <i class="fas fa-home"></i> Overview
        </a>
        <a href="#" onclick="showTab('tab2')">
            <i class="fas fa-globe"></i> Website Monitoring
        </a>
        <a href="#" onclick="showTab('tab3')">
            <i class="fas fa-lock"></i> SSL Monitoring 
            <span class="badge-premium">Premium</span>
        </a>
        <a href="#" onclick="showTab('tab4')">
            <i class="fas fa-exclamation-triangle"></i> Incident Tracking
        </a>
        <a href="#" onclick="showTab('tab5')">
            <i class="fas fa-bell"></i> Alert Notifications
        </a>
        <a href="#" onclick="showTab('tab6')">
            <i class="fas fa-user-plus"></i> Add User
            <span class="badge-premium">Premium</span>
        </a>
        <a href="#" onclick="showTab('tab7')">
            <i class="fas fa-ticket-alt"></i> Raise Ticket
        </a>
        <a href="#" onclick="showTab('tab8')">
            <i class="fas fa-signal"></i> Status page
        </a>
        <a href="#" onclick="showTab('tab9')">
            <i class="fas fa-credit-card"></i> Plan & Subscription
        </a>
    </div>

    <!-- Back Button -->
    <a href="{{ url()->previous() }}" class="back-btn">
        <i class="fas fa-arrow-left"></i> Back
    </a>
</div>

  <!-- Main Content Area -->
  <div class="content">

    <!-- Overview Tab -->
    <div id="tab1" class="tab-content active">
      <div class="content-header">
        <h2><i class="fas fa-home feature-icon"></i> Overview</h2>
      </div>
      
      <p>CheckMySite is a comprehensive solution for tracking the availability and performance of your websites and online services in real-time. Get instant notifications when issues arise and maintain optimal uptime for your digital assets.</p>
      
      <div class="highlight">
        <h5>Key Features:</h5>
        <ul>
          <li><strong>Multi-protocol Monitoring:</strong> Track HTTP/HTTPS, Ping, Port, and DNS services</li>
          <li><strong>SSL Certificate Validation:</strong> Premium feature to monitor certificate expiration and configuration</li>
          <li><strong>Comprehensive Alerting:</strong> Receive notifications via Email and Telegram</li>
          <li><strong>Incident Management:</strong> Detailed tracking with root cause analysis and downtime duration</li>
          <li><strong>Performance Metrics:</strong> Response time tracking and historical data</li>
        </ul>
      </div>
      
      
    </div>

    <!-- Website Monitoring Tab -->
    <div id="tab2" class="tab-content">
      <div class="content-header">
        <h2><i class="fas fa-globe feature-icon"></i> Website Monitoring</h2>
      </div>
      
      <p class="lead">Monitor up to <strong>5 websites</strong> with different check types to ensure maximum uptime and optimal performance for your online services.</p>
      
      <div class="row align-items-center mb-5">
        <div class="col-lg-6">
          <div class="card">
            <div class="card-header bg-primary text-white">
              <h5 class="mb-0">Monitoring Types</h5>
            </div>
            <div class="card-body">
              <ul class="mb-0">
                <li><strong>HTTP/HTTPS:</strong> Verifies website reachability and returns HTTP status code</li>
                <li><strong>Ping:</strong> Measures network latency and packet loss</li>
                <li><strong>Port:</strong> Checks if specific TCP ports are open and responsive</li>
                <li><strong>DNS:</strong> Validates DNS resolution and record correctness</li>
              </ul>
            </div>
          </div>
        </div>
        <div class="col-lg-6 text-center">
          <img src="frontend/assets/img/form.png" class="img-fluid img-preview" alt="Monitor Form Preview">
        </div>
      </div>
      
      <div class="row align-items-center mb-5 flex-lg-row-reverse">
        <div class="col-lg-6">
          <div class="card">
            <div class="card-header bg-primary text-white">
              <h5 class="mb-0">Dashboard Overview</h5>
            </div>
            <div class="card-body">
              <p>Your dashboard provides a comprehensive view of all configured monitors with key metrics:</p>
              <ul class="mb-0">
                <li><strong>Status Indicators:</strong> Real-time up/down status with color coding</li>
                <li><strong>Check Type:</strong> Clear identification of monitoring protocol</li>
                <li><strong>Response Metrics:</strong> Latest response time and status code</li>
                <li><strong>Historical Data:</strong> Quick access to performance trends</li>
              </ul>
            </div>
          </div>
        </div>
        <div class="col-lg-6 text-center">
          <img src="frontend/assets/img/dashboard.png" class="img-fluid img-preview" alt="Dashboard Preview">
        </div>
      </div>
      
      <div class="row align-items-center">
        <div class="col-lg-6">
          <div class="card">
            <div class="card-header bg-primary text-white">
              <h5 class="mb-0">Monitor Management</h5>
            </div>
            <div class="card-body">
              <p>Full control over your monitoring configurations:</p>
              <ul class="mb-0">
                <li><strong>Pause Monitoring:</strong> Temporarily disable checks without deleting</li>
                <li><strong>Edit Configuration:</strong> Modify check parameters as needed</li>
                <li><strong>Delete Monitor:</strong> Remove unwanted checks permanently</li>
                <li><strong>Quick Status:</strong> Immediate manual check option</li>
              </ul>
            </div>
          </div>
        </div>
        <div class="col-lg-6 text-center">
          <img src="frontend/assets/img/monitor.png" class="img-fluid img-preview" alt="Monitor Actions Preview">
        </div>
      </div>
    </div>

    <!-- SSL Monitoring Tab -->
    <div id="tab3" class="tab-content">
      <div class="content-header">
        <h2><i class="fas fa-lock feature-icon"></i> SSL Monitoring <span class="badge-premium">Premium Feature</span></h2>
      </div>
      
      <p class="lead">Advanced SSL certificate monitoring to ensure your website security never lapses. Get alerts before certificates expire and verify proper configuration.</p>
      
      
      
      <div class="row align-items-center mb-5">
        <div class="col-lg-6">
          <div class="card">
            <div class="card-header bg-primary text-white">
              <h5 class="mb-0">SSL Check Interface</h5>
            </div>
            <div class="card-body">
              <p>Simple yet powerful interface to validate SSL certificates:</p>
              <ul class="mb-0">
                <li>Enter any website URL with HTTPS</li>
                <li>Comprehensive certificate validation</li>
                <li>Expiration tracking with countdown</li>
                <li>Configuration best practices check</li>
              </ul>
            </div>
          </div>
        </div>
        <div class="col-lg-6 text-center">
          <img src="frontend/assets/img/sslform.png" class="img-fluid img-preview" alt="SSL Form Preview">
        </div>
      </div>
      
      <div class="row align-items-center mb-5 flex-lg-row-reverse">
        <div class="col-lg-6">
          <div class="card">
            <div class="card-header bg-primary text-white">
              <h5 class="mb-0">Certificate Details</h5>
            </div>
            <div class="card-body">
              <p>Detailed certificate analysis includes:</p>
              <ul class="mb-0">
                <li><strong>Validity Status:</strong> Verified or problematic</li>
                <li><strong>Issuer Information:</strong> Certificate authority details</li>
                <li><strong>Domain Coverage:</strong> SAN and CN validation</li>
                <li><strong>Date Range:</strong> Issue and expiration dates</li>
                <li><strong>Security Grade:</strong> Protocol and cipher strength</li>
              </ul>
            </div>
          </div>
        </div>
        <div class="col-lg-6 text-center">
          <img src="frontend/assets/img/sslcheck.png" class="img-fluid img-preview" alt="SSL Check Result">
        </div>
      </div>
      
      <div class="card">
        <div class="card-header bg-primary text-white">
          <h5 class="mb-0">SSL History Tracking</h5>
        </div>
        <div class="card-body">
          <p>Premium users benefit from historical SSL data tracking:</p>
          <ul>
            <li>Trend analysis of certificate changes</li>
            <li>Expiration countdown timeline</li>
            <li>Configuration change alerts</li>
            <li>Exportable reports for compliance</li>
          </ul>
          <div class="text-center mt-3">
            <img src="frontend/assets/img/sslhistory.png" class="img-fluid img-preview" alt="SSL History" style="max-width: 80%;">
          </div>
        </div>
      </div>
    </div>

    <!-- Incident Tracking Tab -->
    <div id="tab4" class="tab-content">
      <div class="content-header">
        <h2><i class="fas fa-exclamation-triangle feature-icon"></i> Incident Tracking</h2>
      </div>
      
      <p class="lead">Comprehensive incident logging with detailed diagnostics to help you understand and resolve downtime issues quickly.</p>
      
      <div class="row">
        <div class="col-lg-8">
          <div class="card">
            <div class="card-header bg-primary text-white">
              <h5 class="mb-0">Incident Details</h5>
            </div>
            <div class="card-body">
              <p>Each incident record contains essential information for troubleshooting:</p>
              <ul>
                <li><strong>Root Cause Analysis:</strong> Identifies which check failed (HTTP, Ping, Port, or DNS)</li>
                <li><strong>Timeline:</strong> Precise start and end time of the outage</li>
                <li><strong>Duration:</strong> Total downtime calculated automatically</li>
                <li><strong>Status Tracking:</strong> Ongoing incidents vs resolved issues</li>
                <li><strong>Affected Components:</strong> Shows which monitors were impacted</li>
              </ul>
            </div>
          </div>
        </div>
       
      </div>
      
      <div class="text-center mt-4">
        <img src="frontend/assets/img/incidents.png" class="img-fluid img-preview" alt="Incident Log Preview" style="max-width: 80%;">
      </div>
    </div>

    <!-- Alert Notifications Tab -->
    <div id="tab5" class="tab-content">
      <div class="content-header">
        <h2><i class="fas fa-bell feature-icon"></i> Alert Notifications</h2>
      </div>
      
      <p class="lead">Configure multiple notification channels to ensure you never miss an alert when your services experience issues.</p>
      
      <div class="row mt-4">
        <div class="col-lg-6">
          <div class="card h-100">
            <div class="card-header bg-primary text-white">
              <h5 class="mb-0"><i class="fas fa-envelope me-2"></i> Email Alerts</h5>
            </div>
            <div class="card-body">
              <p>Simple email notification setup:</p>
              <ol>
                <li>Navigate to Notification Settings</li>
                <li>Add your email address</li>
                <li>Verify through confirmation email</li>
                <li>Configure alert preferences</li>
              </ol>
             
            </div>
          </div>
        </div>
        
        <div class="col-lg-6">
          <div class="card h-100">
            <div class="card-header bg-primary text-white">
              <h5 class="mb-0"><i class="fab fa-telegram me-2"></i> Telegram Alerts</h5>
            </div>
            <div class="card-body">
              <h6>Setup Instructions:</h6>
              <ol>
                <li><strong>Get Your Telegram ID:</strong>
                  <ul>
                    <li>Search for <code>@userinfobot</code> in Telegram</li>
                    <li>Start the bot and it will show your ID</li>
                  </ul>
                </li>
                <li><strong>Create a Bot Token:</strong>
                  <ul>
                    <li>Search for <code>@BotFather</code> in Telegram</li>
                    <li>Send <code>/newbot</code> command</li>
                    <li>Follow instructions to create your bot</li>
                    <li>Copy the provided HTTP API token</li>
                  </ul>
                </li>
                <li><strong>Configure in UP_TIME:</strong>
                  <ul>
                    <li>Enter your Telegram ID and bot token</li>
                    <li>Test the notification</li>
                    <li>Save configuration</li>
                  </ul>
                </li>
              </ol>
            </div>
          </div>
        </div>
      </div>
      
      <div class="card mt-4">
        <div class="card-header bg-primary text-white">
          <h5 class="mb-0"><i class="fas fa-sliders-h me-2"></i> Alert Preferences</h5>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <h6>Notification Triggers:</h6>
              <ul>
                <li>Service Down (Immediate)</li>
                <li>Service Recovery</li>
                <li>SSL Certificate Expiry (7/30/60 day warnings)</li>
                <li>Uptime Percentage Thresholds</li>
              </ul>
            </div>
            <div class="col-md-6">
              <h6>Alert Frequency:</h6>
              <ul>
                <li>Instant Alerts</li>
                <li>Daily Digest</li>
                <li>Weekly Summary</li>
                <li>Critical Only</li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!--Subuser Tab-->
    <div id="tab6" class="tab-content">
      <div class="content-header">
        <h2><i class="fas fa-user-plus feature-icon"></i> Add User</h2>
      </div>
      
      <p class="lead">Manage your team by adding users and assigning them specific permissions.</p>
      
      <div class="row mt-4">
        <div class="col-lg-12">
          <div class="card h-100">
            <div class="card-header bg-primary text-white">
              <h5 class="mb-0"><i class="fas fa-users me-2"></i> User Management</h5>
            </div>
            <div class="card-body">
              <p>To add a new user, follow these steps:</p>
              <ol>
                <li>Navigate to the User Management section</li>
                <li>Click on "Add User"</li>
                <li>Fill in the user details (name, email, etc.)</li>
                <li>Select permissions for the user</li>
                <li>Click "Save" to create the user</li>
              </ol>
            </div>
          </div>
        </div>
      </div>
      <div class="row mt-4">
        <div class="col-lg-12">
          <div class="card h-100">
            <div class="card-header bg-primary text-white">
              <h5 class="mb-0"><i class="fas fa-user-cog me-2"></i> User Permissions</h5>
            </div>
            <div class="card-body">
              <p>Permissions can be set for each user to control their access level:</p>
              <ul>
                <li><strong>User:</strong> Full access to all features</li>
                <li><strong>SubUser:</strong> Access to Features that the user gives permission's like dashboard,Incident,Status page</li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>

  <!--Raise Ticket Tab-->
    <div id="tab7" class="tab-content">
      <div class="content-header">
        <h2><i class="fas fa-ticket-alt feature-icon"></i> Raise Ticket</h2>
      </div>
      
      <div class="row mt-4">
        <div class="col-lg-12">
          <div class="card h-100">
            <div class="card-header bg-primary text-white">
              <h5 class="mb-0"><i class="fas fa-ticket-alt me-2"></i> Ticket Management</h5>
            </div>
            <div class="card-body">
              <p>To raise a ticket, follow these steps:</p>
              <ol>
                <li>Navigate to the Raise Issue section</li>
                <li>Click on "Raise Ticket"</li>
                <li>Fill in the ticket details (subject, description, etc.)</li>
                <li>Click "Submit" to create the ticket</li>
              </ol>
            </div>
          </div>
        </div>
      </div>
      <div class="row mt-4">
        <div class="col-lg-12">
          <div class="card h-100">
            <div class="card-header bg-primary text-white">
              <h5 class="mb-0"><i class="fas fa-history me-2"></i> Ticket History</h5>
            </div>
            <div class="card-body">
              <p>View your ticket history and track the status of your requests:</p>
              <ul>
                <li><strong>Open Tickets:</strong> The user or subuser can view all tickets that are currently open</li>
                <li><strong>Closed Tickets:</strong> View all tickets that have been resolved</li>
                <li><strong>Ticket Details:</strong> Click on a ticket to view its details and responses</li>
              </ul>
            </div>
          </div>
        </div>
      </div>
      <div class="row mt-4">
        <div class="col-lg-12">
          <div class="card h-100">
            <div class="card-header bg-primary text-white">
              <h5 class="mb-0"><i class="fas fa-comments me-2"></i> Ticket Responses</h5>
            </div>
            <div class="card-body">
              <p>Communicate with our support team through ticket responses:</p>
              <ul>
                <li><strong>View Responses:</strong> Check the responses from our support team</li>
                <li><strong>Add Comments:</strong> Add comments or additional information to your ticket</li>
                <li><strong>Close Ticket:</strong> The ticket is closed once the user issue is resolved</li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Status Page Tab -->
    <div id="tab8" class="tab-content">
      <div class="content-header">
        <h2><i class="fas fa-signal feature-icon"></i>Status page</h2>
      </div>
    <div class="row align-items-center mb-5">
        <div class="col-lg-6">
          <div class="card">
            <div class="card-header bg-primary text-white">
              <h5 class="mb-0">Status page view for user</h5>
            </div>
            <div class="card-body">
              <p>The users can view the status page from their account itself</p>
              <ul class="mb-0">
                <li>Navigate to the Status page section</li>
              <li>It will show the status of all the websites that are being monitored</li>
              </ul>
            </div>
          </div>
        </div>
        <div class="col-lg-6 text-center">
          <img src="frontend/assets/img/status_page.png" class="img-fluid img-preview" alt="SSL Form Preview">
        </div>
      </div>
      <div class="row align-items-center mb-5">
        <div class="col-lg-6">
          <div class="card">
            <div class="card-header bg-primary text-white">
              <h5 class="mb-0">How to make the status page Public</h5>
            </div>
            <div class="card-body">
              <p>If the user wants to make the status page to be seen by other's :</p>
              <ul class="mb-0">
                <li>Navigate to the Status page section</li>
              <li>It will show button to share status page,click on that button</li>
              <li>whitelist your ip address to make the status page public</li>
              <li>click on enable public status page</li>
              <li>now click on save settings button and your status page will be public</li>
              <li>you can share the link with anyone to view the status page</li>
              </ul>
            </div>
          </div>
        </div>
        <div class="col-lg-6 text-center">
          <img src="frontend/assets/img/public_status_page.png" class="img-fluid img-preview" alt="SSL Form Preview">
        </div>
      </div>
      
    </div>

    <!-- Plan & Subscription Tab -->
    <div id="tab9" class="tab-content">
  <div class="content-header">
    <h2><i class="fas fa-credit-card feature-icon"></i> Plan & Subscription</h2>
  </div>

  <p class="lead">Choose the plan that fits your monitoring needs and manage your subscription preferences.</p>

  <div class="row mt-4">
    <!-- Basic Plan -->
    <div class="col-lg-6 mb-4">
      <div class="card h-100">
        <div class="card-header bg-primary text-white">
          <h5 class="mb-0"><i class="fas fa-star me-2"></i> Basic Plan</h5>
        </div>
        <div class="card-body">
          <h4 class="card-title pricing-card-title">₹0<small class="text-muted fw-light">/month</small></h4>
          <ul class="list-unstyled mt-3 mb-4">
            <li class="mb-3"><i class="fas fa-check-circle text-primary"></i> Monitor 5 websites</li>
            <li class="mb-3"><i class="fas fa-check-circle text-primary"></i> 5-minute check</li>
            <li class="mb-3"><i class="fas fa-check-circle text-primary"></i> Email alerts</li>
            <li class="mb-3"><i class="fas fa-check-circle text-primary"></i> 1-Month history</li>
            <li class="mb-3"><i class="fas fa-times-circle text-primary"></i> Public Status page</li>
            <li class="mb-3"><i class="fas fa-times-circle text-danger"></i> Telegram alert unavailable</li>
            <li class="mb-3"><i class="fas fa-times-circle text-danger"></i> SSL expiry check unavailable</li>
            <li class="mb-3"><i class="fas fa-times-circle text-danger"></i> Team management unavailable</li>
          </ul>
        </div>
      </div>
    </div>

    <!-- Premium Plans -->
    @foreach($plans as $plan)
    <div class="col-lg-6 mb-4">
      <div class="card h-100 border-primary">
        <div class="card-header bg-primary text-white">
          <h5 class="mb-0"><i class="fas fa-crown me-2"></i> {{ $plan->name }}</h5>
        </div>
        <div class="card-body">
          <h4 class="card-title pricing-card-title">₹{{ $plan->amount }}<small class="text-muted fw-light">/month</small></h4>
          <ul class="list-unstyled mt-3 mb-4">
            <li class="mb-3"><i class="fas fa-check-circle text-primary"></i> All basic features</li>
            <li class="mb-3"><i class="fas fa-check-circle text-primary"></i> Monitor unlimited websites</li>
            <li class="mb-3"><i class="fas fa-check-circle text-primary"></i> 1-minute check</li>
            <li class="mb-3"><i class="fas fa-check-circle text-primary"></i> Telegram bot notification alert</li>
            <li class="mb-3"><i class="fas fa-check-circle text-primary"></i> 4-Month history</li>
            <li class="mb-3"><i class="fas fa-check-circle text-primary"></i> Public Status page</li>
            <li class="mb-3"><i class="fas fa-check-circle text-primary"></i> SSL expiry check</li>
            <li class="mb-3"><i class="fas fa-check-circle text-primary"></i> Team management</li>
          </ul>
        </div>
      </div>
    </div>
    @endforeach
  </div>
    </div>


  <script>
    function showTab(tabId) {
      // Hide all tab contents
      const tabs = document.querySelectorAll('.tab-content');
      tabs.forEach(tab => tab.classList.remove('active'));
      
      // Show selected tab
      document.getElementById(tabId).classList.add('active');
      
      // Update active nav link
      const navLinks = document.querySelectorAll('.nav-menu a');
      navLinks.forEach(link => link.classList.remove('active'));
      event.currentTarget.classList.add('active');
      
      // Scroll to top of content
      document.querySelector('.content').scrollTo(0, 0);
    }
  </script>
  <!--script so that tab remains active even after refresh-->
<script>
    // Function to show a specific tab
    function showTab(tabId) {
        // Hide all tab contents
        const tabs = document.querySelectorAll('.tab-content');
        tabs.forEach(tab => tab.classList.remove('active'));
        
        // Show the selected tab
        document.getElementById(tabId).classList.add('active');
        
        // Update active nav link
        const navLinks = document.querySelectorAll('.nav-menu a');
        navLinks.forEach(link => link.classList.remove('active'));
        event.currentTarget.classList.add('active');
        
        // Save the active tab ID to localStorage
        localStorage.setItem('activeTab', tabId);
        
        // Scroll to top of content
        document.querySelector('.content').scrollTo(0, 0);
    }

    // Restore the active tab on page load
    document.addEventListener('DOMContentLoaded', () => {
        const savedTab = localStorage.getItem('activeTab') || 'tab1'; // Default to 'tab1' if no tab is saved
        showTab(savedTab);

        // Highlight the corresponding nav link
        const navLinks = document.querySelectorAll('.nav-menu a');
        navLinks.forEach(link => {
            if (link.getAttribute('onclick').includes(savedTab)) {
                link.classList.add('active');
            }
        });
    });
</script>
</body>
</html>