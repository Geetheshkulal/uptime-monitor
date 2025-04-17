


<ul data-aos="fade-right" class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="/">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-heartbeat me-2"></i>
        </div>
        <div class="sidebar-brand-text mx-3">CheckMySite</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    @hasrole('user')
    <li class="nav-item {{ request()->is('dashboard*') ? 'active' : '' }}" 
        style="{{ request()->is('dashboard*') ? 'background-color: #1b3b6f !important; border-left: 4px solid #ffffff; box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.4); transition: all 0.3s ease-in-out;' : '' }}">
        <a class="nav-link text-white" href="{{ route('monitoring.dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>
    


    <li class="nav-item {{ request()->routeIs('incidents') ? 'active' : '' }}" 
        style="{{ request()->routeIs('incidents') ? 'background-color: #1b3b6f !important; border-left: 4px solid #ffffff; box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.4); transition: all 0.3s ease-in-out;' : '' }}">
        <a class="nav-link text-white incident" href="{{ route('incidents') }}">
            <i class="fas fa-exclamation-triangle"></i>
            <span>Incidents</span>
        </a>
    </li>

    <li class="nav-item {{ request()->routeIs('planSubscription') ? 'active' : '' }}" 
        style="{{ request()->routeIs('planSubscription') ? 'background-color: #1b3b6f !important; border-left: 4px solid #ffffff; box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.4); transition: all 0.3s ease-in-out;' : '' }}">
        <a class="nav-link text-white plan" href="{{ route('planSubscription') }}">
            <i class="fas fa-credit-card"></i>
            <span>Plan & subscription</span>
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
        <a class="nav-link ssl text-white d-flex justify-content-between align-items-center" href="{{ route('premium.page') }}">
            <div>
                <i style="color: yellow;" class="fas fa-lock"></i>
                <span style="color: yellow;">SSL Check</span>
            </div>
            <i class="fas fa-crown fa-lg" style="color: gold; animation: glow 1.5s infinite alternate;"></i>
        </a>
    </li>
    @endif
    @endhasrole

    @hasrole('superadmin')
    <li class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" 
        style="{{ request()->routeIs('admin.dashboard') ? 'background-color: #1b3b6f !important; border-left: 4px solid #ffffff; box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.4); transition: all 0.3s ease-in-out;' : '' }}">
        <a class="nav-link text-white" href="{{ route('admin.dashboard') }}">
        <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>
    @endhasrole

    @can('see.users')
    <li class="nav-item {{ request()->routeIs('display.users') ? 'active' : '' }}" 
        style="{{ request()->routeIs('display.users') ? 'background-color: #1b3b6f !important; border-left: 4px solid #ffffff; box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.4); transition: all 0.3s ease-in-out;' : '' }}">
        <a class="nav-link text-white" href="{{ route('display.users') }}">
            <i class="fas fa-user"></i>
            <span>Users</span>
        </a>
    </li>
    @endcan

    @can('see.roles')
    <li class="nav-item {{ request()->routeIs('display.roles') ? 'active' : '' }}" 
        style="{{ request()->routeIs('display.roles') ? 'background-color: #1b3b6f !important; border-left: 4px solid #ffffff; box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.4); transition: all 0.3s ease-in-out;' : '' }}">
        <a class="nav-link text-white" href="{{ route('display.roles') }}">
            <i class="fas fa-user-tag"></i>
            <span>Roles</span>
        </a>
    </li>
    @endcan

    @hasrole('superadmin')
    <li class="nav-item {{ request()->routeIs('display.permissions') ? 'active' : '' }}" 
        style="{{ request()->routeIs('display.permissions') ? 'background-color: #1b3b6f !important; border-left: 4px solid #ffffff; box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.4); transition: all 0.3s ease-in-out;' : '' }}">
        <a class="nav-link text-white" href="{{ route('display.permissions') }}">
            <i class="fas fa-door-open"></i>
            <span>Permissions</span>
        </a>
    </li>
    <li class="nav-item {{ request()->routeIs('billing') ? 'active' : '' }}" 
        style="{{ request()->routeIs('billing') ? 'background-color: #1b3b6f !important; border-left: 4px solid #ffffff; box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.4); transition: all 0.3s ease-in-out;' : '' }}">
        <a class="nav-link text-white" href="{{ route('billing') }}">
            <i class="fas fa-money-bill"></i>
            <span>Billing</span>
        </a>
    </li>
    @endhasrole

    @can('see.activity')
    <li class="nav-item {{ request()->routeIs('display.activity') ? 'active' : '' }}" 
        style="{{ request()->routeIs('display.activity') ? 'background-color: #1b3b6f !important; border-left: 4px solid #ffffff; box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.4); transition: all 0.3s ease-in-out;' : '' }}">
        <a class="nav-link text-white" href="{{ route('display.activity') }}">
            <i class="fas fa-chart-line"></i>
            <span>Activity Log</span>
        </a>
    </li>
    @endcan


    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

    @role('user')
    <!-- Sidebar Message -->
     @if(auth()->user()->status === 'free')
        <div class="sidebar-card d-none d-lg-flex">
            <img class="sidebar-card-illustration mb-2" src="{{asset('frontend/assets/img/undraw_rocket.svg')}}" alt="...">
            <p class="text-center mb-2"><strong>Check My Site Pro</strong> is packed with premium features, components, and more!</p>
            <a class="btn btn-success btn-sm" href="{{route('premium.page')}}">Upgrade to Pro!</a>
        </div>
    @endif
    @endrole
</ul>