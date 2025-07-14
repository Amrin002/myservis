<nav class="navbar navbar-expand-lg navbar-light content-bg shadow-sm">
    <div class="container-fluid">
        <button class="btn btn-outline-primary me-3" onclick="toggleSidebar()">
            <i class="fas fa-bars"></i>
        </button>

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                @yield('breadcrumb')
            </ol>
        </nav>

        <div class="navbar-nav ms-auto">
            <div class="nav-item dropdown">
                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button"
                    data-bs-toggle="dropdown">
                    <i class="fas fa-user-circle me-2"></i>
                    {{ Auth::user()->name ?? 'Admin' }}
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i
                                class="fas fa-user me-2"></i>Profile</a></li>
                    <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>Pengaturan</a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <a class="dropdown-item" href="#" onclick="toggleTheme()">
                            <i class="fas fa-moon me-2 theme-icon"></i>
                            <span class="theme-text">Dark Mode</span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="#"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>
