@extends('../layouts/base')
@section('body')

    <body>
        <div id="wrap">
            <div class="web-wrapper" id="page">
                <main>
                    <div class="page-login">
                        <div class="page-login__box">
                            <div class="page-login__logo"><img src="images/logo-vmm.png" /></div>

                            <form method="POST" action="{{ route('login-store') }}">
                                @csrf
                                @if ($errors->has('username'))
                                    <p class="text-danger">Username dan password anda salah</p>
                                @endif
                                <div class="form-group">
                                    <label>Username</label>
                                    <input class="form-control" type="text" name="username" />
                                </div>
                                <div class="form-group">
                                    <label>Password</label>
                                    <input class="form-control" type="password" name="password" />
                                </div>
                                <div class="form-action">
                                    <button class="btn btn--primary btn--block" type="submit">Masuk</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </main>
                <div class="back-to-top"></div>
            </div>
        </div>
    </body>
@endsection
