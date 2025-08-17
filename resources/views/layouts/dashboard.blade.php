<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="E-Tinda Marketplace - Farm to Table" />
    <meta name="author" content="" />

    <link rel="icon" href="{{ asset('assets/images/favicon.ico') }}">
    <title>E-Tinda Marketplace | Dashboard</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Flag Icons for Language Switcher -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/6.6.6/css/flag-icons.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
    @stack('styles')

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <style>
        :root {
            --primary-color: #198754;
            --primary-dark: #146c43;
            --primary-light: #d1e7dd;
            --sidebar-bg: #1a472a;
            --sidebar-hover: #2d5a3f;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }

        .sidebar {
            background: var(--sidebar-bg);
            min-height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            width: 280px;
            transition: all 0.3s ease;
        }

        .sidebar.collapsed {
            width: 70px;
        }

        .sidebar-header {
            padding: 1.5rem;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .sidebar-logo {
            color: white;
            text-decoration: none;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
        }

        .sidebar-logo i {
            font-size: 2rem;
            color: var(--primary-color);
        }

        .sidebar-logo h4 {
            margin: 0;
            font-weight: 700;
            font-size: 1.1rem;
        }

        .sidebar.collapsed .sidebar-logo h4,
        .sidebar.collapsed .sidebar-logo span {
            display: none;
        }

        .sidebar-menu {
            padding: 1rem 0;
        }

        .sidebar-menu ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar-menu li {
            margin: 0.25rem 0;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 0.875rem 1.5rem;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }

        .sidebar-menu a:hover {
            background-color: var(--sidebar-hover);
            color: white;
            border-left-color: var(--primary-color);
        }

        .sidebar-menu a.active {
            background-color: var(--sidebar-hover);
            color: white;
            border-left-color: var(--primary-color);
        }

        .sidebar-menu i {
            width: 20px;
            margin-right: 0.75rem;
            text-align: center;
        }

        .sidebar.collapsed .sidebar-menu i {
            margin-right: 0;
        }

        .sidebar.collapsed .sidebar-menu span {
            display: none;
        }

        .top-navbar {
            background: white;
            border-bottom: 1px solid #dee2e6;
            padding: 1rem 2rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            position: fixed;
            top: 0;
            right: 0;
            left: 280px;
            z-index: 999;
            transition: all 0.3s ease;
            width: calc(100% - 280px);
        }

        .top-navbar.expanded {
            left: 70px;
            width: calc(100% - 70px);
        }

        .main-content {
            margin-left: 280px;
            margin-top: 80px;
            transition: all 0.3s ease;
            min-height: 100vh;
            background-color: #f8f9fa;
            width: calc(100% - 280px);
        }

        .main-content.expanded {
            margin-left: 70px;
            width: calc(100% - 70px);
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .user-welcome {
            font-size: 1.1rem;
            font-weight: 600;
            color: #495057;
        }

        .sidebar-toggle {
            background: none;
            border: none;
            color: #6c757d;
            font-size: 1.25rem;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 0.375rem;
            transition: all 0.3s ease;
        }

        .sidebar-toggle:hover {
            background-color: #e9ecef;
            color: #495057;
        }

        .logout-btn {
            background-color: #dc3545;
            border: none;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            transition: all 0.3s ease;
        }

        .logout-btn:hover {
            background-color: #c82333;
            transform: translateY(-1px);
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                width: 280px;
            }

            .sidebar.mobile-open {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
                margin-top: 80px;
                width: 100%;
            }

            .top-navbar {
                left: 0;
                padding: 1rem;
                width: 100%;
            }

            .user-welcome {
                font-size: 1rem;
            }

            .sidebar-toggle {
                font-size: 1.1rem;
                padding: 0.4rem;
            }

            .user-info {
                gap: 0.5rem;
            }
        }

        @media (max-width: 576px) {
            .top-navbar {
                padding: 0.75rem;
            }

            .user-welcome {
                display: none;
            }

            .user-info .dropdown-toggle {
                padding: 0.375rem 0.75rem;
                font-size: 0.875rem;
            }

            .user-info {
                gap: 0.25rem;
            }

            .dropdown {
                margin-left: 0.5rem;
            }
        }
    </style>
</head>

<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <a href="{{ route('farmer.dashboard') }}" class="sidebar-logo">
                    <i class="fas fa-leaf"></i>
                    <h4>E-Tinda</h4>
                </a>
            </div>

            <nav class="sidebar-menu">
                <ul>
                    @auth
                        @if(auth()->user()->role->value === 'farmer')
                            <!-- Farmer Menu -->
                            <li>
                                <a href="{{ route('farmer.dashboard') }}" class="{{ request()->routeIs('farmer.dashboard') ? 'active' : '' }}">
                                    <i class="fas fa-tachometer-alt"></i>
                                    <span>{{ __('dashboard.dashboard') }}</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('farmer.products.index') }}" class="{{ request()->routeIs('farmer.products.*') ? 'active' : '' }}">
                                    <i class="fas fa-box"></i>
                                    <span>{{ __('dashboard.my_products') }}</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('farmer.products.create') }}" class="{{ request()->routeIs('farmer.products.create') ? 'active' : '' }}">
                                    <i class="fas fa-plus"></i>
                                    <span>{{ __('dashboard.add_product') }}</span>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="fas fa-list"></i>
                                    <span>{{ __('dashboard.inventory') }}</span>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="fas fa-chart-bar"></i>
                                    <span>{{ __('dashboard.analytics') }}</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('farmer.orders.index') }}" class="{{ request()->routeIs('farmer.orders.*') ? 'active' : '' }}">
                                    <i class="fas fa-shopping-cart"></i>
                                    <span>{{ __('dashboard.orders') }}</span>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="fas fa-comments"></i>
                                    <span>{{ __('dashboard.forums') }}</span>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="fas fa-user"></i>
                                    <span>{{ __('dashboard.profile') }}</span>
                                </a>
                            </li>

                        @elseif(auth()->user()->role->value === 'buyer')
                            <!-- Buyer Menu -->
                            <li>
                                <a href="#" class="active">
                                    <i class="fas fa-tachometer-alt"></i>
                                    <span>{{ __('dashboard.dashboard') }}</span>
                                </a>
                            </li>
                            <li>
                                <a href="/shop">
                                    <i class="fas fa-shopping-bag"></i>
                                    <span>{{ __('dashboard.browse_products') }}</span>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="fas fa-shopping-cart"></i>
                                    <span>{{ __('dashboard.shopping_cart') }}</span>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="fas fa-file-alt"></i>
                                    <span>{{ __('dashboard.order_history') }}</span>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="fas fa-heart"></i>
                                    <span>{{ __('dashboard.wishlist') }}</span>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="fas fa-comments"></i>
                                    <span>{{ __('dashboard.forums') }}</span>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="fas fa-user"></i>
                                    <span>{{ __('dashboard.profile') }}</span>
                                </a>
                            </li>

                        @elseif(auth()->user()->role->value === 'admin')
                            <!-- Admin Menu -->
                            <li>
                                <a href="#" class="active">
                                    <i class="fas fa-tachometer-alt"></i>
                                    <span>{{ __('dashboard.dashboard') }}</span>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="fas fa-users"></i>
                                    <span>{{ __('dashboard.users') }}</span>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="fas fa-box"></i>
                                    <span>{{ __('dashboard.products') }}</span>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="fas fa-file-alt"></i>
                                    <span>{{ __('dashboard.orders') }}</span>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="fas fa-comments"></i>
                                    <span>{{ __('dashboard.forums') }}</span>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="fas fa-list"></i>
                                    <span>{{ __('dashboard.system_logs') }}</span>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="fas fa-cog"></i>
                                    <span>{{ __('dashboard.settings') }}</span>
                                </a>
                            </li>
                        @endif
                    @endauth
                </ul>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="main-content" id="mainContent">
            <!-- Top Navigation -->
            <div class="top-navbar">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-3">
                        <button class="sidebar-toggle" id="sidebarToggle">
                            <i class="fas fa-bars"></i>
                        </button>
                        <div class="user-welcome">
                            {{ __('dashboard.welcome') }}, {{ ucwords(Auth::user()->name) }}
                        </div>
                    </div>

                    <div class="user-info">
                        <div class="dropdown me-3">
                            @include('components.language-switcher')
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user me-2"></i>{{ __('dashboard.profile') }}
                            </button>
                            <ul class="dropdown-menu">
                                <li>
                                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Page Content -->
            <div class="p-4">
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Toastr for notifications -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <!-- Custom JavaScript -->
    <script>
        $(document).ready(function() {
            // Sidebar toggle functionality
            $('#sidebarToggle').on('click', function() {
                if (window.innerWidth > 768) {
                    // Desktop behavior
                    $('#sidebar').toggleClass('collapsed');
                    $('#mainContent').toggleClass('expanded');
                    $('.top-navbar').toggleClass('expanded');
                } else {
                    // Mobile behavior
                    $('#sidebar').toggleClass('mobile-open');
                }
            });

            // Mobile sidebar toggle
            if (window.innerWidth <= 768) {
                $('#sidebar').addClass('mobile-open');
            }

            // Handle window resize
            $(window).on('resize', function() {
                if (window.innerWidth <= 768) {
                    $('#sidebar').removeClass('collapsed');
                    $('#mainContent').removeClass('expanded');
                    $('.top-navbar').removeClass('expanded');
                    $('#sidebar').addClass('mobile-open');
                } else {
                    $('#sidebar').removeClass('mobile-open');
                }
            });

            // Close mobile sidebar when clicking outside
            $(document).on('click', function(e) {
                if (window.innerWidth <= 768) {
                    if (!$(e.target).closest('#sidebar, #sidebarToggle').length) {
                        $('#sidebar').removeClass('mobile-open');
                    }
                }
            });

            // Show success messages with toastr
            @if (session('success'))
                toastr.success("{{ session('success') }}");
            @endif

            @if (session('error'))
                toastr.error("{{ session('error') }}");
            @endif

            @if (session('warning'))
                toastr.warning("{{ session('warning') }}");
            @endif

            @if (session('info'))
                toastr.info("{{ session('info') }}");
            @endif
        });
    </script>

    @stack('scripts')
</body>

</html>
