@extends('../layouts/main')

@section('content')

    <body class="py-5">
        <main>
            <div class="page-title">
                <div class="page-title__left">
                    <h1>Pengaturan</h1>
                </div>
            </div>
            <div class="page-main">
                <div class="row">
                    <div class="col-md-6">
                        <div class="emails">
                            <div class="contact-group" style="display: block;">
                                <div class="contact-group__wrap">
                                    <div class="item"><a class="name" href="{{ route('create-setting') }}">Rubah
                                            Preferensi anda</a>
                                        <div class="meta"><span class="title">Klik untuk mengganti data preferensi user
                                                anda.</span></div>
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
