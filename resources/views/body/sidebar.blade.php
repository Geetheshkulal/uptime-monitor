<style>
    /* ========== SIDEBAR STYLES ========== */
    .sidebar {
        background: linear-gradient(180deg, #4e73df 0%, #224abe 100%);
        min-height: 100vh;
        transition: all 0.3s;
    }

    .sidebar-brand {
        height: 4.375rem;
        background: rgba(0, 0, 0, 0.1);
    }

    .sidebar-brand-icon {
        font-size: 1.2rem;
    }

    .sidebar-brand-text {
        font-size: 1rem;
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    .sidebar-divider {
        border-top: 1px solid rgba(255, 255, 255, 0.15);
        margin: 1rem 0;
    }

    .nav-item {
    position: relative;
    margin: 0.2rem 0.4rem; /* adjust margin */
    border-radius: 0.35rem;
    transition: all 0.3s ease;
    width: calc(100% - 0.8rem); /* set width */
}


    .nav-item.active {
        background-color: rgba(255, 255, 255, 0.1) !important;
        border-left: 3px solid #fff !important;
        box-shadow: 0 0.15rem 0.5rem rgba(0, 0, 0, 0.15);
    }

    .nav-item:hover:not(.active) {
        background-color: rgba(255, 255, 255, 0.05);
    }

    .nav-link {
        color: rgba(255, 255, 255, 0.8) !important;
        font-size: 0.85rem;
        font-weight: 500;
        padding: 0.75rem 1rem;
        display: flex;
        align-items: center;
    }

    .nav-link i {
        font-size: 0.9rem;
        margin-right: 0.5rem;
        width: 1.2rem;
        text-align: center;
    }

    .nav-link:hover {
        color: #fff !important;
    }

    .sidebar-card {
        background: rgba(0, 0, 0, 0.1);
        border-radius: 0.35rem;
        padding: 1rem;
        margin: 1rem;
    }

    .sidebar-card-illustration {
        width: 100%;
        height: auto;
        max-width: 120px;
        margin: 0 auto;
        display: block;
    }

    /* Premium feature indicator */
    .premium-feature {
        position: relative;
    }

    .premium-feature .fa-crown {
        font-size: 0.8rem;
        margin-left: 0.5rem;
        color: gold;
        animation: glow 1.5s infinite alternate;
    }

    @keyframes glow {
        from { opacity: 0.7; }
        to { opacity: 1; text-shadow: 0 0 5px rgb(252, 255, 59); }
    }
    @media (max-width: 767.98px) {
    .sidebar .nav-link {
        flex-direction: column;
        justify-content: center;
        align-items: center;
        font-size: 0.75rem;
        padding: 0.5rem 0.25rem;
        text-align: center;
    }

    .sidebar .nav-link i {
        margin-right: 0;
        margin-bottom: 0.25rem;
        font-size: 1rem;
    }
}

    /* Sidebar toggler */
    #sidebarToggle {
        width: 2.5rem;
        height: 2.5rem;
        background: rgba(255, 255, 255, 0.1);
        color: rgba(255, 255, 255, 0.5);
        margin: 0.5rem auto;
    }

    #sidebarToggle:hover {
        background: rgba(255, 255, 255, 0.2);
        color: #fff;
    }
</style>

<ul data-aos="fade-right" class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="/">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-heartbeat"></i>
        </div>
        <div class="sidebar-brand-text mx-2">CheckMySite</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    @hasrole('user')
    <li class="nav-item {{ request()->is('dashboard*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('monitoring.dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <li class="nav-item {{ request()->routeIs('incidents') ? 'active' : '' }}">
        <a class="nav-link incident" href="{{ route('incidents') }}">
            <i class="fas fa-exclamation-triangle"></i>
            <span>Incidents</span>
        </a>
    </li>

    <li class="nav-item {{ request()->routeIs('planSubscription') ? 'active' : '' }}">
        <a class="nav-link plan" href="{{ route('planSubscription') }}">
            <i class="fas fa-credit-card"></i>
            <span>Plan & Subscription</span>
        </a>
    </li>

    @if (auth()->user()->status === 'paid')
    <li class="nav-item {{ request()->routeIs('ssl.check') ? 'active' : '' }}">
        <a class="nav-link ssl" href="{{ route('ssl.check') }}">
            <i class="fas fa-lock"></i>
            <span>SSL Check</span>
        </a>
    </li>
    @elseif(auth()->user()->status === 'free')
    <li class="nav-item premium-feature {{ request()->routeIs('ssl.check') ? 'active' : '' }}">
        <a class="nav-link ssl" href="{{ route('premium.page') }}">
            <i class="fas fa-lock"></i>
            <span>SSL Check</span>
            <i class="fas fa-crown"></i>
        </a>
    </li>
    @endif
    @endhasrole

    @hasrole('superadmin')
    <li class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>
    @endhasrole

    @can('see.users')
    <li class="nav-item {{ request()->routeIs('display.users') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('display.users') }}">
            <i class="fas fa-user"></i>
            <span>Users</span>
        </a>
    </li>
    @endcan

    @can('see.roles')
    <li class="nav-item {{ request()->routeIs('display.roles') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('display.roles') }}">
            <i class="fas fa-user-tag"></i>
            <span>Roles</span>
        </a>
    </li>
    @endcan

    @hasrole('superadmin')
    <li class="nav-item {{ request()->routeIs('display.permissions') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('display.permissions') }}">
            <i class="fas fa-door-open"></i>
            <span>Permissions</span>
        </a>
    </li>
    <li class="nav-item {{ request()->routeIs('billing') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('billing') }}">
            <i class="fas fa-money-bill"></i>
            <span>Billing</span>
        </a>
    </li>
    @endhasrole

    @can('see.activity')
    <li class="nav-item {{ request()->routeIs('display.activity') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('display.activity') }}">
            <i class="fas fa-chart-line"></i>
            <span>Activity Log</span>
        </a>
    </li>
    @endcan

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle">
            <i class="fas fa-angle-left"></i>
        </button>
    </div>

    @role('user')
    @if(auth()->user()->status === 'free')
    <div class="sidebar-card d-none d-lg-flex">
        <img class="sidebar-card-illustration mb-2" src="{{asset('frontend/assets/img/undraw_rocket.svg')}}" alt="Premium Features">
        <p class="text-center mb-2 text-white-50"><strong>CheckMySite Pro</strong> is packed with premium features</p>
        <a class="btn btn-sm btn-warning" href="{{route('premium.page')}}">
            <i class="fas fa-crown mr-1"></i>Upgrade to Pro
        </a>
    </div>
    @endif
    @endrole
</ul>