<!-- Navbar Start -->
<header class="navbar navbar-fixed">
    <!-- Navbar Header Start -->
    <div class="navbar--header">
        <!-- Logo Start -->
        <a href="index.html" class="logo">
            <img src="assets/img/logo-smartJKT.png" alt="" width="80%" class="d-block mx-auto">
        </a>
        <!-- Logo End -->

        <!-- Sidebar Toggle Button Start -->
        <a href="#" class="navbar--btn" data-toggle="sidebar" title="Toggle Sidebar">
            <i class="fa fa-bars"></i>
        </a>
        <!-- Sidebar Toggle Button End -->
    </div>
    <!-- Navbar Header End -->

    <!-- Sidebar Toggle Button Start -->
    <a href="#" class="navbar--btn" data-toggle="sidebar" title="Toggle Sidebar">
        <i class="fa fa-bars"></i>
    </a>
    <!-- Sidebar Toggle Button End -->

    <!-- Navbar Search Start -->
    <div class="navbar--search d-none">
        <form action="#!">
            <input type="search" name="search" class="form-control" placeholder="Search Something..." required>
            <button class="btn-link"><i class="fa fa-search"></i></button>
        </form>
    </div>
    <!-- Navbar Search End -->

    <div class="mx-4 d-none d-md-block">
        <h3 class="m-0 p-0 text-dark font-weight-bold">Hello <span class="text-capitalize">{{ Auth::user()->username }}</span>! 👋</h3>
        <p class="m-0 p-0">Welcome back, we miss your coming</p>
    </div>

    <div class="navbar--nav ml-auto">
        <ul class="nav">
            <li class="nav-item">
                <a href="#!" class="nav-link px-3">
                    <i class="far fa-envelope" style="font-size: 25px; color:#2C2828;"></i>
                </a>
            </li>

            <li class="nav-item">
                <a href="#" class="nav-link px-3">
                    <span class="badge text-white bg-orange">&nbsp;</span>
                    <i class="far fa-bell" style="font-size: 25px; color:#2C2828;"></i>
                </a>
            </li>


            <!-- Nav User Start -->
            <li class="nav-item dropdown nav--user online">
                <a href="#" class="nav-link" data-toggle="dropdown">
                    <img src="assets/img/avatars/01_80x80.png" alt="" class="rounded-circle">
                    <span>{{ Auth::user()->username }}</span>
                    <i class="fa fa-angle-down"></i>
                </a>

                <ul class="dropdown-menu">
                    <li><a href="#!"><i class="far fa-user"></i>Profile</a></li>
                    <li><a href="#!"><i class="far fa-envelope"></i>Inbox</a></li>
                    <li><a href="#"><i class="fa fa-cog"></i>Settings</a></li>
                    <li class="dropdown-divider"></li>
                    <li><a href="#!"><i class="fa fa-lock"></i>Lock Screen</a></li>
                    <li><a href="#!" onclick="event.preventDefault(); confirmLogout()"><i class="fa fa-power-off"></i>Logout</a> <form id="logout-form" action="{{ route('logout') }}" method="post" style="display: none;">@csrf</form></li>
                </ul>
            </li>
            <!-- Nav User End -->
        </ul>
    </div>
</header>
<!-- Navbar End -->
