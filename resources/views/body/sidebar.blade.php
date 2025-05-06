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
        /* adjust margin */
        border-radius: 0.35rem;
        transition: all 0.3s ease;
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
    .sidebar-brand {
  background: linear-gradient(180deg, #4e73df 0%, #224abe 100%);
  height: 4.375rem;
  margin: 0;
  width: 225px; /* same as .sidebar */
  padding: 0 1rem;
  display: flex;
  align-items: center;
  justify-content: flex-start;
  text-decoration: none;
  box-sizing: border-box;
  align-items: center;
  justify-content: center; 
}


.sidebar-brand-icon {
  font-size: 1.2rem;
  color: white;
  margin-right: 0.5rem; /* spacing between icon and text */
}

.sidebar-brand-text {
  font-size: 1rem;
  font-weight: 600;
  letter-spacing: 0.5px;
  color: white;
  white-space: nowrap; /* Prevent text from wrapping */
}

    /* Premium feature indicator */
    .premium-feature {
        position: relative;
    }

    .premium-feature .fa-crown {
        font-size: 0.8rem;
        margin-left: 0.5rem;
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
    .sidebar-brand {
        width: 105px;
        justify-content: center;
    }

    .sidebar-brand-text {
        display: none;
    }

    }
    .text-gold {
        color: gold !important;
    }

    .trial-banner {
    border: 1px solid rgba(255,255,255,0.15);
    box-shadow: 0 4px 15px rgba(106, 63, 186, 0.25);
    transition: all 0.3s ease;
    margin: 0 0.25rem;
    overflow: hidden;
    background: linear-gradient(135deg, #6e45e2 0%, #88d3ce 100%);
    border-radius: 8px !important;
}
.trial-banner:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(106, 63, 186, 0.35);
}

.trial-badge {
    background: linear-gradient(to right, #f6d365 0%, #fda085 100%) !important;
    color: #3a3a3a !important;
    font-weight: 700 !important;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

.trial-icon {
    background: rgba(255,255,255,0.25) !important;
    color: #fff !important;
}

.trial-notice span {
    text-shadow: 0 1px 2px rgba(0,0,0,0.1);
}

.upgrade-btn {
    background: linear-gradient(to right, #f83600 0%, #f9d423 100%);
    color: white !important;
    font-weight: 600;
    padding: 0.4rem 1rem;
    border: none;
    border-radius: 20px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    margin-top: 0.5rem;
    transition: all 0.3s ease;
    font-size: 0.7rem;
    text-decoration: none !important; 
    display: inline-block;
}

.upgrade-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 6px 12px rgba(0,0,0,0.2);
    color: white !important;
    text-decoration: none !important; 
}

.progress {
    background: rgba(0,0,0,0.15) !important;
    height: 4px !important;
}

.progress-bar {
    background: linear-gradient(to right, #a1ffce, #faffd1) !important;
}

/* Responsive adjustments */
@media (max-width: 992px) {
    .trial-banner {
        padding: 0.4rem !important;
    }
    .trial-badge {
        font-size: 0.55rem !important;
        padding: 0.1rem 0.3rem !important;
        top: -6px !important;
    }
    .trial-icon {
        width: 26px !important;
        height: 26px !important;
    }
    .trial-icon i {
        font-size: 0.9rem !important;
    }
    .trial-notice span {
        font-size: 0.75rem !important;
    }
    .upgrade-btn {
        padding: 0.3rem 0.8rem;
        font-size: 0.65rem;
    }
}
        

</style>

<ul data-aos="fade-right" class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion d-flex flex-column" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="/">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-heartbeat"></i>
        </div>
        <div class="sidebar-brand-text mx-2">CheckMySite</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Wrapper for main nav items -->
    <div class="flex-grow-1">
        @hasanyrole(['user','subuser'])
            @can('see.monitors')
                <li class="nav-item {{ request()->is('dashboard*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('monitoring.dashboard') }}">
                        <i class="fas fa-fw fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
            @endcan 

            @can('see.incidents')
                <li class="nav-item {{ request()->routeIs('incidents') ? 'active' : '' }}">
                    <a class="nav-link incident" href="{{ route('incidents') }}">
                        <i class="fas fa-exclamation-triangle"></i>
                        <span>Incidents</span>
                    </a>
                </li>
            @endcan

            @can('see.statuspage')
                <li class="nav-item {{ request()->routeIs('status') ? 'active' : '' }}"> 
                    <a class="nav-link" href="{{ route('status') }}">
                        <i class="fas fa-signal"></i> 
                        <span>Status Page</span>
                    </a>
                </li>
            @endcan

        @endhasanyrole

        @hasrole('user')
            <li class="nav-item {{ request()->routeIs('planSubscription') ? 'active' : '' }}">
                <a class="nav-link plan" href="{{ route('planSubscription') }}">
                    <i class="fas fa-credit-card"></i>
                    <span>Plan & Subscription</span>
                </a>
            </li>
        @endhasrole

    @if (auth()->user()->status === 'paid' || auth()->user()->status === 'free_trial')
        <li class="nav-item {{ request()->routeIs('ssl.check') ? 'active' : '' }}">
            <a class="nav-link ssl" href="{{ route('ssl.check') }}">
                <i class="fas fa-lock"></i>
                <span>SSL Check</span>
            </a>
        </li>
    @elseif(auth()->user()->status === 'free')
        <li class="nav-item premium-feature {{ request()->routeIs('ssl.check') ? 'active' : '' }}">
            <a class="nav-link ssl text-gold" href="{{ route('premium.page') }}">
                <i class="fas fa-lock text-gold"></i>
                <span class="text-gold">SSL Check</span>
                <i class="fas fa-crown text-gold"></i>
            </a>
        </li>
    @endif

    @hasrole('user')
        <li class="nav-item {{ request()->routeIs('display.sub.users') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('display.sub.users') }}">
                <i class="fas fa-user"></i>
                <span>My Users</span>
            </a>
        </li>
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
    @hasrole('superadmin')
    <li class="nav-item {{ request()->routeIs('tickets') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('tickets') }}">
            <i class="fas fa-ticket-alt"></i>
            <span>Tickets</span>
        </a>
    </li>

    <li class="nav-item {{ request()->routeIs('display.coupons') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('display.coupons') }}">
            <i class="fas fa-percent"></i>
            <span>Coupons</span>
        </a>
    </li>

    @endhasrole

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

            <!-- Helpdesk item at bottom -->
        <li class="nav-item {{ request()->routeIs('display.tickets') ? 'active' : '' }}">
            <a class="nav-link" href="{{route('display.tickets')}}">
                <i class="fas fa-headset"></i>
                <span>Raise Issue</span>
            </a>
        </li>
        @if (auth()->user()->status === 'paid' && auth()->user()->premium_end_date === null)
    @php
        $trialDaysLeft = now()->diffInDays(auth()->user()->created_at->addDays(10), false);
    @endphp
    @hasrole('user')
        @if ($trialDaysLeft > 0)
            <li class="nav-item trial-notice mt-2 mb-2">
                <div class="trial-banner p-2 text-center position-relative" style="border-radius: 6px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <div class="trial-badge bg-warning text-dark px-2 py-1 rounded-pill d-inline-block" style="font-size: 0.6rem; position: absolute; top: -8px; left: 50%; transform: translateX(-50%); white-space: nowrap;">
                        
                    </div>
                    <div class="d-flex flex-column align-items-center pt-2">
                        <div class="trial-icon d-flex align-items-center justify-content-center mb-1" style="width: 30px; height: 30px; background: rgba(255,255,255,0.2); border-radius: 50%;">
                            <i class="fas fa-gift" style="font-size: 1rem; color: #e2fb65;"></i>
                        </div>
                        <span class="text-white fw-bold" style="font-size: 0.8rem; line-height: 1.2;">Premium Trial Active!</span>
                        <span class="text-white-50" style="font-size: 0.7rem;">{{ $trialDaysLeft }} days left</span>
                        <a href="{{ route('premium.page') }}" class="upgrade-btn">Upgrade To Premium</a>
                        <div class="progress w-100 mt-1" style="height: 3px;">    
                            <div class="progress-bar bg-white" role="progressbar" 
                                style="width: {{ 100 - ($trialDaysLeft * 10) }}%" 
                                aria-valuenow="{{ 100 - ($trialDaysLeft * 10) }}" 
                                aria-valuemin="0" 
                                aria-valuemax="100">
                            </div>
                          
                        </div>
                    </div>
                </div>
            </li>
            @endif
        @endif
    @endhasrole
        <!-- Divider -->
        <hr class="sidebar-divider d-none d-md-block">
    </div>



</ul>
