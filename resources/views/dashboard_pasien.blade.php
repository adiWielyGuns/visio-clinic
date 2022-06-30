@extends('../layouts/base')
@section('body')

    <body>
        <div id="wrap">
            <div class="web-wrapper" id="page">
                <main>
                    <div class="container-fluid auth-page pasien-daftar">
                        <div class="row">
                            <div class="col-lg-8 left-wrap">
                                <div class="image" style="background-image: url(images/masthead-home.jpg)"></div>
                                <div class="description">
                                    <div class="feature1">
                                        <h3>Fitur Rekam Medis</h3>
                                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Temporibus dolor maxime
                                            id magni laborum officiis, maiores minus quo quam corporis laboriosam inventore,
                                            suscipit nostrum deleniti alias deserunt saepe voluptatibus vel?</p>
                                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Temporibus dolor maxime
                                            id magni laborum officiis, maiores minus quo quam corporis laboriosam inventore,
                                            suscipit nostrum deleniti alias deserunt saepe voluptatibus vel?p Lorem ipsum
                                            dolor sit amet consectetur adipisicing elit. Temporibus dolor maxime id magni
                                            laborum officiis, maiores minus quo quam corporis laboriosam inventore, suscipit
                                            nostrum deleniti alias deserunt saepe voluptatibus vel?</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 right-wrap">
                                <div class="form-wrap">
                                    <form action="{{ route('logout-pasien') }}" method="POST" id="logout">@csrf</form>
                                    <div class="box">
                                        <h1 class="logo">Visio Mandiri Medika</h1>
                                        <div class="akun"><span>Selamat Datang,
                                                {{ Auth::guard('pasien')->user()->name }}.</span><a class="btn btn--link"
                                                onclick="$('#logout').submit()">Keluar</a></div>
                                        <div class="page-tabs">
                                            <ul class="nav">
                                                <li class="nav__item"><a class="nav__link active" href="#reservasi"
                                                        data-toggle="tab">Reservasi</a></li>
                                                <li class="nav__item"><a class="nav__link" href="#reservasid"
                                                        data-toggle="tab">Panggil Dokter</a></li>
                                                <li class="nav__item"><a class="nav__link" href="#antrian"
                                                        data-toggle="tab">Antrian</a></li>
                                                <li class="nav__item"><a class="nav__link" href="#pembayaran"
                                                        data-toggle="tab">Pembayaran</a></li>
                                            </ul>
                                        </div>
                                        <div class="tab-content">
                                            <div class="tab-pane fade active show" id="reservasi">
                                                <p style="max-width: 450px;color: #333A47;margin: 1rem auto;">Reservasi yang
                                                    di lakukan untuk minggu berikutnya, sehingga reservasi dapat dilakukan 1
                                                    minggu sebelum hari berobat.</p>
                                                <form class="form--add-contact" method="POST"
                                                    action="{{ route('store-reservasi') }}" id="form-reservasi">
                                                    @csrf
                                                    <div class="row justify-content-center">
                                                        <div class="col-md-8">
                                                            <div class="panel-body reserv">
                                                                <div class="form-group dp">
                                                                    <label>Pilih Dokter</label>
                                                                    <select class="select select-contact-group"
                                                                        id="dokter_id_reservasi" name="dokter_id"
                                                                        title="Pilih Dokter" required="required"
                                                                        onchange="getJadwalDokter('reservasi')">
                                                                        @foreach ($dokter as $item)
                                                                            <option value="{{ $item->id }}">
                                                                                {{ $item->name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="form-group collapse" id="jadwal_dokter_id_div">
                                                                    <label>Pilih Hari</label>
                                                                    <select class="select select-contact-group"
                                                                        id="jadwal_dokter_id_reservasi"
                                                                        onchange="changeJadwalDokter(this)"
                                                                        name="jadwal_dokter_id" title="Pilih Hari"
                                                                        required="required">

                                                                    </select>
                                                                    <input type="hidden" value="reservasi" name="param">
                                                                    <input type="hidden" value="" id="hari_reservasi"
                                                                        name="hari">
                                                                </div>
                                                                <div class="form-action"><a
                                                                        class="btn btn--primary btn--block"
                                                                        href="javascript:;"
                                                                        onclick="store('reservasi')">Daftar</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="tab-pane fade" id="reservasid">
                                                <p style="max-width: 450px;color: #333A47;margin: 1rem auto;">Panggilan
                                                    Dokter untuk minggu berikutnya dari minggu pendaftaran dan hanya dapat
                                                    dilakukan oleh pasien yang terdaftar dan harus berdomisili di Surabaya.
                                                </p>
                                                <form class="form--add-contact" method="POST"
                                                    action="{{ route('store-reservasi') }}" id="form-panggilan">
                                                    @csrf
                                                    <div class="row justify-content-center">
                                                        <div class="col-md-8">
                                                            <div class="panel-body reserv">
                                                                <div class="form-group dp">
                                                                    <label>Pilih Dokter</label>
                                                                    <select class="select select-contact-group"
                                                                        id="dokter_id_panggilan" name="dokter_id"
                                                                        title="Pilih Dokter" required="required"
                                                                        onchange="getJadwalDokter('panggilan')">
                                                                        @foreach ($dokter as $item)
                                                                            <option value="{{ $item->id }}">
                                                                                {{ $item->name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="form-group collapse"
                                                                    id="jadwal_dokter_id_panggilan_div">
                                                                    <label>Pilih Hari</label>
                                                                    <select class="select select-contact-group"
                                                                        id="jadwal_dokter_id_panggilan"
                                                                        onchange="changeJadwalDokter(this)"
                                                                        name="jadwal_dokter_id" title="Pilih Hari"
                                                                        required="required">

                                                                    </select>
                                                                    <input type="hidden" value="panggilan"
                                                                        name="param">
                                                                    <input type="hidden" value=""
                                                                        id="hari_panggilan" name="hari">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>No. Telephon</label>
                                                                    <input class="form-control" type="text"
                                                                        name="telp"
                                                                        value="{{ Auth::guard('pasien')->user()->telp }}" />
                                                                </div>
                                                                <div class="form-group">
                                                                    <label> Alamat</label>
                                                                    <textarea class="form-control" type="text" name="alamat">{{ Auth::guard('pasien')->user()->alamat }}</textarea>
                                                                </div>
                                                                <div class="form-action"><a
                                                                        class="btn btn--primary btn--block"
                                                                        href="javascript:;"
                                                                        onclick="store('panggilan')">Daftar</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="tab-pane fade" id="antrian">
                                                <p
                                                    style="text-align: right;margin-top: 1rem;font-size: 21px;line-height: 1.5em;">
                                                    <span>Antrian Saat ini: </span> <b>04</b>
                                                </p>
                                                <div class="table">
                                                    <table>
                                                        <thead>
                                                            <tr>
                                                                <th class="check-all" width="5%"><span>No.</span></th>
                                                                <th><span>Tgl. Antrian</span></th>
                                                                <th><span>Nama Dokter</span></th>
                                                                <th><span>No. Antrian</span></th>
                                                                <td class="has-action"></td>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>1</td>
                                                                <td>3 Mei 2021</td>
                                                                <td>Dr. Abdullah</td>
                                                                <td>01</td>
                                                                <td><a class="btn-edit" href="#modalDelete"
                                                                        data-toggle="modal"><img class="svg"
                                                                            src="images/ic-delete.svg" /></a></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="pembayaran">
                                                <div class="table">
                                                    <table>
                                                        <thead>
                                                            <tr>
                                                                <th class="check-all" width="5%"><span>No.</span></th>
                                                                <th><span>No. Invoice</span></th>
                                                                <th><span>Total Tagian</span></th>
                                                                <td class="has-action"></td>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>1</td>
                                                                <td>202012-00023</td>
                                                                <td>200.000</td>
                                                                <td><a class="btn btn--primary" href="#modalDelete"
                                                                        data-toggle="modal">Bayar</a></td>
                                                            </tr>
                                                            <tr>
                                                                <td>2</td>
                                                                <td>202012-00024</td>
                                                                <td>100.000</td>
                                                                <td><span>Terbayar</span></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
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

@section('script')
    <script>
        function changeJadwalDokter(par) {
            $('#hari_reservasi').val($(par).find('option:selected').data('hari'));
        }

        function getJadwalDokter(param) {
            $.ajax({
                url: '{{ route('get-jadwal-dokter') }}',
                data: {
                    id: function() {
                        switch (param) {
                            case 'reservasi':
                                return $('#dokter_id_reservasi').val();
                                break;
                            case 'panggilan':
                                return $('#dokter_id_panggilan').val();
                                break;
                            default:
                                break;
                        }
                    },
                },
                type: 'get',
                success: function(data) {

                    switch (param) {
                        case 'reservasi':
                            $('#jadwal_dokter_id_reservasi_div').removeClass('collapse')
                            data.data.forEach((d, i) => {
                                var option = '<option data-hari="' + d.hari + '" value="' + d.id +
                                    '">' + d
                                    .hari + '(' + d.sisa_kuota + ')' + '</option>';

                                $('#jadwal_dokter_id_reservasi').append(option);

                            });

                            $('#jadwal_dokter_id_reservasi').selectpicker('refresh');
                            $('#jadwal_dokter_id_reservasi').selectpicker('val', null);
                            break;
                        case 'panggilan':
                            $('#jadwal_dokter_id_panggilan_div').removeClass('collapse')
                            data.data.forEach((d, i) => {
                                var option = '<option data-hari="' + d.hari + '" value="' + d.id +
                                    '">' + d
                                    .hari + '(' + d.sisa_kuota + ')' + '</option>';

                                $('#jadwal_dokter_id_panggilan').append(option);

                            });

                            $('#jadwal_dokter_id_panggilan').selectpicker('refresh');
                            $('#jadwal_dokter_id_panggilan').selectpicker('val', null);
                            break;
                        default:
                            break;
                    }

                },
                error: function(data) {
                    getJadwalDokter(param);
                }
            });
        }

        function store(param) {
            switch (param) {
                case 'reservasi':
                    var validation = 0;
                    if ($('#dokter_id_reservasi').val() == null || $('#dokter_id_reservasi').val() == '') {
                        $('#dokter_id_reservasi').addClass('is-invalid');
                        validation++
                    }

                    if ($('#jadwal_dokter_id_reservasi').val() == null || $('#jadwal_dokter_id_reservasi').val() == '' || $(
                            '#jadwal_dokter_id_reservasi').val() ==
                        undefined) {
                        $('#jadwal_dokter_id_reservasi').addClass('is-invalid');
                        validation++
                    }

                    if (validation != 0) {
                        Swal.fire({
                            title: 'Oops Something Wrong',
                            html: 'Semua data harus diisi',
                            icon: "warning",
                        });
                        return false;
                    }

                    overlay(true);
                    $('#form-reservasi').submit()
                    break;
                case 'panggilan':
                    var validation = 0;
                    if ($('#dokter_id_panggilan').val() == null || $('#dokter_id_panggilan').val() == '') {
                        $('#dokter_id_panggilan').addClass('is-invalid');
                        validation++
                    }

                    if ($('#jadwal_dokter_id_panggilan').val() == null || $('#jadwal_dokter_id_panggilan').val() == '' || $(
                            '#jadwal_dokter_id_panggilan').val() ==
                        undefined) {
                        $('#jadwal_dokter_id_panggilan').addClass('is-invalid');
                        validation++
                    }

                    if (validation != 0) {
                        Swal.fire({
                            title: 'Oops Something Wrong',
                            html: 'Semua data harus diisi',
                            icon: "warning",
                        });
                        return false;
                    }

                    overlay(true);
                    $('#form-panggilan').submit()
                    break;
                default:
                    break;
            }
        }
    </script>
@endsection
