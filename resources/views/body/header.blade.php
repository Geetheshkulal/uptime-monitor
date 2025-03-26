

<link rel="stylesheet" href="{{ asset('css/app.css') }}">


<style>
    @import url("https://fonts.googleapis.com/css2?family=Montserrat&display=swap");
  
    * { box-sizing: border-box; }
  
    body {
      font-family: "Montserrat", sans-serif;
      background-color: #fff;
      transition: background 0.2s linear;
    }
  
    body.dark { background-color: #292c35; color: white; }
  
    .checkbox {
      opacity: 0;
      position: absolute;
    }
  
    .checkbox-label {
      background-color: #111;
      width: 50px;
      height: 26px;
      border-radius: 50px;
      position: relative;
      padding: 5px;
      cursor: pointer;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
  
    .fa-moon { color: #f1c40f; }
    .fa-sun { color: #f39c12; }
  
    .checkbox-label .ball {
      background-color: #fff;
      width: 22px;
      height: 22px;
      position: absolute;
      left: 2px;
      top: 2px;
      border-radius: 50%;
      transition: transform 0.2s linear;
    }
  
    .checkbox:checked + .checkbox-label .ball {
      transform: translateX(24px);
    }

    /* Ensure Bootstrap does not override dark mode */
body.dark {
  background-color: #292c35 !important; /* Force dark background */
  color: white !important;
}

/* Override Bootstrap background */
body.dark .bg-light {
  background-color: #333 !important;
  color: white !important;
}

body.dark .navbar, body.dark .card, body.dark .dropdown-menu {
  background-color: #444 !important;
  color: white !important;
}

body.dark .btn-primary {
  background-color: #007bff !important;
  border-color: #0056b3 !important;
}

body.dark .btn-secondary {
  background-color: #6c757d !important;
  border-color: #545b62 !important;
}

body.dark a {
  color: #f8d210 !important;
}

/* Ensure Bootstrap table is styled in dark mode */
body.dark .table {
  background-color: #2c2f33 !important;
  color: white !important;
}

body.dark .table thead {
  background-color: #23272a !important;
}

body.dark .table tbody tr {
  background-color: #2c2f33 !important;
  border-color: #555 !important;
}

/* Form Inputs */
body.dark input, body.dark textarea, body.dark select {
  background-color: #333 !important;
  color: white !important;
  border: 1px solid #555 !important;
}

/* Bootstrap Dropdown */
body.dark .dropdown-menu {
  background-color: #333 !important;
  border-color: #555 !important;
}

body.dark .dropdown-item {
  color: white !important;
}

body.dark .dropdown-item:hover {
  background-color: #444 !important;
}


  </style>



<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

    <!-- Sidebar Toggle (Topbar) -->
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>

    <!-- Topbar Search -->
    <form
        class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
        <div class="input-group">
            <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..."
                aria-label="Search" aria-describedby="basic-addon2">
            <div class="input-group-append">
                <button class="btn btn-primary" type="button">
                    <i class="fas fa-search fa-sm"></i>
                </button>
            </div>
        </div>
    </form>

 

    <!-- Topbar Navbar -->
    <ul class="navbar-nav ml-auto">

        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
        <li class="nav-item dropdown no-arrow d-sm-none">
            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-search fa-fw"></i>
            </a>
            <!-- Dropdown - Messages -->
            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                aria-labelledby="searchDropdown">
                <form class="form-inline mr-auto w-100 navbar-search">
                    <div class="input-group">
                        <input type="text" class="form-control bg-light border-0 small"
                            placeholder="Search for..." aria-label="Search"
                            aria-describedby="basic-addon2">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="button">
                                <i class="fas fa-search fa-sm"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </li>

        {{-- dark mode and light mode --}}

        {{-- <div>
            <input type="checkbox" class="checkbox" id="checkbox">
            <label for="checkbox" class="checkbox-label">
              <i class="fas fa-moon"></i>
              <i class="fas fa-sun"></i>
              <span class="ball"></span>
            </label>
          </div> --}}


          @php
                 $notifications = auth()->user()->unreadNotifications;
                 $notificationCount = $notifications->count();
          @endphp
        <!-- Nav Item - Alerts -->
        <li class="nav-item dropdown no-arrow mx-1">
            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-bell fa-fw"></i>
                <!-- Counter - Alerts -->
                @if($notificationCount > 0)
                <span class="badge badge-danger badge-counter">{{ $notificationCount }}</span>
            @endif
            </a>
            <!-- Dropdown - Alerts -->
            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                aria-labelledby="alertsDropdown">
                <h6 class="dropdown-header">
                    Alerts Center
                </h6>
                
                @forelse($notifications as $notification)
                <a class="dropdown-item d-flex align-items-center" href="{{ $notification->data['url'] }}">
                    <div>
                        <span class="font-weight-bold">{{ $notification->data['message'] }}</span>
                    </div>
                </a>
            @empty
                <a class="dropdown-item text-center small text-gray-500">No new notifications</a>
            @endforelse
            <a class="dropdown-item text-center small text-gray-500" href="{{ route('notifications.read') }}">Mark all as read</a>

                <a class="dropdown-item text-center small text-gray-500" href="#">Show All Alerts</a>
            </div>
        </li>

    

        <div class="topbar-divider d-none d-sm-block"></div>

        <!-- Nav Item - User Information -->
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{Auth::user()->name}}</span>
                <img class="img-profile rounded-circle"
                    src="{{ Avatar::create(auth()->user()->name)->toBase64() }}">
            </a>
            <!-- Dropdown - User Information -->
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                aria-labelledby="userDropdown">
                <a class="dropdown-item" href="{{ url('/profile') }}">
                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                    Profile
                </a>
                <a class="dropdown-item" href="#">
                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                    Settings
                </a>
                <a class="dropdown-item" href="#">
                    <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                    Activity Log
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                    Logout
                </a>
            </div>
        </li>

    </ul>

</nav>

<script>
const checkbox = document.getElementById("checkbox");

// Check local storage for theme preference
if (localStorage.getItem("theme") === "dark") {
    document.body.classList.add("dark");
    checkbox.checked = true;
}

checkbox.addEventListener("change", () => {
    document.body.classList.toggle("dark");

    if (document.body.classList.contains("dark")) {
        localStorage.setItem("theme", "dark");
    } else {
        localStorage.setItem("theme", "light");
    }
});
</script>
