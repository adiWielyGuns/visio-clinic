<header class="header" id="page_header">
    <div class="header__wrap"><a class="header__logo" href="{{ url('/dashboard') }}"><img
                src="{{ asset('images/logo-vmm.png') }}" alt="Mekari Design" width="147" height="48" /></a>
        <div class="header__right">
            <div class="user-snapshot dropdown">
                <div class="user-snapshot__toggle" data-toggle="dropdown">
                    <div class="user-snapshot__avatar"><img src="{{ asset('images/icon-notif.svg') }}" alt="Avatar"
                            width="32" height="32" /></div>
                </div>
                <div class="dropdown-menu" id="dropdown-menu" style="width: 500px">
                    @forelse (Auth::user()->unreadNotifications as $item)
                        <a class="dropdown-item" href="{{ $item['data']['url'] }}?notification_id={{ $item->id }}"
                            style="white-space: normal">
                            <span>
                                <b> {{ $item['data']['jenis'] }} </b>
                                <small>
                                    {{ CarbonParse($item->created_at, 'd/m/Y H:i') }}

                                </small>
                            </span>

                            <br>

                            <p>{{ $item['data']['message'] }}</p>
                        </a>
                    @empty
                        <a class="dropdown-item" href="javascript:;" style="white-space: normal">
                            <p class="tidak-ada-notifikasi collapse show" style="text-align: center">Tidak ada notifikasi</p>
                        </a>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</header>
<nav class="sidebar">
    <div class="sidebar__wrap">
        <ul class="main-menu">

            <li class="main-menu__item {{ Request::segment(1) == 'dashboard' ? 'active' : '' }}"><a
                    class="main-menu__link" href="{{ route('dashboard') }}">
                    <div class="icon"><img src="{{ asset('images/ic-dashboard.svg') }}" /></div>
                    <span>Dashboard</span>
                </a>
            </li>
            @if (Auth::user()->role->name == 'SuperAdmin')
                <li class="main-menu__item {{ Request::segment(1) == 'staff' ? 'active' : '' }}"><a
                        class="main-menu__link" href="{{ route('staff') }}">
                        <div class="icon"><img src="{{ asset('images/ic-subscribers.svg') }}" /></div>
                        <span>Staff</span>
                    </a>
                </li>
            @endif
            @if (Auth::user()->role->name == 'SuperAdmin' or Auth::user()->role->name == 'Terapis')
                <li class="main-menu__item {{ Request::segment(1) == 'jadwal-dokter' ? 'active' : '' }}"><a
                        class="main-menu__link" href="{{ route('jadwal-dokter') }}">
                        <div class="icon"><img src="{{ asset('images/ic-reports.svg') }}" /></div><span>Jadwal
                            Dokter</span>
                    </a>
                </li>
            @endif

            @if (Auth::user()->role->name == 'SuperAdmin' or Auth::user()->role->name == 'Perawat')
                <li class="main-menu__item {{ Request::segment(1) == 'pasien' ? 'active' : '' }}"><a
                        class="main-menu__link" href="{{ route('pasien') }}">
                        <div class="icon"><img src="{{ asset('images/ic-contact.svg') }}" /></div>
                        <span>Pasien</span>
                    </a>
                </li>
            @endif

            @if (Auth::user()->role->name == 'SuperAdmin' or Auth::user()->role->name == 'Terapis')
                <li class="main-menu__item {{ Request::segment(1) == 'pemeriksaan' ? 'active' : '' }}"><a
                        class="main-menu__link" href="{{ route('pemeriksaan') }}">
                        <div class="icon"><img src="{{ asset('images/ic-pages.svg') }}" /></div>
                        <span>Pemeriksaan</span>
                    </a>
                </li>
            @endif
            @if (Auth::user()->role->name == 'SuperAdmin' or Auth::user()->role->name == 'Perawat')
                <li class="main-menu__item {{ Request::segment(1) == 'pembayaran' ? 'active' : '' }}"><a
                        class="main-menu__link" href="{{ route('pembayaran') }}">
                        <div class="icon"><img src="{{ asset('images/ic-payment.svg') }}" /></div>
                        <span>Pembayaran</span>
                    </a>
                </li>
            @endif

            @if (Auth::user()->role->name == 'SuperAdmin' or Auth::user()->role->name == 'Perawat')
                <li class="main-menu__item {{ Request::segment(1) == 'item' ? 'active' : '' }}"><a
                        class="main-menu__link" href="{{ route('item') }}">
                        <div class="icon"><img src="{{ asset('images/ic-item.svg') }}" /></div>
                        <span>Item</span>
                    </a>
                </li>
            @endif

            <li class="main-menu__item {{ Request::segment(1) == 'setting' ? 'active' : '' }}"><a
                    class="main-menu__link" href="{{ route('setting') }}">
                    <div class="icon"><img src="{{ asset('images/ic-settings.svg') }}" /></div>
                    <span>Pengaturan</span>
                </a>
            </li>
        </ul>
        <div class="user-snapshot dropdown">
            <div class="user-snapshot__toggle" data-toggle="dropdown">
                <div class="user-snapshot__avatar"><img src="{{ asset('images/img-avatar.png') }}" alt="Avatar"
                        width="32" height="32" /></div>
                <div class="user-snapshot__wrap"><span class="username">{{ Auth::user()->name }}</span><span
                        class="title">{{ Auth::user()->role->name }}</span></div>
            </div>
            <form action="{{ route('logout') }}" method="POST" id="logout">@csrf</form>
            <div class="dropdown-menu"><a class="dropdown-item" href="javascript:;"
                    onclick="$('#logout').submit()">Logout</a></div>
        </div>
    </div>
</nav>
