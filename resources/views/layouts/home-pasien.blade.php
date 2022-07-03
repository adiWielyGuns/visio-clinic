@extends('../layouts/base')

@section('body')

    <body class="py-5">
        <div id="wrap">
            <div class="web-wrapper" id="page">
                <main>
                    <div class="container-fluid auth-page">
                        <div class="row">
                            <div class="col-lg-10 left-wrap">
                                <div class="image" style="background-image: url(images/masthead-home.jpg)"></div>
                                <div class="description">
                                    <div class="feature1">
                                        <h3>Menangani</h3>
                                        <ul>
                                        <li>Sakit punggung</li>
                                        <li>Sakit lutut</li>
                                        <li>Sakit persendian</li>
                                        <li>Pemuliah Stroke</li>
                                        <li>Cedera Olahraga</li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="address">
                                    <p>Jl. Jelidro No. 52 Sambikerep Sby - 081332000593</p>
                                </div>
                            </div>
                            <div class="col-lg-2 right-wrap">
                                <div class="form-wrap">
                                    <div class="box">
                                        <div class="imglogo"><img src="images/logo-vmm.png"/></div>
                                        <h1 class="logo">Fisio Mandiri Medika</h1>
                                        <p>Masuk Untuk Melanjutkan</p>
                                        <form class="login" action="{{ route('login-pasien') }}" method="post">
                                            @csrf
                                            <div class="form-group empw">
                                                <input class="form-control top" id="id_pasien" type="text"
                                                    name="id_pasien" placeholder="No. Rekam Medis" />
                                                <input class="form-control bot" id="tanggal_lahir" type="text"
                                                    name="tanggal_lahir" placeholder="tahun-bulan-tanggal" />
                                            </div>
                                            @if ($errors->has('credential'))
                                                <p style="color: red">Data yang Anda masukan salah.</p>
                                            @endif
                                            <div class="form-action">
                                                <button
                                                    class="btn btn--primary btn--block btn-lgn btn btn-primary btn-block"
                                                    type="submit">Masuk</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
                <div class="back-to-top"></div>
            </div>
        </div>
    </body>
@endsection
