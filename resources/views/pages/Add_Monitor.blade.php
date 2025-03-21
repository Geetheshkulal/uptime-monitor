@extends('dashboard')
@section('content')

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
                <form id="monitoringForm" method="POST" action="">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input id="name" class="form-control" name="name" type="text" required>
                    </div>
                    <div class="mb-3">
                        <label for="url" class="form-label">URL</label>
                        <input id="url" class="form-control" name="url" type="text" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input id="email" class="form-control" name="email" type="email" required>
                    </div>
                    <h5 class="card-title">Notification</h5>
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
            fields: `
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input id="name" class="form-control" name="name" type="text" required>
                </div>
                <div class="mb-3">
                    <label for="url" class="form-label">URL</label>
                    <input id="url" class="form-control" name="url" type="text" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input id="email" class="form-control" name="email" type="email" required>
                </div>
                <h5 class="card-title">Notification</h5>
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
        ping: {
            title: "Ping Monitoring",
            fields: `
                <div class="mb-3">
                    <label for="ip_address" class="form-label">IP Address</label>
                    <input id="ip_address" class="form-control" name="ip_address" type="text" required>
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
                    <label for="interval" class="form-label">Interval (seconds)</label>
                    <input id="interval" class="form-control" name="interval" type="number" value="60" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email (notification)</label>
                    <input id="email" class="form-control" name="email" type="text" required>
                </div>
            `
        },
        dns: {
            title: "DNS Monitoring",
            fields: `
                <div class="mb-3">
                    <label for="domain" class="form-label">Domain</label>
                    <input id="domain" class="form-control" name="domain" type="text" required>
                </div>
                <div class="mb-3">
                    <label for="dns_server" class="form-label">DNS Server</label>
                    <input id="dns_server" class="form-control" name="dns_server" type="text" required>
                </div>
            `
        }
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

@endsection
