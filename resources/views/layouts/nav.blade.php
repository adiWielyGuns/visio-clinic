<header class="header" id="page_header">
    <div class="header__wrap"><a class="header__logo" href="{{ url('/dashboard') }}"><img
                src="{{ asset('images/logo-vmm.png') }}" alt="Mekari Design" width="147" height="48" /></a></div>
</header>
<nav class="sidebar">
    <div class="sidebar__wrap">
        <ul class="main-menu">
            <li class="main-menu__item {{ Request::segment(1) == 'dashboard' ? 'active' : '' }}"><a
                    class="main-menu__link" href="dashboard.html">
                    <div class="icon"><img src="{{ asset('images/ic-dashboard.svg') }}" /></div>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="main-menu__item {{ Request::segment(1) == 'staff' ? 'active' : '' }}"><a
                    class="main-menu__link" href="{{ route('staff') }}">
                    <div class="icon"><img src="{{ asset('images/ic-subscribers.svg') }}" /></div><span>Staff</span>
                </a>
            </li>
            <li class="main-menu__item {{ Request::segment(1) == 'jadwal-dokter' ? 'active' : '' }}"><a
                    class="main-menu__link" href="{{ route('jadwal-dokter') }}">
                    <div class="icon"><img src="{{ asset('images/ic-reports.svg') }}" /></div><span>Jadwal
                        Dokter</span>
                </a>
            </li>
            <li class="main-menu__item {{ Request::segment(1) == 'pasien' ? 'active' : '' }}"><a
                    class="main-menu__link" href="{{ route('pasien') }}">
                    <div class="icon"><img src="{{ asset('images/ic-contact.svg') }}" /></div><span>Pasien</span>
                </a>
            </li>

            <li class="main-menu__item {{ Request::segment(1) == 'pemeriksaan' ? 'active' : '' }}"><a
                    class="main-menu__link" href="{{ route('pemeriksaan') }}">
                    <div class="icon"><img src="{{ asset('images/ic-payment.svg') }}" /></div>
                    <span>Pemeriksaan</span>
                </a>
            </li>
            <li class="main-menu__item {{ Request::segment(1) == 'pembayaran' ? 'active' : '' }}"><a
                    class="main-menu__link" href="{{ route('pembayaran') }}">
                    <div class="icon"><img src="{{ asset('images/ic-payment.svg') }}" /></div>
                    <span>Pembayaran</span>
                </a>
            </li>
            <li class="main-menu__item {{ Request::segment(1) == 'setting' ? 'active' : '' }}"><a
                    class="main-menu__link" href="{{ route('setting') }}">
                    <div class="icon"><img src="{{ asset('images/ic-settings.svg') }}" /></div>
                    <span>Pengaturan</span>
                </a></li>
        </ul>
        <div class="user-snapshot dropdown">
            <div class="user-snapshot__toggle" data-toggle="dropdown">
                <div class="user-snapshot__avatar"><img src="{{ asset('images/img-avatar.png') }}" alt="Avatar"
                        width="32" height="32" /></div>
                <div class="user-snapshot__wrap"><span class="username">Faisal Nurmansyah</span><span
                        class="title">Admin</span></div>
            </div>
            <form action="{{ route('logout') }}" method="POST" id="logout">@csrf</form>
            <div class="dropdown-menu"><a class="dropdown-item" href="javascript:;"
                    onclick="$('#logout').submit()">Logout</a></div>
        </div>
    </div>
</nav>
