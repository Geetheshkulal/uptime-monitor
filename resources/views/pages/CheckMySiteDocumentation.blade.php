<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>UP_TIME Monitoring System - Documentation</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    :root {
      --primary-color: #0d6efd;
      --primary-dark: #0a58ca;
      --accent-color: #ffc107;
      --sidebar-width: 280px;
    }
    
    
    body {
      display: flex;
      min-height: 100vh;
      margin: 0;
      font-family: 'Segoe UI', Roboto, 'Helvetica Neue', sans-serif;
      background-color: #f8f9fa;
      color: #333;
    }

    .sidebar {
      width: var(--sidebar-width);
      background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
      color: white;
      padding: 30px 0;
      box-shadow: 2px 0 10px rgba(0,0,0,0.1);
      position: relative;
      z-index: 10;
    }

    .sidebar-header {
      padding: 0 25px 20px;
      border-bottom: 1px solid rgba(255,255,255,0.1);
    }

    .sidebar h4 {
      font-weight: 600;
      margin: 0;
      font-size: 1.25rem;
    }

    .sidebar .nav-menu {
      padding: 20px 0;
    }

    .sidebar a {
      color: rgba(255,255,255,0.9);
      text-decoration: none;
      padding: 12px 25px;
      display: flex;
      align-items: center;
      font-size: 15px;
      transition: all 0.3s ease;
      border-left: 4px solid transparent;
      margin: 2px 0;
    }

    .sidebar a i {
      margin-right: 12px;
      width: 20px;
      text-align: center;
    }

    .sidebar a:hover {
      background-color: rgba(255, 255, 255, 0.1);
      border-left: 4px solid var(--accent-color);
      color: white;
    }

    .sidebar a.active {
      background-color: rgba(255, 255, 255, 0.15);
      border-left: 4px solid var(--accent-color);
      color: white;
    }

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

    .content {
      flex: 1;
      padding: 40px;
      overflow-y: auto;
      position: relative;
    }

    .back-btn {
      position: absolute;
      top: 20px;
      right: 20px;
      background-color: var(--primary-color);
      color: white;
      border: none;
      padding: 8px 16px;
      border-radius: 4px;
      font-size: 14px;
      transition: all 0.3s;
      display: flex;
      align-items: center;
    }

    .back-btn:hover {
      background-color: var(--primary-dark);
      transform: translateY(-1px);
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    .back-btn i {
      margin-right: 6px;
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
      
      .content {
        padding: 30px 20px;
      }
      
      .back-btn {
        position: static;
        margin-bottom: 20px;
        display: inline-flex;
      }
    }
    .monitor-module-doc {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #ffffff;
    padding: 30px;
    border-radius: 16px;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.05);
    color: #333;
    line-height: 1.7;
    max-width: 100%;
}

.doc-header h2 {
    font-size: 28px;
    font-weight: bold;
    color: #007bff;
    margin-bottom: 10px;
}

.doc-header p {
    font-size: 16px;
    color: #666;
}

.doc-section {
    margin-top: 40px;
    padding: 20px;
    border-left: 4px solid #007bff;
    background-color: #f9f9f9;
    border-radius: 12px;
    box-shadow: inset 0 0 10px rgba(0, 0, 0, 0.02);
}

.doc-section h3 {
    font-size: 22px;
    color: #333;
    margin-bottom: 10px;
}

.doc-section p {
    font-size: 15px;
    color: #555;
}

pre {
    background-color: #2d2d2d;
    color: #f8f8f2;
    padding: 15px;
    border-radius: 10px;
    overflow-x: auto;
    font-size: 14px;
    margin: 20px 0;
}

code {
    font-family: 'Courier New', Courier, monospace;
    display: block;
    white-space: pre;
}

.doc-footer {
    margin-top: 50px;
    padding-top: 20px;
    border-top: 1px solid #ddd;
}

.doc-footer h4 {
    font-size: 20px;
    color: #007bff;
    margin-bottom: 10px;
}
  

    pre code .comment {
        color: green; /* Comment color */
    }

  </style>
</head>
<body>

  <!-- Sidebar Navigation -->
  <div class="sidebar">
    <div class="sidebar-header">
      <h4><i class="fas fa-heartbeat me-2"></i>CheckMySite</h4>
    </div>
    <div class="nav-menu">
      <a href="#" onclick="showTab('tab1')" class="active">
        <i class="fas fa-home"></i> Overview
      </a>
      <a href="#" onclick="showTab('tab2')">
        <i class="fas fa-globe"></i>Monitor
      </a>
      <a href="#" onclick="showTab('tab3')">
        <i class="fas fa-lock"></i> SSL Check
        <span class="badge-premium">Premium feature</span>
      </a>      
      <a href="#" onclick="showTab('tab4')">
        <i class="fas fa-exclamation-triangle"></i> Incident Tracking
      </a>
      <a href="#" onclick="showTab('tab5')">
        <i class="fas fa-bell"></i> Alert Notifications
      </a>
      <a href="#" onclick="showTab('tab6')">
        <i class="fas fa-credit-card"></i> Plan & Subscription
      </a>
    </div>
  </div>

  <!-- Main Content Area -->
<div class="content">
    <a href="/" class="back-btn">
      <i class="fas fa-arrow-left"></i> Back
    </a>

    <!-- Overview Tab -->
    <div id="tab1" class="tab-content active">
        <div class="container py-4">
            <div class="card shadow rounded-4 mb-4">
                <div class="card-body">
                    <h2 class="card-title fw-bold text-primary">🚀 Uptime Monitoring System</h2>
                    <p class="text-muted">A robust Laravel-based solution for monitoring website availability, SSL certificates, and server status with real-time alerts.</p>
                </div>
            </div>
        
            <!-- Features -->
            <div class="card shadow-sm rounded-4 mb-4">
                <div class="card-body">
                    <h4 class="fw-bold text-success">🌟 Features</h4>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">🖥 <strong>Monitoring:</strong> HTTP/HTTPS status checks, DNS record validation, Ping availability testing, Port scanning, SSL certificate expiration tracking</li>
                        <li class="list-group-item">🔔 <strong>Notifications:</strong> Email alerts (SMTP), Telegram bot integration</li>
                        <li class="list-group-item">💳 <strong>Payment Integration:</strong> Cashfree gateway, Free & Premium subscriptions</li>
                        <li class="list-group-item">👤 <strong>User Management:</strong> Role-based access, multi-tier authentication</li>
                        <li class="list-group-item">🔑 <strong>User Access Levels:</strong> 
                            <ul>
                                <li>Free Tier: Up to 5 monitors, email + Telegram alerts, priority support</li>
                                <li>Premium Tier: Unlimited monitors, email + Telegram alerts, priority support</li>
                            </ul>
                        </li>
                        <li class="list-group-item">📊 <strong>Dashboard:</strong> Real-time charts with Chart.js, uptime stats, incident logs</li>
                    </ul>
                </div>
            </div>
        
            <!-- Tech Stack -->
            <div class="card shadow-sm rounded-4 mb-4">
                <div class="card-body">
                    <h4 class="fw-bold text-info">🛠 Tech Stack</h4>
                    <ul>
                        <li><strong>Frontend:</strong> Blade, Bootstrap</li>
                        <li><strong>Backend:</strong> Laravel, MySQL</li>
                        <li><strong>Monitoring & Charts:</strong> Chart.js</li>
                        <li><strong>Logging:</strong> Spatie Laravel Activity Log</li>
                    </ul>
                </div>
            </div>
        
            <!-- Installation Guide -->
            <div class="card shadow-sm rounded-4 mb-4">
                <div class="card-body">
                    <h4 class="fw-bold text-warning">📌 Installation</h4>
                    <ol class="list-group list-group-numbered">
                        <li class="list-group-item">
                            <strong>Clone the Repository</strong>
                            <pre><code>git clone https://github.com/your-username/uptime-monitoring.git
        cd uptime-monitoring</code></pre>
                        </li>
                        <li class="list-group-item">
                            <strong>Install Dependencies</strong>
                            <pre><code>composer install
        npm install</code></pre>
                        </li>
                        <li class="list-group-item">
                            <strong>Setup Environment</strong>
                            <pre><code>cp .env.example .env
        php artisan key:generate</code></pre>
                            Edit your <code>.env</code> file and set DB credentials.
                        </li>
                        <li class="list-group-item">
                            <strong>Run Migrations</strong>
                            <pre><code>php artisan migrate</code></pre>
                        </li>
                        <li class="list-group-item">
                            <strong>Seed Data (Optional)</strong>
                            <pre><code>php artisan db:seed</code></pre>
                        </li>
                        <li class="list-group-item">
                            <strong>Start Development Server</strong>
                            <pre><code>php artisan serve</code></pre>
                        </li>
                    </ol>
                </div>
            </div>
        
            <!-- Cashfree Setup -->
            <div class="card shadow-sm rounded-4">
                <div class="card-body">
                    <h4 class="fw-bold text-danger">💰 Setting Up Cashfree Payments</h4>
                    <ol>
                        <li>Register on <a href="https://www.cashfree.com/" target="_blank">Cashfree</a> and get your API keys.</li>
                        <li>Update your <code>.env</code> file with:
                            <pre><code>CASHFREE_API_KEY=
        CASHFREE_API_SECRET=</code></pre>
                        </li>
                    </ol>
                </div>
            </div>
        </div>
        
      
      <p>CheckMySite is a comprehensive solution for tracking the availability and performance of your websites and online services in real-time. Get instant notifications when issues arise and maintain optimal uptime for your digital assets.</p>
      
      <div class="highlight">
        <h5>Key Features:</h5>
        <ul>
          <li><strong>Multi-protocol Monitoring:</strong> Track HTTP/HTTPS, Ping, Port, and DNS services</li>
          <li><strong>SSL Check:</strong> Premium feature to monitor certificate expiration and configuration</li>
          <li><strong>Comprehensive Alerting:</strong> Receive notifications via Email and Telegram</li>
          <li><strong>Incident Management:</strong> Detailed tracking with root cause analysis and downtime duration</li>
          <li><strong>Performance Metrics:</strong> Response time tracking and historical data</li>
        </ul>
      </div>
      
      
    </div>

    <!-- Website Monitoring Tab -->
    <div id="tab2" class="tab-content">
        <div class="monitor-module-doc">
            <!-- Title Section -->
            <div class="doc-header">
                <h2>Monitor Module</h2>
                <p>This section describes the functionality of the Monitor Module in the CheckMySite application, covering monitor creation, management, and response handling for different monitor types (Ping, Port, HTTP, and DNS).</p>
            </div>

            <!-- Monitor Creation Section -->
            <div class="doc-section">
                <h3>Monitor Creation</h3>
                <p>To create a new monitor, navigate to the <strong>Monitor Dashboard</strong> page, click <strong>Add Monitor</strong>, and fill in the monitor type (Ping, Port, HTTP, DNS).</p>
            </div>

            <!-- HTTP Monitor Section -->
            <div class="doc-section">
                <h3>HTTP Monitor</h3>
                <p>The HTTP monitor checks the status and response time of a URL. It sends a request to the given URL and tracks the response time and status code.</p>

                <!-- Code Snippet for HTTP Monitor Handling -->
                <pre><code>
                    
                    public function checkHttp(Monitors $monitor)
                    {
                        $start = microtime(true); // Record start time
                    
                        try {
                            // Send HTTP GET request to the monitor's URL
                            $response = Http::timeout(10)->get($monitor->url);
                            $end = microtime(true); // Record end time
                    
                            // Save HTTP response details in database
                            HttpResponse::create([
                                'monitor_id'    => $monitor->id,
                                'status_code'   => $response->status(), // HTTP status code
                                'response_time' => round(($end - $start) * 1000, 2), // ms
                                'checked_at'    => now(), // Timestamp of the check
                            ]);
                        } catch (\Exception $e) {
                            $end = microtime(true);
                    
                            // Store failed response with null status code
                            HttpResponse::create([
                                'monitor_id'    => $monitor->id,
                                'status_code'   => null,
                                'response_time' => round(($end - $start) * 1000, 2),
                                'checked_at'    => now(),
                            ]);
                        }
                    }                
                </code></pre>

                <p>The above method checkHttp function is designed to perform an HTTP check for a given monitor. It verifies the status of the service by sending an HTTP GET request to the monitor's URL and records the response details, including the response time and HTTP status code. <strong>HttpResponse</strong> table.</p>
            </div>

            <!-- Ping Monitor Section -->
            <div class="doc-section">
                <h3>Ping Monitor</h3>
                <p>The Ping monitor checks the response time of a network ping to a server's IP address. It helps to determine if a server is reachable.</p>

                <!-- Code Snippet for Ping Monitor Handling -->
                <pre><code>

                    public function checkPing(Monitors $monitor)
                    {
                        // Extract host from URL or use as-is
                        $host = parse_url($monitor->url, PHP_URL_HOST) ?? $monitor->url;
                    
                        $start = microtime(true); // Start timing
                    
                        // Detect OS type to use correct ping command
                        $isWindows = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
                    
                        // Use OS-specific ping command
                        $command = $isWindows ? "ping -n 1 $host" : "ping -c 1 $host";
                    
                        // Execute ping command
                        exec($command, $output, $status);
                    
                        $end = microtime(true); // End timing
                    
                        // Store ping response in database
                        PingResponse::create([
                            'monitor_id'    => $monitor->id,
                            'status'        => $status === 0 ? 'up' : 'down', // Status 0 = success
                            'response_time' => round(($end - $start) * 1000, 2),
                            'checked_at'    => now(),
                        ]);
                    }
                    
                </code></pre>

                <p>The above method is designed to monitor the availability of a service by performing a ping test to the target URL of a given monitor. It checks the response time and the availability (up or down) of the service based on the result of the ping command<strong>PingResponse</strong> table.</p>
            </div>

            <!-- Port Monitor Section -->
            <div class="doc-section">
                <h3>Port Monitor</h3>
                <p>The Port monitor checks whether a specific port is open on a given server. It attempts to connect to the server on the specified port.</p>

                <!-- Code Snippet for Port Monitor Handling -->
                <pre><code>

                    public function checkPort(Monitors $monitor)
                    {
                        $start = microtime(true); // Start timing
                    
                        // Try opening the given host:port with a timeout
                        $connection = @fsockopen($monitor->host, $monitor->port, $errno, $errstr, 10);
                        $end = microtime(true); // End timing
                    
                        // Save port status and response time
                        PortResponse::create([
                            'monitor_id'    => $monitor->id,
                            'port'          => $monitor->port,
                            'status'        => $connection ? 'up' : 'down', // Based on connection
                            'response_time' => round(($end - $start) * 1000, 2),
                            'checked_at'    => now(),
                        ]);
                    
                        // Close connection if successful
                        if ($connection) {
                            fclose($connection);
                        }
                    }
                    
                </code></pre>

                <p>function is responsible for checking the availability of a specific port on a given host (e.g., a server). It attempts to establish a connection to the provided host and port, measures the response time, and records whether the port is open (up) or closed (down)</strong> table.</p>
            </div>

            <!-- DNS Monitor Section -->
            <div class="doc-section">
                <h3>DNS Monitor</h3>
                <p>The DNS monitor checks the DNS resolution time for a domain. It ensures that the domain can be resolved correctly to an IP address.</p>

                <!-- Code Snippet for DNS Monitor Handling -->
                <pre><code>

                    public function checkDnsRecords(Monitors $monitor)
                    {
                        $start = microtime(true); // Start timing
                    
                        // Parse host from URL or fallback to plain domain
                        $parsed = parse_url($monitor->url);
                        $host = $parsed['host'] ?? $monitor->url;
                    
                        // Default to 'A' record type if not specified
                        $type = $monitor->dns_record_type ?? 'A';
                    
                        // Perform DNS lookup of specified type
                        $records = dns_get_record($host, constant("DNS_{$type}"));
                        $end = microtime(true); // End timing
                    
                        // Store DNS response data in database
                        DnsResponse::create([
                            'monitor_id'    => $monitor->id,
                            'record_type'   => $type,
                            'found_records' => json_encode($records), // Save records as JSON
                            'response_time' => round(($end - $start) * 1000, 2),
                            'checked_at'    => now(),
                        ]);
                    }
                    
                </code></pre>

                <p>function is responsible for checking DNS records for a given host (domain) to ensure that it resolves correctly and to gather data about the specified record type (e.g., A record, MX record). The function measures the time it takes to perform the DNS lookup and stores the results</strong> table.</p>
            </div>

            <!-- Monitor Management Section -->
            <div class="doc-section">
                <h3>Monitor Management</h3>
                <p>Monitors can be edited, paused, resumed, or deleted from the Monitor Dashboard. Each action triggers the appropriate method to update the monitor's status.</p>

                <!-- Code Snippet for Pausing a Monitor -->
                <pre><code>
                    public function pauseMonitor(Request $request, $id)
                    {
                        // Get the currently authenticated user
                        $user = auth()->user();
                    
                        // Retrieve the monitor by its ID, or fail if it does not exist
                        $monitor = Monitors::findOrFail($id);
                    
                        // Toggle the 'paused' status of the monitor (if paused, resume it, if not paused, pause it)
                        $monitor->paused = !$monitor->paused;
                    
                        // Save the updated monitor status to the database
                        $monitor->save();
                    
                        // Determine the status message based on whether the monitor is paused or resumed
                        $status = $monitor->paused ? 'paused' : 'resumed';
                    
                        // Log the activity with the details of the action (pausing/resuming the monitor)
                        activity()
                            ->performedOn($monitor) // Specify the monitor object being affected
                            ->causedBy(auth()->user()) // Log the user who performed the action
                            ->inLog('monitor_management') // Log category for monitoring actions
                            ->event($status) // Event name based on whether the monitor is paused or resumed
                            ->withProperties([ // Attach properties to the log entry
                                'name' => $user->name, // The name of the user performing the action
                                'monitor_id' => $monitor->id, // The ID of the monitor being paused/resumed
                                'monitor_name' => $monitor->name, // The name of the monitor
                                'status' => $status, // The new status (paused/resumed)
                            ])
                            ->log("Monitor {$monitor->name} has been {$status}"); // Custom log message
                    
                        // Return a JSON response with the result of the action (success status, message, and updated monitor status)
                        return response()->json([
                            'success' => true,
                            'message' => "Monitor has been {$status} successfully.",
                            'paused' => $monitor->paused, // Include the current paused status of the monitor
                        ]);
                    }
                    
                </code></pre>

                <p>function is responsible for toggling the "paused" status of a specific monitor. When a monitor is paused, it stops being checked for availability, and when resumed, it starts being monitored again.</p>

                <!-- Code Snippet for Deleting a Monitor -->
                <pre><code>
                    public function MonitorDelete($id)
                    {
                        // Retrieve the monitor by its ID, or fail if it does not exist
                        $DeleteMonitor = Monitors::findOrFail($id);
                    
                        // If the monitor doesn't exist (though findOrFail would have already returned an error)
                        if (!$DeleteMonitor) {
                            // Redirect back with an error message if the monitor was not found
                            return redirect()->back()->with('error', 'Monitoring data not found.');
                        }
                    
                        // Proceed to delete the monitor from the database
                        $DeleteMonitor->delete();
                    
                        // Log the deletion activity with relevant details
                        activity()
                            ->performedOn($DeleteMonitor) // Specify the monitor object being deleted
                            ->causedBy(auth()->user()) // Log the user who performed the action
                            ->inLog('monitor_management') // Log category for monitoring actions
                            ->event('monitor deleted') // Event name for the deletion action
                            ->withProperties([ // Attach properties to the log entry
                                'monitor_name' => $DeleteMonitor->name, // Name of the monitor being deleted
                                'monitor_type' => $DeleteMonitor->type, // Type of monitor (HTTP, DNS, etc.)
                                'user_id' => auth()->id(), // ID of the user who deleted the monitor
                                'ip' => request()->ip(), // IP address of the user performing the action
                            ])
                            ->log("User deleted {$DeleteMonitor->type} monitor"); // Custom log message
                    
                        // Redirect to the monitoring dashboard with a success message after deletion
                        return redirect()->route('monitoring.dashboard')->with('success', 'Monitoring data deleted successfully.');
                    }
                    
                </code></pre>

                <p>function is responsible for deleting a monitor from the database. This method ensures that a monitor can be safely removed, logs the action for auditing purposes, and provides user feedback.</p>
                <p>To edit monitor</p>
                <pre><code>
                    public function MonitorEdit(Request $request, $id)
                    {
                        // Validate the incoming request data to ensure proper format and types.
                        $request->validate([
                            'name' => 'required|string|max:255', // Name must be a string and a max of 255 characters
                            'url' => 'required|url', // URL must be a valid URL
                            'retries' => 'required|integer|min:1', // Retries must be an integer greater than or equal to 1
                            'interval' => 'required|integer|min:1', // Interval must be an integer greater than or equal to 1
                            'email' => 'required|email', // Email must be a valid email
                            'port' => 'nullable|integer', // Port is optional but must be an integer if provided
                            'dns_resource_type' => 'nullable|string', // DNS resource type is optional but must be a string if provided
                            'telegram_id' => 'nullable|string', // Telegram ID is optional but must be a string if provided
                            'telegram_bot_token' => 'nullable|string', // Telegram bot token is optional but must be a string if provided
                            'type' => 'string' // Type must be a string
                        ]);
                    
                        // Find the monitor by its ID, or fail if not found
                        $EditMonitoring = Monitors::findOrFail($id);
                    
                        // Get the original values of the monitor before updating, to compare later
                        $original = $EditMonitoring->getOriginal();
                    
                        // Update the monitor's attributes with the request data
                        $EditMonitoring->update($request->all());
                    
                        // Initialize the changes array to track old and new values
                        $changes = [
                            'old' => [],
                            'new' => [],
                        ];
                    
                        // Loop through all the incoming request data and compare it with the original values
                        foreach ($request->all() as $key => $value) {
                            // If the key exists in the original monitor and the value has changed
                            if (array_key_exists($key, $original) && $original[$key] != $value) {
                                // Add the old value and new value to the changes array for logging
                                $changes['old'][$key] = $original[$key];
                                $changes['new'][$key] = $value;
                            }
                        }
                    
                        // Log the activity, indicating that the monitor was updated
                        activity()
                            ->performedOn($EditMonitoring) // Specify the object being updated
                            ->causedBy(auth()->user()) // Log the user who made the change
                            ->inLog('monitor_management') // Specify the log type/category
                            ->event('updated monitor') // Specify the event name
                            ->withProperties($changes) // Attach the changes (old and new data) to the log entry
                            ->log('Monitoring details updated'); // Log the message
                    
                        // Redirect back to the previous page with a success message
                        return redirect()->back()->with('success', 'Monitoring details updated successfully.');
                    }
                    
                </code></pre>
                <p> function is designed to handle the editing and updating of a monitor's details in the system. It ensures that the incoming data is valid, updates the monitor record with the new values, and logs the changes for auditing purposes.</p>
            </div>

            <!-- Conclusion Section -->
            <div class="doc-footer">
                <h4>Conclusion</h4>
                <p>The Monitor Module in CheckMySite provides a comprehensive solution for tracking the status of various services using Ping, Port, HTTP, and DNS monitors. Each type of monitor is handled with specific logic tailored to its purpose, ensuring accurate monitoring and reliable alerting.</p>
            </div>
        </div>
    </div>


    <!-- SSL Check Tab -->
    <div id="tab3" class="tab-content">
        <div class="monitor-module-doc">
            <!-- Title Section -->
            <div class="doc-header">
                <h2>SSL Module</h2>
                <p>This section describes the functionality of the SSL Module in the CheckMySite application which is a Premium feature, covering SSL Checks and additonally how each check is stored aand displayed in history tab</p>
            </div>
    
            <!-- SSL Check  -->
            <div class="doc-section">
                <h3>SSl Check</h3>
                <p>To check a website's SSL Certificate valididty , navigate to the <strong>SSL Check</strong> page, and fill in the URL and click on<strong>Check SSL Expiry</strong> button.</p>
            </div>
    
            <!-- HTTP Monitor Section -->
            <div class="doc-section">
                <h3>SSL Check</h3>
                <p>The SSL Check's checks the validity of a SSL Certificate and additonal information about the SSL certificate.</p>
    
                <!-- Code Snippet for HTTP Monitor Handling -->
                <pre><code>
                    public function check(Request $request)
                    {
                        //Validate the incoming request to ensure 'domain' is present and is a valid URL
                        $request->validate([
                            'domain' => 'required|url',
                        ]);
                    
                        //Get the input domain from the form and extract just the host (e.g., example.com)
                        $inputUrl = $request->domain;
                        $host = parse_url($inputUrl, PHP_URL_HOST); // Extract host from full URL
                    
                        // If host is not extracted properly, try extracting manually (handles edge cases)
                        if (!$host) {
                            $inputUrl = preg_replace('#^https?://#', '', $inputUrl); // Remove http:// or https://
                            $host = explode('/', $inputUrl)[0]; // Get only the domain part
                        }
                    
                        try {
                            Create a stream context to capture SSL certificate information
                            $context = stream_context_create([
                                "ssl" => ["capture_peer_cert" => true]
                            ]);
                    
                            Open an SSL connection to the domain on port 443 (HTTPS)
                            $stream = @stream_socket_client(
                                "ssl://{$host}:443",  // Target SSL address
                                $errno,               // Error number (if any)
                                $errstr,              // Error string (if any)
                                10,                   // Timeout: 10 seconds
                                STREAM_CLIENT_CONNECT,// Connect mode
                                $context              // Stream context with SSL capture
                            );
                    
                            // If unable to connect, throw an exception
                            if (!$stream) {
                                throw new \Exception("Could not connect to '{$host}' ({$errstr})");
                            }
                    
                            // Retrieve the stream context parameters, including SSL certificate data
                            $params = stream_context_get_params($stream);
                    
                            // Parse the SSL certificate into readable array using OpenSSL
                            $cert = openssl_x509_parse($params['options']['ssl']['peer_certificate']);
                    
                            //  Extract relevant dates and compute certificate status
                            $validFrom = Carbon::createFromTimestamp($cert['validFrom_time_t']); // Start date
                            $validTo = Carbon::createFromTimestamp($cert['validTo_time_t']);     // Expiry date
                            $daysRemaining = Carbon::now()->diffInDays($validTo, false);         // Days left
                            $status = $daysRemaining <= 0 ? 'Expired' : 'Valid';                 // Set status
                    
                            // Store the SSL data in the database for future reference
                            $ssl = Ssl::create([
                                'user_id'        => Auth::id(),                                // Owner
                                'url'            => $host,                                     // Domain
                                'issuer'         => $cert['issuer']['CN'] ?? 'Unknown',        // Cert issuer
                                'valid_from'     => $validFrom,                                // Start date
                                'valid_to'       => $validTo,                                  // Expiry date
                                'days_remaining' => $daysRemaining,                            // Days left
                                'status'         => $status                                    // Valid/Expired
                            ]);
                    
                            //  Log this monitoring activity for auditing purposes
                            activity()
                                ->causedBy(auth()->user())           // Who performed the action
                                ->performedOn($ssl)                  // What the action was on
                                ->inLog('ssl_monitoring')            // Log channel
                                ->event('created')                   // Type of event
                                ->withProperties([                   // Additional metadata
                                    'url' => $host,
                                    'issuer' => $cert['issuer']['CN'] ?? 'Unknown',
                                    'valid_to' => $validTo->toDateString(),
                                    'status' => $status
                                ])
                                ->log('SSL certificate monitored and logged.');
                    
                            // Redirect back with success message and certificate details
                            return redirect()->back()->with([
                                'success' => 'SSL check successful!',
                                'ssl_details' => [
                                    'domain'         => $host,
                                    'issuer'         => $cert['issuer']['CN'] ?? 'Unknown',
                                    'valid_from'     => $validFrom->toDateString(),
                                    'valid_to'       => $validTo->toDateString(),
                                    'days_remaining' => $daysRemaining,
                                    'status'         => $status
                                ]
                            ]);
                    
                        } catch (\Exception $e) {
                            //Catch errors and redirect back with a failure message
                            return redirect()->back()->with('error', "No valid SSL certificate found for '{$host}'.");
                        }
                    }                                                      
                </code></pre>
                <p>The `check()` function is responsible for monitoring the SSL certificate of a user-submitted domain. It begins by validating the input URL and extracting the host name. It then attempts to establish a secure connection to the domain on port 443 and captures the SSL certificate using a stream context. Once the certificate is retrieved, it parses the data to extract the issuer's name, validity period (from and to dates), and calculates how many days remain until the certificate expires. Based on this, it determines whether the certificate is still valid or expired. The certificate details are then stored in the database, linked to the currently authenticated user. Additionally, the function logs this SSL monitoring activity for auditing purposes. If the certificate cannot be retrieved, a user-friendly error message is displayed.</p>
                <h2>Additional feature </h2>
                <br>
                <h3>SSL History</h3>
            <p>Below is the code for the SSL history page:</p>
            <pre><code>
                public function history()
                {
                    // Retrieve all SSL check records for the currently authenticated user,
                    // ordered by the most recent entries first.
                    $sslChecks = Ssl::where('user_id', Auth::id())->latest()->get();
                
                    // Pass the retrieved records to the 'ssl.history' Blade view.
                    return view('ssl.history', compact('sslChecks'));
                }
            </code></pre>    
            <p>This history() function retrieves the SSL check history for the currently authenticated user. It queries the Ssl table to get all SSL records associated with the logged-in user (user_id), orders them by the latest entry first using latest(), and passes the results to the ssl.history Blade view using the compact() function. This allows the user to view a list of all SSL monitoring checks they have previously performed.</p>
            </div>
            <div class="doc-footer">
                <h4>Conclusion</h4>
                <p>The SSL Certificate Expiry Monitoring feature ensures your website’s SSL certificates are always valid. It provides real-time alerts for expiring certificates and detailed info on their status, issuer, and validity. Notifications via email and Telegram help you stay informed, while the system logs all SSL checks for easy access to historical data, enhancing your website’s security and reliability.</p>
            </div>
        </div>
    </div>

    <!-- Incident Tracking Tab -->
    <div id="tab4" class="tab-content">
        <div class="monitor-module-doc">
            <!-- Title Section -->
            <div class="doc-header">
                <h2>Incident Module</h2>
                <p>This section describes the functionality of the Incident Module in the CheckMySite application. This feature covers the monitoring and tracking of incidents related to your monitored websites and servers, allowing you to stay updated on any potential issues with your online services.</p>
            </div>
    
            <!-- Incident Check -->
            <div class="doc-section">
                <h3>Incident Monitoring</h3>
                <p>To monitor incidents for your website or server, navigate to the <strong>Incidents</strong> page, where all incidents related to your monitors will be displayed. The system automatically records incidents based on HTTP, DNS, and Port checks, and alerts you about any abnormalities in the monitoring data.</p>
            </div>
    
            <!-- Incident List Section -->
            <div class="doc-section">
                <h3>Incident Creation</h3>
            <p>When a monitor goes down, the system automatically creates an incident. This section details how incidents are created when the status is 'down' and how they are resolved when the status is 'up'.</p>

            <!-- Code Snippet for Incident Creation -->
            <pre><code>
                private function createIncident(Monitors $monitor, string $status, string $monitorType)
                {
                    // If the status is 'down', we create an incident
                    if ($status === 'down') {
                        // Check if there's an existing 'down' incident for the same monitor that's still open (no end_timestamp)
                        $existingIncident = Incident::where('monitor_id', $monitor->id)
                            ->where('status', 'down')  // Looking for incidents that are 'down'
                            ->whereNull('end_timestamp')  // Ensure that the incident is still open
                            ->first();
                        
                        // If no existing open incident, create a new one
                        if (!$existingIncident) {
                            Incident::create([
                                'monitor_id' => $monitor->id,
                                'status' => 'down',
                                'root_cause' => "{$monitorType} Monitoring Failed",  // Log the type of failure (e.g., Ping, DNS, HTTP)
                                'start_timestamp' => now(),
                                'updated_at' => now(),
                            ]);
                        }
                    }

                    // If the status is 'up', we check and close any open incidents
                    elseif ($status === 'up') {
                        // Check for any open incidents (status = 'down' and no end_timestamp)
                        $incident = Incident::where('monitor_id', $monitor->id)
                            ->where('status', 'down')
                            ->whereNull('end_timestamp')  // Ensure it's open (still 'down')
                            ->first();

                        // If an open incident is found, mark it as resolved
                        if ($incident) {
                            $incident->update([
                                'status' => 'up',
                                'end_timestamp' => now(),  // Set the time the monitor came back up
                                'updated_at' => now(),
                            ]);
                        }
                    }
                }
            </code></pre>
            <p>The `createIncident()` function handles the creation and resolution of incidents based on the status of a monitor. When the status is 'down', it checks if there are any open incidents for the same monitor. If no incident is found, a new one is created with the appropriate status and root cause. If the status changes to 'up', the system searches for any open 'down' incidents for the monitor and resolves them by updating the status and setting the end timestamp.</p>
        

                <h3>Viewing Incidents</h3>
                <p>On the incidents page, you will see a list of all incidents that have occurred with your monitors. The data is dynamically fetched from the server using an AJAX request for real-time updates. You can also refresh the incident list to see the most up-to-date information about your monitors' status.</p>
    
                <!-- Code Snippet for Fetching Incidents -->
                <pre><code>
                    public function incidents()
                    {
                        // Get the logged-in user's ID
                        $userId = Auth::id();
    
                        // Get the monitor IDs associated with the logged-in user
                        $userMonitors = Monitors::where('user_id', $userId)->pluck('id');
    
                        // Fetch incidents that belong to the logged-in user's monitors
                        $incidents = Incident::with('monitor') // Load incidents with associated monitors
                            ->whereIn('monitor_id', $userMonitors) // Filter incidents by monitor IDs
                            ->get();
                        
                        // Log the user's visit to the incidents page
                        $tempMonitor = Monitors::where('user_id', $userId)->first();
                        if ($tempMonitor) {
                            activity()
                                ->performedOn($tempMonitor)
                                ->causedBy(auth()->user())
                                ->inLog('incident monitoring')
                                ->event('visited')
                                ->withProperties([
                                    'name' => auth()->user()->name,
                                    'email' => auth()->user()->email,
                                    'page' => 'Incidents Page'
                                ])
                                ->log('Visited the incidents page');
                        }
    
                        return view('pages.incidents', compact('incidents'));
                    }
                </code></pre>
                <p>The `incidents()` function retrieves the incidents related to the user's monitors and passes them to a Blade view for display. The function also logs the user's activity for auditing purposes.</p>
    
                
    
                <h2>Additional Features</h2>
                <br>
                <h3>Dynamic Incident Data</h3>
                <p>The following method is responsible for fetching incidents dynamically, using AJAX to ensure that the incident list is always up-to-date without needing to refresh the page.</p>
    
                <!-- Code Snippet for Fetching Incidents Dynamically -->
                <pre><code>
                    public function fetchIncidents()
                    {
                        $userId = Auth::id();
    
                        // Get the monitor IDs associated with the logged-in user
                        $userMonitors = Monitors::where('user_id', $userId)->pluck('id');
    
                        // Fetch incidents with related monitor data
                        $incidents = Incident::with('monitor')
                            ->whereIn('monitor_id', $userMonitors)
                            ->get();
    
                        return response()->json(['incidents' => $incidents]);
                    }
                </code></pre>
                <p>The `fetchIncidents()` method fetches incidents dynamically and returns them as a JSON response. This method is typically used with AJAX for real-time updates of the incident list without reloading the page.</p>
                
            </div>
    
            <div class="doc-footer">
                <h4>Conclusion</h4>
                <p>The Incident Monitoring feature ensures that any potential issues with your monitors are detected and recorded. It provides real-time updates on incidents related to HTTP, DNS, and Port checks, as well as an easy-to-view incident history. Notifications and alerts are available to keep you informed of any critical incidents that may require your attention. The system also maintains a history of all incidents for quick reference and auditing purposes.</p>
            </div>
        </div>
    </div>
    
    

    <!-- Alert Notifications Tab -->
    <div id="tab5" class="tab-content">
        <div class="monitor-module-doc">
            <!-- Title Section -->
            <div class="doc-header">
                <h2>Alert and Notification Module</h2>
                <p>This section describes the functionality of the Alert and Notification Module in the CheckMySite application. It covers how alerts are triggered when a monitor goes down, and how notifications are sent through different channels such as Email, Telegram, and PWA Push Notifications.</p>
            </div>
    
            <!-- Alert Triggering -->
            <div class="doc-section">
                <h3>Alert Triggering</h3>
                <p>When a monitor goes down, the system automatically triggers alerts. This section explains how alerts are triggered based on the status change of a monitor and how they are sent to the users.</p>
    
                <!-- Code Snippet for Alert Triggering -->
                <pre><code>
                    private function sendAlert(Monitors $monitor, string $status, string $monitorType)
                    {
                        // Only send alert if the monitor status changes from 'up' to 'down'
                        if ($status === 'down') {
                            // Create a new alert record in the Notification table
                            Notification::create([
                                'monitor_id' => $monitor->id,
                                'status' => 'down',
                                'message' => "{$monitorType} Monitoring Failed",  // Log the type of failure
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);
    
                            // Send email notification
                            $this->sendEmailNotification($monitor);
    
                            // Send Telegram notification if enabled
                            $this->sendTelegramNotification($monitor);
    
                            // Send PWA push notification if the user is subscribed
                            $this->sendPwaNotification($monitor);
                        }
                    }
                </code></pre>
                <p>The `sendAlert()` function handles the triggering of alerts when the monitor goes down. It checks if the status has changed to 'down' and then creates an alert record. It also sends notifications via email, Telegram, and PWA based on the monitor's settings.</p>
            </div>
    
            <!-- Email Notification -->
            <div class="doc-section">
                <h3>Email Notification</h3>
                <p>The system sends an email notification to the user when a monitor goes down. The email includes details about the monitor's failure and the type of issue encountered.</p>
    
                <!-- Code Snippet for Email Notification -->
                <pre><code>
                    private function sendEmailNotification(Monitors $monitor)
                    {
                        // Use the MonitorDownAlert mailable to send the email
                        Mail::to($monitor->user->email)->send(new MonitorDownAlert($monitor));
                    }
                </code></pre>
                <p>The `sendEmailNotification()` function sends an email to the user's registered email address, notifying them that the monitor has gone down. The email content is generated using the `MonitorDownAlert` mailable class.</p>
            </div>
    
            <!-- Telegram Notification -->
            <div class="doc-section">
                <h3>Telegram Notification</h3>
                <p>If the user has enabled Telegram notifications for their monitor, the system sends a message to the specified Telegram chat. This allows the user to receive instant alerts on their mobile or desktop Telegram app.</p>
    
                <!-- Code Snippet for Telegram Notification -->
                <pre><code>
                    private function sendTelegramNotification(Monitors $monitor)
                    {
                        // Send a Telegram message using the Telegram API
                        $chatId = $monitor->user->telegram_chat_id;
                        $message = "Alert: {$monitor->name} is down due to {$monitor->monitor_type}.";
                        
                        Http::get("https://api.telegram.org/bot{$this->telegramToken}/sendMessage", [
                            'chat_id' => $chatId,
                            'text' => $message,
                        ]);
                    }
                </code></pre>
                <p>The `sendTelegramNotification()` function sends a message to the user's Telegram chat when a monitor goes down. The message includes details of the monitor's failure and the issue type (e.g., HTTP, DNS, Ping).</p>
            </div>
    
            <!-- PWA Push Notification -->
            <div class="doc-section">
                <h3>PWA Push Notification</h3>
                <p>When the monitor goes down, and the user is subscribed to push notifications, the system sends a PWA push notification to their browser. This provides an additional channel for real-time alerts.</p>
    
                <!-- Code Snippet for PWA Push Notification -->
                <pre><code>
                    private function sendPwaNotification(Monitors $monitor)
                    {
                        // Retrieve the PWA subscription for the user
                        $subscription = $monitor->user->pwa_subscription;
    
                        // Check if subscription exists
                        if ($subscription) {
                            // Prepare the notification payload
                            $payload = [
                                'title' => "Monitor Down Alert",
                                'message' => "{$monitor->name} is down due to {$monitor->monitor_type}.",
                            ];
    
                            // Send the push notification
                            WebPush::send($subscription, $payload);
                        }
                    }
                </code></pre>
                <p>The `sendPwaNotification()` function sends a push notification to the user's browser if they are subscribed to PWA notifications. It retrieves the user's subscription and sends a message with details about the monitor failure.</p>
            </div>
    
            <div class="doc-footer">
                <h4>Conclusion</h4>
                <p>The Alert and Notification Module in CheckMySite ensures that users are notified promptly whenever a monitor goes down. It supports multiple notification channels, including email, Telegram, and PWA push notifications, to keep users informed and allow them to take action quickly. The system is flexible and can be extended to support additional alert channels or features based on user preferences.</p>
            </div>
        </div>
    </div>
    

    <!-- Plan & Subscription Tab -->
    <div id="tab6" class="tab-content">
        <div class="monitor-module-doc">
            <!-- Title Section -->
            <div class="doc-header">
                <h2>Plan and Subsribtion Module</h2>
                <p>This section describes the Plan and Subsribtion functionaly in the CheckMySite application.</p>
            </div>
    
            <!-- premium middleware -->
            <div class="doc-section">
                <h3>Premium Middleware</h3>
            <p>Allows access to the premium page route without restriction<br>Redirects unauthenticated users to the login page<br>Redirects non-premium users to the premium upgrade page<br>Lets premium users proceed normally</p>

            <!-- Code Snippet for Incident Creation -->
            <pre><code>
                public function handle(Request $request, Closure $next): Response
                {
                    // Allow unrestricted access to the premium page (upgrade page).
                    if ($request->routeIs('premium.page')) {
                        return $next($request);
                    }
                
                    // Check if the user is not logged in.
                    // If not authenticated, redirect to the login page.
                    if (!$request->user()) {
                        return redirect()->route('login');
                    }
                
                    // Check if the authenticated user does NOT have a 'paid' status.
                    // If not a premium user, redirect them to the premium upgrade page with an error message.
                    if ($request->user()->status !== 'paid') {
                        return redirect()->route('premium.page')
                            ->with('error', 'This feature requires a premium subscription');
                    }
                
                    // If the user is authenticated and has 'paid' status, allow the request to proceed.
                    return $next($request);
                }
                
            </code></pre>
            <p>The handle function in the PremiumMiddleware class is responsible for restricting access to certain parts of the application based on the user's subscription status <br>Ensure's that only users with a premium subscription (status = 'paid') can access certain routes in the application(When a user makes a request to a route or controller that is protected by the PremiumMiddleware.).</p>
        

                <h3>Example of using PremiumMiddleware</h3>
                <p>The following route applies the PremiumMiddleware to the /ssl-check endpoint. If a user without a 'paid' status accesses this route, they will be redirected to the premium upgrade page.</p>

                    <!-- Code Snippet for SSL Check Route with Middleware -->
                    <pre><code>
                    Route::get('/ssl-check', [SslCheckController::class, 'index'])
                        ->middleware('premium_middleware')
                        ->name('ssl.check');
                    </code></pre>
                
    
                <h2>Additional Features</h2>
                <br>
                <h3>Dynamic Incident Data</h3>
                <p>The following method is responsible for fetching incidents dynamically, using AJAX to ensure that the incident list is always up-to-date without needing to refresh the page.</p>
    
                <!-- Code Snippet for Fetching Incidents Dynamically -->
                <pre><code>
                    public function fetchIncidents()
                    {
                        $userId = Auth::id();
    
                        // Get the monitor IDs associated with the logged-in user
                        $userMonitors = Monitors::where('user_id', $userId)->pluck('id');
    
                        // Fetch incidents with related monitor data
                        $incidents = Incident::with('monitor')
                            ->whereIn('monitor_id', $userMonitors)
                            ->get();
    
                        return response()->json(['incidents' => $incidents]);
                    }
                </code></pre>
                <p>The `fetchIncidents()` method fetches incidents dynamically and returns them as a JSON response. This method is typically used with AJAX for real-time updates of the incident list without reloading the page.</p>
                
            </div>
    
            <div class="doc-footer">
                <h4>Conclusion</h4>
                <p>The Incident Monitoring feature ensures that any potential issues with your monitors are detected and recorded. It provides real-time updates on incidents related to HTTP, DNS, and Port checks, as well as an easy-to-view incident history. Notifications and alerts are available to keep you informed of any critical incidents that may require your attention. The system also maintains a history of all incidents for quick reference and auditing purposes.</p>
            </div>
        </div>
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
  <script>
    document.addEventListener("DOMContentLoaded", function() {
        const codeBlocks = document.querySelectorAll("pre code");

        codeBlocks.forEach(block => {
            let codeContent = block.innerHTML;

            // Regular expression to match comments starting with '//' and continuing to the end of the line
            const commentRegex = /(\/\/[^\n]*)/g;

            // Replace comments with <span> that has the 'comment' class
            codeContent = codeContent.replace(commentRegex, function(match) {
                return `<span class="comment">${match}</span>`;
            });

            // Update the code content with the styled comments
            block.innerHTML = codeContent;
        });
    });
</script>

</body>
</html>