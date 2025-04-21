@extends('dashboard')
@section('content')

    <head>
        <!-- Toastr CSS -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intro.js/7.2.0/introjs.min.css"/>

    </head>
  <style>
    /* ========== INTROJS TOUR ========== */
        .introjs-tooltip {
            background-color: white;
            color: rgb(51, 48, 48);
            font-family: 'Poppins', sans-serif;
            border-radius: 0.35rem;
            /* box-shadow: 0 0.5rem 1.5rem rgba(7, 18, 144, 0.2); */
            box-shadow: 0px 0px 6px 4px rgba(28, 61, 245, 0.2);   
        }

        .introjs-tooltip-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--primary);
        }

        .introjs-button {
            background-color: var(--primary);
            border-radius: 0.25rem;
            font-weight: 600;
            color: black;
            cursor: pointer;
            text-shadow: none;
        }

        .introjs-button:hover {
            background-color: #2e59d9;
            color: white;
        } 
        .introjs-overlay
         {
        pointer-events: none; 
        }

        .introjs-helperLayer {
        pointer-events: none;
        z-index: 1001;
        }
 </style>


    <div class="row mb-4 px-3 px-lg-4">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h1 class="mb-0 ml-lg-3">Add Monitoring</h1>
                <a href="{{ route('monitoring.dashboard') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left mr-1"></i> Back
                </a>
            </div>
        </div>
    </div>

    <div class="dropdown mb-4 mx-3 mx-lg-5">
        <button class="btn btn-primary dropdown-toggle MonitorTypes" type="button" id="dropdownMenuButton" data-toggle="dropdown"
            aria-haspopup="true" aria-expanded="false">
            HTTP Monitoring
        </button>
        <div class="dropdown-menu animated--fade-in" aria-labelledby="dropdownMenuButton">
            <a class="dropdown-item fs-6" href="#" onclick="updateDropdown('HTTP Monitoring', 'http')">HTTP
                Monitoring</a>
            <a class="dropdown-item fs-6" href="#" onclick="updateDropdown('Ping Monitoring', 'ping')">Ping
                Monitoring</a>
            <a class="dropdown-item fs-6" href="#" onclick="updateDropdown('Port Monitoring', 'port')">Port
                Monitoring</a>
            <a class="dropdown-item fs-6" href="#" onclick="updateDropdown('DNS Monitoring', 'dns')">DNS
                Monitoring</a>
        </div>
    </div>

    {{-- Form Section --}}
    <div class="d-flex justify-content-center">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body" id="formContainer">
                    <!-- Default Form (HTTP Monitoring) -->
                    <h4 class="card-title">HTTP Monitoring</h4>

                    {{-- // add action based on route --}}
                    <form id="monitoringForm" method="POST" action="{{ route('monitoring.http.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Friendly name</label>
                            <input id="name" class="form-control" name="name" type="text"
                                placeholder="E.g. Google" required>
                        </div>
                        <div class="mb-3">
                            <label for="url" class="form-label">URL</label>
                            <input id="url" class="form-control" name="url" type="text"
                                placeholder="E.g. https://www.google.com" required>
                        </div>
                        <div class="mb-3">
                            <label for="retries" class="form-label">Retries</label>
                            <input id="retries" class="form-control" name="retries" type="number" value="3"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="interval" class="form-label">Interval (in minutes)</label>
                            <input id="interval" class="form-control" name="interval" type="number" value="1"
                                required>
                        </div>

                        <h5 class="card-title">Notification</h5>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input id="email" class="form-control" name="email" type="email"
                                placeholder="example@gmail.com" required>
                        </div>
                        <div class="mb-3">
                            <label for="telegram_id" class="form-label">Telegram Id (Optional)</label>
                            <input id="telegram_id" class="form-control" name="telegram_id" type="text">
                        </div>
                        <div class="mb-3">
                            <label for="telegram_bot_token" class="form-label">Telegram Bot Token (Optional)</label>
                            <input id="telegram_bot_token" class="form-control" name="telegram_bot_token" type="text">
                        </div>
                        <input class="btn btn-primary w-100" type="submit" value="Submit">
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- JavaScript to Handle Form Switching --}}
    <script>
        const forms = {
            http: {
                title: "HTTP Monitoring",
                action: "{{ route('monitoring.http.store') }}",
                fields: `
                <div class="mb-3">
                        <label for="name" class="form-label">Friendly name</label>
                        <input id="name" class="form-control" name="name" type="text" placeholder="E.g. Google" required>
                    </div>
                    <div class="mb-3">
                        <label for="url" class="form-label">URL</label>
                        <input id="url" class="form-control" name="url" type="text" placeholder="E.g. https://www.google.com" required>
                    </div>
                    <div class="mb-3">
                        <label for="retries" class="form-label">Retries</label>
                        <input id="retries" class="form-control" name="retries" type="number" value="3" required>
                    </div>
                    <div class="mb-3">
                        <label for="interval" class="form-label">Interval (in minutes)</label>
                        <input id="interval" class="form-control" name="interval" type="number" value="1" required>
                    </div>
                    
                    <h5 class="card-title">Notification</h5>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input id="email" class="form-control" name="email" type="email" placeholder="example@gmail.com" required>
                    </div>
                    <div class="mb-3">
                        <label for="telegram_id" class="form-label">Telegram Id (Optional)</label>
                        <input id="telegram_id" class="form-control" name="telegram_id" type="text">
                    </div>
                    <div class="mb-3">
                        <label for="telegram_bot_token" class="form-label">Telegram Bot Token (Optional)</label>
                        <input id="telegram_bot_token" class="form-control" name="telegram_bot_token" type="text">
                    </div>
                    <input class="btn btn-primary w-100" type="submit" value="Submit">
            `
            },
            ping: {
                title: "Ping Monitoring",
                action: "{{ route('ping.monitoring.store') }}",
                fields: `
                <div class="mb-3">
                    <label for="name" class="form-label">Friendly name</label>
                    <input id="name" class="form-control" name="name" type="text" placeholder="E.g. Google" required>
                </div>
                <div class="mb-3">
                    <label for="url" class="form-label">Domain or URL</label>
                    <input id="url" class="form-control" name="url" type="text" placeholder="E.g. https://www.google.com" required>
                </div>
                <div class="mb-3">
                        <label for="retries" class="form-label">Retries</label>
                        <input id="retries" class="form-control" name="retries" type="number" value="3" required>
                    </div>
                    <div class="mb-3">
                        <label for="interval" class="form-label">Interval (in minutes)</label>
                        <input id="interval" class="form-control" name="interval" type="number" value="1" required>
                    </div>
                <h5 class="card-title">Notification</h5>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input id="email" class="form-control" name="email" type="email" placeholder="example@gmail.com" required>
                </div>
                <div class="mb-3">
                    <label for="telegram_id" class="form-label">Telegram Id (Optional)</label>
                    <input id="telegram_id" class="form-control" name="telegram_id" type="text">
                </div>
                <div class="mb-3">
                    <label for="telegram_bot_token" class="form-label">Telegram Bot Token (Optional)</label>
                    <input id="telegram_bot_token" class="form-control" name="telegram_bot_token" type="text">
                </div>

                <input class="btn btn-primary w-100" type="submit" value="Submit">
                
            `
            },
            port: {
                title: "Port Monitoring",
                action: "{{ route('monitor.port') }}",
                fields: `
               <div class="mb-3">
                    <label for="name" class="form-label">Friendly name</label>
                    <input id="name" class="form-control" name="name" type="text" placeholder="E.g. Google" required>
                </div>

                <div class="mb-3">
                    <label for="url" class="form-label">Domain or URL</label>
                    <input id="url" class="form-control" name="url" type="text" placeholder="E.g. www.google.com" required>
                </div>
               <div class="mb-3">
                    <label for="port" class="form-label">Port</label>
                    <select id="port" class="form-control" name="port" required>
                        <option value="" disabled selected>Select Port</option>
                        <option value="21">FTP - 21</option>
                        <option value="22">SSH / SFTP-22</option>
                        <option value="25">SMTP - 25</option>
                        <option value="53">DNS - 53</option>
                        <option value="80">HTTP - 80</option>
                        <option value="110">POP3 - 110</option>
                        <option value="143">IMAP-143</option>
                        <option value="443">HTTPS-443</option>
                        <option value="465">SMTP-465</option>
                        <option value="587">SMTP-587</option>
                        <option value="993">IMAP-993</option>
                        <option value="995">POP3-995</option>
                        <option value="3306">MYSQL-3306</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="retries" class="form-label">Retries</label>
                    <input id="retries" class="form-control" name="retries" type="number" value="3" required>
                </div>
                <div class="mb-3">
                    <label for="interval" class="form-label">Interval (in minutes)</label>
                    <input id="interval" class="form-control" name="interval" type="number" value="1" required>
                </div>
                
                <h5 class="card-title">Notification</h5>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input id="email" class="form-control" name="email" type="email" placeholder="example@gmail.com" required>
                </div>
                <div class="mb-3">
                    <label for="telegram_id" class="form-label">Telegram Id (Optional)</label>
                    <input id="telegram_id" class="form-control" name="telegram_id" type="text">
                </div>
                <div class="mb-3">
                    <label for="telegram_bot_token" class="form-label">Telegram Bot Token (Optional)</label>
                    <input id="telegram_bot_token" class="form-control" name="telegram_bot_token" type="text">
                </div>

                <input class="btn btn-primary w-100" type="submit" value="Submit">
            `
            },
            dns: {
                title: "DNS Monitoring",
                fields: `
                <div class="mb-3">
                        <label for="name" class="form-label">Friendly name</label>
                        <input id="name" class="form-control" name="name" type="text" placeholder="E.g. Google" required>
                    </div>

                    <div class="mb-3">
                        <label for="domain" class="form-label">Domain or URL</label>
                        <input id="domain" class="form-control" name="domain" type="text" placeholder="E.g. google.com"  required>
                    </div>

                    <div class="mb-3">
                        <label for="interval" class="form-label">Interval (in minutes)</label>
                        <input id="interval" class="form-control" name="interval" type="number" min="1" value="1" required>
                    </div>

                    <div class="mb-3">
                        <label for="retries" class="form-label">Retries</label>
                        <input id="retries" class="form-control" name="retries" type="number" min="0" value="3" required>
                    </div>

                    <div class="mb-3">
                        <label for="dns_resource_type" class="form-label">DNS Resource Type</label>
                        <select id="dns_resource_type" class="form-control" name="dns_resource_type" required>
                            <option value="A">A</option>
                            <option value="AAAA">AAAA</option>
                            <option value="CNAME">CNAME</option>
                            <option value="MX">MX</option>
                            <option value="NS">NS</option>
                            <option value="SOA">SOA</option>
                            <option value="TXT">TXT</option>
                            <option value="SRV">SRV</option>
                            <option value="DNS_ALL">DNS_ALL</option>
                        </select>
                    </div>

                    <h5 class="card-title">Notification</h5>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input id="email" class="form-control" name="email" type="email" placeholder="example@gmail.com" required>
                    </div>

                    <div class="mb-3">
                        <label for="telegram_id" class="form-label">Telegram Id (Optional)</label>
                        <input id="telegram_id" class="form-control" name="telegram_id" type="text">
                    </div>

                    <div class="mb-3">
                        <label for="telegram_bot_token" class="form-label">Telegram Bot Token (Optional)</label>
                        <input id="telegram_bot_token" class="form-control" name="telegram_bot_token" type="text">
                    </div>
                    
                    <input class="btn btn-primary w-100" type="submit" value="Submit">
            `,
                action: '/add/dns'
            },
        };


        // Keep your existing updateDropdown function
        function updateDropdown(selectedType, formType) {
            document.getElementById("dropdownMenuButton").innerText = selectedType;
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

        // The showForm function with improved validation
        function showForm(type) {
            const formContainer = document.getElementById('formContainer');
            formContainer.innerHTML = `
            <h4 class="card-title">${forms[type].title}</h4>
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
        });
    </script>

    <!-- jQuery and Toastr scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/intro.js/7.2.0/intro.min.js"></script>

    <script>
        @if (session('success'))
            toastr.success("{{ session('success') }}");
        @endif
    </script>
    
    <script>
         // Initialize tour(tool tip)
    document.addEventListener("DOMContentLoaded", function () {
        introJs().setOptions({
            disableInteraction: false,
            steps:[
        {
         element:document.querySelector('.MonitorTypes'),
         intro:'Choose types of monitoring.',
         position:'right'
       }
      ],
            dontShowAgain: true,
            nextLabel: 'Next',
            prevLabel: 'Back',
            doneLabel: 'Finish'
        }).start();
    });
        </script>
@endsection
