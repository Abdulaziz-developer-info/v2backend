<!doctype html>
<html lang="en" style="
margin: 0;
">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Dashboard</title>

    <!-- Tabler CSS -->
    <link href="{{ asset('assets/dist/css/tabler.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/dist/css/tabler-flags.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/dist/css/tabler-socials.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/dist/css/tabler-payments.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/dist/css/tabler-vendors.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/dist/css/tabler-marketing.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/dist/css/tabler-themes.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/preview/css/demo.css') }}" rel="stylesheet" />

    <!-- Tabler icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

    <!-- Local Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('assets/bootstrap/bootstrap.min.css') }}">

    <style>
        @import url("https://rsms.me/inter/inter.css");
    </style>

</head>

<body>
    <script src="{{ asset('assets/dist/js/tabler-theme.min.js') }}"></script>
    <div class="page">

        @include('layout.navbar')


        <div class="page-wrapper">
            @yield('content')
        </div>
        @include('layout.settings')
    </div>



    <!-- Tabler JS -->
    <script src="{{ asset('assets/dist/js/beta-tabler.min.js') }}"></script>
    <script src="{{ asset('assets/preview/js/demo.min.js') }}"></script>


</body>

</html>
