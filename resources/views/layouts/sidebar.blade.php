<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item">
            <a class="nav-link" href="{{route(get_role_name().'.dashboard')}}">
                <i class="icon-grid menu-icon"></i>
                <span class="menu-title">Dashboard</span>
            </a>
        </li>
        @if (has_role('ADMIN'))
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
             <li class="nav-item">
                 <a class="nav-link" href="{{ route('admin.lowongan-magang.index') }}">
                    <i class="mdi mdi-clipboard-text menu-icon"></i>
                    <span class="menu-title">Lowongan Magang</span>
                </a>
            </li>
            <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.kegiatan-magang.index') }}">
                    <i class="mdi mdi-cogs menu-icon"></i>
                    <span class="menu-title">Kegiatan Magang</span>
                </a>
            </li>
        @endif
        @if (has_role('DOSEN'))
            <li class="nav-item">
                <a class="nav-link" href="{{route('dosen.bimbingan-magang.index')}}">
                    <i class="mdi mdi-account-multiple menu-icon"></i>
                    <span class="menu-title">Bimbingan Magang</span>
                </a>
            </li>
        @endif
        @if (has_role('MAHASISWA'))
            <li class="nav-item">
                <a class="nav-link" href="{{route('mahasiswa.lowongan-magang.index')}}">
                    <i class="mdi mdi-bag-checked menu-icon"></i>
                    <span class="menu-title">Lowongan Magang</span>
                </a>
            </li>
            <li class="nav-item">
                 <a class="nav-link" href="{{ route('mahasiswa.pengajuan-magang.index') }}">
                    <i class="mdi mdi-clipboard-text menu-icon"></i>
                    <span class="menu-title">Pengajuan Magang</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{route('mahasiswa.evaluasi-magang.index')}}">
                    <i class="mdi mdi mdi-briefcase-check  menu-icon"></i>
                    <span class="menu-title">Magang</span>
                </a>
            </li>
        @endif
        @if (has_any_role('MAHASISWA', 'DOSEN'))

        @endif
    </ul>
</nav>
