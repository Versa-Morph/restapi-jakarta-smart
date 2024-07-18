<!-- Sidebar Start -->
<aside class="sidebar" data-trigger="scrollbar">
    <!-- Sidebar Profile Start -->
    <div class="sidebar--profile">
        <div class="profile--img">
            <a href="profile.html">
                <img src="{{ asset(Auth::user()->load('userBio')->profile_pict_path ?? 'profile_pictures/default.png') }}" alt="" class="rounded-circle">
            </a>
        </div>

        <div class="profile--name">
            <a href="{{ route('overview') }}" class="btn-link">{{ Auth::user()->username }}</a>
        </div>

        <div class="profile--nav">
            <ul class="nav">
                <li class="nav-item">
                    <a href="#!" class="nav-link" title="User Profile">
                        <i class="fa fa-user"></i>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#!" class="nav-link" title="Lock Screen">
                        <i class="fa fa-lock"></i>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#!" class="nav-link" title="Messages">
                        <i class="fa fa-envelope"></i>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#!" class="nav-link" title="Logout" onclick="event.preventDefault(); confirmLogout()">
                        <i class="fa fa-sign-out-alt"></i>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="post" style="display: none;">
                        @csrf
                    </form>
                </li>
            </ul>
        </div>
    </div>
    <!-- Sidebar Profile End -->

    <!-- Sidebar Navigation Start -->
    <div class="sidebar--nav">
        <ul>
            <li>
                <ul>
                    <li class="py-1">
                        <span class="ml-3 font-weight-bold" style="color:#D99022;">MAIN MENU</span>
                    </li>
                    <li class="{{ request()->routeIs('overview') ? 'active' : '' }}">
                        <a href="{{ route('overview') }}">
                            <i class="fas fa-th-large"></i>
                            <span>Overview</span>
                        </a>
                    </li>
                    @if(Auth::user()->role == 'admin')
                    <li class="{{ request()->routeIs('data.index') ? 'active' : '' }}">
                        <a href="{{ route('data.index') }}">
                            <i class="fas fa-shopping-basket"></i>
                            <span>Data</span>
                        </a>
                    </li>
                    <li class="{{ request()->routeIs(['instances.*', 'instance-details.*']) ? 'active' : '' }}">
                        <a href="{{ route('instances.index') }}">
                            <i class="fas fa-user-secret"></i>
                            <span>Instance</span>
                        </a>
                    </li>
                    @endif

                    <li class="{{ request()->routeIs('statistic.index') ? 'active' : '' }}">
                        <a href="{{ route('statistic.index') }}">
                            <i class="fas fa-chart-bar"></i>
                            <span>Statistic</span>
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('incidents.index') ? 'active' : '' }}">
                        <a href="{{ route('incidents.index') }}">
                            <i class="fas fa-calendar-alt"></i>
                            <span>Incident</span>
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('queue.index') ? 'active' : '' }}">
                        <a href="{{ route('queue.index') }}">
                            <i class="fas fa-users"></i>
                            <span>Queue</span>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
    <!-- Sidebar Navigation End -->
</aside>
<!-- Sidebar End -->
