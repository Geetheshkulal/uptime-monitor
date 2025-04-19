<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CheckMySite Modules</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
        .btn-module {
            margin: 5px;
            font-size: 0.9rem;
            padding: 10px 15px;
            text-align: center;
            width: 100%;
        }

        .module-description {
            margin-top: 30px;
        }

        .module-description h2 {
            font-size: 1.8rem;
            margin-bottom: 20px;
        }

        .module-description p {
            font-size: 1rem;
            line-height: 1.6;
        }
        
        .container {
            margin-top: 20px;
        }

        .sidebar {
            position: sticky;
            top: 20px;
        }

        .module-header {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container text-center mt-5">
        <h1 class="mb-4">CheckMySite Modules</h1>
    </div>

    <div class="container">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 sidebar">
                <div class="list-group">
                    <a href="#monitor-management" class="btn btn-primary btn-module">Monitor Management</a>
                    <a href="#monitor-types" class="btn btn-secondary btn-module">Monitor Types</a>
                    <a href="#alerts" class="btn btn-success btn-module">Alerts & Notifications</a>
                    <a href="#ssl-monitoring" class="btn btn-info btn-module">SSL Monitoring</a>
                    <a href="#user-auth" class="btn btn-warning btn-module">User Auth</a>
                    <a href="#dashboard" class="btn btn-dark btn-module">Dashboard</a>
                    <a href="#api" class="btn btn-danger btn-module">API Integration</a>
                    <a href="#reports" class="btn btn-primary btn-module">Reporting</a>
                    <a href="#subscription" class="btn btn-secondary btn-module">Subscription</a>
                    <a href="#system-health" class="btn btn-success btn-module">System Health</a>
                    <a href="#logs" class="btn btn-info btn-module">Logs & Audit</a>
                    <a href="#support" class="btn btn-warning btn-module">Help & Support</a>
                    <a href="#settings" class="btn btn-dark btn-module">Settings</a>
                </div>
            </div>

            <!-- Content Area -->
            <div class="col-md-9">
                <div id="monitor-management" class="module-description">
                    <h2>Monitor Management</h2>
                    <p>This module allows you to manage all of your monitors in one place...</p>
                </div>

                <div id="monitor-types" class="module-description">
                    <h2>Monitor Types</h2>
                    <p>Learn about different types of monitoring checks that CheckMySite supports...</p>
                </div>

                <div id="alerts" class="module-description">
                    <h2>Alerts & Notifications</h2>
                    <p>Receive real-time alerts on your monitors' status via multiple channels...</p>
                </div>

                <div id="ssl-monitoring" class="module-description">
                    <h2>SSL Monitoring</h2>
                    <p>This premium feature notifies you about the status of your SSL certificates...</p>
                </div>

                <div id="user-auth" class="module-description">
                    <h2>User Authentication</h2>
                    <p>Manage user roles, access levels, and more to ensure proper security...</p>
                </div>

                <div id="dashboard" class="module-description">
                    <h2>Dashboard</h2>
                    <p>The dashboard provides an overview of your monitored websites, response times, and more...</p>
                </div>

                <div id="api" class="module-description">
                    <h2>API Integration</h2>
                    <p>Integrate CheckMySite with your system via our easy-to-use API...</p>
                </div>

                <div id="reports" class="module-description">
                    <h2>Reporting</h2>
                    <p>View detailed reports on your monitoring activities, uptime, and much more...</p>
                </div>

                <div id="subscription" class="module-description">
                    <h2>Subscription</h2>
                    <p>Choose from free or premium plans, with features like extended monitoring...</p>
                </div>

                <div id="system-health" class="module-description">
                    <h2>System Health</h2>
                    <p>Monitor the health of your system’s components in real-time...</p>
                </div>

                <div id="logs" class="module-description">
                    <h2>Logs & Audit</h2>
                    <p>Review system logs and track any incidents or issues for compliance...</p>
                </div>

                <div id="support" class="module-description">
                    <h2>Help & Support</h2>
                    <p>Get help with troubleshooting, FAQs, or reach out to support...</p>
                </div>

                <div id="settings" class="module-description">
                    <h2>Settings</h2>
                    <p>Configure settings for monitoring thresholds, notifications, and more...</p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
</body>
</html>
