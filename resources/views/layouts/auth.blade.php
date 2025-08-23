<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'POS Apotek') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <!-- Livewire Styles -->
    @livewireStyles

    <!-- Custom Auth Styles -->
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Nunito', sans-serif;
            overflow: hidden;
        }

        .auth-container {
            min-height: 100vh;
            width: 100vw;
            display: flex;
            align-items: stretch;
        }

        /* Remove auth-card wrapper styles */
        .auth-header,
        .auth-body,
        .auth-footer {
            display: none;
        }
    </style>
</head>
<body>
    <div class="auth-container">
        @yield('content')
    </div>

    @stack('scripts')
</body>
</html>
