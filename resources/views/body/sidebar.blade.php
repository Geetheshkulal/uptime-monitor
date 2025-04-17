<ul data-aos="fade-right" class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar" style="box-shadow: 5px 0 15px rgba(0, 0, 0, 0.2);">

    <a class="sidebar-brand d-flex align-items-center justify-content-center bg-primary-dark py-4" href="/">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-heartbeat me-2" style="font-size: 1.4rem;"></i>
        </div>
        <div class="sidebar-brand-text mx-3" style="font-size: 1.1rem; font-weight: bold;">CheckMySite</div>
    </a>

  

    @hasrole('user')
    <li class="nav-item {{ request()->is('dashboard*') ? 'active' : '' }} mb-2">
        <a class="nav-link text-white py-3 px-3" href="{{ route('monitoring.dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt me-2" style="font-size: 1rem;"></i>
            <span style="font-size: 1rem;">Dashboard</span>
        </a>
    </li>

    <li class="nav-item {{ request()->routeIs('incidents') ? 'active' : '' }} mb-2">
        <a class="nav-link text-white py-3 px-3 incident" href="{{ route('incidents') }}">
            <i class="fas fa-exclamation-triangle me-2" style="font-size: 1rem;"></i>
            <span style="font-size: 1rem;">Incidents</span>
        </a>
    </li>

    <li class="nav-item {{ request()->routeIs('planSubscription') ? 'active' : '' }} mb-2">
        <a class="nav-link text-white py-3 px-3 plan" href="{{ route('planSubscription') }}">
            <i class="fas fa-credit-card me-2" style="font-size: 1rem;"></i>
            <span style="font-size: 1rem;">Plan & subscription</span>
        </a>
    </li>

    @if (auth()->user()->status === 'paid')
    <li class="nav-item {{ request()->routeIs('ssl.check') ? 'active' : '' }} mb-2">
        <a class="nav-link text-white py-3 px-3" href="{{ route('ssl.check') }}">
            <i class="fas fa-lock me-2" style="font-size: 1rem;"></i>
            <span style="font-size: 1rem;">SSL Check</span>
        </a>
    </li>
    @elseif(auth()->user()->status === 'free')
    <li class="nav-item {{ request()->routeIs('ssl.check') ? 'active' : '' }} mb-2">
        <a class="nav-link ssl text-white py-3 px-3 d-flex justify-content-between align-items-center" href="{{ route('premium.page') }}">
            <div class="d-flex align-items-center">
                <i style="color: yellow; font-size: 1rem;" class="fas fa-lock me-2"></i>
                <span style="color: yellow; font-size: 1rem;">SSL Check</span>
            </div>
            <i class="fas fa-crown fa-lg" style="color: gold; animation: glow 1.5s infinite alternate;"></i>
        </a>
    </li>
    @endif
    @endhasrole

    @hasrole('superadmin')
    <li class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }} mb-2">
        <a class="nav-link text-white py-3 px-3" href="{{ route('admin.dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt me-2" style="font-size: 1rem;"></i>
            <span style="font-size: 1rem;">Dashboard</span>
        </a>
    </li>
    @endhasrole

    @can('see.users')
    <li class="nav-item {{ request()->routeIs('display.users') ? 'active' : '' }} mb-2">
        <a class="nav-link text-white py-3 px-3" href="{{ route('display.users') }}">
            <i class="fas fa-user me-2" style="font-size: 1rem;"></i>
            <span style="font-size: 1rem;">Users</span>
        </a>
    </li>
    @endcan

    @can('see.roles')
    <li class="nav-item {{ request()->routeIs('display.roles') ? 'active' : '' }} mb-2">
        <a class="nav-link text-white py-3 px-3" href="{{ route('display.roles') }}">
            <i class="fas fa-user-tag me-2" style="font-size: 1rem;"></i>
            <span style="font-size: 1rem;">Roles</span>
        </a>
    </li>
    @endcan

    @hasrole('superadmin')
    <li class="nav-item {{ request()->routeIs('display.permissions') ? 'active' : '' }} mb-2">
        <a class="nav-link text-white py-3 px-3" href="{{ route('display.permissions') }}">
            <i class="fas fa-door-open me-2" style="font-size: 1rem;"></i>
            <span style="font-size: 1rem;">Permissions</span>
        </a>
    </li>
    <li class="nav-item {{ request()->routeIs('billing') ? 'active' : '' }} mb-2">
        <a class="nav-link text-white py-3 px-3" href="{{ route('billing') }}">
            <i class="fas fa-money-bill me-2" style="font-size: 1rem;"></i>
            <span style="font-size: 1rem;">Billing</span>
        </a>
    </li>
    @endhasrole

    @can('see.activity')
    <li class="nav-item {{ request()->routeIs('display.activity') ? 'active' : '' }} mb-2">
        <a class="nav-link text-white py-3 px-3" href="{{ route('display.activity') }}">
            <i class="fas fa-chart-line me-2" style="font-size: 1rem;"></i>
            <span style="font-size: 1rem;">Activity Log</span>
        </a>
    </li>
    @endcan

    <hr class="sidebar-divider d-none d-md-block my-3" style="border-color: rgba(255, 255, 255, 0.2);">

    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>