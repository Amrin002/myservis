<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Tukang Servis') - {{ config('app.name', 'Laravel') }}</title>

    <!-- PWA Links -->
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    {{-- <link rel="apple-touch-icon" href="{{ asset('icons/icon-192x192.png') }}"> --}}

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <link rel="stylesheet" href="{{ asset('asset/style.css') }}">

    @stack('styles')
</head>

<body>

    @yield('content')

    <script src="{{ asset('asset/app.js') }}"></script>
    @stack('script')
</body>

</html>
