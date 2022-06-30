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
                                <h2 class="panel-title">Selamat datang, Faisal Nurmansyah</h2>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-8">
                                        <p>Hak Akses: Superadmin <br> Terakhir password diubah: 19 Nov 2019 | 20:29</p>
                                    </div>
                                    <div class="col text-right"><a class="btn btn--white" href="#">Ubah Password</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="panel">
                            <div class="panel-body">
                                <div class="summary">
                                    <h2 class="summary-label">Pengunjung Bulan Ini</h2><span class="summary-value">20 <i
                                            class="summary-value-text">pasien</i></span>
                                    <div class="progress">
                                        <div class="progress-bar" style="width: 20%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="panel">
                            <div class="panel-body">
                                <div class="summary">
                                    <h2 class="summary-label">Pasien Terapi Bulan Ini</h2><span class="summary-value">10 <i
                                            class="summary-value-text">pasien</i></span>
                                    <div class="progress">
                                        <div class="progress-bar" style="width: 10%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="panel">
                            <div class="panel-body">
                                <div class="summary">
                                    <h2 class="summary-label">Dokter Kunjungan Bulan Ini</h2><span class="summary-value">10
                                        <i class="summary-value-text">pasien</i></span>
                                    <div class="progress">
                                        <div class="progress-bar" style="width: 10%"></div>
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
