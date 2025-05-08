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
            color: white;
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
        .btn {
            border-radius: 0.35rem;
            font-weight: 600;
            padding: 0.5rem 1.25rem;
        }

        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
        }

        .btn-primary:hover {
            background-color: #2e59d9;
            border-color: #2653d4;
        }
        .container-fluid{
            overflow: auto;
        }
 </style>

<div class="container-fluid">
    <div class="row mb-4 px-3 px-lg-4">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h1 class="mb-0 ml-lg-3">Add Monitoring</h1>
                <a href="{{ route('monitoring.dashboard') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                    <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Dashboard
                </a>
            </div>
        </div>
    </div>
</div>

    {{-- Dropdown for Monitor Types --}}
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
                        <input type="hidden" name="form_type" value="http"> <!-- Add this hidden input -->
                        <div class="mb-3">
                            <label for="name" class="form-label">Friendly name</label>
                            <input id="name" class="form-control" name="name" type="text"
                                placeholder="E.g. Google"  >
                            @if (old('form_type') === 'http')
                                @error('name')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            @endif
                        </div>
                        <div class="mb-3">
                            <label for="url" class="form-label">URL</label>
                            <input id="url" class="form-control" name="url" type="text"
                                placeholder="E.g. https://www.google.com"  >
                            @if (old('form_type') === 'http')
                                @error('url')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            @endif
                        </div>
                        <div class="mb-3">
                            <label for="retries" class="form-label">Retries</label>
                            <input id="retries" class="form-control" name="retries" type="number" value="3"
                                 >
                            @if (old('form_type') === 'http')
                                @error('retries')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            @endif
                        </div>
                        <div class="mb-3">
                            <label for="interval" class="form-label">Interval (in minutes)</label>
                            <input 
                                id="interval"
                                class="form-control {{ auth()->user()->status === 'free' ? 'bg-light text-muted border-secondary' : '' }}"
                                name="interval"
                                type="number"
                                min="{{ auth()->user()->status === 'free' ? 5 : 1 }}"
                                max="10"
                                value="{{ old('interval', auth()->user()->status === 'free' ? 5 : 1) }}"
                                {{ auth()->user()->status === 'free' ? 'title=Only 5-10 minutes allowed for free users' : '' }}
                            >
                            @if (auth()->user()->status === 'free')
                                <small class="form-text text-muted">
                                    Free users can set an interval between <strong>5 and 10 minutes</strong>. 
                                    <a href="{{ route('premium.page') }}">Upgrade to premium <i class="fa-solid fa-crown" style="color: #FFD43B;"></i></a> to set a shorter interval.
                                </small>
                            @endif
                        </div>

                        <h5 class="card-title">Notification</h5>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input id="email" class="form-control" name="email" type="email"
                                placeholder="example@gmail.com" >
                            @if (old('form_type') === 'http')
                                @error('email')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            @endif
                        </div>
                        <div class="mb-3">
                            <label for="telegram_id" class="form-label">Telegram ID (Optional)</label>
                            <input id="telegram_id" class="form-control {{ auth()->user()->status === 'free' ? 'bg-light text-muted border-secondary' : '' }}" name="telegram_id" type="text"
                                   {{ auth()->user()->status === 'free' ? 'disabled title=Only available for paid users' : '' }}>
                            @if (auth()->user()->status === 'free')
                                <small class="form-text text-muted">
                                    <a href="{{ route('premium.page') }}">Upgrade to premium <i class="fa-solid fa-crown" style="color: #FFD43B;"></i></a> to enable Telegram notifications.
                                </small>
                            @endif
                        </div>
                        
                        <div class="mb-3">
                            <label for="telegram_bot_token" class="form-label">Telegram Bot Token (Optional)</label>
                            <input id="telegram_bot_token" class="form-control {{ auth()->user()->status === 'free' ? 'bg-light text-muted border-secondary' : '' }}" name="telegram_bot_token" type="text"
                                   {{ auth()->user()->status === 'free' ? 'disabled title=Only available for paid users' : '' }}>
                            @if (auth()->user()->status === 'free')
                                <small class="form-text text-muted">
                                    <a href="{{ route('premium.page') }}">Upgrade to premium <i class="fa-solid fa-crown" style="color: #FFD43B;"></i></a> to enable Telegram notifications.
                                </small>
                            @endif
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
                <input type="hidden" name="form_type" value="http"> <!-- Add this hidden input -->
                <div class="mb-3">
                        <label for="name" class="form-label">Friendly name</label>
                        <input id="name" class="form-control" name="name" type="text" placeholder="E.g. Google"  >
                        @if (old('form_type') === 'http')
                            @error('name')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        @endif
                    </div>
                    <div class="mb-3">
                        <label for="url" class="form-label">URL</label>
                        <input id="url" class="form-control" name="url" type="text" placeholder="E.g. https://www.google.com"  >
                        @if (old('form_type') === 'http')
                            @error('url')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        @endif
                    </div>
                    <div class="mb-3">
                        <label for="retries" class="form-label">Retries</label>
                        <input id="retries" class="form-control" name="retries" type="number" value="3"  >
                        @if (old('form_type') === 'http')
                            @error('retries')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        @endif
                    </div>
                   <div class="mb-3">
                            <label for="interval" class="form-label">Interval (in minutes)</label>
                            <input 
                                id="interval"
                                class="form-control {{ auth()->user()->status === 'free' ? 'bg-light text-muted border-secondary' : '' }}"
                                name="interval"
                                type="number"
                                min="{{ auth()->user()->status === 'free' ? 5 : 1 }}"
                                max="10"
                                value="{{ old('interval', auth()->user()->status === 'free' ? 5 : 1) }}"
                                {{ auth()->user()->status === 'free' ? 'title=Only 5-10 minutes allowed for free users' : '' }}
                            >
                            @if (auth()->user()->status === 'free')
                                <small class="form-text text-muted">
                                    Free users can set an interval between <strong>5 and 10 minutes</strong>. 
                                    <a href="{{ route('premium.page') }}">Upgrade to premium <i class="fa-solid fa-crown" style="color: #FFD43B;"></i></a> to set a shorter interval.
                                </small>
                            @endif
                        </div>
                    
                    <h5 class="card-title">Notification</h5>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input id="email" class="form-control" name="email" type="email" placeholder="example@gmail.com">
                        @if (old('form_type') === 'http')
                            @error('email')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        @endif
                    </div>
                    <div class="mb-3">
    <label for="telegram_id" class="form-label">Telegram ID (Optional)</label>
    <input id="telegram_id" class="form-control {{ auth()->user()->status === 'free' ? 'bg-light text-muted border-secondary' : '' }}" name="telegram_id" type="text"
           {{ auth()->user()->status === 'free' ? 'disabled title=Only available for paid users' : '' }}>
    @if (auth()->user()->status === 'free')
        <small class="form-text text-muted">
            <a href="{{ route('premium.page') }}">Upgrade to premium <i class="fa-solid fa-crown" style="color: #FFD43B;"></i></a> to enable Telegram notifications.
        </small>
    @endif
</div>

<div class="mb-3">
    <label for="telegram_bot_token" class="form-label">Telegram Bot Token (Optional)</label>
    <input id="telegram_bot_token" class="form-control {{ auth()->user()->status === 'free' ? 'bg-light text-muted border-secondary' : '' }}" name="telegram_bot_token" type="text"
           {{ auth()->user()->status === 'free' ? 'disabled title=Only available for paid users' : '' }}>
    @if (auth()->user()->status === 'free')
        <small class="form-text text-muted">
            <a href="{{ route('premium.page') }}">Upgrade to premium <i class="fa-solid fa-crown" style="color: #FFD43B;"></i></a> to enable Telegram notifications.
        </small>
    @endif
</div>

                    <input class="btn btn-primary w-100" type="submit" value="Submit">
            `
            },
            ping: {
                title: "Ping Monitoring",
                action: "{{ route('ping.monitoring.store') }}",
                fields: `
                <input type="hidden" name="form_type" value="ping"> <!-- Add this hidden input -->
                <div class="mb-3">
                    <label for="name" class="form-label">Friendly name</label>
                    <input id="name" class="form-control" name="name" type="text" placeholder="E.g. Google"  >
                    @if (old('form_type') === 'ping')
                        @error('name')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    @endif
                </div>
                <div class="mb-3">
                    <label for="url" class="form-label">Domain or URL</label>
                    <input id="url" class="form-control" name="url" type="text" placeholder="E.g. https://www.google.com"  >
                    @if (old('form_type') === 'ping')
                        @error('url')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    @endif
                </div>
                <div class="mb-3">
                        <label for="retries" class="form-label">Retries</label>
                        <input id="retries" class="form-control" name="retries" type="number" value="3"  >
                        @if (old('form_type') === 'ping')
                            @error('retries')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        @endif
                    </div>
                    <div class="mb-3">
                            <label for="interval" class="form-label">Interval (in minutes)</label>
                            <input 
                                id="interval"
                                class="form-control {{ auth()->user()->status === 'free' ? 'bg-light text-muted border-secondary' : '' }}"
                                name="interval"
                                type="number"
                                min="{{ auth()->user()->status === 'free' ? 5 : 1 }}"
                                max="10"
                                value="{{ old('interval', auth()->user()->status === 'free' ? 5 : 1) }}"
                                {{ auth()->user()->status === 'free' ? 'title=Only 5-10 minutes allowed for free users' : '' }}
                            >
                            @if (auth()->user()->status === 'free')
                                <small class="form-text text-muted">
                                    Free users can set an interval between <strong>5 and 10 minutes</strong>. 
                                    <a href="{{ route('premium.page') }}">Upgrade to premium <i class="fa-solid fa-crown" style="color: #FFD43B;"></i></a> to set a shorter interval.
                                </small>
                            @endif
                        </div>
                <h5 class="card-title">Notification</h5>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input id="email" class="form-control" name="email" type="email" placeholder="example@gmail.com"  >
                    @if (old('form_type') === 'ping')
                        @error('email')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    @endif
                </div>
                <div class="mb-3">
    <label for="telegram_id" class="form-label">Telegram ID (Optional)</label>
    <input id="telegram_id" class="form-control {{ auth()->user()->status === 'free' ? 'bg-light text-muted border-secondary' : '' }}" name="telegram_id" type="text"
           {{ auth()->user()->status === 'free' ? 'disabled title=Only available for paid users' : '' }}>
    @if (auth()->user()->status === 'free')
        <small class="form-text text-muted">
            <a href="{{ route('premium.page') }}">Upgrade to premium <i class="fa-solid fa-crown" style="color: #FFD43B;"></i></a> to enable Telegram notifications.
        </small>
    @endif
</div>

<div class="mb-3">
    <label for="telegram_bot_token" class="form-label">Telegram Bot Token (Optional)</label>
    <input id="telegram_bot_token" class="form-control {{ auth()->user()->status === 'free' ? 'bg-light text-muted border-secondary' : '' }}" name="telegram_bot_token" type="text"
           {{ auth()->user()->status === 'free' ? 'disabled title=Only available for paid users' : '' }}>
    @if (auth()->user()->status === 'free')
        <small class="form-text text-muted">
            <a href="{{ route('premium.page') }}">Upgrade to premium <i class="fa-solid fa-crown" style="color: #FFD43B;"></i></a> to enable Telegram notifications.
        </small>
    @endif
</div>


                <input class="btn btn-primary w-100" type="submit" value="Submit">
                
            `
            },
            port: {
                title: "Port Monitoring",
                action: "{{ route('monitor.port') }}",
                fields: `
                <input type="hidden" name="form_type" value="port"> <!-- Add this hidden input -->
                    <div class="mb-3">
                    <label for="name" class="form-label">Friendly name</label>
                    <input id="name" class="form-control" name="name" type="text" placeholder="E.g. Google" value="{{ old('name') }}">
                    @if (old('form_type') === 'port')
                        @error('name')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    @endif
                </div>

                <div class="mb-3">
                    <label for="url" class="form-label">Domain or URL</label>
                    <input id="url" class="form-control" name="url" type="text" placeholder="E.g. www.google.com" value="{{ old('url') }}">
                    @if (old('form_type') === 'port')
                        @error('url')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    @endif
                </div>

                <div class="mb-3">
                    <label for="port" class="form-label">Port</label>
                    <input 
                        id="portInput" 
                        class="form-control" 
                        name="port" 
                        type="number" 
                        placeholder="E.g., 80, 443, or custom port (1-65535)"
                        list="defaultPorts"
                        value="{{ old('port') }}"
                    >
                    <datalist id="defaultPorts">
                        <option value="21">FTP</option>
                        <option value="22">SSH/SFTP</option>
                        <option value="25">SMTP</option>
                        <option value="53">DNS</option>
                        <option value="80">HTTP</option>
                        <option value="110">POP3</option>
                        <option value="143">IMAP</option>
                        <option value="443">HTTPS</option>
                        <option value="465">SMTP (SSL)</option>
                        <option value="587">SMTP (TLS)</option>
                        <option value="993">IMAP (SSL)</option>
                        <option value="995">POP3 (SSL)</option>
                        <option value="3306">MySQL</option>
                    </datalist>
                    @if (old('form_type') === 'port')
                        @error('port')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    @endif
                </div>

                <div class="mb-3">
                    <label for="retries" class="form-label">Retries</label>
                    <input id="retries" class="form-control" name="retries" type="number" value="{{ old('retries', 3) }}">
                    @if (old('form_type') === 'port')
                        @error('retries')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    @endif
                </div>

                <div class="mb-3">
                            <label for="interval" class="form-label">Interval (in minutes)</label>
                            <input 
                                id="interval"
                                class="form-control {{ auth()->user()->status === 'free' ? 'bg-light text-muted border-secondary' : '' }}"
                                name="interval"
                                type="number"
                                min="{{ auth()->user()->status === 'free' ? 5 : 1 }}"
                                max="10"
                                value="{{ old('interval', auth()->user()->status === 'free' ? 5 : 1) }}"
                                {{ auth()->user()->status === 'free' ? 'title=Only 5-10 minutes allowed for free users' : '' }}
                            >
                            @if (auth()->user()->status === 'free')
                                <small class="form-text text-muted">
                                    Free users can set an interval between <strong>5 and 10 minutes</strong>. 
                                    <a href="{{ route('premium.page') }}">Upgrade to premium <i class="fa-solid fa-crown" style="color: #FFD43B;"></i></a> to set a shorter interval.
                                </small>
                            @endif
                        </div>

                <h5 class="card-title">Notification</h5>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input id="email" class="form-control" name="email" type="email" placeholder="example@gmail.com" value="{{ old('email') }}">
                    @if (old('form_type') === 'port')
                        @error('email')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    @endif
                </div>

               <div class="mb-3">
    <label for="telegram_id" class="form-label">Telegram ID (Optional)</label>
    <input id="telegram_id" class="form-control {{ auth()->user()->status === 'free' ? 'bg-light text-muted border-secondary' : '' }}" name="telegram_id" type="text"
           {{ auth()->user()->status === 'free' ? 'disabled title=Only available for paid users' : '' }}>
    @if (auth()->user()->status === 'free')
        <small class="form-text text-muted">
            <a href="{{ route('premium.page') }}">Upgrade to premium <i class="fa-solid fa-crown" style="color: #FFD43B;"></i></a> to enable Telegram notifications.
        </small>
    @endif
</div>

<div class="mb-3">
    <label for="telegram_bot_token" class="form-label">Telegram Bot Token (Optional)</label>
    <input id="telegram_bot_token" class="form-control {{ auth()->user()->status === 'free' ? 'bg-light text-muted border-secondary' : '' }}" name="telegram_bot_token" type="text"
           {{ auth()->user()->status === 'free' ? 'disabled title=Only available for paid users' : '' }}>
    @if (auth()->user()->status === 'free')
        <small class="form-text text-muted">
            <a href="{{ route('premium.page') }}">Upgrade to premium <i class="fa-solid fa-crown" style="color: #FFD43B;"></i></a> to enable Telegram notifications.
        </small>
    @endif
</div>


                <input class="btn btn-primary w-100" type="submit" value="Submit">
            `
            },
            dns: {
                title: "DNS Monitoring",
                fields: `
                <input type="hidden" name="form_type" value="dns"> <!-- Add this hidden input -->
                <div class="mb-3">
                        <label for="name" class="form-label">Friendly name</label>
                        <input id="name" class="form-control" name="name" type="text" placeholder="E.g. Google"  >
                        @if (old('form_type') === 'dns')
                            @error('name')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        @endif
                    </div>

                    <div class="mb-3">
                        <label for="domain" class="form-label">Domain or URL</label>
                        <input id="domain" class="form-control" name="domain" type="text" placeholder="E.g. google.com"   >
                        @if (old('form_type') === 'dns')
                            @error('domain')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        @endif
                    </div>


<div class="mb-3">
    <label for="interval" class="form-label">Interval (in minutes)</label>
    <input 
        id="interval"
        class="form-control {{ auth()->user()->status === 'free' ? 'bg-light text-muted border-secondary' : '' }}"
        name="interval"
        type="number"
        min="{{ auth()->user()->status === 'free' ? 5 : 1 }}"
        max="10"
        value="{{ old('interval', auth()->user()->status === 'free' ? 5 : 1) }}"
        {{ auth()->user()->status === 'free' ? 'title=Only 5-10 minutes allowed for free users' : '' }}
    >
    @if (auth()->user()->status === 'free')
        <small class="form-text text-muted">
            Free users can set an interval between <strong>5 and 10 minutes</strong>. 
            <a href="{{ route('premium.page') }}">Upgrade to premium <i class="fa-solid fa-crown" style="color: #FFD43B;"></i></a> to set a shorter interval.
        </small>
    @endif
</div>
                    <div class="mb-3">
                        <label for="retries" class="form-label">Retries</label>
                        <input id="retries" class="form-control" name="retries" type="number" min="0" value="3"  >
                        @if (old('form_type') === 'dns')
                            @error('retries')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        @endif
                    </div>

                    <div class="mb-3">
                        <label for="dns_resource_type" class="form-label">DNS Resource Type</label>
                        <select id="dns_resource_type" class="form-control" name="dns_resource_type"  >
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
                        @if (old('form_type') === 'dns')
                            @error('dns_resource_type')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        @endif
                    </div>

                    <h5 class="card-title">Notification</h5>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input id="email" class="form-control" name="email" type="email" placeholder="example@gmail.com"  >
                        @if (old('form_type') === 'dns')
                            @error('email')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        @endif
                    </div>

                    <div class="mb-3">
    <label for="telegram_id" class="form-label">Telegram ID (Optional)</label>
    <input id="telegram_id" class="form-control {{ auth()->user()->status === 'free' ? 'bg-light text-muted border-secondary' : '' }}" name="telegram_id" type="text"
           {{ auth()->user()->status === 'free' ? 'disabled title=Only available for paid users' : '' }}>
    @if (auth()->user()->status === 'free')
        <small class="form-text text-muted">
            <a href="{{ route('premium.page') }}">Upgrade to premium <i class="fa-solid fa-crown" style="color: #FFD43B;"></i></a> to enable Telegram notifications.
        </small>
    @endif
</div>

<div class="mb-3">
    <label for="telegram_bot_token" class="form-label">Telegram Bot Token (Optional)</label>
    <input id="telegram_bot_token" class="form-control {{ auth()->user()->status === 'free' ? 'bg-light text-muted border-secondary' : '' }}" name="telegram_bot_token" type="text"
           {{ auth()->user()->status === 'free' ? 'disabled title=Only available for paid users' : '' }}>
    @if (auth()->user()->status === 'free')
        <small class="form-text text-muted">
            <a href="{{ route('premium.page') }}">Upgrade to premium <i class="fa-solid fa-crown" style="color: #FFD43B;"></i></a> to enable Telegram notifications.
        </small>
    @endif
</div>

                    
                    <input class="btn btn-primary w-100" type="submit" value="Submit">
            `,
                action: '/add/dns'
            },
        };


    function updateDropdown(selectedType, formType) {
        document.getElementById("dropdownMenuButton").innerText = selectedType;
        document.getElementById("selectedType").value = formType; // Update hidden input value
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

    function isValidEmail(email) {
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailPattern.test(email);
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
                <input type="hidden" name="form_type" value="${type}"> <!-- Add this hidden input -->
                <input type="hidden" id="selectedType" name="selectedType" value="${type}"> <!-- Hidden input -->
                ${forms[type].fields}
            </form>
        `;

        const form = document.getElementById('monitoringForm');
        form.addEventListener('submit', function(event) {
            let isValid = true;

            // URL/Domain validation
            const urlField = document.getElementById('url') || document.getElementById('domain');
            if (urlField) {
                const fieldValue = urlField.value.trim();
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

            // Retries validation (1-5)
            const retriesField = document.getElementById('retries');
            if (retriesField) {
                const retriesValue = parseInt(retriesField.value);
                if (isNaN(retriesValue) || retriesValue < 1 || retriesValue > 5) {
                    event.preventDefault();
                    showError(retriesField, 'Retries must be between 1 and 5');
                    isValid = false;
                } else {
                    clearError(retriesField);
                }
            }

            // Interval validation (1-1440)
            const intervalField = document.getElementById('interval');
            if (intervalField) {
                const intervalValue = parseInt(intervalField.value);
                if (isNaN(intervalValue) || intervalValue < 1 || intervalValue > 1440) {
                    event.preventDefault();
                    showError(intervalField, 'Interval must be between 1 and 1440 minutes');
                    isValid = false;
                } else {
                    clearError(intervalField);
                }
            }

            // Email validation
            const emailField = document.getElementById('email');
            if (emailField) {
                const emailValue = emailField.value.trim();
                if (!isValidEmail(emailValue)) {
                    event.preventDefault();
                    showError(emailField, 'Please enter a valid email address');
                    isValid = false;
                } else {
                    clearError(emailField);
                }
            }

            return isValid;
        });

        // URL/Domain field validation
        const urlField = document.getElementById('url') || document.getElementById('domain');
        if (urlField) {
            urlField.addEventListener('blur', function() {
                const fieldValue = urlField.value.trim();
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

            urlField.addEventListener('input', function() {
                const fieldValue = urlField.value.trim();
                if (fieldValue === '') {
                    clearError(urlField);
                }
            });
        }

        // Retries field validation
        const retriesField = document.getElementById('retries');
        if (retriesField) {
            retriesField.addEventListener('blur', function() {
                const value = parseInt(this.value);
                if (isNaN(value) || value < 1 || value > 5) {
                    showError(this, 'Retries must be between 1 and 5');
                } else {
                    clearError(this);
                }
            });

            retriesField.addEventListener('input', function() {
                if (this.value === '') {
                    clearError(this);
                }
            });
        }

        // Interval field validation
        const intervalField = document.getElementById('interval');
        if (intervalField) {
            intervalField.addEventListener('blur', function() {
                const value = parseInt(this.value);
                if (isNaN(value) || value < 1 || value > 1440) {
                    showError(this, 'Interval must be between 1 and 1440 minutes');
                } else {
                    clearError(this);
                }
            });

            intervalField.addEventListener('input', function() {
                if (this.value === '') {
                    clearError(this);
                }
            });
        }

        // Email field validation
        const emailField = document.getElementById('email');
        if (emailField) {
            emailField.addEventListener('blur', function() {
                const emailValue = this.value.trim();
                if (!isValidEmail(emailValue)) {
                    showError(this, 'Please enter a valid email address');
                } else {
                    clearError(this);
                }
            });

            emailField.addEventListener('input', function() {
                if (this.value === '') {
                    clearError(this);
                }
            });
        }
    }

    // Initialize form on page load
    document.addEventListener('DOMContentLoaded', function() {
        const selectedType = "{{ old('selectedType', 'http') }}"; // Retain selected type after submission
        document.getElementById("dropdownMenuButton").innerText = forms[selectedType].title;
        showForm(selectedType);
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

<script>
    $(document).ready(function() {
        $('#startTourBtn').click(function() {
            introJs().setOptions({
                steps: [
                    {
                        element: document.querySelector('.MonitorTypes'),
                        intro: 'Choose types of monitoring.',
                        position: 'right'
                    },
                    {
                        element: document.querySelector('#formContainer'),
                        intro: 'Fill in the details for the selected monitoring type.',
                        position: 'top'
                    }
                ],
                nextLabel: 'Next',
                prevLabel: 'Back',
                doneLabel: 'Finish'
            }).start();
        });
    });
</script>


@endsection
