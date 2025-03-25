@extends('dashboard')
@section('content')
<head>
    <!-- Toastr CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

</head>

<h1 class="mb-4 mx-5">Add Monitoring</h1>

<div class="dropdown mb-4 mx-5">
    <button class="btn btn-primary dropdown-toggle" type="button"
        id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
        aria-expanded="false">
        Types of Monitoring
    </button>
    <div class="dropdown-menu animated--fade-in"
        aria-labelledby="dropdownMenuButton">
        <a class="dropdown-item fs-6" href="#" onclick="showForm('http')">HTTP Monitoring</a>
        <a class="dropdown-item fs-6" href="#" onclick="showForm('ping')">Ping Monitoring</a>
        <a class="dropdown-item fs-6" href="#" onclick="showForm('port')">Port Monitoring</a>
        <a class="dropdown-item fs-6" href="#" onclick="showForm('dns')">DNS Monitoring</a>
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
            action:"{{route('monitoring.http.store')}}",
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

                <input id="submitBtn" class="btn btn-primary" type="submit" value="Submit">
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
                    <label for="url" class="form-label">Host or URL</label>
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
                <label for="domain" class="form-label">Domain</label>
                <input id="domain" class="form-control" name="domain" type="text" placeholder="E.g. google.com"  required>
            </div>

            <div class="mb-3">
                <label for="interval" class="form-label">Interval (in minutes)</label>
                <input id="interval" class="form-control" name="interval" type="number" min="1" placeholder="1" required>
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

            `,
            action:'/add/dns'
    },
    };

    

    function showForm(type) {
        const formContainer = document.getElementById('formContainer');
        formContainer.innerHTML = `
            <h4 class="card-title">${forms[type].title}</h4>
            <form id="monitoringForm" method="POST" action="${forms[type].action}">
                @csrf
                ${forms[type].fields}

                <input class="btn btn-primary w-100" type="submit" value="Submit">
            </form>
        `;
    }

</script>
<!-- jQuery (Required for Toastr) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Toastr JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
    @if(session('success'))
        toastr.success("{{ session('success') }}");
    @endif
</script>



@endsection
