 <!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="E-Tinda Marketplace - Farm to Table" />
    <meta name="author" content="" />

    <title>E-Tinda</title>

    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ asset('template-assets/js/jquery-ui/css/no-theme/jquery-ui-1.10.3.custom.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template-assets/css/font-icons/entypo/css/entypo.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Noto+Sans:400,700,400italic">
    <link rel="stylesheet" href="{{ asset('template-assets/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('template-assets/css/neon-core.css') }}">
    <link rel="stylesheet" href="{{ asset('template-assets/css/neon-theme.css') }}">
    <link rel="stylesheet" href="{{ asset('template-assets/css/neon-forms.css') }}">
    <link rel="stylesheet" href="{{ asset('template-assets/css/skins/green.css') }}">
    <link rel="stylesheet" href="{{ asset('template-assets/css/custom.css') }}">
    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <!-- Custom CSS for Sidebar Collapse -->
    <style>
        /* Ensure sidebar collapse works properly */
        .page-container.sidebar-collapsed .sidebar-menu {
            width: 65px !important;
        }

        .page-container.sidebar-collapsed .sidebar-menu .sidebar-menu-inner {
            width: 65px !important;
        }

        .page-container.sidebar-collapsed .sidebar-menu .logo-env > div.logo {
            overflow: hidden;
            width: 65px; /* keep logo visible when collapsed */
            text-align: center;
        }

        .page-container.sidebar-collapsed .sidebar-menu #main-menu > li > a > span:not(.badge) {
            position: absolute;
            zoom: 1;
            filter: alpha(opacity=0);
            -webkit-opacity: 0;
            -moz-opacity: 0;
            opacity: 0;
        }


        .page-container.sidebar-collapsed .sidebar-menu #main-menu > li > a > i {
            margin-right: 0;
        }

        /* Ensure main content adjusts when sidebar is collapsed */
        .page-container.sidebar-collapsed .main-content {
            margin-left: 65px;
        }

        /* Smooth transition for sidebar collapse */
        .sidebar-menu, .sidebar-menu-inner, .main-content {
            transition: all 0.3s ease;
        }

        /* Additional styles to ensure proper collapse behavior */
        .page-container.sidebar-collapsed .sidebar-menu .logo-env {
            padding: 20px 10px;
        }

        .page-container.sidebar-collapsed .sidebar-menu .logo-env > div.sidebar-collapse {
            display: block;
            padding: 0;
            left: 3px;
        }

        .page-container.sidebar-collapsed .sidebar-menu #main-menu > li > a {
            padding: 15px 20px;
            text-align: center;
        }

        .page-container.sidebar-collapsed .sidebar-menu #main-menu > li > a > i {
            margin-right: 0;
            font-size: 18px;
        }

        /* Menu expansion in collapsed mode */
        .page-container.sidebar-collapsed .sidebar-menu #main-menu > li:hover > a > span:not(.badge) {
            position: absolute;
            left: 65px;
            top: 0;
            width: 200px;
            background: #303641;
            color: #fff;
            padding: 15px 20px;
            border-radius: 0 3px 3px 0;
            opacity: 1;
            z-index: 1000;
            white-space: nowrap;
            box-shadow: 2px 2px 5px rgba(0,0,0,0.3);
        }


        /* Ensure proper positioning in collapsed mode */
        .page-container.sidebar-collapsed .sidebar-menu #main-menu > li {
            position: relative;
        }

        .page-container.sidebar-collapsed .sidebar-menu #main-menu > li > a {
            position: relative;
        }

        /* Smooth transitions for hover effects */
        .page-container.sidebar-collapsed .sidebar-menu #main-menu > li > a > span {
            transition: all 0.2s ease;
        }

        /* Ensure the collapse icon is visible and clickable */
        .sidebar-collapse-icon {
            cursor: pointer;
            display: block;
            padding: 10px;
            color: #fff;
            text-decoration: none;
        }

        .sidebar-collapse-icon:hover {
            background-color: rgba(255,255,255,0.1);
            border-radius: 3px;
        }

        /* Dark Green Sidebar Theme Overrides */
        .sidebar-menu,
        .sidebar-menu .sidebar-menu-inner,
        .sidebar-menu .logo-env,
        .sidebar-menu #main-menu {
            background-color: #0b5e20 !important; /* dark green */
        }
        .sidebar-mobile-menu a {
            background-color: #0b5e20;
            color: #e8f5e9;
        }
        .sidebar-menu #main-menu > li {
            background-color: #0b5e20; /* unify li background */
            border-bottom: 1px solid #0a4d1c; /* subtle divider */
        }
        .sidebar-menu #main-menu > li > a {
            color: #e8f5e9;
        }
        .sidebar-menu #main-menu > li > a:hover,
        .sidebar-menu #main-menu > li.active > a,
        .sidebar-menu #main-menu > li.opened > a {
            background-color: #094a19 !important;
            color: #ffffff;
        }
        /* Collapsed hover popout colors override to dark green */
        .page-container.sidebar-collapsed .sidebar-menu #main-menu > li:hover > a > span:not(.badge) {
            background: #0b5e20 !important;
            color: #ffffff;
        }

        /* Logo sizing and icon color overrides */
        .logo a {
            font-size: 24px;
            font-weight: 700;
            color: #e8f5e9;
            text-decoration: none;
        }
        .logo a i {
            color: #ffffff !important; /* ensure icon is not green */
            font-size: 28px;
            margin-right: 8px;
        }

        .fa-leaf {
            color: #ffffff !important;
        }

        /* Sticky Sidebar Styles */
        .page-container {
            display: flex;
            min-height: 100vh;
        }

        .sidebar-menu {
            position: fixed !important;
            top: 0;
            left: 0;
            height: 100vh !important;
            overflow-y: auto;
            overflow-x: hidden;
            z-index: 1000;
            width: 280px;
            transition: width 0.3s ease;
        }

        .sidebar-menu-inner {
            height: 100% !important;
            overflow-y: auto;
            overflow-x: hidden;
        }

        .main-content {
            margin-left: 280px !important;
            flex: 1;
            min-height: 100vh;
            overflow-y: auto;
            transition: margin-left 0.3s ease;
        }

        /* Collapsed sidebar styles */
        .page-container.sidebar-collapsed .sidebar-menu {
            width: 65px !important;
        }

        .page-container.sidebar-collapsed .main-content {
            margin-left: 65px !important;
        }

        /* Header styling - no longer sticky */
        .main-content > .row:first-child {
            background: #fff;
            margin: 0;
            padding: 15px 20px;
            border-bottom: 1px solid #e5e5e5;
        }

        /* Content area styling */
        .main-content > hr {
            margin: 0;
            border: none;
            height: 1px;
            background: #e5e5e5;
        }

        /* Footer positioning */
        .main-content > footer.main {
            margin-top: auto;
            padding: 20px;
            background: #f8f9fa;
            border-top: 1px solid #e5e5e5;
        }

        /* Mobile Responsive Styles */
        @media (max-width: 768px) {
            .sidebar-menu {
                width: 280px !important;
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }

            .sidebar-menu.mobile-open {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0 !important;
                width: 100%;
                padding: 10px !important;
            }

            .page-container.sidebar-collapsed .sidebar-menu {
                width: 280px !important;
                transform: translateX(-100%);
            }

            .page-container.sidebar-collapsed .main-content {
                margin-left: 0 !important;
            }

            /* Mobile overlay */
            .mobile-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.5);
                z-index: 999;
                display: none;
                pointer-events: auto;
            }

            .mobile-overlay.active {
                display: block;
            }

            /* Ensure sidebar is above overlay on mobile */
            .sidebar-menu.mobile-open {
                z-index: 1001 !important;
            }

            /* Ensure menu links are clickable on mobile */
            .sidebar-menu.mobile-open #main-menu > li > a {
                pointer-events: auto !important;
                z-index: 1002 !important;
                position: relative;
            }

            /* Mobile menu button */
            .mobile-menu-toggle {
                display: block;
                position: fixed;
                top: 15px;
                left: 15px;
                z-index: 1001;
                background: #0b5e20;
                color: white;
                border: none;
                padding: 10px 15px;
                border-radius: 5px;
                cursor: pointer;
                font-size: 18px;
            }

            .mobile-menu-toggle:hover {
                background: #094a19;
            }

            /* Adjust header for mobile */
            .main-content > .row:first-child {
                padding: 10px 15px 10px 60px !important;
                flex-wrap: wrap;
                margin-bottom: 20px !important;
            }

            .main-content > .row:first-child .col-md-6 {
                width: 100%;
                margin-bottom: 10px;
            }

            /* Ensure sidebar is above everything on mobile */
            .sidebar-menu {
                z-index: 1000;
            }

            /* Make menu items touch-friendly on mobile */
            .sidebar-menu #main-menu > li > a {
                padding: 18px 20px !important;
                min-height: 50px;
            }

            /* Responsive tables */
            .table-responsive {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }

            /* Responsive cards */
            .card {
                margin-bottom: 15px;
            }

            /* Responsive buttons */
            .btn {
                width: 100%;
                margin-bottom: 10px;
            }

            .btn-group .btn {
                width: auto;
            }

            /* Responsive forms */
            .form-group {
                margin-bottom: 15px;
            }

            /* Footer adjustments */
            footer.main {
                padding: 15px !important;
                font-size: 14px;
            }
        }

        @media (min-width: 769px) {
            .mobile-menu-toggle {
                display: none;
            }

            .mobile-overlay {
                display: none !important;
            }
        }

        /* Prevent body scroll when mobile menu is open */
        body.mobile-menu-open {
            overflow: hidden;
        }

        /* Ensure menu items are visible */
        .sidebar-menu #main-menu {
            display: block !important;
            visibility: visible !important;
        }

        .sidebar-menu #main-menu > li {
            display: block !important;
            visibility: visible !important;
        }

        .sidebar-menu #main-menu > li > a {
            display: block !important;
            visibility: visible !important;
            pointer-events: auto !important;
            cursor: pointer !important;
            position: relative !important;
            z-index: 1 !important;
            -webkit-tap-highlight-color: rgba(0, 0, 0, 0.1);
            touch-action: manipulation;
        }

        /* On mobile, ensure links are always clickable */
        @media (max-width: 768px) {
            .sidebar-menu #main-menu > li > a {
                z-index: 1002 !important;
                pointer-events: auto !important;
            }
        }

        .sidebar-menu #main-menu > li > a > span.title {
            display: inline-block !important;
            visibility: visible !important;
            pointer-events: none !important;
        }

        /* Ensure proper menu styling */
        .sidebar-menu #main-menu > li > a {
            padding: 15px 20px !important;
            line-height: 1.5 !important;
        }

        .sidebar-menu #main-menu > li > a > i {
            margin-right: 10px !important;
            width: 20px !important;
            text-align: center !important;
            pointer-events: none !important;
        }

        /* Ensure links are clickable */
        .sidebar-menu #main-menu > li > a:hover {
            text-decoration: none !important;
        }

        /* Fix hover popout to not block clicks */
        .page-container.sidebar-collapsed .sidebar-menu #main-menu > li:hover > a > span:not(.badge) {
            pointer-events: none !important;
        }
    </style>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @stack('styles')
</head>

<body class="page-body page-fade" data-url="http://neon.dev">

<div class="page-container">
    <!-- Mobile Menu Toggle Button -->
    <button class="mobile-menu-toggle" id="mobileMenuToggle">
        <i class="entypo-menu"></i>
    </button>

    <!-- Mobile Overlay -->
    <div class="mobile-overlay" id="mobileOverlay"></div>

    <div class="sidebar-menu" id="sidebarMenu">
        <div class="sidebar-menu-inner">

            <header class="logo-env">
                <!-- logo -->
                <div class="logo">
                    <a href="{{ route('farmer.dashboard') }}">
                    <i class="fas fa-leaf me-2"></i>E-Tinda
                    </a>
                </div>

                <!-- logo collapse icon -->
                <div class="sidebar-collapse">
                    <a href="#" class="sidebar-collapse-icon with-animation">
                        <i class="entypo-menu"></i>
                    </a>
                </div>

                <!-- open/close menu icon (do not remove if you want to enable menu on mobile devices) -->
                <div class="sidebar-mobile-menu visible-xs">
                    <a href="#" class="with-animation">
                        <i class="entypo-menu"></i>
                    </a>
                </div>
            </header>

            <ul id="main-menu" class="main-menu">
                <!-- Dashboard -->
                <li class="{{ request()->routeIs('farmer.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('farmer.dashboard') }}">
                        <i class="entypo-gauge"></i>
                        <span class="title">Dashboard</span>
                    </a>
                </li>

                <!-- Product Management -->
                <li class="{{ request()->routeIs('farmer.products.*') ? 'active' : '' }}">
                    <a href="{{ route('farmer.products.index') }}">
                        <i class="entypo-newspaper"></i>
                        <span class="title">Manage Products</span>
                    </a>
                </li>

                <!-- Order Management -->
                <li class="{{ request()->routeIs('farmer.orders') ? 'active' : '' }}">
                    <a href="{{ route('farmer.orders') }}">
                        <i class="entypo-mail"></i>
                        <span class="title">Order Management</span>
                        @if(isset($stats) && isset($stats['pending_orders']) && $stats['pending_orders'] > 0)
                            <span class="badge badge-secondary badge-roundless">{{ $stats['pending_orders'] }}</span>
                        @endif
                    </a>
                </li>

                <!-- Inventory Management -->
                <li class="{{ request()->routeIs('farmer.products.*') ? 'active' : '' }}">
                    <a href="{{ route('farmer.products.index') }}">
                        <i class="entypo-box"></i>
                        <span class="title">View Inventory</span>
                    </a>
                </li>


                <!-- Analytics Dashboard -->
                <li class="{{ request()->routeIs('farmer.analytics.*') ? 'active' : '' }}">
                    <a href="{{ route('farmer.analytics') }}">
                        <i class="entypo-chart-bar"></i>
                        <span class="title">Analytics Dashboard</span>
                    </a>
                </li>

                <!-- Reports -->
                <li class="{{ request()->routeIs('farmer.reports.*') ? 'active' : '' }}">
                    <a href="{{ route('farmer.reports.index') }}">
                        <i class="entypo-doc-text"></i>
                        <span class="title">Reports</span>
                    </a>
                </li>

                <!-- Settings -->
                <li class="{{ request()->routeIs('farmer.account.*') ? 'active' : '' }}">
                    <a href="{{ route('farmer.account') }}">
                        <i class="entypo-cog"></i>
                        <span class="title">Profile Settings</span>
                    </a>
                </li>

                <li class="{{ request()->routeIs('farmer.account.*') ? 'active' : '' }}">
                    <a href="{{ route('farmer.account') }}">
                        <i class="entypo-cog"></i>
                        <span class="title">Account Settings</span>
                    </a>
                </li>

            </ul>

        </div>
    </div>

    <div class="main-content">
        <div class="row">
            <!-- Profile Info and Notifications -->
            <div class="col-md-6 col-sm-8 clearfix">
                <ul class="user-info pull-left pull-none-xsm">
                    <!-- Profile Info -->
                    <li class="profile-info dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            {{ ucwords(Auth::user()->name) }}
                        </a>
                        <ul class="dropdown-menu">
                            <!-- Reverse Caret -->
                            <li class="caret"></li>
                            <!-- Profile sub-links -->
                            <li>
                                <a href="{{ route('profile.edit') }}">
                                    <i class="entypo-user"></i>
                                    Edit Profile
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('farmer.dashboard') }}">
                                    <i class="entypo-gauge"></i>
                                    Dashboard
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="entypo-cog"></i>
                                    Settings
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>

                <ul class="user-info pull-left pull-right-xs pull-none-xsm">
                    <!-- Notifications removed - keeping only logout functionality -->
                </ul>
            </div>

            <!-- Raw Links -->
            <div class="col-md-6 col-sm-4 clearfix hidden-xs">
                <ul class="list-inline links-list pull-right">
                    <li>
                        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            Log Out <i class="entypo-logout right"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <hr />

        <!-- Page Content -->
        @yield('content')

        <!-- Footer -->
        <footer class="main">
            &copy; {{ date('Y') }} <strong>E-Tinda</strong> Marketplace
        </footer>
    </div>
</div>

<!-- Chat -->
<div id="chat" class="fixed" data-current-user="{{ ucwords(Auth::user()->name) }}" data-order-by-status="1" data-max-chat-history="25">
    <div class="chat-inner">
        <h2 class="chat-header">
            <a href="#" class="chat-close"><i class="entypo-cancel"></i></a>
            <i class="entypo-users"></i>
            Chat
            <span class="badge badge-success is-hidden">0</span>
        </h2>
        <div class="chat-group" id="group-1">
            <strong>Favorites</strong>
            <a href="#"><span class="user-status is-online"></span> <em>Support Team</em></a>
        </div>
    </div>
</div>

<!-- Logout Form -->
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>

<!-- Template Scripts -->
<script src="{{ asset('template-assets/js/jquery-1.11.3.min.js') }}"></script>
<script src="{{ asset('template-assets/js/gsap/TweenMax.min.js') }}"></script>
<script src="{{ asset('template-assets/js/jquery-ui/js/jquery-ui-1.10.3.minimal.min.js') }}"></script>
<script src="{{ asset('template-assets/js/bootstrap.js') }}"></script>
<script src="{{ asset('template-assets/js/joinable.js') }}"></script>
<script src="{{ asset('template-assets/js/resizeable.js') }}"></script>
<script src="{{ asset('template-assets/js/neon-api.js') }}"></script>
<script src="{{ asset('template-assets/js/neon-chat.js') }}"></script>
<script src="{{ asset('template-assets/js/neon-custom.js') }}"></script>
<script src="{{ asset('template-assets/js/neon-skins.js') }}"></script>
<!-- Toastr JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<!-- Custom JavaScript -->
<script>
$(document).ready(function() {
    // Wait for Neon theme to be fully loaded
    setTimeout(function() {
        // Initialize public_vars for Neon theme
        if (typeof public_vars === 'undefined') {
            public_vars = {};
        }

        // Set up the required variables for sidebar functionality
        public_vars.$body = $("body");
        public_vars.$pageContainer = public_vars.$body.find(".page-container");
        public_vars.$sidebarMenu = public_vars.$pageContainer.find('.sidebar-menu');
        public_vars.$mainMenu = public_vars.$sidebarMenu.find('#main-menu');
        public_vars.$mainContent = public_vars.$pageContainer.find('.main-content');

        // Verify all elements exist
        if (public_vars.$pageContainer.length === 0) {
            console.error('Page container not found');
            return;
        }

        if (public_vars.$sidebarMenu.length === 0) {
            console.error('Sidebar menu not found');
            return;
        }

        console.log('Elements found:', {
            'pageContainer': public_vars.$pageContainer.length,
            'sidebarMenu': public_vars.$sidebarMenu.length,
            'mainMenu': public_vars.$mainMenu.length,
            'mainContent': public_vars.$mainContent.length
        });

        // Call the setup function to initialize sidebar functionality
        if (typeof setup_sidebar_menu === 'function') {
            setup_sidebar_menu();
        }

        // Sidebar toggle functionality using Neon theme functions
        $('.sidebar-collapse-icon').on('click', function(e) {
            e.preventDefault();
            console.log('Sidebar collapse icon clicked');

            var with_animation = $(this).hasClass('with-animation');
            console.log('With animation:', with_animation);

            // Check if toggle function exists, otherwise use manual toggle
            if (typeof toggle_sidebar_menu === 'function') {
                console.log('Using Neon theme toggle function');
                toggle_sidebar_menu(with_animation);
            } else {
                console.log('Using manual toggle fallback');
                // Manual toggle as fallback
                var $pageContainer = $('.page-container');
                var $sidebarMenu = $('.sidebar-menu');
                var $mainContent = $('.main-content');

                if ($pageContainer.hasClass('sidebar-collapsed')) {
                    // Expand sidebar
                    console.log('Expanding sidebar');
                    $pageContainer.removeClass('sidebar-collapsed');
                    $sidebarMenu.css('width', '280px');
                    $mainContent.css('margin-left', '280px');

                    // Show menu text but only open submenus that are marked opened/active
                    $('.sidebar-menu #main-menu > li > a > span:not(.badge)').show();
                    $('.sidebar-menu #main-menu > li').each(function() {
                        var $li = $(this);
                        var $submenu = $li.children('ul');
                        if ($li.hasClass('opened') || $li.find('ul li.active').length) {
                            $submenu.show();
                        } else {
                            $submenu.hide();
                        }
                    });
                } else {
                    // Collapse sidebar
                    console.log('Collapsing sidebar');
                    $pageContainer.addClass('sidebar-collapsed');
                    $sidebarMenu.css('width', '65px');
                    $mainContent.css('margin-left', '65px');

                    // Hide all menu text and submenus
                    $('.sidebar-menu #main-menu > li > a > span:not(.badge)').hide();
                    $('.sidebar-menu #main-menu > li > ul').hide();
                }
            }
        });

        // Simple navigation for flattened menu (no dropdowns)
        public_vars.$mainMenu.on('click', '> li > a', function(e) {
            // Allow default navigation for all menu items
            // No need to prevent default since we removed dropdowns
        });

        // Handle menu hover effects in collapsed mode (simplified for flat menu)
        $(document).on('mouseenter', '.page-container.sidebar-collapsed .sidebar-menu #main-menu > li', function() {
            var $this = $(this);
            var $linkText = $this.find('> a > span:not(.badge)');

            // Show link text on hover in collapsed mode
            if ($linkText.length > 0) {
                $linkText.show().css({
                    'position': 'absolute',
                    'left': '65px',
                    'top': '0',
                    'width': '200px',
                    'background': '#0b5e20',
                    'color': '#fff',
                    'padding': '15px 20px',
                    'border-radius': '0 3px 3px 0',
                    'opacity': '1',
                    'z-index': '1000',
                    'white-space': 'nowrap',
                    'box-shadow': '2px 2px 5px rgba(0,0,0,0.3)',
                    'pointer-events': 'none'
                });
            }
        });

        $(document).on('mouseleave', '.page-container.sidebar-collapsed .sidebar-menu #main-menu > li', function() {
            var $this = $(this);
            var $linkText = $this.find('> a > span:not(.badge)');

            // Hide link text when not hovering
            $linkText.hide();
        });

        // Ensure all menu links are clickable - optimized for mobile
        $(document).on('click', '#main-menu > li > a', function(e) {
            var $link = $(this);
            var href = $link.attr('href');

            // Only handle if it's a valid link
            if (href && href !== '#' && href !== 'javascript:void(0)') {
                // On mobile, close the menu after clicking (with small delay to allow navigation)
                if ($(window).width() <= 768) {
                    var currentHref = href;
                    setTimeout(function() {
                        closeMobileMenu();
                        // Ensure navigation happens
                        if (window.location.href !== currentHref) {
                            window.location.href = currentHref;
                        }
                    }, 50);
                }
                // Allow default navigation
                return true;
            }
        });

        // Ensure proper cleanup when sidebar is expanded
        $(document).on('click', '.sidebar-collapse-icon', function() {
            // Show all menu text when expanding sidebar
            setTimeout(function() {
                if (!$('.page-container').hasClass('sidebar-collapsed')) {
                    $('.sidebar-menu #main-menu > li > a > span').show();
                }
            }, 300);
        });

        // Mobile menu toggle functionality - handle both click and touch
        $('#mobileMenuToggle').on('click touchstart', function(e) {
            e.preventDefault();
            e.stopPropagation();
            toggleMobileMenu();
        });

        // Close mobile menu when clicking overlay
        $('#mobileOverlay').on('click touchstart', function(e) {
            e.preventDefault();
            e.stopPropagation();
            closeMobileMenu();
        });

        // Close mobile menu when clicking outside on mobile
        $(document).on('click touchstart', function(e) {
            if ($(window).width() <= 768) {
                var $target = $(e.target);
                // Don't close if clicking inside sidebar or toggle button
                if (!$target.closest('.sidebar-menu, #mobileMenuToggle').length) {
                    // Don't close if clicking on a menu link (let it navigate)
                    if (!$target.closest('#main-menu > li > a').length) {
                        closeMobileMenu();
                    }
                }
            }
        });

        // Handle window resize
        $(window).on('resize', function() {
            if ($(window).width() > 768) {
                closeMobileMenu();
            }
        });

        function toggleMobileMenu() {
            if ($(window).width() <= 768) {
                var $sidebar = $('#sidebarMenu');
                var $overlay = $('#mobileOverlay');

                if ($sidebar.hasClass('mobile-open')) {
                    closeMobileMenu();
                } else {
                    openMobileMenu();
                }
            }
        }

        function openMobileMenu() {
            $('#sidebarMenu').addClass('mobile-open');
            $('#mobileOverlay').addClass('active');
            $('body').addClass('mobile-menu-open');
        }

        function closeMobileMenu() {
            $('#sidebarMenu').removeClass('mobile-open');
            $('#mobileOverlay').removeClass('active');
            $('body').removeClass('mobile-menu-open');
        }

        // Legacy mobile menu toggle (for existing functionality)
        $('.sidebar-mobile-menu a').on('click', function(e) {
            e.preventDefault();
            var with_animation = $(this).hasClass('with-animation');

            if(with_animation) {
                public_vars.$mainMenu.stop().slideToggle('normal', function() {
                    public_vars.$mainMenu.css('height', 'auto');
                });
            } else {
                public_vars.$mainMenu.toggle();
            }
        });

        // Debug: Check if functions are available
        console.log('Neon functions available:', {
            'setup_sidebar_menu': typeof setup_sidebar_menu,
            'toggle_sidebar_menu': typeof toggle_sidebar_menu,
            'public_vars': typeof public_vars !== 'undefined'
        });

        console.log('Sidebar collapse functionality initialized successfully');

        // Force menu visibility
        setTimeout(function() {
            $('#main-menu').show();
            $('#main-menu > li').show();
            $('#main-menu > li > a').show();
            $('#main-menu > li > a > span.title').show();
        }, 100);

    }, 200); // Increased delay to ensure Neon theme is fully loaded

    // Toast notifications
    @if(session('success'))
        toastr.success("{{ session('success') }}");
    @endif
    @if(session('error'))
        toastr.error("{{ session('error') }}");
    @endif
    @if(session('warning'))
        toastr.warning("{{ session('warning') }}");
    @endif
    @if(session('info'))
        toastr.info("{{ session('info') }}");
    @endif
});
</script>

@stack('scripts')
</body>
</html>