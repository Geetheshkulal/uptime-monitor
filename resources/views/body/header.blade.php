@push('styles')
<head>
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
  .Free-trial-notice{
    position: absolute;
    background-color: #f8d210;
    color: #000;
    font-size: 16px;
    font-weight: bold;
    z-index: 1000; 
    width: 100%;
    margin-bottom: 5px;
    padding: 3px;
  }
  </style>
</head>
{{-- @if (auth()->user()->status === 'paid' && auth()->user()->premium_end_date===Null)
@php
    // Calculate remaining trial days
    $trialDaysLeft = now()->diffInDays(auth()->user()->created_at->addDays(10), false);
@endphp

@if ($trialDaysLeft > 0)
    <div class="Free-trial-notice">
        You have free trial access for {{ $trialDaysLeft }} more days!
    </div>
@endif
@endif --}}



<header class="navbar navbar-expand navbar-light bg-white topbar mb-4 shadow">
  <!-- Sidebar Toggle (Topbar) -->
  <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
      <i class="fa fa-bars"></i>
  </button>
  @hasrole('user')
  <div class="dropdown">
    <button
      class="btn dropdown-toggle"
      type="button"
      id="helpBtn"
      data-toggle="dropdown"
      aria-haspopup="true"
      aria-expanded="false"
    >
      <i class="fas fa-question-circle mr-2"></i> Help
    </button>
    <div class="dropdown-menu" aria-labelledby="helpBtn">
      @if (request()->is('dashboard*') || request()->is('ssl-check*') || request()->is('monitoring/add*'))
          <button class="dropdown-item" id="startTourBtn">
              <i class="fas fa-play mr-2"></i> Start Tour
          </button>
      @endif
      <a class="dropdown-item" href="mailto:checkmysite2025@gmail.com?subject=Issue%20Report">
          <i class="fas fa-bug mr-2"></i> Report an Issue
      </a>
      <a class="dropdown-item" href="{{ url('/documentation') }}">
          <i class="fas fa-info-circle mr-2"></i> For more info
      </a>
  </div>
  </div>
  
  @endhasrole

  <!-- Topbar Navbar -->
  <ul class="navbar-nav ml-auto">


      <div class="topbar-divider d-sm-block"></div>

      <!-- Nav Item - User Information -->
      <li class="nav-item">
          <a class="nav-link" href="{{ url('/profile') }}" role="button" aria-haspopup="true" aria-expanded="false">
              <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ Auth::user()->name }}</span>
              <img class="img-profile rounded-circle profile"
                  src="{{ Avatar::create(auth()->user()->name)->toBase64() }}">
          </a>
      </li>

       
       <li class="nav-item">
        <a class="nav-link" href="#" data-toggle="modal" data-target="#logoutModal">
            <i class="fas fa-sign-out-alt fa-sm fa-fw fa-rotate-180  text-gray-600"></i>
        </a>
    </li>

  </ul>

</header>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

