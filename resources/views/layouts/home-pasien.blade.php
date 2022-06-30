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
                                        <h3>Fitur Rekam Medis</h3>
                                        <p>
                                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Temporibus dolor maxime
                                            id
                                            magni laborum officiis, maiores minus quo quam corporis laboriosam inventore,
                                            suscipit nostrum deleniti alias deserunt saepe voluptatibus vel?
                                        </p>
                                        <p>
                                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Temporibus dolor maxime
                                            id
                                            magni laborum officiis, maiores minus quo quam corporis laboriosam inventore,
                                            suscipit nostrum deleniti alias deserunt saepe voluptatibus vel?p Lorem ipsum
                                            dolor
                                            sit amet consectetur adipisicing elit. Temporibus dolor maxime id magni laborum
                                            officiis, maiores minus quo quam corporis laboriosam inventore, suscipit nostrum
                                            deleniti alias deserunt saepe voluptatibus vel?
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2 right-wrap">
                                <div class="form-wrap">
                                    <div class="box">
                                        <h1 class="logo">Visio Mandiri Medika</h1>
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
