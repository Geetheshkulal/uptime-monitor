


<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="/">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-heartbeat me-2"></i>
        </div>
        <div class="sidebar-brand-text mx-3">CheckMySite</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    {{-- <li class="nav-item active">
        <a class="nav-link" href="{{route('monitoring.dashboard')}}">
           
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li> --}}


    {{-- <li class="nav-item active">
        <a class="nav-link" href="{{route('incidents')}}">
            <i class="fas fa-exclamation-triangle"></i>
            <span>Incidents</span></a>
    </li> --}}


    {{-- <li class="nav-item active">
        <a class="nav-link" href="{{ route('ssl.check') }}">
            <i class="fas fa-lock"></i>
            <span>SSL Check</span></a>
    </li> --}}


    <li class="nav-item {{ request()->is('monitoring*') ? 'active' : '' }}" 
        style="{{ request()->is('monitoring*') ? 'background-color: #1b3b6f !important; border-left: 4px solid #ffffff; box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.4); transition: all 0.3s ease-in-out;' : '' }}">
        <a class="nav-link text-white" href="{{ route('monitoring.dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>
    


    <li class="nav-item {{ request()->routeIs('incidents') ? 'active' : '' }}" 
        style="{{ request()->routeIs('incidents') ? 'background-color: #1b3b6f !important; border-left: 4px solid #ffffff; box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.4); transition: all 0.3s ease-in-out;' : '' }}">
        <a class="nav-link text-white" href="{{ route('incidents') }}">
            <i class="fas fa-exclamation-triangle"></i>
            <span>Incidents</span>
        </a>
    </li>


    <li class="nav-item {{ request()->routeIs('ssl.check') ? 'active' : '' }}" 
        style="{{ request()->routeIs('ssl.check') ? 'background-color: #1b3b6f !important; border-left: 4px solid #ffffff; box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.4); transition: all 0.3s ease-in-out;' : '' }}">
        <a class="nav-link text-white" href="{{ route('ssl.check') }}">
            <i class="fas fa-lock"></i>
            <span>SSL Check</span>
        </a>
    </li>

    <!-- Divider -->
    <!-- <hr class="sidebar-divider"> -->

    <!-- Heading -->
    <!-- <div class="sidebar-heading">
        Interface
    </div>

    
    <li class="nav-item">
        <a href="{{route('monitoring.dashboard')}}" class="nav-link " href="#"  data-target="#collapseTwo"
            aria-expanded="true" aria-controls="collapseTwo">
            <i class="fas fa-fw fa-cog"></i>
            <span>Monitoring</span>
        </a> -->
        {{-- <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Custom Components:</h6>
                <a class="collapse-item" href="buttons.html">Buttons</a>
                <a class="collapse-item" href="cards.html">Cards</a>
            </div>
        </div> --}}
    </li>

    
    <!-- <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities"
            aria-expanded="true" aria-controls="collapseUtilities">
            <i class="fas fa-fw fa-wrench"></i>
            <span>Utilities</span>
        </a>
        <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities"
            data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Custom Utilities:</h6>
                <a class="collapse-item" href="utilities-color.html">Colors</a>
                <a class="collapse-item" href="utilities-border.html">Borders</a>
                <a class="collapse-item" href="utilities-animation.html">Animations</a>
                <a class="collapse-item" href="utilities-other.html">Other</a>
            </div>
        </div>
    </li>

    
    <hr class="sidebar-divider">

    
    <div class="sidebar-heading">
        Addons
    </div>

    
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages"
            aria-expanded="true" aria-controls="collapsePages">
            <i class="fas fa-fw fa-folder"></i>
            <span>Pages</span>
        </a>
        <div id="collapsePages" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Login Screens:</h6>
                <a class="collapse-item" href="login.html">Login</a>
                <a class="collapse-item" href="register.html">Register</a>
                <a class="collapse-item" href="forgot-password.html">Forgot Password</a>
                <div class="collapse-divider"></div>
                <h6 class="collapse-header">Other Pages:</h6>
                <a class="collapse-item" href="404.html">404 Page</a>
                <a class="collapse-item" href="blank.html">Blank Page</a>
            </div>
        </div>
    </li>

    
    <li class="nav-item">
        <a class="nav-link" href="charts.html">
            <i class="fas fa-fw fa-chart-area"></i>
            <span>Charts</span></a>
    </li>

    
    <li class="nav-item">
        <a class="nav-link" href="tables.html">
            <i class="fas fa-fw fa-table"></i>
            <span>Tables</span></a>
    </li> -->

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

    <!-- Sidebar Message -->
    <div class="sidebar-card d-none d-lg-flex">
        <img class="sidebar-card-illustration mb-2" src="img/undraw_rocket.svg" alt="...">
        <p class="text-center mb-2"><strong>CheckMySite Pro</strong> is packed with premium features, components, and more!</p>
        <a class="btn btn-success btn-sm" href="https://startbootstrap.com/theme/sb-admin-pro">Upgrade to Pro!</a>
    </div>

</ul>