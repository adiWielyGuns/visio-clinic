@extends('../layouts/main')
@section('css')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/jq-3.6.0/dt-1.12.1/datatables.min.css" />
@endsection
@section('content')
    <main>
        <div class="page-title">
            <div class="page-title__left"><a class="btn btn--icon btn--back" href="{{ route('staff') }}">
                    <div class="btn--wrap">
                        <div class="icon"><img class="svg" src="{{ asset('images/ic-left.svg') }}" /></div>
                        <span>Kembali ke Daftar
                            Pasien</span>
                    </div>
                </a></div>
        </div>
        <div class="page-main">
            <div class="add-contact">
                <div class="container--small">
                    <form class="form--add-contact" id="form-data">
                        @csrf
                        <div class="add-contact__title">
                            <h1 class="title--primary">Tambah Pasien Baru</h1>
                        </div>
                        <div class="form-group">
                            <label>Id Pasien</label>
                            <input value="{{ $data->id_pasien }}" readonly class="form-control required" type="text"
                                name="id_pasien" id="id_pasien" />
                        </div>
                        <div class="form-group">
                            <label>Nama</label>
                            <input class="form-control required" type="text" name="name" id="name" />
                        </div>
                        <div class="form-group dp">
                            <label>Tanggal Lahir</label>
                            <input class="form-control required" type="text" name="tanggal_lahir" id="tanggal_lahir" />
                            <div class="inpt-apend"></div>
                        </div>
                        <div class="form-group">
                            <label>Jenis Kelamin</label>
                            <select class="select select-contact-group" id="jenis_kelamin" name="jenis_kelamin"
                                title="Pilih Jenis Kelamin" onchange="generateKode()">
                                @foreach (\App\Models\Pasien::$enumJenisKelamin as $item)
                                    <option value="{{ $item }}">{{ $item }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Mobile No.</label>

                            <input class="form-control required number" type="text" name="telp" id="telp" />
                        </div>
                        <div class="form-group">
                            <label>Alamat</label>
                            <input class="form-control required number" maxlength="255" type="text" name="alamat" id="alamat" />
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
        })

        function generateKode() {
            $.ajax({
                url: '{{ route('generate-kode-pasien') }}',
                data: {
                    param: function() {
                        return $('#role_id').val();
                    },
                },
                type: 'get',
                success: function(data) {
                    $('#user_id').val(data.kode);
                },
                error: function(data) {
                    generateKode();
                }
            });
        }

        function store() {
            var validation = 0;
            if ($('#jenis_kelamin').val() == null || $('#jenis_kelamin').val() == '') {
                $('#jenis_kelamin').addClass('is-invalid');
                validation++
            }

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
                        url: '{{ route('store-pasien') }}',
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
                                    location.href = '{{ route('pasien') }}';
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
