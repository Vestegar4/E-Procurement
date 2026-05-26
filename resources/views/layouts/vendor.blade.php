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
                    <a href="{{ route('vendor.dashboard') }}"
                        class="nav-link {{ request()->routeIs('vendor.dashboard') ? 'active-menu' : '' }}">
                        <i class="fa-solid fa-chart-pie w-20px text-center me-2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('vendor.tenders') }}"
                        class="nav-link {{ request()->routeIs('vendor.tenders') ? 'active-menu' : '' }}">
                        <i class="fa-solid fa-cart-shopping w-20px text-center me-2"></i> Pengadaan
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('vendor.bids.index') }}"
                        class="nav-link {{ request()->routeIs('vendor.bids.index') ? 'active-menu' : '' }}">
                        <i class="fa-solid fa-file-invoice-dollar w-20px text-center me-2"></i> Penawaran
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('vendor.documents.index') }}"
                        class="nav-link {{ request()->routeIs('vendor.documents.*') ? 'active-menu' : '' }}">
                        <i class="fa-solid fa-folder-open w-20px text-center me-2"></i> Dokumen
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('vendor.reports') }}"
                        class="nav-link {{ request()->routeIs('vendor.reports') ? 'active-menu' : '' }}">
                        <i class="fa-solid fa-file-lines w-20px text-center me-2"></i> Laporan
                    </a>
                </li>
                <li class="nav-item mt-4">
                    <a href="{{ route('vendor.settings') }}"
                        class="nav-link {{ request()->routeIs('vendor.settings') ? 'active-menu' : '' }}">
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
                    <div class="dropdown">
                        <button class="notification-btn position-relative" type="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <i class="fa-regular fa-bell"></i>
                            <span
                                class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle"></span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm" style="min-width: 280px;">
                            <li>
                                <h6 class="dropdown-header">Notifikasi</h6>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <span class="dropdown-item-text text-muted small text-center d-block py-3">
                                    Tidak ada notifikasi baru
                                </span>
                            </li>
                        </ul>
                    </div>
                    <div class="dropdown">
                        <div class="vendor-profile" data-bs-toggle="dropdown" aria-expanded="false"
                            style="cursor: pointer;">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                            <li>
                                <span class="dropdown-item-text fw-bold">{{ Auth::user()->name }}</span>
                            </li>
                            <li>
                                <span class="dropdown-item-text text-muted small">{{ Auth::user()->email }}</span>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="fa-solid fa-right-from-bracket me-2"></i> Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="content-area">
                @yield('content')
            </div>
        </div>

    </div>
</body>

</html>
