<div class="navbar-bg custom-navbar-bg"></div>
<nav class="navbar navbar-expand-lg main-navbar custom-navbar">
    <form class="form-inline mr-auto">
        <ul class="navbar-nav mr-3">
            <li><a href="#" data-toggle="sidebar" style="color: #a0a0a0" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
        </ul>
    </form>
<ul class="navbar-nav navbar-right">
    <li class="dropdown"><a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">

    {{-- <img alt="image" src="{{ asset('assets/img/avatar/avatar-2.png') }}" class="rounded-circle mr-1"> --}}
    @if (auth()->user()->photo != '')
        <img alt="image" src="{{ asset('profiles/'.auth()->user()->photo) }}" class="rounded-circle mr-1">
    @else
        <img alt="image" src="{{ asset('assets') }}/img/avatar/avatar-3.png" class="rounded-circle mr-1">
    @endif

    <div class="d-sm-none d-lg-inline-block text-dark">Hi, {{ Auth::user()->name }}</div></a>
    <div class="dropdown-menu dropdown-menu-right">
        <a href="{{ route('profile.index') }}" class="dropdown-item has-icon">
            <i class="far fa-user"></i> Profile
        </a>
        <a href="{{ route('change-password.index') }}" class="dropdown-item has-icon">
            <i class="fas fa-unlock-alt"></i> Change Password
        </a>
        {{-- <a href="features-activities.html" class="dropdown-item has-icon">
            <i class="fas fa-bolt"></i> Activities
        </a>
        <a href="features-settings.html" class="dropdown-item has-icon">
            <i class="fas fa-cog"></i> Settings
        </a> --}}
        <div class="dropdown-divider"></div>
        <a href="{{ route('logout') }}" class="dropdown-item has-icon text-danger" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>

        <form action="{{ route('logout') }}" method="POST" id="logout-form">
            @csrf
        </form>
    </div>
    </li>
</ul>
</nav>