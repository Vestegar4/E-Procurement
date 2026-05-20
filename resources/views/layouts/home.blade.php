<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Procurement Admin</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>

<body>

    <div class="wrapper">

        <!-- SIDEBAR -->
        <div class="sidebar">

            <div class="sidebar-header">
                <h3>E-Proc</h3>
            </div>

            <ul class="nav flex-column sidebar-menu">

                <li class="nav-item">
                    <a href="/"
                        class="nav-link {{ request()->is('/') ? 'active-menu' : '' }}">
                        <i class="fa-solid fa-chart-line"></i>
                        Dashboard
                    </a>
                </li>

                <li class="nav-item">
                    <a href="/procurement"
                        class="nav-link {{ request()->is('procurement') ? 'active-menu' : '' }}">
                        <i class="fa-solid fa-cart-shopping"></i>
                        Procurement
                    </a>
                </li>

                <li class="nav-item">
                    <a href="/contracts"
                        class="nav-link {{ request()->is('contracts') ? 'active-menu' : '' }}">
                        <i class="fa-solid fa-file-contract"></i>
                        Contracts
                    </a>
                </li>

                <li class="nav-item">
                    <a href="/po"
                        class="nav-link {{ request()->is('po') ? 'active-menu' : '' }}">
                        <i class="fa-solid fa-receipt"></i>
                        Purchase Order
                    </a>
                </li>

                <li class="nav-item">
                    <a href="/reports"
                        class="nav-link {{ request()->is('reports') ? 'active-menu' : '' }}">
                        <i class="fa-solid fa-chart-pie"></i>
                        Reports
                    </a>
                </li>

                <li class="nav-item">
                    <a href="/settings"
                        class="nav-link {{ request()->is('settings') ? 'active-menu' : '' }}">
                        <i class="fa-solid fa-gear"></i>
                        Settings
                    </a>
                </li>

            </ul>

        </div>

        <!-- MAIN -->
        <div class="main-content">

            <!-- TOPBAR -->
            <div class="top-navbar">

                <div>
                    <h4 class="page-title">
                        @yield('title', 'Dashboard')
                    </h4>
                </div>

                <div class="top-right">

                    <button class="notification-btn">
                        <i class="fa-regular fa-bell"></i>
                    </button>

                    <div class="admin-profile">
                        A
                    </div>

                </div>

            </div>

            <!-- CONTENT -->
            <div class="content-area">
                @yield('content')
            </div>

        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>