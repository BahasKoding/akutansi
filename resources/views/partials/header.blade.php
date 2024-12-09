<nav class="app-header navbar navbar-expand bg-body">
    <div class="container-fluid">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button"><i class="bi bi-list"></i></a>
            </li>
            <li class="nav-item d-none d-md-block">
                <a href="#" class="nav-link">Home</a>
            </li>
            <li class="nav-item d-none d-md-block">
                <a href="#" class="nav-link">Contact</a>
            </li>
        </ul>

        <!-- Right navbar links -->
        <ul class="navbar-nav ms-auto">
            <!-- Navbar Search -->
            <li class="nav-item">
                <a class="nav-link" data-widget="navbar-search" href="#" role="button">
                    <i class="bi bi-search"></i>
                </a>
            </li>

            <!-- Messages Dropdown Menu -->
            <li class="nav-item dropdown">
                <a class="nav-link" data-bs-toggle="dropdown" href="#">
                    <i class="bi bi-chat-text"></i>
                    <span class="navbar-badge badge text-bg-danger">3</span>
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                    <a href="#" class="dropdown-item">
                        <!-- Message Start -->
                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                <img src="src/assets/img/aibdani.jpg" alt="User Avatar" class="img-size-50 rounded-circle me-3">
                            </div>
                            <div class="flex-grow-1">
                                <h3 class="dropdown-item-title">
                                    Dani Laku
                                    <span class="float-end fs-7 text-danger"><i class="bi bi-star-fill"></i></span>
                                </h3>
                                <p class="fs-7">Ya namanya juga manusia bukan nabi boy...</p>
                                <p class="fs-7 text-secondary"><i class="bi bi-clock-fill me-1"></i> 2 Ages Ago</p>
                            </div>
                        </div>
                        <!-- Message End -->
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item dropdown-footer">See All Messages</a>
                </div>
            </li>

            <!-- Notifications Dropdown Menu -->
            <li class="nav-item dropdown">
                <a class="nav-link" data-bs-toggle="dropdown" href="#">
                    <i class="bi bi-bell-fill"></i>
                    <span class="navbar-badge badge text-bg-warning">15</span>
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                    <span class="dropdown-item dropdown-header">15 Notifications</span>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item">
                        <i class="bi bi-envelope me-2"></i> 4 new messages
                        <span class="float-end text-secondary fs-7">3 mins</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
                </div>
            </li>
        </ul>
        <form action="{{ route('logout') }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-link nav-link">
                <i class="bi bi-box-arrow-right"></i> Logout
            </button>
        </form>

    </div>
</nav>
