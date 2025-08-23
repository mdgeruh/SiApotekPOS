<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ \App\Helpers\AppSettingHelper::faviconUrl() }}">
    <link rel="shortcut icon" type="image/svg+xml" href="{{ \App\Helpers\AppSettingHelper::faviconUrl() }}">
    <link rel="apple-touch-icon" href="{{ \App\Helpers\AppSettingHelper::logoUrl() }}">

    <!-- Meta tags for better browser support -->
    <meta name="application-name" content="{{ \App\Helpers\AppSettingHelper::appName() }}">
    <meta name="msapplication-TileColor" content="#4e73df">
    <meta name="theme-color" content="#4e73df">

    <title>{{ \App\Helpers\AppSettingHelper::fullPageTitle() }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <!-- Sidebar Fixes CSS -->
    <link rel="stylesheet" href="{{ asset('css/sidebar-fixes.css') }}">

    <!-- Components CSS -->
    <link rel="stylesheet" href="{{ asset('css/components.css') }}">

    <!-- Sidebar JavaScript -->
    <script src="{{ asset('js/sidebar.js') }}" defer></script>

    <!-- Notification System -->
    <script src="{{ asset('js/notification-system.js') }}" defer></script>

    <!-- Livewire Styles -->
    @livewireStyles

    <!-- Custom SB Admin 2 Styles -->
    <style>
        /* SB Admin 2 Custom Styles */
        body {
            font-family: 'Nunito', sans-serif;
            overflow-x: hidden;
        }

        #wrapper {
            display: flex;
            min-height: 100vh;
            position: relative;
        }

        /* Sidebar Styles */
        .sidebar {
            width: 250px;
            min-height: 100vh;
            background: linear-gradient(180deg, #4e73df 10%, #224abe 100%);
            transition: all 0.3s ease;
            position: fixed;
            left: 0;
            top: 0;
            z-index: 1000;
            overflow-y: auto;
            overflow-x: hidden;
        }

        .sidebar.toggled {
            width: 60px;
        }

        .sidebar.toggled .sidebar-brand-text,
        .sidebar.toggled .sidebar-heading,
        .sidebar.toggled .nav-link span,
        .sidebar.toggled .dropdown-menu {
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .sidebar.toggled .sidebar-brand-icon {
            opacity: 1;
            visibility: visible;
        }

        .sidebar.toggled .nav-link {
            text-align: center;
            padding: 0.75rem 0.5rem;
        }

        .sidebar.toggled .nav-link i {
            margin-right: 0;
            font-size: 1.2rem;
        }

        /* Ensure toggle button is always visible when sidebar is toggled */
        .sidebar.toggled #sidebarToggle {
            background: #4e73df;
            color: white;
            border: 2px solid white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }

        .sidebar.toggled #sidebarToggle:hover {
            background: #2e59d9;
            transform: translateY(-50%) scale(1.1);
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 0.75rem 1rem;
            border-radius: 0.35rem;
            margin: 0.2rem 0.5rem;
            transition: all 0.3s ease;
            text-decoration: none;
            display: flex;
            align-items: center;
        }

        .sidebar .nav-link:hover {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.1);
            text-decoration: none;
            transform: translateX(5px);
        }

        .sidebar .nav-link.active {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.2);
            font-weight: 600;
        }

        .sidebar .nav-link i {
            width: 20px;
            margin-right: 0.5rem;
        }

        .sidebar-brand {
            height: 4.375rem;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(0, 0, 0, 0.2);
            margin: 0.5rem;
            border-radius: 0.35rem;
            text-decoration: none;
        }

        .sidebar-brand-text {
            color: #fff;
            font-size: 1.2rem;
            font-weight: 700;
            text-decoration: none;
        }

        .sidebar-brand-icon {
            font-size: 1.5rem;
            margin-right: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .sidebar-brand-icon img {
            max-width: 40px;
            max-height: 40px;
            object-fit: contain;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.1);
            padding: 4px;
        }

        .sidebar-divider {
            border-top: 1px solid rgba(255, 255, 255, 0.15);
            margin: 1rem 0.5rem;
            opacity: 0.6;
        }

        .sidebar-heading {
            color: rgba(255, 255, 255, 0.4);
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            padding: 0.5rem 1rem;
            margin: 0.5rem 0;
            letter-spacing: 0.5px;
        }

        .content-wrapper {
            flex: 1;
            margin-left: 250px;
            transition: margin-left 0.3s ease;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            position: relative;
        }

        .content-wrapper.toggled {
            margin-left: 60px;
        }

        /* Sidebar toggle button positioning */
        #sidebarToggle {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: rgba(255, 255, 255, 0.8);
            padding: 0.5rem;
            border-radius: 50%;
            transition: all 0.3s ease;
            position: absolute;
            right: -15px;
            top: 50%;
            transform: translateY(-50%);
            z-index: 1002;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }

        #sidebarToggle:hover {
            background: rgba(255, 255, 255, 0.2);
            color: #fff;
            transform: translateY(-50%) scale(1.1);
            box-shadow: 0 4px 15px rgba(0,0,0,0.3);
        }

        #sidebarToggle i {
            transition: transform 0.3s ease;
        }

        /* Sidebar toggle button top */
        #sidebarToggleTop {
            background: transparent;
            border: none;
            color: #858796;
            padding: 0.5rem;
            border-radius: 50%;
            transition: all 0.3s ease;
        }

        #sidebarToggleTop:hover {
            background: #f8f9fc;
            color: #4e73df;
        }

        .topbar {
            height: 4.375rem;
            background: #fff;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            position: sticky;
            top: 0;
            z-index: 999;
        }

        .topbar .navbar-brand {
            font-size: 1.25rem;
            font-weight: 700;
            color: #5a5c69;
        }

        .topbar .nav-link {
            color: #858796;
            padding: 0.5rem 1rem;
        }

        .topbar .nav-link:hover {
            color: #4e73df;
        }

        .main-content {
            flex: 1;
            background-color: #f8f9fc;
            padding: 1.5rem;
        }

        .card {
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            border: 0;
            border-radius: 0.35rem;
        }

        .card-header {
            background-color: #f8f9fc;
            border-bottom: 1px solid #e3e6f0;
            font-weight: 700;
            color: #5a5c69;
        }

        .btn-primary {
            background-color: #4e73df;
            border-color: #4e73df;
        }

        .btn-primary:hover {
            background-color: #2e59d9;
            border-color: #2653d4;
        }

        .bg-primary {
            background-color: #4e73df !important;
        }

        .bg-success {
            background-color: #1cc88a !important;
        }

        .bg-info {
            background-color: #36b9cc !important;
        }

        .bg-warning {
            background-color: #f6c23e !important;
        }

        .bg-danger {
            background-color: #e74a3b !important;
        }

        .text-primary {
            color: #4e73df !important;
        }

        .text-success {
            color: #1cc88a !important;
        }

        .text-info {
            color: #36b9cc !important;
        }

        .text-warning {
            color: #f6c23e !important;
        }

        .text-danger {
            color: #e74a3b !important;
        }

        .dropdown-menu {
            border: 0;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            border-radius: 0.35rem;
        }

        .dropdown-item:hover {
            background-color: #f8f9fc;
        }

        .alert {
            border: 0;
            border-radius: 0.35rem;
        }

        .table {
            border-radius: 0.35rem;
            overflow: hidden;
        }

        .table thead th {
            background-color: #f8f9fc;
            border-bottom: 2px solid #e3e6f0;
            color: #5a5c69;
            font-weight: 700;
        }

        .form-control {
            border-radius: 0.35rem;
            border: 1px solid #d1d3e2;
        }

        .form-control:focus {
            border-color: #bac8f3;
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
        }

        .badge {
            border-radius: 0.35rem;
            font-size: 0.75rem;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                position: fixed;
                top: 0;
                left: 0;
                z-index: 1050;
                transition: transform 0.3s ease;
                width: 250px;
            }

            .sidebar.show {
                transform: translateX(0);
                box-shadow: 2px 0 10px rgba(0,0,0,0.3);
            }

            .sidebar.toggled {
                width: 250px;
                transform: translateX(-100%);
            }

            .content-wrapper {
                margin-left: 0;
            }

            .content-wrapper.toggled {
                margin-left: 0;
            }

            .main-content {
                padding: 1rem;
            }

            #sidebarToggle {
                display: none;
            }

            /* Mobile overlay */
            #mobileOverlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0,0,0,0.5);
                z-index: 1049;
                cursor: pointer;
            }
        }

        /* Animation classes */
        .fade-in {
            animation: fadeIn 0.5s ease-in;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .slide-in {
            animation: slideIn 0.3s ease-out;
        }

        @keyframes slideIn {
            from { transform: translateX(-100%); }
            to { transform: translateX(0); }
        }

        /* Scroll to top button */
        .scroll-to-top {
            position: fixed;
            right: 15px;
            bottom: 15px;
            display: none;
            width: 50px;
            height: 50px;
            text-align: center;
            color: white;
            background: #4e73df;
            line-height: 45px;
            border-radius: 50%;
            z-index: 1001;
        }

        .scroll-to-top:hover {
            background: #2e59d9;
            color: white;
            text-decoration: none;
        }

        /* Sidebar dropdown styles */
        .sidebar .dropdown-menu {
            background: rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.1);
            margin-left: 1rem;
            margin-right: 0.5rem;
            border-radius: 0.35rem;
        }

        .sidebar .dropdown-item {
            color: rgba(255, 255, 255, 0.8);
            padding: 0.5rem 1rem;
            border-radius: 0.25rem;
            margin: 0.1rem 0.5rem;
            transition: all 0.3s ease;
        }

        .sidebar .dropdown-item:hover {
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
            text-decoration: none;
            transform: translateX(5px);
        }

        .sidebar .dropdown-item i {
            width: 16px;
            margin-right: 0.5rem;
        }

        /* Sidebar dropdown specific styling */
        .sidebar-dropdown {
            position: static !important;
            transform: none !important;
            width: 100% !important;
            margin: 0.5rem 0.5rem 0.5rem 1rem !important;
            padding: 0.5rem 0 !important;
            background: rgba(0, 0, 0, 0.15) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            border-radius: 0.35rem !important;
            box-shadow: none !important;
        }

        .sidebar-dropdown .dropdown-item {
            color: rgba(255, 255, 255, 0.8) !important;
            padding: 0.5rem 1rem !important;
            margin: 0.1rem 0.5rem !important;
            border-radius: 0.25rem !important;
            transition: all 0.3s ease !important;
            font-size: 0.9rem !important;
        }

        .sidebar-dropdown .dropdown-item:hover {
            background: rgba(255, 255, 255, 0.1) !important;
            color: #fff !important;
            transform: translateX(5px) !important;
        }

        .sidebar-dropdown .dropdown-item i {
            width: 16px !important;
            margin-right: 0.5rem !important;
            opacity: 0.8 !important;
        }

        /* Enhanced transitions for better UX */
        .sidebar {
            transition: width 0.3s ease, transform 0.3s ease;
        }

        .sidebar .nav-link,
        .sidebar .sidebar-brand,
        .sidebar .sidebar-heading,
        .sidebar .nav-link span,
        .sidebar .dropdown-menu {
            transition: all 0.3s ease;
        }

        .content-wrapper {
            transition: margin-left 0.3s ease;
        }

        /* Ensure smooth icon rotation on toggle */
        #sidebarToggle i {
            transition: transform 0.3s ease;
        }

        .sidebar.toggled #sidebarToggle i {
            transform: rotate(180deg);
        }

        /* Topbar Enhanced Styles */
        .topbar {
            height: 4.375rem;
            background: #fff;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            position: sticky;
            top: 0;
            z-index: 999;
            padding: 0 1rem;
        }

        .topbar-divider {
            width: 0;
            border-right: 1px solid #e3e6f0;
            height: 2rem;
            margin: auto 1rem;
        }

        .img-profile {
            height: 2.5rem;
            width: 2.5rem;
            object-fit: cover;
            border: 2px solid #e3e6f0;
            transition: all 0.3s ease;
        }

        .img-profile:hover {
            border-color: #4e73df;
            transform: scale(1.05);
        }

        /* Search Dropdown */
        .navbar-search .input-group {
            background: #f8f9fc;
            border-radius: 0.35rem;
            overflow: hidden;
        }

        .navbar-search .form-control {
            border: none;
            background: transparent;
            padding: 0.75rem 1rem;
        }

        .navbar-search .form-control:focus {
            box-shadow: none;
            background: #fff;
        }

        .navbar-search .btn {
            border: none;
            padding: 0.75rem 1rem;
            border-radius: 0;
        }

        /* Alerts and Messages Dropdowns */
        .dropdown-list {
            max-height: 400px;
            overflow-y: auto;
        }

        .dropdown-list .dropdown-header {
            background-color: #4e73df;
            border: 1px solid #4e73df;
            color: white;
            font-weight: 700;
            text-transform: uppercase;
            padding: 1rem;
            border-radius: 0.35rem 0.35rem 0 0;
        }

        .dropdown-list .dropdown-item {
            border-bottom: 1px solid #e3e6f0;
            padding: 1rem;
            transition: all 0.3s ease;
        }

        .dropdown-list .dropdown-item:hover {
            background-color: #f8f9fc;
            transform: translateX(5px);
        }

        .dropdown-list .dropdown-item:last-child {
            border-bottom: none;
            text-align: center;
            font-weight: 600;
            color: #4e73df;
        }

        .icon-circle {
            height: 2.5rem;
            width: 2.5rem;
            border-radius: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.875rem;
        }

        .status-indicator {
            height: 0.75rem;
            width: 0.75rem;
            border-radius: 100%;
            display: inline-block;
            margin-right: 0.5rem;
            background-color: #858796;
        }

        .status-indicator.bg-success {
            background-color: #1cc88a !important;
        }

        .status-indicator.bg-warning {
            background-color: #f6c23e !important;
        }

        .dropdown-list-image {
            position: relative;
            height: 2.5rem;
            width: 2.5rem;
        }

        .dropdown-list-image img {
            height: 2.5rem;
            width: 2.5rem;
            object-fit: cover;
        }

        .dropdown-list-image .status-indicator {
            position: absolute;
            bottom: 0;
            right: 0;
            height: 0.75rem;
            width: 0.75rem;
            border: 2px solid #fff;
        }

        /* Badge Counter */
        .badge-counter {
            position: absolute;
            transform: scale(0.7);
            transform-origin: top right;
            right: 0.25rem;
            margin-top: -0.25rem;
        }

        /* User Dropdown Enhanced */
        .dropdown-menu .dropdown-item.text-center {
            padding: 1.5rem 1rem;
            border-bottom: none;
        }

        .dropdown-menu .dropdown-item.text-center img {
            border: 3px solid #4e73df;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(78, 115, 223, 0.25);
        }

        .dropdown-menu .dropdown-item.text-center .font-weight-bold {
            font-size: 1.1rem;
            margin-bottom: 0.25rem;
        }

        .dropdown-menu .dropdown-item.text-center .small {
            margin-bottom: 0.5rem;
        }

        .dropdown-menu .dropdown-item.text-center .badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
        }

        /* Responsive adjustments for topbar */
        @media (max-width: 768px) {
            .topbar {
                padding: 0 0.5rem;
            }

            .topbar-divider {
                display: none !important;
            }

            .navbar-nav .nav-item {
                margin: 0 0.25rem;
            }

            .dropdown-list {
                width: 300px !important;
                max-width: 90vw;
            }
        }

        /* Animation for dropdowns */
        .animated--grow-in {
            animation-name: growIn;
            animation-duration: 200ms;
            animation-timing-function: transform cubic-bezier(0.18, 1.25, 0.4, 1), opacity cubic-bezier(0, 1, 0.4, 1);
        }

        @keyframes growIn {
            0% {
                transform: scale(0.9);
                opacity: 0;
            }
            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        /* Hover effects for nav items */
        .topbar .nav-link {
            position: relative;
            transition: all 0.3s ease;
        }

        .topbar .nav-link:hover {
            color: #4e73df;
            transform: translateY(-2px);
        }

        .topbar .nav-link:hover .fa-fw {
            transform: scale(1.1);
        }

        .topbar .fa-fw {
            transition: transform 0.3s ease;
        }

        /* Additional navbar improvements */
        .navbar-nav .nav-item {
            position: relative;
        }

        .navbar-nav .nav-link {
            padding: 0.5rem 0.75rem;
            border-radius: 0.35rem;
            transition: all 0.3s ease;
        }

        .navbar-nav .nav-link:hover {
            background-color: #f8f9fc;
        }

        /* Fix for dropdown positioning */
        .dropdown-menu {
            margin-top: 0.5rem;
            border: none;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }

        /* Ensure proper spacing in topbar */
        .topbar .container-fluid {
            padding-left: 0;
            padding-right: 0;
        }

        /* Brand styling */
        .topbar .text-primary {
            font-weight: 700;
            text-shadow: 0 1px 2px rgba(0,0,0,0.1);
        }

        /* Notification badge positioning */
        .nav-link .badge-counter {
            position: absolute;
            top: 0.25rem;
            right: 0.25rem;
            font-size: 0.6rem;
            padding: 0.25rem 0.4rem;
            border-radius: 0.75rem;
            min-width: 1.2rem;
            text-align: center;
        }

        /* Ensure proper z-index for dropdowns */
        .dropdown-menu {
            z-index: 1000;
        }

        .dropdown-list {
            z-index: 1001;
        }

        /* Smooth transitions for all interactive elements */
        .topbar * {
            transition: all 0.3s ease;
        }

        /* Loading spinner for search button */
        .fa-spinner {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        /* Mobile optimizations */
        @media (max-width: 576px) {
            .topbar .navbar-brand {
                font-size: 1rem;
            }

            .topbar .nav-link {
                padding: 0.5rem 0.5rem;
            }

            .dropdown-list {
                width: 280px !important;
                max-width: 95vw;
            }
        }
    </style>
</head>
<body id="page-top">
    <div id="wrapper">
        <!-- Sidebar -->
        <ul class="navbar-nav sidebar sidebar-dark accordion" id="accordionSidebar">
            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('dashboard') }}">
                <div class="sidebar-brand-icon">
                    @if(\App\Helpers\AppSettingHelper::logoUrl())
                        <img src="{{ \App\Helpers\AppSettingHelper::logoUrl() }}" alt="Logo {{ \App\Helpers\AppSettingHelper::pharmacyName() }}" title="{{ \App\Helpers\AppSettingHelper::pharmacyName() }}">
                    @else
                        <i class="fas fa-clinic-medical"></i>
                    @endif
                </div>
                <div class="sidebar-brand-text mx-3">{{ \App\Helpers\AppSettingHelper::pharmacyName() ?: 'Apotek' }}</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Menu Utama
            </div>

            <!-- Nav Item - Dashboard -->
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Manajemen
            </div>

            @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('manajemen_obat'))
            <!-- Nav Item - Obat -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle {{ request()->routeIs('medicines.*') ? 'active' : '' }}" href="#" id="medicinesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-fw fa-pills"></i>
                    <span>Obat</span>
                </a>
                <ul class="dropdown-menu sidebar-dropdown" aria-labelledby="medicinesDropdown">
                    <li><a class="dropdown-item" href="{{ route('medicines.index') }}">
                        <i class="fas fa-fw fa-list"></i>Daftar Obat
                    </a></li>
                    <li><a class="dropdown-item" href="{{ route('medicines.log') }}">
                        <i class="fas fa-fw fa-archive"></i>Log Obat
                    </a></li>
                </ul>
            </li>

            <!-- Nav Item - Kategori -->
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}" href="{{ route('categories.index') }}">
                    <i class="fas fa-fw fa-tags"></i>
                    <span>Kategori</span>
                </a>
            </li>
            @endif

            @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('kasir'))
            <!-- Nav Item - Penjualan -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle {{ request()->routeIs('sales.*') ? 'active' : '' }}" href="#" id="salesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-fw fa-shopping-cart"></i>
                    <span>Penjualan</span>
                </a>
                <ul class="dropdown-menu sidebar-dropdown" aria-labelledby="salesDropdown">
                    <li><a class="dropdown-item {{ request()->routeIs('sales.index') ? 'active' : '' }}" href="{{ route('sales.index') }}">
                        <i class="fas fa-fw fa-list"></i>Daftar Penjualan
                    </a></li>
                    <li><a class="dropdown-item {{ request()->routeIs('sales.log') ? 'active' : '' }}" href="{{ route('sales.log') }}">
                        <i class="fas fa-fw fa-archive"></i>Log Penjualan
                    </a></li>
                </ul>
            </li>
            @endif

            @if(auth()->user()->hasRole('admin'))
            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Laporan & Analisis
            </div>

            <!-- Nav Item - Laporan -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="reportsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-fw fa-chart-bar"></i>
                    <span>Laporan</span>
                </a>
                <ul class="dropdown-menu sidebar-dropdown" aria-labelledby="reportsDropdown">
                    <li><a class="dropdown-item" href="{{ route('reports.sales') }}">
                        <i class="fas fa-fw fa-chart-line"></i>Laporan Penjualan
                    </a></li>
                    <li><a class="dropdown-item" href="{{ route('reports.statistics') }}">
                        <i class="fas fa-fw fa-chart-pie"></i>Statistik
                    </a></li>
                    <li><a class="dropdown-item" href="{{ route('reports.stock') }}">
                        <i class="fas fa-fw fa-boxes"></i>Laporan Stok
                    </a></li>
                </ul>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Sistem
            </div>

            <!-- Nav Item - Users -->
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                    <i class="fas fa-fw fa-users"></i>
                    <span>Users</span>
                </a>
            </li>

            <!-- Nav Item - App Settings -->
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('app-settings.*') ? 'active' : '' }}" href="{{ route('app-settings.index') }}">
                    <i class="fas fa-fw fa-cogs"></i>
                    <span>Pengaturan</span>
                </a>
            </li>
            @endif



            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle" type="button">
                    <i class="fas fa-angle-left"></i>
                </button>
            </div>
        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="content-wrapper">
            <!-- Main Content -->
            <div id="content">
                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light topbar mb-4 static-top shadow">
                    <div class="container-fluid">
                        <!-- Sidebar Toggle (Topbar) -->
                        <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle me-3">
                            <i class="fas fa-bars"></i>
                        </button>

                        <!-- Topbar Brand -->
                        <div class="d-none d-sm-inline-block me-3">
                            <h5 class="text-primary mb-0">{{ \App\Helpers\AppSettingHelper::pharmacyName() ?: 'POS Apotek' }}</h5>
                        </div>

                        <!-- Topbar Navbar -->
                        <ul class="navbar-nav ms-auto">
                            <!-- Nav Item - Search -->
                            <li class="nav-item dropdown no-arrow d-none d-sm-block">
                                <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-search fa-fw"></i>
                                </a>
                                <!-- Dropdown - Search -->
                                <div class="dropdown-menu dropdown-menu-end p-3 shadow animated--grow-in" aria-labelledby="searchDropdown" style="width: 300px;">
                                    <form class="form-inline mr-auto w-100 navbar-search">
                                        <div class="input-group">
                                            <input type="text" class="form-control bg-light border-0 small" placeholder="Cari obat, kategori, atau laporan..." aria-label="Search" aria-describedby="basic-addon2">
                                            <div class="input-group-append">
                                                <button class="btn btn-primary" type="button">
                                                    <i class="fas fa-search fa-sm"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </li>

                            <!-- Nav Item - Alerts -->
                            <li class="nav-item dropdown no-arrow mx-1">
                                <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-bell fa-fw"></i>
                                    <!-- Counter - Alerts -->
                                    <span class="badge badge-danger badge-counter">3+</span>
                                </a>
                                <!-- Dropdown - Alerts -->
                                <div class="dropdown-list dropdown-menu dropdown-menu-end shadow animated--grow-in" aria-labelledby="alertsDropdown" style="width: 350px;">
                                    <h6 class="dropdown-header">
                                        Pusat Notifikasi
                                    </h6>
                                    <a class="dropdown-item d-flex align-items-center" href="#">
                                        <div class="me-3">
                                            <div class="icon-circle bg-primary">
                                                <i class="fas fa-file-alt text-white"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="small text-gray-500">December 12, 2024</div>
                                            <span class="font-weight-bold">Laporan bulanan telah tersedia untuk diunduh.</span>
                                        </div>
                                    </a>
                                    <a class="dropdown-item d-flex align-items-center" href="#">
                                        <div class="me-3">
                                            <div class="icon-circle bg-success">
                                                <i class="fas fa-donate text-white"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="small text-gray-500">December 7, 2024</div>
                                            $290.29 telah ditambahkan ke saldo Anda!
                                        </div>
                                    </a>
                                    <a class="dropdown-item d-flex align-items-center" href="#">
                                        <div class="me-3">
                                            <div class="icon-circle bg-warning">
                                                <i class="fas fa-exclamation-triangle text-white"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="small text-gray-500">December 2, 2024</div>
                                            Stok obat "Paracetamol" hampir habis.
                                        </div>
                                    </a>
                                    <a class="dropdown-item text-center small text-gray-500" href="#">Tampilkan Semua Notifikasi</a>
                                </div>
                            </li>

                            <!-- Nav Item - Messages -->
                            <li class="nav-item dropdown no-arrow mx-1">
                                <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-envelope fa-fw"></i>
                                    <!-- Counter - Messages -->
                                    <span class="badge badge-danger badge-counter">7</span>
                                </a>
                                <!-- Dropdown - Messages -->
                                <div class="dropdown-list dropdown-menu dropdown-menu-end shadow animated--grow-in" aria-labelledby="messagesDropdown" style="width: 350px;">
                                    <h6 class="dropdown-header">
                                        Pusat Pesan
                                    </h6>
                                    <a class="dropdown-item d-flex align-items-center" href="#">
                                        <div class="dropdown-list-image me-3">
                                            <img class="rounded-circle" src="{{ asset('images/default-avatar.svg') }}" alt="">
                                            <div class="status-indicator bg-success"></div>
                                        </div>
                                        <div class="font-weight-bold">
                                            <div class="text-truncate">Hi there! I am wondering if you can help me with a problem I've been having.</div>
                                            <div class="small text-gray-500">Emily Fowler · 58m</div>
                                        </div>
                                    </a>
                                    <a class="dropdown-item d-flex align-items-center" href="#">
                                        <div class="dropdown-list-image me-3">
                                            <img class="rounded-circle" src="{{ asset('images/default-avatar.svg') }}" alt="">
                                            <div class="status-indicator"></div>
                                        </div>
                                        <div>
                                            <div class="text-truncate">I have the photos that you ordered last month, how would you like them sent to you?</div>
                                            <div class="small text-gray-500">Jae Chun · 1d</div>
                                        </div>
                                    </a>
                                    <a class="dropdown-item d-flex align-items-center" href="#">
                                        <div class="dropdown-list-image me-3">
                                            <img class="rounded-circle" src="{{ asset('images/default-avatar.svg') }}" alt="">
                                            <div class="status-indicator bg-warning"></div>
                                        </div>
                                        <div>
                                            <div class="text-truncate">Last month's report looks great, I am very happy with the progress so far, keep up the excellent work!</div>
                                            <div class="small text-gray-500">Morgan Alvarez · 2d</div>
                                        </div>
                                    </a>
                                    <a class="dropdown-item d-flex align-items-center" href="#">
                                        <div class="dropdown-list-image me-3">
                                            <img class="rounded-circle" src="{{ asset('images/default-avatar.svg') }}" alt="">
                                            <div class="status-indicator bg-success"></div>
                                        </div>
                                        <div>
                                            <div class="text-truncate">Am I a good boy? The reason I ask is because someone told me that people say this to all dogs, even if they aren't good...</div>
                                            <div class="small text-gray-500">Chicken the Dog · 2w</div>
                                        </div>
                                    </a>
                                    <a class="dropdown-item text-center small text-gray-500" href="#">Baca Semua Pesan</a>
                                </div>
                            </li>

                            <div class="topbar-divider d-none d-sm-block"></div>

                            <!-- Nav Item - User Information -->
                            <li class="nav-item dropdown no-arrow">
                                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <span class="me-2 d-none d-lg-inline text-gray-600 small">{{ Auth::user()->name ?? 'User' }}</span>
                                    <img class="img-profile rounded-circle" src="{{ Auth::user()->profile_photo_url }}" alt="Profile Photo">
                                </a>
                                <!-- Dropdown - User Information -->
                                <div class="dropdown-menu dropdown-menu-end shadow animated--grow-in" aria-labelledby="userDropdown">
                                    <div class="dropdown-item text-center">
                                        <img class="img-profile rounded-circle mb-2" src="{{ Auth::user()->profile_photo_url }}" alt="Profile Photo" style="width: 60px; height: 60px;">
                                        <div class="font-weight-bold text-primary">{{ Auth::user()->name ?? 'User' }}</div>
                                        <div class="small text-gray-500">{{ Auth::user()->email ?? 'user@example.com' }}</div>
                                        <div class="small text-gray-500">
                                            <span class="badge badge-primary">{{ Auth::user()->role->name ?? 'User' }}</span>
                                        </div>
                                    </div>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="{{ route('profile') }}">
                                        <i class="fas fa-user fa-sm fa-fw me-2 text-gray-400"></i>
                                        Profile
                                    </a>
                                    <a class="dropdown-item" href="{{ route('settings') }}">
                                        <i class="fas fa-cogs fa-sm fa-fw me-2 text-gray-400"></i>
                                        Pengaturan
                                    </a>
                                    <a class="dropdown-item" href="{{ route('profile.activities') }}">
                                        <i class="fas fa-list fa-sm fa-fw me-2 text-gray-400"></i>
                                        Aktivitas
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#logoutModal">
                                        <i class="fas fa-sign-out-alt fa-sm fa-fw me-2 text-gray-400"></i>
                                        Logout
                                    </a>
                                </div>
                            </li>
                        </ul>
                    </div>
                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="main-content">
                    @yield('content')
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; {{ config('app.name', 'POS Apotek') }} {{ date('Y') }}</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->
        </div>
        <!-- End of Content Wrapper -->
    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-primary">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Livewire Scripts -->
    @livewireScripts

        <!-- Custom Scripts -->
    <script>
        // Scroll to top functionality
        document.addEventListener('DOMContentLoaded', function() {
            const scrollToTopBtn = document.querySelector('.scroll-to-top');
            if (scrollToTopBtn) {
                window.addEventListener('scroll', function() {
                    if (window.pageYOffset > 100) {
                        scrollToTopBtn.style.display = 'block';
                    } else {
                        scrollToTopBtn.style.display = 'none';
                    }
                });

                scrollToTopBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                });
            }

            // Add fade-in animation to cards
            const cards = document.querySelectorAll('.card');
            cards.forEach((card, index) => {
                card.style.animationDelay = `${index * 0.1}s`;
                card.classList.add('fade-in');
            });

            // Enhanced navbar functionality
            initializeNavbar();
        });

        // Initialize navbar functionality
        function initializeNavbar() {
            // Search functionality
            const searchInput = document.querySelector('.navbar-search input');
            const searchBtn = document.querySelector('.navbar-search button');

            if (searchInput && searchBtn) {
                searchBtn.addEventListener('click', function() {
                    performSearch(searchInput.value);
                });

                searchInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        performSearch(this.value);
                    }
                });
            }

            // Notification counters
            updateNotificationCounters();

            // Profile image fallback
            setupProfileImageFallback();

            // Dropdown animations
            setupDropdownAnimations();
        }

        // Perform search
        function performSearch(query) {
            if (query.trim() === '') {
                showNotification('Masukkan kata kunci pencarian', 'warning');
                return;
            }

            // Show loading state
            const searchBtn = document.querySelector('.navbar-search button');
            const originalContent = searchBtn.innerHTML;
            searchBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            searchBtn.disabled = true;

            // Simulate search (replace with actual search logic)
            setTimeout(() => {
                searchBtn.innerHTML = originalContent;
                searchBtn.disabled = false;

                // Redirect to search results or show results
                showNotification(`Mencari: "${query}"`, 'info');

                // You can redirect to a search page here
                // window.location.href = `/search?q=${encodeURIComponent(query)}`;
            }, 1000);
        }

        // Update notification counters
        function updateNotificationCounters() {
            // Simulate real-time updates
            setInterval(() => {
                const alertCounter = document.querySelector('#alertsDropdown .badge-counter');
                const messageCounter = document.querySelector('#messagesDropdown .badge-counter');

                if (alertCounter) {
                    const currentCount = parseInt(alertCounter.textContent);
                    if (currentCount > 0) {
                        alertCounter.textContent = Math.max(0, currentCount - 1);
                    }
                }
            }, 30000); // Update every 30 seconds
        }

        // Setup profile image fallback
        function setupProfileImageFallback() {
            const profileImages = document.querySelectorAll('.img-profile');
            profileImages.forEach(img => {
                img.addEventListener('error', function() {
                    this.src = '{{ asset("images/default-avatar.svg") }}';
                });
            });
        }

        // Setup dropdown animations
        function setupDropdownAnimations() {
            const dropdowns = document.querySelectorAll('.dropdown-toggle');
            dropdowns.forEach(dropdown => {
                dropdown.addEventListener('click', function() {
                    const targetId = this.getAttribute('aria-expanded');
                    if (targetId === 'false') {
                        this.querySelector('i')?.classList.add('fa-spin');
                    } else {
                        this.querySelector('i')?.classList.remove('fa-spin');
                    }
                });
            });
        }

        // Show notification (legacy function - now uses global notification system)
        function showNotification(message, type = 'info') {
            if (window.showNotification) {
                window.showNotification(message, type);
            } else {
                // Fallback jika sistem notifikasi belum tersedia
                const notification = document.createElement('div');
                notification.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
                notification.style.cssText = 'top: 80px; right: 20px; z-index: 9999; min-width: 300px;';
                notification.innerHTML = `
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;

                document.body.appendChild(notification);

                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.remove();
                    }
                }, 5000);
            }
        }

        // Enhanced mobile sidebar toggle
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggleTop = document.getElementById('sidebarToggleTop');
            const sidebar = document.querySelector('.sidebar');
            const mobileOverlay = document.getElementById('mobileOverlay');

            if (sidebarToggleTop) {
                sidebarToggleTop.addEventListener('click', function() {
                    sidebar.classList.toggle('show');

                    // Create overlay if it doesn't exist
                    if (!mobileOverlay) {
                        const overlay = document.createElement('div');
                        overlay.id = 'mobileOverlay';
                        overlay.style.cssText = `
                            position: fixed;
                            top: 0;
                            left: 0;
                            width: 100%;
                            height: 100%;
                            background: rgba(0,0,0,0.5);
                            z-index: 1049;
                            cursor: pointer;
                        `;
                        document.body.appendChild(overlay);

                        overlay.addEventListener('click', function() {
                            sidebar.classList.remove('show');
                            this.remove();
                        });
                    }
                });
            }
                        });
    </script>

        <!-- Confirmation Modal -->
    @include('components.confirmation-modal')

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Livewire Scripts -->
    @livewireScripts

    <!-- Scripts Stack -->
    @stack('scripts')
</body>
</html>
