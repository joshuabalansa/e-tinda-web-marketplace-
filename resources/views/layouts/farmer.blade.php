 <!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="E-Tinda Marketplace - Farm to Table" />
    <meta name="author" content="" />

    <link rel="icon" href="{{ asset('template-assets/images/favicon.ico') }}">
    <title>E-Tinda | Farmer Dashboard</title>

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

        .page-container.sidebar-collapsed .sidebar-menu #main-menu > li > ul {
            display: none !important;
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

        .page-container.sidebar-collapsed .sidebar-menu #main-menu > li:hover > ul {
            position: absolute;
            left: 65px;
            top: 100%;
            width: 200px;
            background: #303641;
            border-radius: 0 3px 3px 0;
            box-shadow: 2px 2px 5px rgba(0,0,0,0.3);
            display: block !important;
            z-index: 1000;
        }

        .page-container.sidebar-collapsed .sidebar-menu #main-menu > li:hover > ul > li > a {
            padding: 10px 20px;
            color: #fff;
            border-bottom: 1px solid #454a54;
        }

        .page-container.sidebar-collapsed .sidebar-menu #main-menu > li:hover > ul > li > a:hover {
            background: #454a54;
        }

        /* Ensure proper positioning in collapsed mode */
        .page-container.sidebar-collapsed .sidebar-menu #main-menu > li {
            position: relative;
        }

        .page-container.sidebar-collapsed .sidebar-menu #main-menu > li > a {
            position: relative;
        }

        /* Smooth transitions for hover effects */
        .page-container.sidebar-collapsed .sidebar-menu #main-menu > li > a > span,
        .page-container.sidebar-collapsed .sidebar-menu #main-menu > li > ul {
            transition: all 0.2s ease;
        }

        /* Ensure submenu items are properly styled */
        .page-container.sidebar-collapsed .sidebar-menu #main-menu > li > ul > li > a {
            display: block;
            padding: 10px 20px;
            color: #fff;
            text-decoration: none;
            border-bottom: 1px solid #454a54;
            transition: background-color 0.2s ease;
        }

        .page-container.sidebar-collapsed .sidebar-menu #main-menu > li > ul > li > a:hover {
            background-color: #454a54;
        }

        .page-container.sidebar-collapsed .sidebar-menu #main-menu > li > ul > li:last-child > a {
            border-bottom: none;
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
        .sidebar-menu #main-menu > li > ul {
            background: #0a5221 !important;
        }
        .sidebar-menu #main-menu > li > ul > li > a {
            color: #e8f5e9;
        }
        .sidebar-menu #main-menu > li > ul > li > a:hover {
            background-color: #094a19;
            color: #ffffff;
        }
        /* Collapsed hover popout colors override to dark green */
        .page-container.sidebar-collapsed .sidebar-menu #main-menu > li:hover > a > span:not(.badge) {
            background: #0b5e20 !important;
            color: #ffffff;
        }
        .page-container.sidebar-collapsed .sidebar-menu #main-menu > li:hover > ul {
            background: #0b5e20 !important;
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
    </style>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @stack('styles')
</head>

<body class="page-body page-fade" data-url="http://neon.dev">

<div class="page-container">
    <div class="sidebar-menu">
        <div class="sidebar-menu-inner">

            <header class="logo-env">
                <!-- logo -->
                <div class="logo">
                    <a href="{{ route('farmer.dashboard') }}">
                    <i class="fas fa-leaf me-2"></i>Etinda
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
                <!-- add class "multiple-expanded" to allow multiple submenus to open -->
                <!-- class "auto-inherit-active-class" will automatically add "active" class for parent elements who are marked already with class "active" -->
                <li class="{{ request()->routeIs('farmer.dashboard') ? 'active opened active' : '' }} has-sub">
                    <a href="{{ route('farmer.dashboard') }}">
                        <i class="entypo-gauge"></i>
                        <span class="title">Dashboard</span>
                    </a>
                    <ul class="{{ request()->routeIs('farmer.dashboard') ? 'visible' : '' }}">
                        <li class="{{ request()->routeIs('farmer.dashboard') ? 'active' : '' }}">
                            <a href="{{ route('farmer.dashboard') }}">
                                <span class="title">Main Dashboard</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="has-sub {{ request()->routeIs('farmer.products.*') ? 'active opened' : '' }}">
                    <a href="{{ route('farmer.products.index') }}">
                        <i class="entypo-newspaper"></i>
                        <span class="title">Product Management</span>
                    </a>
                    <ul>
                        <li class="{{ request()->routeIs('farmer.products.create') ? 'active' : '' }}">
                            <a href="{{ route('farmer.products.create') }}">
                                <span class="title">Add New Product</span>
                            </a>
                        </li>
                        <li class="{{ request()->routeIs('farmer.products.index') ? 'active' : '' }}">
                            <a href="{{ route('farmer.products.index') }}">
                                <span class="title">Manage Products</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="has-sub {{ request()->routeIs('farmer.orders.*') ? 'active opened' : '' }}">
                    <a href="{{ route('farmer.orders.index') }}">
                        <i class="entypo-mail"></i>
                        <span class="title">Order Management</span>
                        @if(isset($stats) && $stats['pending_orders'] > 0)
                            <span class="badge badge-secondary badge-roundless">{{ $stats['pending_orders'] }}</span>
                        @endif
                    </a>
                    <ul>
                        <li class="{{ request()->routeIs('farmer.orders.index') ? 'active' : '' }}">
                            <a href="{{ route('farmer.orders.index') }}">
                                <i class="entypo-inbox"></i>
                                <span class="title">View Orders</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('farmer.orders.index') }}">
                                <i class="entypo-attach"></i>
                                <span class="title">Order Tracking</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="has-sub">
                    <a href="#">
                        <i class="entypo-chart-bar"></i>
                        <span class="title">Analytics</span>
                    </a>
                    <ul>
                        <li>
                            <a href="#">
                                <span class="title">Sales Reports</span>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <span class="title">Inventory Reports</span>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <span class="title">Performance Metrics</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="has-sub">
                    <a href="#">
                        <i class="entypo-doc-text"></i>
                        <span class="title">Reports</span>
                    </a>
                    <ul>
                        <li>
                            <a href="#">
                                <span class="title">Monthly Reports</span>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <span class="title">Annual Reports</span>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <span class="title">Custom Reports</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="has-sub">
                    <a href="#">
                        <i class="entypo-cog"></i>
                        <span class="title">Settings</span>
                    </a>
                    <ul>
                        <li>
                            <a href="{{ route('profile.edit') }}">
                                <span class="title">Profile Settings</span>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <span class="title">Account Settings</span>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <span class="title">Preferences</span>
                            </a>
                        </li>
                    </ul>
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
                    <!-- Raw Notifications -->
                    <li class="notifications dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                            <i class="entypo-attention"></i>
                            @if(isset($stats) && $stats['pending_orders'] > 0)
                                <span class="badge badge-info">{{ $stats['pending_orders'] }}</span>
                            @endif
                        </a>
                        <ul class="dropdown-menu">
                            <li class="top">
                                <p class="small">
                                    <a href="{{ route('farmer.orders.index') }}" class="pull-right">View All</a>
                                    You have <strong>{{ $stats['pending_orders'] ?? 0 }}</strong> new notifications.
                                </p>
                            </li>

                            <li>
                                <ul class="dropdown-menu-list scroller">
                                    <li class="unread notification-success">
                                        <a href="{{ route('farmer.orders.index') }}">
                                            <i class="entypo-user-add pull-right"></i>
                                            <span class="line">
                                                <strong>New orders received</strong>
                                            </span>
                                            <span class="line small">
                                                Check your order management
                                            </span>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <li class="external">
                                <a href="{{ route('farmer.orders.index') }}">View all notifications</a>
                            </li>
                        </ul>
                    </li>

                    <!-- Message Notifications -->
                    <li class="notifications dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                            <i class="entypo-mail"></i>
                            <span class="badge badge-secondary">0</span>
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <form class="top-dropdown-search">
                                    <div class="form-group">
                                        <input type="text" class="form-control" placeholder="Search anything..." name="s" />
                                    </div>
                                </form>
                            </li>
                            <li class="external">
                                <a href="#">All Messages</a>
                            </li>
                        </ul>
                    </li>

                    <!-- Task Notifications -->
                    <li class="notifications dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                            <i class="entypo-list"></i>
                            <span class="badge badge-warning">0</span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="top">
                                <p>You have 0 pending tasks</p>
                            </li>
                            <li class="external">
                                <a href="#">See all tasks</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>

            <!-- Raw Links -->
            <div class="col-md-6 col-sm-4 clearfix hidden-xs">
                <ul class="list-inline links-list pull-right">
                    <li>
                        <a href="{{ route('farmer.dashboard') }}">
                            <i class="entypo-gauge"></i>
                            Dashboard
                        </a>
                    </li>
                    <li class="sep"></li>
                    <li>
                        <a href="#" data-toggle="chat" data-collapse-sidebar="1">
                            <i class="entypo-chat"></i>
                            Chat
                        </a>
                    </li>
                    <li class="sep"></li>
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

        // Click-to-expand for submenus (single click, even if sidebar is collapsed)
        public_vars.$mainMenu.on('click', '> li.has-sub > a', function(e) {
            var $parentLi = $(this).parent('li');
            var $submenu = $parentLi.children('ul');

            // If there is no submenu, allow default navigation
            if ($submenu.length === 0) {
                return;
            }

            // Prevent navigation and stop duplicate handlers
            e.preventDefault();
            e.stopPropagation();
            e.stopImmediatePropagation();

            var $pageContainer = public_vars.$pageContainer || $('.page-container');
            var $sidebarMenu = public_vars.$sidebarMenu || $('.sidebar-menu');
            var $mainContent = public_vars.$mainContent || $('.main-content');

            // If collapsed, expand first so submenu can be shown
            if ($pageContainer.hasClass('sidebar-collapsed')) {
                if (typeof toggle_sidebar_menu === 'function') {
                    toggle_sidebar_menu(false); // expand without animation flags
                } else {
                    $pageContainer.removeClass('sidebar-collapsed');
                    $sidebarMenu.css('width', '280px');
                    $mainContent.css('margin-left', '280px');
                    $('.sidebar-menu #main-menu > li > a > span:not(.badge)').show();
                    $('.sidebar-menu #main-menu > li > ul').hide();
                }
            }

            var animate = true;

            if ($parentLi.hasClass('opened')) {
                $parentLi.removeClass('opened active');
                animate ? $submenu.stop(true, true).slideUp(200) : $submenu.hide();
            } else {
                if (!public_vars.$mainMenu.hasClass('multiple-expanded')) {
                    $parentLi.siblings('.opened').removeClass('opened active')
                        .children('ul').stop(true, true).slideUp(200);
                }
                $parentLi.addClass('opened active');
                animate ? $submenu.stop(true, true).slideDown(200) : $submenu.show();
            }
        });

        // Ensure active menu's parent is opened on load (expanded sidebar)
        if (!public_vars.$pageContainer.hasClass('sidebar-collapsed')) {
            public_vars.$mainMenu.find('> li.has-sub').each(function() {
                var $li = $(this);
                var $submenu = $li.children('ul');
                if ($li.find('ul li.active').length) {
                    $li.addClass('opened active');
                    $submenu.show();
                }
            });
        }

        // Handle menu hover effects in collapsed mode
        $(document).on('mouseenter', '.page-container.sidebar-collapsed .sidebar-menu #main-menu > li', function() {
            var $this = $(this);
            var $submenu = $this.find('> ul');
            var $linkText = $this.find('> a > span:not(.badge)');

            // Show submenu and link text on hover
            if ($submenu.length > 0) {
                $submenu.show().css({
                    'position': 'absolute',
                    'left': '65px',
                    'top': '100%',
                    'width': '200px',
                    'background': '#303641',
                    'z-index': '1000',
                    'border-radius': '0 3px 3px 0',
                    'box-shadow': '2px 2px 5px rgba(0,0,0,0.3)'
                });
            }

            if ($linkText.length > 0) {
                $linkText.show().css({
                    'position': 'absolute',
                    'left': '65px',
                    'top': '0',
                    'width': '200px',
                    'background': '#303641',
                    'color': '#fff',
                    'padding': '15px 20px',
                    'border-radius': '0 3px 3px 0',
                    'opacity': '1',
                    'z-index': '1000',
                    'white-space': 'nowrap',
                    'box-shadow': '2px 2px 5px rgba(0,0,0,0.3)'
                });
            }
        });

        $(document).on('mouseleave', '.page-container.sidebar-collapsed .sidebar-menu #main-menu > li', function() {
            var $this = $(this);
            var $submenu = $this.find('> ul');
            var $linkText = $this.find('> a > span:not(.badge)');

            // Hide submenu and link text when not hovering
            $submenu.hide();
            $linkText.hide();
        });

        // Additional handling for submenu hover
        $(document).on('mouseenter', '.page-container.sidebar-collapsed .sidebar-menu #main-menu > li > ul', function() {
            // Keep submenu visible when hovering over it
            $(this).show();
        });

        $(document).on('mouseleave', '.page-container.sidebar-collapsed .sidebar-menu #main-menu > li > ul', function() {
            // Hide submenu when leaving it
            $(this).hide();
        });

        // Ensure proper cleanup when sidebar is expanded
        $(document).on('click', '.sidebar-collapse-icon', function() {
            // Hide all expanded menus when toggling sidebar
            setTimeout(function() {
                if (!$('.page-container').hasClass('sidebar-collapsed')) {
                    $('.sidebar-menu #main-menu > li > ul').show();
                    $('.sidebar-menu #main-menu > li > a > span').show();
                }
            }, 300);
        });

        // Mobile menu toggle
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

    }, 200); // Increased delay to ensure Neon theme is fully loaded

    // Show success messages
    @if(session('success'))
        // You can add toastr or custom notification here
        alert("{{ session('success') }}");
    @endif

    @if(session('error'))
        alert("{{ session('error') }}");
    @endif
});
</script>

@stack('scripts')
</body>
</html>