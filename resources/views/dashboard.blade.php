@extends('../layouts/main')

@section('content')

    <body class="py-5">
        <main>
            <div class="page-title">
                <div class="page-title__left">
                    <h1>Dashboard</h1>
                </div>
            </div>
            <div class="page-main">
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel">
                            <div class="panel-header">
                                <h2 class="panel-title">Selamat datang, {{ Auth::user()->name }}</h2>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-8">
                                        <p>Hak Akses: {{ Auth::user()->role->name }} <br> Terakhir password diubah:
                                            {{ CarbonParse(Auth::user()->password_change_date, 'd F Y | H:i') }}</p>
                                    </div>
                                    <div class="col text-right"><a class="btn btn--white"
                                            href="{{ route('setting') }}">Ubah Password</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="panel">
                            <div class="panel-body">
                                <div class="summary">
                                    <h2 class="summary-label">Pengunjung Bulan Ini</h2><span
                                        class="summary-value">{{ $pasien }}<i
                                            class="summary-value-text">pasien</i></span>
                                    <div class="progress">
                                        <div class="progress-bar" style="width: {{ ($pasien / 100) * 100 }}%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="panel">
                            <div class="panel-body">
                                <div class="summary">
                                    <h2 class="summary-label">Pasien Terapi Bulan Ini</h2><span
                                        class="summary-value">{{ $terapi }} <i
                                            class="summary-value-text">pasien</i></span>
                                    <div class="progress">
                                        <div class="progress-bar" style="width: {{ ($terapi / 100) * 100 }}%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="panel">
                            <div class="panel-body">
                                <div class="summary">
                                    <h2 class="summary-label">Dokter Kunjungan Bulan Ini</h2><span
                                        class="summary-value">{{ $kunjungan }} <i
                                            class="summary-value-text">pasien</i></span>
                                    <div class="progress">
                                        <div class="progress-bar" style="width: {{ ($kunjungan / 100) * 100 }}%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="panel">
                            <div class="panel-body">
                                <div class="summary">
                                    <h2 class="summary-label">Pengunjung Hari Ini</h2><span
                                        class="summary-value">{{ $pasienHariIni }}
                                        <i class="summary-value-text">pasien</i></span>
                                    <div class="progress">
                                        <div class="progress-bar" style="width: {{ ($pasienHariIni / 100) * 100 }}%">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="panel">
                            <div class="panel-body">
                                <div class="summary">
                                    <h2 class="summary-label">Kunjungan Pasien Minggu Ini</h2><span
                                        class="summary-value">{{ $pasienMingguIni }}
                                        <i class="summary-value-text">pasien</i></span>
                                    <div class="progress">
                                        <div class="progress-bar" style="width: {{ ($pasienMingguIni / 100) * 100 }}%">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </body>
@endsection
