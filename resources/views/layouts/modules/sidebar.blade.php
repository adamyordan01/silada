@php
    // $role = auth()->user()->role->name;
    $role = auth()->user()->role->name;
@endphp
<div class="main-sidebar">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            {{-- <a href="index.html">Stisla</a> --}}
            <a href="{{ route('dashboard') }}">
                <img src="{{ asset('assets/img/logo_silada.png') }}" alt="logo" width="150px" class="">
            </a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            {{-- <a href="index.html">St</a> --}}
            <a href="">
                <img src="{{ asset('assets/img/logo-atam.png') }}" alt="logo" width="35" class="">
            </a>
        </div>
        <ul class="sidebar-menu">
            <li class="{{ request()->is('dashboard') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('dashboard') }}">
                    <i class="fas fa-fire"></i> <span>Dashboard</span>
                </a>
            </li>

            <li class="menu-header">Arsip</li>
            <li class="{{ request()->is('archive') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('archive.index') }}">
                    <i class="fas fa-archive"></i> <span>Daftar Arsip</span>
                </a>
            </li>
            @if ($role == 'user')
                <li class="{{ request()->is('archive/incoming-letter') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('archive.incoming-letter') }}">
                        <i class="fas fa-folder"></i> <span>Surat Masuk</span>
                    </a>
                </li>
                <li class="{{ request()->is('archive/outgoing-letter') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('archive.outgoing-letter') }}">
                        <i class="fas fa-folder"></i> <span>Surat Keluar</span>
                    </a>
                </li>
            @endif

            @if ($role == 'ppats')
                <li class="{{ request()->is('archive/ajb') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('archive.ajb') }}">
                        <i class="fas fa-folder"></i> <span>AJB</span>
                    </a>
                </li>
                <li class="{{ request()->is('archive/aphb') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('archive.aphb') }}">
                        <i class="fas fa-folder"></i> <span>APHB</span>
                    </a>
                </li>
                <li class="{{ request()->is('archive/akta-hibah') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('archive.akta-hibah') }}">
                        <i class="fas fa-folder"></i> <span>Akta Hibah</span>
                    </a>
                </li>
                <li class="{{ request()->is('archive/aphgb') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('archive.aphgb') }}">
                        <i class="fas fa-folder"></i> <span>APHGB</span>
                    </a>
                </li>
            @endif

            @if ($role == 'admin')
                <li class="menu-header">SEKRETARIS KECAMATAN</li>
                <li class="{{ request()->is('archive/incoming-letter') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('archive.incoming-letter') }}?position=2">
                        <i class="fas fa-folder"></i> <span>Surat Masuk</span>
                    </a>
                </li>
                <li class="">
                    <a class="nav-link" href="#">
                        <i class="fas fa-folder"></i> <span>Surat Keluar</span>
                    </a>
                </li>

                <li class="menu-header">UMUM & KEPEGEGAWAIAN</li>
                <li class="">
                    <a class="nav-link" href="#">
                        <i class="fas fa-folder"></i> <span>Surat Masuk</span>
                    </a>
                </li>
                <li class="">
                    <a class="nav-link" href="#">
                        <i class="fas fa-folder"></i> <span>Surat Keluar</span>
                    </a>
                </li>

                <li class="menu-header">PERENCANAAN & KEUANGAN</li>
                <li class="">
                    <a class="nav-link" href="#">
                        <i class="fas fa-folder"></i> <span>Surat Masuk</span>
                    </a>
                </li>
                <li class="">
                    <a class="nav-link" href="#">
                        <i class="fas fa-folder"></i> <span>Surat Keluar</span>
                    </a>
                </li>

                <li class="menu-header">TATA PEMERINTAHAN</li>
                <li class="">
                    <a class="nav-link" href="#">
                        <i class="fas fa-folder"></i> <span>Surat Masuk</span>
                    </a>
                </li>
                <li class="">
                    <a class="nav-link" href="#">
                        <i class="fas fa-folder"></i> <span>Surat Keluar</span>
                    </a>
                </li>

                <li class="menu-header">PEMBERDAYAAN MASYARAKAT DAN KAMPUNG</li>
                <li class="">
                    <a class="nav-link" href="#">
                        <i class="fas fa-folder"></i> <span>Surat Masuk</span>
                    </a>
                </li>
                <li class="">
                    <a class="nav-link" href="#">
                        <i class="fas fa-folder"></i> <span>Surat Keluar</span>
                    </a>
                </li>

                <li class="menu-header">KESEJAHTERAAN RAKYAT DAN KEISTIMEWAAN ACEH</li>
                <li class="">
                    <a class="nav-link" href="#">
                        <i class="fas fa-folder"></i> <span>Surat Masuk</span>
                    </a>
                </li>
                <li class="">
                    <a class="nav-link" href="#">
                        <i class="fas fa-folder"></i> <span>Surat Keluar</span>
                    </a>
                </li>

                <li class="menu-header">PELAYANAN</li>
                <li class="">
                    <a class="nav-link" href="#">
                        <i class="fas fa-folder"></i> <span>Surat Masuk</span>
                    </a>
                </li>
                <li class="">
                    <a class="nav-link" href="#">
                        <i class="fas fa-folder"></i> <span>Surat Keluar</span>
                    </a>
                </li>
            @endif
            
            {{-- Master --}}
            @if ($role == 'admin')
                <li class="menu-header">Master Data</li>
                <li class="{{ request()->is('user*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('user.index') }}">
                        <i class="fas fa-users"></i> <span>Pengguna</span>
                    </a>
                </li>

                <li class="{{ request()->is('role*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('role.index') }}">
                        <i class="fas fa-user-tag"></i> <span>Role</span>
                    </a>
                </li>

                {{-- <li class="menu-header">Dokumen</li> --}}
                <li class="{{ request()->is('document-type*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('document-type.index') }}">
                        <i class="fas fa-file-alt"></i> <span>Jenis Dokumen</span>
                    </a>
                </li>
            @endif
        </ul>
    </aside>
</div>