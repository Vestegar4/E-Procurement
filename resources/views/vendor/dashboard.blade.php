<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Procurement Admin - @yield('title', 'Dashboard')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
</head>

<body>

    <div class="wrapper">

        <div class="sidebar">
            <div class="sidebar-header">
                <h3>E-Proc</h3>
            </div>

            <ul class="nav flex-column sidebar-menu">
                <li class="nav-item">
                    <a href="/" class="nav-link {{ request()->is('/') ? 'active-menu' : '' }}">
                        <i class="fa-solid fa-chart-pie w-20px text-center me-2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/users" class="nav-link {{ request()->is('users*') ? 'active-menu' : '' }}">
                        <i class="fa-solid fa-users w-20px text-center me-2"></i> Manajemen User
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/vendors" class="nav-link {{ request()->is('vendors*') ? 'active-menu' : '' }}">
                        <i class="fa-solid fa-building w-20px text-center me-2"></i> Vendor Management
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/procurement" class="nav-link {{ request()->is('procurement*') ? 'active-menu' : '' }}">
                        <i class="fa-solid fa-cart-shopping w-20px text-center me-2"></i> Procurement
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/products" class="nav-link {{ request()->is('products*') ? 'active-menu' : '' }}">
                        <i class="fa-solid fa-box-open w-20px text-center me-2"></i> Produk & Kategori
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/po" class="nav-link {{ request()->is('po*') ? 'active-menu' : '' }}">
                        <i class="fa-solid fa-file-invoice-dollar w-20px text-center me-2"></i> Purchase Order (PO)
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/reports" class="nav-link {{ request()->is('reports*') ? 'active-menu' : '' }}">
                        <i class="fa-solid fa-file-lines w-20px text-center me-2"></i> Laporan
                    </a>
                </li>
                <li class="nav-item mt-4">
                    <a href="/settings" class="nav-link {{ request()->is('settings*') ? 'active-menu' : '' }}">
                        <i class="fa-solid fa-gear w-20px text-center me-2"></i> Pengaturan
                    </a>
                </li>
            </ul>
        </div>

        <div class="main-content">
            <div class="top-navbar">
                <div>
                    <h4 class="page-title">@yield('title', 'Dashboard Overview')</h4>
                </div>
                <div class="top-right">
                    <div class="input-group" style="width: 250px;">
                        <input type="text" class="form-control border-0 bg-light" placeholder="Cari...">
                        <button class="btn btn-light bg-light border-0"><i class="fa-solid fa-search"></i></button>
                    </div>
                    <button class="notification-btn position-relative">
                        <i class="fa-regular fa-bell"></i>
                        <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle"></span>
                    </button>
                    <div class="admin-profile cursor-pointer" data-bs-toggle="dropdown">
                        A
                    </div>
                </div>
            </div>

            <div class="content-area">
                @yield('content')
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>