<nav class="sidebar bg-primary" id="sidebar">
    <div class="p-3">
        <h4 class="text-white text-center mb-4">
            <i class="fas fa-tools me-2"></i>
            Kelola Servisan
        </h4>

        <ul class="nav nav-pills flex-column">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                    href="{{ route('tuser.tuser.dashboard') }}">
                    <i class="fas fa-tachometer-alt me-2"></i>
                    Dashboard
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('pelanggan.*') ? 'active' : '' }}"
                    href="{{ route('pelanggan.index') }}">
                    <i class="fas fa-users me-2"></i>
                    Kelola Pelanggan
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('servisan.*') ? 'active' : '' }}"
                    href="{{ route('servisan.index') }}">
                    <i class="fas fa-wrench me-2"></i>
                    Kelola Servisan
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('spareparts.*') ? 'active' : '' }}"
                    href="{{ route('spareparts.index') }}">
                    <i class="fas fa-cogs me-2"></i>
                    Kelola Spareparts
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#reportModal">
                    <i class="fas fa-chart-bar me-2"></i>
                    Laporan
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="#"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt me-2"></i>
                    Logout
                </a>
            </li>
        </ul>
    </div>
</nav>
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>
