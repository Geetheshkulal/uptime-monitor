


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


    @if (auth()->user()->status === 'paid')
    <li class="nav-item {{ request()->routeIs('ssl.check') ? 'active' : '' }}" 
        style="{{ request()->routeIs('ssl.check') ? 'background-color: #1b3b6f !important; border-left: 4px solid #ffffff; box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.4); transition: all 0.3s ease-in-out;' : '' }}">
        <a class="nav-link text-white" href="{{ route('ssl.check') }}">
            <i class="fas fa-lock"></i>
            <span>SSL Check</span>
        </a>
    </li>
    @elseif(auth()->user()->status === 'free')
    <li class="nav-item {{ request()->routeIs('ssl.check') ? 'active' : '' }}" 
        style="{{ request()->routeIs('ssl.check') ? 'background-color: #1b3b6f !important; border-left: 4px solid #ffffff; box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.4); transition: all 0.3s ease-in-out;' : '' }}">
        <a class="nav-link text-white d-flex justify-content-between align-items-center" href="{{ route('premium.page') }}">
            <div>
                <i style="color: yellow;" class="fas fa-lock"></i>
                <span style="color: yellow;">SSL Check</span>
            </div>
            <i class="fas fa-crown fa-lg" style="color: gold; animation: glow 1.5s infinite alternate;"></i>
        </a>
    </li>
    @endif

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

    <!-- Sidebar Message -->
     @if(auth()->user()->status === 'free')
        <div class="sidebar-card d-none d-lg-flex">
            <img class="sidebar-card-illustration mb-2" src="{{asset('frontend/assets/img/undraw_rocket.svg')}}" alt="...">
            <p class="text-center mb-2"><strong>Check My Site Pro</strong> is packed with premium features, components, and more!</p>
            <a class="btn btn-success btn-sm" href="{{route('premium.page')}}">Upgrade to Pro!</a>
        </div>
    @endif

</ul>