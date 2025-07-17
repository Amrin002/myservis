<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Sistem Kelola Servisan') }} - @yield('title')</title>

    <!-- PWA Meta Tags -->
    <meta name="theme-color" content="#4f46e5">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="{{ config('app.name', 'Sistem Kelola Servisan') }}">

    <!-- PWA Links -->
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <link rel="apple-touch-icon" href="{{ asset('icons/icon-192x192.png') }}">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <link rel="stylesheet" href="{{ asset('asset/style.css') }}">

    @stack('styles')
</head>

<body>
    <!-- Sidebar -->
    @include('tuser.layouts.sidebar')

    <!-- Main Content -->
    <div class="main-content flex-grow-1 d-flex flex-column" id="main-content">
        <!-- Navbar -->
        @include('tuser.layouts.navbar')

        <!-- Page Content -->
        <main class="p-4 flex-grow-1">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('warning'))
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    {{ session('warning') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('info'))
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <i class="fas fa-info-circle me-2"></i>
                    {{ session('info') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </main>

        <!-- Footer -->
        @include('tuser.layouts.footer')
    </div>
    </div>

    <!-- PWA Install Banner -->
    <div id="installBanner" class="d-none position-fixed" style="bottom: 20px; left: 20px; right: 20px; z-index: 1050;">
        <div class="alert alert-primary shadow-lg" role="alert">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="mb-1"><i class="fas fa-download me-2"></i>Install Aplikasi</h6>
                    <small>Install aplikasi ini untuk akses yang lebih mudah</small>
                </div>
                <div class="d-flex gap-2">
                    <button id="installBtn" class="btn btn-light btn-sm fw-bold">
                        Install
                    </button>
                    <button id="dismissBtn" class="btn btn-outline-light btn-sm">
                        Dismiss
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script src="{{ asset('asset/app.js') }}"></script>

    @stack('scripts')
</body>

</html>
