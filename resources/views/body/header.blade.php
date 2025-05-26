@push('styles')
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
 #helpDropdown {
    padding: 10px 15px;
    font-size: 1rem;
    font-weight: 600;
}

#helpDropdown i {
    font-size: 1.2rem;
}

#helpDropdown .fa-caret-down {
    font-size: 0.9rem;
    margin-left: 5px;
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



<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
 <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>
  {{-- @hasrole('user')
  <div class="dropdown d-flex align-items-center">
    <span class="d-flex align-items-center mr-2">
        <i class="fas fa-question-circle mr-1"></i> Help
    </span>
    <button
      class="btn btn-sm dropdown-toggle"
      type="button"
      id="helpBtn"
      data-toggle="dropdown"
      aria-haspopup="true"
      aria-expanded="false"
    >
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
  
  @endhasrole --}}

  <!-- Topbar Navbar -->
  <ul class="navbar-nav ml-auto">
    @hasanyrole(['user','subuser'])
      <li class="nav-item dropdown no-arrow mx-1">
          <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="helpDropdown" role="button"
              data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="padding: 10px 15px; font-size: 1rem; font-weight: 600;">
              <i class="fas fa-question-circle mr-2" style="font-size: 1.2rem;"></i>
              <span class="text-gray-600">Help</span>
              <i class="fas fa-caret-down ml-1" style="font-size: 0.9rem;"></i> <!-- Dropdown indicator -->
          </a>
          <!-- Dropdown - Help -->
          <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="helpDropdown">
              @if (request()->is('dashboard*') || request()->is('ssl-check*') || request()->is('monitoring/add*'))
                  <button class="dropdown-item" id="startTourBtn">
                      <i class="fas fa-play mr-2"></i> Start Tour
                  </button>
              @endif
              <a class="dropdown-item" href="{{url('/raise/tickets')}}">
                  <i class="fas fa-bug mr-2"></i> Report an Issue
              </a>
              <a class="dropdown-item" href="{{ url('/documentation') }}">
                  <i class="fas fa-info-circle mr-2"></i> For more info
              </a>
          </div>
      </li>
    
  @endhasanyrole
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

</nav>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
 