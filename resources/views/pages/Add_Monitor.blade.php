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
<div class="d-flex justify-content-center align-items-center">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body" id="formContainer">
                <!-- Default Form (HTTP Monitoring) -->
                <h4 class="card-title">HTTP Monitoring</h4>

                {{-- // add action based on route --}}
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
          
            fields: `
                <div class="mb-3">
                    <label for="host" class="form-label">Host</label>
                    <input id="host" class="form-control" name="host" type="text" required>
                </div>
                <div class="mb-3">
                    <label for="port" class="form-label">Port</label>
                    <input id="port" class="form-control" name="port" type="number" required>
                </div>
            `
        },
         dns: {
        title: "DNS Monitoring",
        fields: `
          <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input id="name" class="form-control" name="name" type="text" required>
            </div>

            <div class="mb-3">
                <label for="domain" class="form-label">Domain</label>
                <input id="domain" class="form-control" name="domain" type="text" required>
            </div>

            <div class="mb-3">
                <label for="interval" class="form-label">Interval (in minutes)</label>
                <input id="interval" class="form-control" name="interval" type="number" min="1" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input id="email" class="form-control" name="email" type="email" required>
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
        // Get the form container and update it dynamically
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
