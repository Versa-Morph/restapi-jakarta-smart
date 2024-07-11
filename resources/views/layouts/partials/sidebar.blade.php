<!-- Sidebar Start -->
<aside class="sidebar" data-trigger="scrollbar">
    <!-- Sidebar Profile Start -->
    <div class="sidebar--profile">
        <div class="profile--img">
            <a href="profile.html">
                <img src="assets/img/avatars/01_80x80.png" alt="" class="rounded-circle">
            </a>
        </div>

        <div class="profile--name">
            <a href="profile.html" class="btn-link">Andika</a>
        </div>

        <div class="profile--nav">
            <ul class="nav">
                <li class="nav-item">
                    <a href="profile.html" class="nav-link" title="User Profile">
                        <i class="fa fa-user"></i>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="lock-screen.html" class="nav-link" title="Lock Screen">
                        <i class="fa fa-lock"></i>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="mailbox_inbox.html" class="nav-link" title="Messages">
                        <i class="fa fa-envelope"></i>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link" title="Logout">
                        <i class="fa fa-sign-out-alt"></i>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <!-- Sidebar Profile End -->

    <!-- Sidebar Navigation Start -->
    <div class="sidebar--nav d-none">
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
                    <li class="">
                        <a href="#!">
                            <i class="fas fa-shopping-basket"></i>
                            <span>Data</span>
                        </a>
                    </li>
                    <li class="">
                        <a href="#!">
                            <i class="fas fa-user-secret"></i>
                            <span>Instance</span>
                        </a>
                    </li>
                    <li class="">
                        <a href="#!">
                            <i class="fas fa-calendar-alt"></i>
                            <span>Incident</span>
                        </a>
                    </li>
                    <li class="">
                        <a href="#!">
                            <i class="fas fa-chart-bar"></i>
                            <span>Statistic</span>
                        </a>
                    </li>
                    <li class="">
                        <a href="#!">
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
