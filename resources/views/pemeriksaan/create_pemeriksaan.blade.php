@extends('../layouts/main')
@section('css')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/jq-3.6.0/dt-1.12.1/datatables.min.css" />
@endsection
@section('content')
    <main>
        <div class="page-title">
            <div class="page-title__left"><a class="btn btn--icon btn--back" href="dokter-pasien-detail.html">
                    <div class="btn--wrap">
                        <div class="icon"><img class="svg" src="{{ asset('images/ic-left.svg') }}" /></div>
                        <span>Kembali ke Detail
                            Pasiean</span>
                    </div>
                </a></div>
        </div>
        <div class="page-main">
            <div class="add-contact">
                <div class="container--small">
                    <form class="form--add-contact" id="form-data">
                        @csrf
                        <div class="add-contact__title">
                            <h1 class="title--primary">Pemeriksaan</h1>
                        </div>
                        <div class="form-group">
                            <label>Id Rekam Medis</label>
                            <input class="form-control" type="text" value="505071" readonly id="id_rekam_medis"
                                name="id_rekam_medis" />
                            <input type="hidden" value="{{ $data->id }}" id="id" name="id" />
                            <input type="hidden" value="{{ $data->jadwal_dokter_id }}" id="jadwal_dokter_id"
                                name="jadwal_dokter_id" />
                        </div>
                        <div class="form-group">
                            <label>Jenis Reservasi</label>
                            <input type="text" disabled class="form-control" value="{{ $data->jenis }}">
                        </div>
                        <div class="form-group dp">
                            <label>Tgl Berobat</label>
                            <div class="datepicker">
                                <input class="form-control tanggal" id="tgl_lahir" name="tgl_awal" type="text"
                                    value="{{ CarbonParse($data->tanggal, 'd/m/Y') }}" disabled />
                            </div>
                            <div class="inpt-apend"></div>
                        </div>
                        <div class="form-group">
                            <label>Nama Dokter</label>
                            <input type="text" disabled class="form-control"
                                value="{{ $data->jadwal_dokter->dokter->name }}">
                        </div>
                        <div class="form-group">
                            <label>Tindakan / Resep</label>
                            <textarea class="form-control number required" type="text" name="tindakan" id="tindakan"></textarea>
                        </div>
                        <div class="form-group">
                            <label>Keterangan</label>
                            <textarea class="form-control number required" name="keterangan" id="keterangan" type="text"></textarea>
                        </div>
                        <div class="form-action text-right">
                            <button class="btn btn--primary btn--next btn--submit" type="button"
                                onclick="store()">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
@endsection
@section('script_content')
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/jq-3.6.0/dt-1.12.1/datatables.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#tanggal_lahir').datepicker({
                format: 'dd/mm/yyyy',
                autoclose: true,
                todayHighlight: true
            });
            generateKode();
        })

        function generateKode() {
            $.ajax({
                url: '{{ route('generate-kode-pemeriksaan') }}',

                type: 'get',
                success: function(data) {
                    $('#id_rekam_medis').val(data.kode);
                },
                error: function(data) {
                    generateKode();
                }
            });
        }


        function store() {
            var validation = 0;
            $('#form-data .required').each(function() {
                var par = $(this).parents('.form-group');
                if ($(this).val() == '' || $(this).val() == null) {
                    console.log($(this));
                    $(this).addClass('is-invalid');
                    validation++
                }
            })

            if (validation != 0) {
                Swal.fire({
                    title: 'Oops Something Wrong',
                    html: 'Semua data harus diisi',
                    icon: "warning",
                });
                return false;
            }

            var formData = new FormData();

            var data = $('#form-data').serializeArray();


            data.forEach((d, i) => {
                formData.append(d.name, d.value);
            })

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
                    $.ajax({
                        url: '{{ route('store-pemeriksaan') }}',
                        data: formData,
                        type: 'post',
                        processData: false,
                        contentType: false,
                        success: function(data) {
                            if (data.status == 1) {
                                Swal.fire({
                                    title: 'Success',
                                    text: data.message,
                                    icon: "success",
                                }).then(() => {
                                    location.href = '{{ route('pemeriksaan') }}';
                                })
                            } else if (data.status == 2) {
                                Swal.fire({
                                    title: 'Oops Something Wrong',
                                    html: data.message,
                                    icon: "warning",
                                });
                            } else {
                                Swal.fire({
                                    title: 'Oops Something Wrong',
                                    html: data,
                                    icon: "warning",
                                });
                            }
                            overlay(false);
                        },
                        error: function(data) {
                            overlay(false);
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
