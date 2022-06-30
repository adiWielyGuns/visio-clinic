@extends('../layouts/base')
@section('body')

    <body>
        <div class="loading style-2" id="loading">
            <div class="loading-wheel"></div>
        </div>
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
                                                <li class="nav__item"><a class="nav__link" href="#rekam_medis"
                                                        data-toggle="tab">Rekam Medis</a></li>
                                            </ul>
                                        </div>
                                        <div class="tab-content">
                                            <div class="tab-pane fade active show" id="reservasi">
                                                <p style="max-width: 450px;color: #333A47;margin: 1rem auto;">Reservasi yang
                                                    di lakukan untuk minggu berikutnya, sehingga reservasi dapat dilakukan 1
                                                    minggu sebelum hari berobat.</p>
                                                <form class="form--add-contact" method="POST" target="_blank"
                                                    action="{{ route('store-reservasi') }}" id="form-reservasi">
                                                    @csrf
                                                    <div class="row justify-content-center">
                                                        <div class="col-md-8">
                                                            @if ($errors->has('already'))
                                                                <p
                                                                    style="max-width: 450px;color: #ff0000;margin: 1rem auto;">
                                                                    {{ $errors->messages()['already'][0] }}
                                                                </p>
                                                            @endif
                                                            <div class="panel-body reserv">
                                                                <div class="form-group dp">
                                                                    <label>Pilih Dokter</label>
                                                                    <select class="select select-contact-group"
                                                                        id="dokter_id_reservasi" name="dokter_id"
                                                                        title="Pilih Dokter" required="required"
                                                                        onchange="getJadwalDokter('On Site')">
                                                                        @foreach ($dokter as $item)
                                                                            <option value="{{ $item->id }}">
                                                                                {{ $item->name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="form-group collapse"
                                                                    id="jadwal_dokter_id_reservasi_div">
                                                                    <label>Pilih Hari</label>
                                                                    <select class="select select-contact-group"
                                                                        id="jadwal_dokter_id_reservasi"
                                                                        onchange="changeJadwalDokter(this)"
                                                                        name="jadwal_dokter_id[]" title="Pilih Hari"
                                                                        required="required" multiple>

                                                                    </select>
                                                                    <input type="hidden" value="On Site" name="param">
                                                                    <input type="hidden" value="" id="hari_reservasi"
                                                                        name="hari">
                                                                </div>
                                                                <div class="form-action"><a
                                                                        class="btn btn--primary btn--block"
                                                                        href="javascript:;"
                                                                        onclick="store('On Site')">Daftar</a>
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
                                                <form class="form--add-contact" method="POST" target="_blank"
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
                                                                        onchange="getJadwalDokter('Panggilan')">
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
                                                                        name="jadwal_dokter_id[]" title="Pilih Hari"
                                                                        required="required" multiple>

                                                                    </select>
                                                                    <input type="hidden" value="Panggilan"
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
                                                                        onclick="store('Panggilan')">Daftar</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="tab-pane fade" id="antrian">

                                                <div class="table">
                                                    <table>
                                                        <thead>
                                                            <tr>
                                                                <th class="check-all" width="5%"><span>No.</span></th>
                                                                <th><span>Tgl. Antrian</span></th>
                                                                <th><span>Nama Dokter</span></th>
                                                                <th><span>Antrian Saat Ini</span></th>
                                                                <th><span>No. Antrian</span></th>
                                                                <td class="has-action"></td>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($antrian->sortBy('tanggal') as $i => $item)
                                                                <tr>
                                                                    <td class="index-antrian">{{ $i + 1 }}</td>
                                                                    <td>{{ CarbonParse($item->tanggal, 'd/m/Y') }}</td>
                                                                    <td>{{ $item->jadwal_dokter->dokter->name }}</td>
                                                                    <td>{{ $item->no_reservasi }}</td>
                                                                    <td>{{ $item->no_reservasi }}</td>
                                                                    <td>
                                                                        @if (diffdate($item->tanggal, dateStore()) < -1)
                                                                            <a class="" style="cursor: pointer"
                                                                                title="Batalkan reservasi"
                                                                                onclick="hapus('{{ $item->jadwal_dokter_id }}','{{ $item->id }}',this)">
                                                                                <img class="svg"
                                                                                    src="{{ asset('images/ic-delete.svg') }}" />
                                                                            </a>
                                                                        @endif

                                                                    </td>
                                                                </tr>
                                                            @endforeach

                                                            {{-- <tr>
                                                                <td>1</td>
                                                                <td>3 Mei 2021</td>
                                                                <td>Dr. Abdullah</td>
                                                                <td>01</td>
                                                                <td><a class="btn-edit" href="#modalDelete"
                                                                        data-toggle="modal"><img class="svg"
                                                                            src="images/ic-delete.svg" /></a></td>
                                                            </tr> --}}
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="rekam_medis">

                                                <div class="table">
                                                    <table id="table-pasien">
                                                        <thead>
                                                            <tr>
                                                                <th class="check-all"><span>No.</span></th>
                                                                <th><span>No. Rekam Medis</span></th>
                                                                <th><span>Tgl. Berobat</span></th>
                                                                <th><span>Tindakan</span></th>
                                                                <th><span>Keterangan</span></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($rm as $i => $item)
                                                                <tr>
                                                                    <td>No</td>
                                                                    <td>No Rm</td>
                                                                    <td>Tgl Periksa</td>
                                                                    <td>Tindakan</td>
                                                                    <td>Keterangan</td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            {{-- <div class="tab-pane fade" id="pembayaran">
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
                                            </div> --}}
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
                            case 'On Site':
                                return $('#dokter_id_reservasi').val();
                                break;
                            case 'Panggilan':
                                return $('#dokter_id_panggilan').val();
                                break;
                            default:
                                break;
                        }
                    },
                    param: param
                },
                type: 'get',
                success: function(data) {

                    switch (param) {
                        case 'On Site':
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
                        case 'Panggilan':
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
                case 'On Site':
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
                case 'Panggilan':
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
            var delayInMilliseconds = 1000; //1 second

            setTimeout(function() {
                location.reload();
            }, delayInMilliseconds);
        }

        function reindex() {
            $(".index-antrian").each(function(i) {
                $(this).html(i + 1);
            })
        }

        function hapus(jadwal_dokter_id, id, par) {
            var previousWindowKeyDown = window.onkeydown;
            Swal.fire({
                title: "Hapus Data",
                text: "Aksi ini tidak bisa dikembalikan",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya lanjutkan!',
                cancelButtonText: 'Tidak!',
                showLoaderOnConfirm: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    window.onkeydown = previousWindowKeyDown;
                    $.ajax({
                        url: '{{ route('delete-reservasi') }}',
                        data: {
                            jadwal_dokter_id: jadwal_dokter_id,
                            id: id,
                            _token: "{{ csrf_token() }}"
                        },
                        type: 'post',
                        success: function(data) {
                            if (data.status == 1) {
                                Swal.fire({
                                    title: data.message,
                                    icon: 'success',
                                });

                                $(par).parents('tr').remove();
                                reindex();
                            } else if (data.status == 2) {
                                Swal.fire({
                                    title: data.message,
                                    icon: "warning",
                                });
                            }
                        },
                        error: function(data) {
                            var html = '';
                            Object.keys(data.responseJSON).forEach(element => {
                                html += data.responseJSON[element][0] + '<br>';
                            });
                            Swal.fire({
                                title: 'Oops Something Wrong!',
                                html: data.responseJSON.message == undefined ? html : data
                                    .responseJSON.message,
                                icon: "error",
                            });
                        }
                    });
                }
            })
        }
    </script>
@endsection
