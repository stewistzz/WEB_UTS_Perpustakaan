<div class="sidebar">
    <!-- Sidebar Search Form -->
    <div class="form-inline mt-2">
        <div class="input-group" data-widget="sidebar-search">
            <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
            <div class="input-group-append">
                <button class="btn btn-sidebar">
                    <i class="fas fa-search fa-fw"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

            
            @if (Auth::user() && Auth::user()->role === 'admin')
            <!-- Dashboard -->
            <li class="nav-item">
                <a href="{{ url('/') }}" class="nav-link {{ $activeMenu == 'dashboard_admin' ? 'active' : '' }}">
                    <i class="nav-icon fas fa-home"></i>
                    <p>Dashboard</p>
                </a>
            </li>
                <!-- Admin Only: Data Pengguna -->
                <li class="nav-header">Data Pengguna</li>
                <li class="nav-item">
                    <a href="{{ url('/user') }}" class="nav-link {{ $activeMenu == 'user' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user-shield"></i>
                        <p>Data User</p>
                    </a>
                </li>

                <!-- Admin Only: Data Barang -->
                <li class="nav-header">Data Barang</li>
                <li class="nav-item">
                    <a href="{{ url('/book') }}" class="nav-link {{ $activeMenu == 'book' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-book"></i>
                        <p>Data Buku</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('/category') }}" class="nav-link {{ $activeMenu == 'category' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tags"></i>
                        <p>Data Kategori</p>
                    </a>
                </li>

                <!-- Admin Only: Peminjaman -->
                <li class="nav-header">Data Peminjaman</li>
                <li class="nav-item">
                    <a href="{{ url('/borrow') }}" class="nav-link {{ $activeMenu == 'borrow' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-exchange-alt"></i>
                        <p>Peminjaman Buku</p>
                    </a>
                </li>
            @else
            <li class="nav-item">
                <a href="{{ url('/') }}" class="nav-link {{ $activeMenu == 'dashboard_mahasiswa' ? 'active' : '' }}">
                    <i class="nav-icon fas fa-home"></i>
                    <p>Dashboard</p>
                </a>
            </li>
                <!-- User Only: Peminjaman -->
                <li class="nav-header">Data Peminjaman</li>
                <li class="nav-item">
                    <a href="{{ url('/booking') }}" class="nav-link {{ $activeMenu == 'booking' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-book-reader"></i>
                        <p>Peminjaman Buku</p>
                    </a>
                </li>
            @endif

            <!-- Logout -->
            <li class="nav-item mt-3">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="nav-link btn btn-link text-start text-danger"
                        style="color: inherit; text-decoration: none;">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>Logout</p>
                    </button>
                </form>
            </li>
        </ul>
    </nav>
</div>
