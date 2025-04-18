@extends('dashboard')
@section('content')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<style>
    /* Consistent Form Styling */
    .monitoring-container {
        max-width: 800px;
        margin: 0 auto;
    }
    
    .form-header {
        border-bottom: 1px solid #e0e0e0;
        padding-bottom: 1rem;
        margin-bottom: 1.5rem;
    }
    
    .form-card {
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        border: none;
        overflow: hidden;
    }
    
    .form-card .card-body {
        padding: 2rem;
    }
    
    .form-title {
        color: #2c3e50;
        font-weight: 600;
        margin-bottom: 1.5rem;
    }
    
    .section-title {
        color: #2c3e50;
        font-weight: 500;
        margin: 1.5rem 0 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid #f0f0f0;
    }
    
    .form-label {
        font-weight: 500;
        color: #4a5568;
        margin-bottom: 0.5rem;
    }
    
    .form-control {
        border-radius: 6px;
        padding: 0.75rem 1rem;
        border: 1px solid #e2e8f0;
        transition: all 0.2s ease;
    }
    
    .form-control:focus {
        border-color: #4a90e2;
        box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.1);
    }
    
    .btn-submit {
        background-color: #4a90e2;
        border: none;
        padding: 0.75rem 1.5rem;
        font-weight: 500;
        letter-spacing: 0.5px;
        transition: all 0.2s ease;
    }
    
    .btn-submit:hover {
        background-color: #3a7bc8;
        transform: translateY(-1px);
    }
    
    .btn-back {
        background-color: #f8f9fa;
        color: #4a5568;
        border: 1px solid #e2e8f0;
    }
    
    .btn-back:hover {
        background-color: #e2e8f0;
    }
    
    .dropdown-toggle {
        padding: 0.75rem 1.5rem;
        border-radius: 6px;
        font-weight: 500;
    }
    
    .dropdown-menu {
        border-radius: 6px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        border: none;
    }
    
    .dropdown-item {
        padding: 0.5rem 1.5rem;
        color: #4a5568;
    }
    
    .dropdown-item:hover {
        background-color: #f8f9fa;
        color: #2c3e50;
    }
    
    .invalid-feedback {
        font-size: 0.85rem;
        color: #e74c3c;
    }
    
    .is-invalid {
        border-color: #e74c3c;
    }
    
    .is-invalid:focus {
        box-shadow: 0 0 0 3px rgba(231, 76, 60, 0.1);
    }
    
    .port-select {
        appearance: none;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 0.75rem center;
        background-size: 16px 12px;
    }
</style>
@endpush

<div class="monitoring-container px-3 px-lg-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="form-header">
            <h1 class="h3 mb-0">Add New Monitor</h1>
            <p class="text-muted mb-0">Configure your monitoring settings</p>
        </div>
        <a href="{{ route('monitoring.dashboard') }}" class="btn btn-back">
            <i class="fas fa-arrow-left mr-2"></i> Back to Dashboard
        </a>
    </div>

    <div class="mb-4">
        <div class="dropdown">
            <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-globe mr-2"></i> HTTP Monitoring
            </button>
            <div class="dropdown-menu animated--fade-in" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item" href="#" onclick="updateDropdown('HTTP Monitoring', 'http')">
                    <i class="fas fa-globe mr-2"></i> HTTP Monitoring
                </a>
                <a class="dropdown-item" href="#" onclick="updateDropdown('Ping Monitoring', 'ping')">
                    <i class="fas fa-network-wired mr-2"></i> Ping Monitoring
                </a>
                <a class="dropdown-item" href="#" onclick="updateDropdown('Port Monitoring', 'port')">
                    <i class="fas fa-plug mr-2"></i> Port Monitoring
                </a>
                <a class="dropdown-item" href="#" onclick="updateDropdown('DNS Monitoring', 'dns')">
                    <i class="fas fa-server mr-2"></i> DNS Monitoring
                </a>
            </div>
        </div>
    </div>

    <div class="card form-card">
        <div class="card-body" id="formContainer">
            <!-- Default Form (HTTP Monitoring) -->
            <h4 class="form-title"><i class="fas fa-globe mr-2"></i> HTTP Monitoring</h4>
            
            <form id="monitoringForm" method="POST" action="{{ route('monitoring.http.store') }}">
                @csrf
                <div class="mb-4">
                    <label for="name" class="form-label">Friendly Name</label>
                    <input id="name" class="form-control" name="name" type="text" placeholder="e.g. Google Homepage" required>
                </div>
                
                <div class="mb-4">
                    <label for="url" class="form-label">Website URL</label>
                    <input id="url" class="form-control" name="url" type="text" placeholder="e.g. https://www.google.com" required>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label for="retries" class="form-label">Retry Attempts</label>
                        <input id="retries" class="form-control" name="retries" type="number" min="1" max="5" value="3" required>
                    </div>
                    <div class="col-md-6 mb-4">
                        <label for="interval" class="form-label">Check Interval (minutes)</label>
                        <input id="interval" class="form-control" name="interval" type="number" min="1" max="60" value="1" required>
                    </div>
                </div>
                
                <h5 class="section-title"><i class="fas fa-bell mr-2"></i> Notification Settings</h5>
                
                <div class="mb-4">
                    <label for="email" class="form-label">Notification Email</label>
                    <input id="email" class="form-control" name="email" type="email" placeholder="your.email@example.com" required>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label for="telegram_id" class="form-label">Telegram Chat ID (Optional)</label>
                        <input id="telegram_id" class="form-control" name="telegram_id" type="text" placeholder="e.g. 123456789">
                    </div>
                    <div class="col-md-6 mb-4">
                        <label for="telegram_bot_token" class="form-label">Telegram Bot Token (Optional)</label>
                        <input id="telegram_bot_token" class="form-control" name="telegram_bot_token" type="text" placeholder="e.g. 123456:ABC-DEF1234ghIkl-zyx57W2v1u123ew11">
                    </div>
                </div>
                
                <button type="submit" class="btn btn-submit btn-block">
                    <i class="fas fa-plus-circle mr-2"></i> Add Monitor
                </button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
    const forms = {
        http: {
            title: '<i class="fas fa-globe mr-2"></i> HTTP Monitoring',
            action: "{{ route('monitoring.http.store') }}",
            fields: `
                <div class="mb-4">
                    <label for="name" class="form-label">Friendly Name</label>
                    <input id="name" class="form-control" name="name" type="text" placeholder="e.g. Google Homepage" required>
                </div>
                
                <div class="mb-4">
                    <label for="url" class="form-label">Website URL</label>
                    <input id="url" class="form-control" name="url" type="text" placeholder="e.g. https://www.google.com" required>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label for="retries" class="form-label">Retry Attempts</label>
                        <input id="retries" class="form-control" name="retries" type="number" min="1" max="5" value="3" required>
                    </div>
                    <div class="col-md-6 mb-4">
                        <label for="interval" class="form-label">Check Interval (minutes)</label>
                        <input id="interval" class="form-control" name="interval" type="number" min="1" max="60" value="1" required>
                    </div>
                </div>
                
                <h5 class="section-title"><i class="fas fa-bell mr-2"></i> Notification Settings</h5>
                
                <div class="mb-4">
                    <label for="email" class="form-label">Notification Email</label>
                    <input id="email" class="form-control" name="email" type="email" placeholder="your.email@example.com" required>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label for="telegram_id" class="form-label">Telegram Chat ID (Optional)</label>
                        <input id="telegram_id" class="form-control" name="telegram_id" type="text" placeholder="e.g. 123456789">
                    </div>
                    <div class="col-md-6 mb-4">
                        <label for="telegram_bot_token" class="form-label">Telegram Bot Token (Optional)</label>
                        <input id="telegram_bot_token" class="form-control" name="telegram_bot_token" type="text" placeholder="e.g. 123456:ABC-DEF1234ghIkl-zyx57W2v1u123ew11">
                    </div>
                </div>
                
                <button type="submit" class="btn btn-submit btn-block">
                    <i class="fas fa-plus-circle mr-2"></i> Add Monitor
                </button>
            `
        },
        ping: {
            title: '<i class="fas fa-network-wired mr-2"></i> Ping Monitoring',
            action: "{{ route('ping.monitoring.store') }}",
            fields: `
                <div class="mb-4">
                    <label for="name" class="form-label">Friendly Name</label>
                    <input id="name" class="form-control" name="name" type="text" placeholder="e.g. Google Server" required>
                </div>
                
                <div class="mb-4">
                    <label for="url" class="form-label">Hostname or IP Address</label>
                    <input id="url" class="form-control" name="url" type="text" placeholder="e.g. google.com or 8.8.8.8" required>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label for="retries" class="form-label">Retry Attempts</label>
                        <input id="retries" class="form-control" name="retries" type="number" min="1" max="5" value="3" required>
                    </div>
                    <div class="col-md-6 mb-4">
                        <label for="interval" class="form-label">Check Interval (minutes)</label>
                        <input id="interval" class="form-control" name="interval" type="number" min="1" max="60" value="1" required>
                    </div>
                </div>
                
                <h5 class="section-title"><i class="fas fa-bell mr-2"></i> Notification Settings</h5>
                
                <div class="mb-4">
                    <label for="email" class="form-label">Notification Email</label>
                    <input id="email" class="form-control" name="email" type="email" placeholder="your.email@example.com" required>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label for="telegram_id" class="form-label">Telegram Chat ID (Optional)</label>
                        <input id="telegram_id" class="form-control" name="telegram_id" type="text" placeholder="e.g. 123456789">
                    </div>
                    <div class="col-md-6 mb-4">
                        <label for="telegram_bot_token" class="form-label">Telegram Bot Token (Optional)</label>
                        <input id="telegram_bot_token" class="form-control" name="telegram_bot_token" type="text" placeholder="e.g. 123456:ABC-DEF1234ghIkl-zyx57W2v1u123ew11">
                    </div>
                </div>
                
                <button type="submit" class="btn btn-submit btn-block">
                    <i class="fas fa-plus-circle mr-2"></i> Add Monitor
                </button>
            `
        },
        port: {
            title: '<i class="fas fa-plug mr-2"></i> Port Monitoring',
            action: "{{ route('monitor.port') }}",
            fields: `
                <div class="mb-4">
                    <label for="name" class="form-label">Friendly Name</label>
                    <input id="name" class="form-control" name="name" type="text" placeholder="e.g. Google HTTPS Port" required>
                </div>
                
                <div class="mb-4">
                    <label for="url" class="form-label">Hostname or IP Address</label>
                    <input id="url" class="form-control" name="url" type="text" placeholder="e.g. google.com or 8.8.8.8" required>
                </div>
                
                <div class="mb-4">
                    <label for="port" class="form-label">Port Number</label>
                    <select id="port" class="form-control port-select" name="port" required>
                        <option value="" disabled selected>Select a port...</option>
                        <option value="21">FTP (Port 21)</option>
                        <option value="22">SSH/SFTP (Port 22)</option>
                        <option value="25">SMTP (Port 25)</option>
                        <option value="53">DNS (Port 53)</option>
                        <option value="80">HTTP (Port 80)</option>
                        <option value="110">POP3 (Port 110)</option>
                        <option value="143">IMAP (Port 143)</option>
                        <option value="443">HTTPS (Port 443)</option>
                        <option value="465">SMTP SSL (Port 465)</option>
                        <option value="587">SMTP TLS (Port 587)</option>
                        <option value="993">IMAP SSL (Port 993)</option>
                        <option value="995">POP3 SSL (Port 995)</option>
                        <option value="3306">MySQL (Port 3306)</option>
                    </select>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label for="retries" class="form-label">Retry Attempts</label>
                        <input id="retries" class="form-control" name="retries" type="number" min="1" max="5" value="3" required>
                    </div>
                    <div class="col-md-6 mb-4">
                        <label for="interval" class="form-label">Check Interval (minutes)</label>
                        <input id="interval" class="form-control" name="interval" type="number" min="1" max="60" value="1" required>
                    </div>
                </div>
                
                <h5 class="section-title"><i class="fas fa-bell mr-2"></i> Notification Settings</h5>
                
                <div class="mb-4">
                    <label for="email" class="form-label">Notification Email</label>
                    <input id="email" class="form-control" name="email" type="email" placeholder="your.email@example.com" required>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label for="telegram_id" class="form-label">Telegram Chat ID (Optional)</label>
                        <input id="telegram_id" class="form-control" name="telegram_id" type="text" placeholder="e.g. 123456789">
                    </div>
                    <div class="col-md-6 mb-4">
                        <label for="telegram_bot_token" class="form-label">Telegram Bot Token (Optional)</label>
                        <input id="telegram_bot_token" class="form-control" name="telegram_bot_token" type="text" placeholder="e.g. 123456:ABC-DEF1234ghIkl-zyx57W2v1u123ew11">
                    </div>
                </div>
                
                <button type="submit" class="btn btn-submit btn-block">
                    <i class="fas fa-plus-circle mr-2"></i> Add Monitor
                </button>
            `
        },
        dns: {
            title: '<i class="fas fa-server mr-2"></i> DNS Monitoring',
            action: '/add/dns',
            fields: `
                <div class="mb-4">
                    <label for="name" class="form-label">Friendly Name</label>
                    <input id="name" class="form-control" name="name" type="text" placeholder="e.g. Google DNS Records" required>
                </div>
                
                <div class="mb-4">
                    <label for="domain" class="form-label">Domain Name</label>
                    <input id="domain" class="form-control" name="domain" type="text" placeholder="e.g. google.com" required>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label for="retries" class="form-label">Retry Attempts</label>
                        <input id="retries" class="form-control" name="retries" type="number" min="1" max="5" value="3" required>
                    </div>
                    <div class="col-md-6 mb-4">
                        <label for="interval" class="form-label">Check Interval (minutes)</label>
                        <input id="interval" class="form-control" name="interval" type="number" min="1" max="60" value="1" required>
                    </div>
                </div>
                
                <div class="mb-4">
                    <label for="dns_resource_type" class="form-label">DNS Record Type</label>
                    <select id="dns_resource_type" class="form-control port-select" name="dns_resource_type" required>
                        <option value="A">A (Address) Record</option>
                        <option value="AAAA">AAAA (IPv6 Address) Record</option>
                        <option value="CNAME">CNAME (Canonical Name) Record</option>
                        <option value="MX">MX (Mail Exchange) Record</option>
                        <option value="NS">NS (Name Server) Record</option>
                        <option value="SOA">SOA (Start of Authority) Record</option>
                        <option value="TXT">TXT (Text) Record</option>
                        <option value="SRV">SRV (Service) Record</option>
                        <option value="DNS_ALL">All DNS Records</option>
                    </select>
                </div>
                
                <h5 class="section-title"><i class="fas fa-bell mr-2"></i> Notification Settings</h5>
                
                <div class="mb-4">
                    <label for="email" class="form-label">Notification Email</label>
                    <input id="email" class="form-control" name="email" type="email" placeholder="your.email@example.com" required>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label for="telegram_id" class="form-label">Telegram Chat ID (Optional)</label>
                        <input id="telegram_id" class="form-control" name="telegram_id" type="text" placeholder="e.g. 123456789">
                    </div>
                    <div class="col-md-6 mb-4">
                        <label for="telegram_bot_token" class="form-label">Telegram Bot Token (Optional)</label>
                        <input id="telegram_bot_token" class="form-control" name="telegram_bot_token" type="text" placeholder="e.g. 123456:ABC-DEF1234ghIkl-zyx57W2v1u123ew11">
                    </div>
                </div>
                
                <button type="submit" class="btn btn-submit btn-block">
                    <i class="fas fa-plus-circle mr-2"></i> Add Monitor
                </button>
            `
        }
    };

    function updateDropdown(selectedType, formType) {
        const dropdownButton = document.getElementById("dropdownMenuButton");
        const iconClass = forms[formType].title.match(/class="([^"]+)"/)[1];
        dropdownButton.innerHTML = `<i class="${iconClass}"></i> ${selectedType}`;
        showForm(formType);
    }

    // Validation helper functions
    function isValidUrl(string) {
        try {
            new URL(string);
            return true;
        } catch (_) {
            return false;
        }
    }

    function isValidDomain(string) {
        const domainPattern = /^(?:[a-z0-9](?:[a-z0-9-]{0,61}[a-z0-9])?\.)+[a-z0-9][a-z0-9-]{0,61}[a-z0-9]$/i;
        return domainPattern.test(string);
    }

    function showError(inputElement, message) {
        const existingError = inputElement.parentNode.querySelector('.invalid-feedback');
        if (existingError) {
            existingError.remove();
        }

        inputElement.classList.add('is-invalid');

        const errorDiv = document.createElement('div');
        errorDiv.className = 'invalid-feedback';
        errorDiv.innerText = message;
        inputElement.parentNode.appendChild(errorDiv);
    }

    function clearError(inputElement) {
        inputElement.classList.remove('is-invalid');
        const existingError = inputElement.parentNode.querySelector('.invalid-feedback');
        if (existingError) {
            existingError.remove();
        }
    }

    function showForm(type) {
        const formContainer = document.getElementById('formContainer');
        formContainer.innerHTML = `
            <h4 class="form-title">${forms[type].title}</h4>
            <form id="monitoringForm" method="POST" action="${forms[type].action}">
                @csrf
                ${forms[type].fields}
            </form>
        `;

        const form = document.getElementById('monitoringForm');
        form.addEventListener('submit', function(event) {
            let isValid = true;

            const urlField = document.getElementById('url') || document.getElementById('domain');

            if (urlField) {
                const fieldValue = urlField.value.trim();

                // Skip validation if field is empty (HTML required will handle this)
                if (fieldValue !== '') {
                    if (type === 'http') {
                        if (!isValidUrl(fieldValue)) {
                            event.preventDefault();
                            showError(urlField, 'Please enter a valid URL (e.g., https://www.example.com)');
                            isValid = false;
                        } else {
                            clearError(urlField);
                        }
                    } else {
                        if (!isValidUrl(fieldValue) && !isValidDomain(fieldValue)) {
                            event.preventDefault();
                            showError(urlField, 'Please enter a valid URL or domain name');
                            isValid = false;
                        } else {
                            clearError(urlField);
                        }
                    }
                }
            }

            return isValid;
        });

        const urlField = document.getElementById('url') || document.getElementById('domain');
        if (urlField) {
            // Validate on blur (when leaving the field)
            urlField.addEventListener('blur', function() {
                const fieldValue = urlField.value.trim();

                // If field is empty, just clear any errors and don't validate
                if (fieldValue === '') {
                    clearError(urlField);
                    return;
                }

                if (type === 'http') {
                    if (!isValidUrl(fieldValue)) {
                        showError(urlField, 'Please enter a valid URL (e.g., https://www.example.com)');
                    } else {
                        clearError(urlField);
                    }
                } else {
                    if (!isValidUrl(fieldValue) && !isValidDomain(fieldValue)) {
                        showError(urlField, 'Please enter a valid URL or domain name');
                    } else {
                        clearError(urlField);
                    }
                }
            });

            // Clear error when input changes - especially useful when field is cleared
            urlField.addEventListener('input', function() {
                const fieldValue = urlField.value.trim();
                if (fieldValue === '') {
                    clearError(urlField);
                }
            });
        }
    }

    // Initialize form on page load
    document.addEventListener('DOMContentLoaded', function() {
        showForm('http');
        
        @if (session('success'))
            toastr.success("{{ session('success') }}", "Success", {
                closeButton: true,
                progressBar: true,
                positionClass: "toast-top-right",
                timeOut: 5000
            });
        @endif
    });
</script>
@endpush

@endsection