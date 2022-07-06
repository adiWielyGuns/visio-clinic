@extends('../layouts/base')
@section('body')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.min.css"
        integrity="sha512-EZSUkJWTjzDlspOoPSpUFR0o0Xy7jdzW//6qhUkoZ9c4StFkVsp9fbbd0O06p9ELS3H486m4wmrCELjza4JEog=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <body>
        <div class="loading style-2" id="loading">
            <div class="loading-wheel"></div>
        </div>
        <div id="wrap">
            <div class="web-wrapper" id="page">
                <main>
                    <div class="container-fluid auth-page pasien-daftar logged">
                        <div class="row">
                            <div class="col-lg-4 left-wrap">
                                <div class="image" style="background-image: url(images/masthead-home.jpg)"></div>
                                {{-- <div class="description">
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
                                </div> --}}
                            </div>
                            <div class="col-lg-8 right-wrap">
                                <div class="form-wrap">
                                    <form action="{{ route('logout-pasien') }}" method="POST" id="logout">
                                        {{ csrf_field() }}</form>
                                    <div class="box">
                                        <div class="imglogo"><img src="images/logo-vmm.png" /></div>
                                        <h1 class="logo">Fisio Mandiri Medika</h1>
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
                                                <li class="nav__item"><a class="nav__link" href="#pembayaran"
                                                        data-toggle="tab">Pembayaran</a></li>
                                            </ul>
                                            @if (Session::has('message'))
                                                <p style="max-width: 450px;color: #34cc69;margin: 1rem auto;">
                                                    Berhasil upload bukti pembayaran
                                                </p>
                                            @endif

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
                                                                    <td>{{ $i + 1 }}</td>
                                                                    <td>{{ $item->id_rekam_medis }}</td>
                                                                    <td>{{ CarbonParse($item->tanggal, 'd/m/Y') }}</td>
                                                                    <td>{{ $item->tindakan }}</td>
                                                                    <td>{{ $item->keterangan }}</td>
                                                                </tr>
                                                            @endforeach
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
                                                                <th><span>Tgl</span></th>
                                                                <th><span>Total Tagihan</span></th>
                                                                <td class="has-action"></td>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($invoice as $i => $item)
                                                                <tr>
                                                                    <td>{{ $i + 1 }}</td>
                                                                    <td>{{ $item->nomor_invoice }}</td>
                                                                    <td>{{ CarbonParse($item->tanggal, 'd/m/Y') }}</td>
                                                                    <td>{{ number_format($item->total) }}</td>
                                                                    <td>
                                                                        @if ($item->status == 'Released' and $item->metode_pembayaran == 'Transfer Bank')
                                                                            <a class="btn btn--primary" href="#modalVerif"
                                                                                onclick="modalVerifikasi('{{ $item->id }}')"
                                                                                data-toggle="modal">Bayar</a>
                                                                        @elseif($item->status == 'Rejected')
                                                                            <a class="btn btn--danger" href="#modalVerif"
                                                                                onclick="modalVerifikasi('{{ $item->id }}')"
                                                                                data-toggle="modal">Bayar Ulang</a>
                                                                        @elseif($item->status == 'Waiting')
                                                                            <span style="color: #e9b414">Menunggu Verifikasi</span>
                                                                        @elseif($item->status == 'Done')
                                                                            <span style="color: #34cc69">Terbayar</span>
                                                                        @endif

                                                                    </td>
                                                                </tr>
                                                            @endforeach
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

        <div class="modal" id="modalVerif" tabindex="-1">
            <div class="modal-dialog modal-dialog--centered modal-dialog--sm">
                <div class="modal-content">
                    <div class="modal-body">
                        <h2>Verifikasi Pembayaran</h2>
                        <form id="form-data" method="POST" enctype="multipart/form-data"
                            action="{{ route('verifikasi-pembayaran') }}">
                            @csrf
                            <div class="form-group">
                                <div class="form-group metode_pembayaran_div">
                                    <label>Nama Rekening</label>
                                    <input class="form-control required metode_pembayaran" type="text"
                                        name="no_transaksi" id="no_transaksi" />
                                    <input type="hidden" id="pembayaran_id" name="pembayaran_id" class="required">
                                    <input type="hidden" id="param" name="param" value="input_pembayaran">
                                </div>
                                <div class="form-group metode_pembayaran_div">
                                    <label>No. Rekening</label>
                                    <input class="form-control required metode_pembayaran" type="text"
                                        name="no_rekening" id="no_rekening" />
                                </div>
                                <div class="form-group metode_pembayaran_div">
                                    <label>Bukti Transfer</label>
                                    <input type="file" class="dropify" id="upload_bukti_transfer"
                                        name="upload_bukti_transfer" data-allowed-file-extensions="jpeg jpg png">
                                </div>
                            </div>
                            <div class="form-action text-right">
                                <button class="btn btn--white" type="button" data-dismiss="modal">Cancel</button>
                                <button class="btn btn--acc" type="button"
                                    onclick="verifikasiPembayaran()">Verifikasi</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </body>
@endsection

@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.min.js"
        integrity="sha512-8QFTrG0oeOiyWo/VM9Y8kgxdlCryqhIxVeRpWSezdRRAvarxVtwLnGroJgnVW9/XBRduxO/z1GblzPrMQoeuew=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        (function() {
            $('.dropify').dropify();
        }())

        function changeJadwalDokter(par) {
            $('#hari_reservasi').val($(par).find('option:selected').data('hari'));
        }

        function capitalizeFirstLetter(string) {
            return string.charAt(0).toUpperCase() + string.slice(1);
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
                                    '">' + d.tanggal +
                                    ' | ' +
                                    capitalizeFirstLetter(d.hari) +
                                    ' (' + d.sisa_kuota + ')' +
                                    '</option>';

                                $('#jadwal_dokter_id_reservasi').append(option);

                            });

                            $('#jadwal_dokter_id_reservasi').selectpicker('refresh');
                            $('#jadwal_dokter_id_reservasi').selectpicker('val', null);
                            break;
                        case 'Panggilan':
                            $('#jadwal_dokter_id_panggilan_div').removeClass('collapse')
                            data.data.forEach((d, i) => {
                                var option = '<option data-hari="' + d.hari + '" value="' + d.id +
                                    '">' + d.tanggal +
                                    ' | ' +
                                    capitalizeFirstLetter(d.hari) +
                                    ' (' + d.sisa_kuota + ')' +
                                    '</option>';

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

        function modalVerifikasi(id) {
            $('#pembayaran_id').val(id);
        }

        function verifikasiPembayaran() {
            var validation = 0;
            $('#form-data .required').each(function() {
                var par = $(this).parents('.form-group');
                if ($(this).val() == '' || $(this).val() == null) {
                    console.log($(this));
                    $(this).addClass('is-invalid');
                    validation++
                }
            })

            $('.dropify').each(function(i) {
                var parDrop = $(this).parents('.form-group');
                if ($(this)[0].files[0] == undefined) {
                    validation++;
                    $(parDrop).find('.dropify-wrapper').addClass('is-invalid');
                } else {
                    $(parDrop).find('.dropify-wrapper').removeClass('is-invalid');
                }
            });

            if (validation != 0) {
                Swal.fire({
                    title: 'Oops Something Wrong',
                    html: 'Semua data harus diisi',
                    icon: "warning",
                });
                return false;
            }

            var previousWindowKeyDown = window.onkeydown;
            Swal.fire({
                title: 'Proses Aksi Ini?',
                text: "Proses ini tidak bisa dikembalikan!",
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
                    overlay(true);
                    $('#form-data').submit();
                }
            })
        }
    </script>
@endsection
