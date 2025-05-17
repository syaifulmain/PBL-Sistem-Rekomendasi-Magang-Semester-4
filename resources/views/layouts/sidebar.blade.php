<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        @if (has_role('ADMIN'))
            <li class="nav-item">
                <a class="nav-link" href="{{route('admin.dashboard')}}">
                    <i class="icon-grid menu-icon"></i>
                    <span class="menu-title">Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{route('admin.manajemen-pengguna.index')}}">
                    <i class="icon-head menu-icon"></i>
                    <span class="menu-title">Manajemen Pengguna</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{route('admin.perusahaan.index')}}">
                    <i class="icon-briefcase menu-icon"></i>
                    <span class="menu-title">Mitra Perusahaan</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.periode-magang.index') }}">
                    <i class="mdi mdi-calendar-clock menu-icon"></i>
                    <span class="menu-title">Periode Magang</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.program-studi.index') }}">
                    <i class="mdi mdi-school menu-icon"></i>
                    <span class="menu-title">Program Studi</span>
                </a>
            </li>
        @endif
        @if (has_role('DOSEN'))
            <li class="nav-item">
                <a class="nav-link" href="pages/documentation/documentation.html">
                    <i class="icon-paper menu-icon"></i>
                    <span class="menu-title">DOSEN</span>
                </a>
            </li>
        @endif
        @if (has_any_role(['MAHASISWA', 'DOSEN']))
            <li class="nav-item">
                <a class="nav-link" href="pages/documentation/documentation.html">
                    <i class="icon-paper menu-icon"></i>
                    <span class="menu-title">MAHASISWA</span>
                </a>
            </li>
        @endif
    </ul>
</nav>
