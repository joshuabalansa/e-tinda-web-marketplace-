<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="Neon Admin Panel" />
    <meta name="author" content="" />

    <link rel="icon" href="{{ asset('assets/images/favicon.ico') }}">

    <title>Neon | Dashboard</title>

    <link rel="stylesheet" href="{{ asset('assets/js/jquery-ui/css/no-theme/jquery-ui-1.10.3.custom.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/font-icons/entypo/css/entypo.css') }}">
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Noto+Sans:400,700,400italic">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/neon-core.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/neon-theme.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/neon-forms.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">

    <script src="{{ asset('assets/js/jquery-1.11.3.min.js') }}"></script>

    <style>
        .sidebar-menu {
            background-color: #1a472a !important;
            /* Dark green color */
        }

        .sidebar-menu .main-menu>li>a {
            color: #ffffff !important;
        }

        .sidebar-menu .main-menu>li>a:hover {
            background-color: #2d5a3f !important;
            /* Slightly lighter green for hover */
        }

        .sidebar-menu .main-menu>li.active>a {
            background-color: #2d5a3f !important;
        }
    </style>

    <!--[if lt IE 9]><script src="assets/js/ie8-responsive-file-warning.js"></script><![endif]-->

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
 <![endif]-->


</head>

<body class="page-body  page-fade" data-url="http://neon.dev">
    <div class="page-container">
        <div class="sidebar-menu">

            <div class="sidebar-menu-inner">

                <header class="logo-env">

                    <!-- logo -->
                    <div class="logo">
                        <a href="index.html">
                            <i class="fas fa-leaf fa-2x text-success"
                                style="display: block; margin-bottom: 0.5rem; color: white; text-align: center;"></i>
                            <h4 class="text-success fw-bold" style="display: block; margin: 0; color: white;">E-Tinda
                                Marketplace</h4>
                        </a>
                    </div>

                    <!-- logo collapse icon -->
                    <div class="sidebar-collapse">
                        <a href="#"
                            class="sidebar-collapse-icon"><!-- add class "with-animation" if you want sidebar to have animation during expanding/collapsing transition -->
                            <i class="entypo-menu"></i>
                        </a>
                    </div>


                    <!-- open/close menu icon (do not remove if you want to enable menu on mobile devices) -->
                    <div class="sidebar-mobile-menu visible-xs">
                        <a href="#" class="with-animation"><!-- add class "with-animation" to support animation -->
                            <i class="entypo-menu"></i>
                        </a>
                    </div>

                </header>


                <ul id="main-menu" class="main-menu">
                    @auth
                        @if(auth()->user()->role->value === 'farmer')
                            <!-- Farmer Menu -->
                            <li class="active opened active">
                                <a href="#">
                                    <i class="entypo-gauge"></i>
                                    <span class="title">Dashboard</span>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="entypo-bag"></i>
                                    <span class="title">My Products</span>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="entypo-plus"></i>
                                    <span class="title">Add Product</span>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="entypo-chart-bar"></i>
                                    <span class="title">Analytics</span>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="entypo-doc-text"></i>
                                    <span class="title">Orders</span>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="entypo-comment"></i>
                                    <span class="title">Forums</span>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="entypo-user"></i>
                                    <span class="title">Profile</span>
                                </a>
                            </li>

                        @elseif(auth()->user()->role->value === 'buyer')
                            <!-- Buyer Menu -->
                            <li class="active opened active">
                                <a href="#">
                                    <i class="entypo-gauge"></i>
                                    <span class="title">Dashboard</span>
                                </a>
                            </li>
                            <li>
                                <a href="/shop">
                                    <i class="entypo-bag"></i>
                                    <span class="title">Browse Products</span>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="entypo-basket"></i>
                                    <span class="title">Shopping Cart</span>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="entypo-doc-text"></i>
                                    <span class="title">Order History</span>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="entypo-heart"></i>
                                    <span class="title">Wishlist</span>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="entypo-comment"></i>
                                    <span class="title">Forums</span>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="entypo-user"></i>
                                    <span class="title">Profile</span>
                                </a>
                            </li>

                        @elseif(auth()->user()->role->value === 'admin')
                            <!-- Admin Menu -->
                            <li class="active opened active">
                                <a href="#">
                                    <i class="entypo-gauge"></i>
                                    <span class="title">Dashboard</span>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="entypo-users"></i>
                                    <span class="title">Users</span>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="entypo-bag"></i>
                                    <span class="title">Products</span>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="entypo-doc-text"></i>
                                    <span class="title">Orders</span>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="entypo-comment"></i>
                                    <span class="title">Forums</span>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="entypo-list"></i>
                                    <span class="title">System Logs</span>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="entypo-cog"></i>
                                    <span class="title">Settings</span>
                                </a>
                            </li>
                        @endif
                    @endauth
                </ul>

            </div>

        </div>
        @yield('content')
    </div>
    <!-- Imported styles on this page -->
    <link rel="stylesheet" href="{{ asset('assets/js/jvectormap/jquery-jvectormap-1.2.2.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/js/rickshaw/rickshaw.min.css') }}">

    <!-- Bottom scripts (common) -->
    <script src="{{ asset('assets/js/gsap/TweenMax.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery-ui/js/jquery-ui-1.10.3.minimal.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.js') }}"></script>
    <script src="{{ asset('assets/js/joinable.js') }}"></script>
    <script src="{{ asset('assets/js/resizeable.js') }}"></script>
    <script src="{{ asset('assets/js/neon-api.js') }}"></script>
    <script src="{{ asset('assets/js/jvectormap/jquery-jvectormap-1.2.2.min.js') }}"></script>

    <!-- Imported scripts on this page -->
    <script src="{{ asset('assets/js/jvectormap/jquery-jvectormap-europe-merc-en.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.sparkline.min.js') }}"></script>
    <script src="{{ asset('assets/js/rickshaw/vendor/d3.v3.js') }}"></script>
    <script src="{{ asset('assets/js/rickshaw/rickshaw.min.js') }}"></script>
    <script src="{{ asset('assets/js/raphael-min.js') }}"></script>
    <script src="{{ asset('assets/js/morris.min.js') }}"></script>
    <script src="{{ asset('assets/js/toastr.js') }}"></script>
    <script src="{{ asset('assets/js/neon-chat.js') }}"></script>


    <!-- JavaScripts initializations and stuff -->
    <script src="{{ asset('assets/js/neon-custom.js') }}"></script>


    <!-- Demo Settings -->
    <script src="{{ asset('assets/js/neon-demo.js') }}"></script>

</body>

</html>
