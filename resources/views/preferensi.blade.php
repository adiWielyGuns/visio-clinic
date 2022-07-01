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
                            Staff</span>
                    </div>
                </a></div>
        </div>
        <div class="page-main">
            <div class="add-contact">
                <div class="container--small">
                    <form class="form--add-contact" id="form-data">
                        @csrf
                        <div class="add-contact__title">
                            <h1 class="title--primary">Ubah Preferensi Anda</h1>
                        </div>
                        <div class="form-group">
                            <label>Username</label>
                            <input readonly class="form-control required" disabled value="{{ $data->username }}"
                                type="text" name="user_id" id="user_id" />
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input class="form-control" placeholder="Kosongi jika tidak ingin merubah" type="text"
                                name="password" id="password" value="">
                        </div>
                        <div class="form-group">
                            <label>Nama</label>
                            <input class="form-control required" type="text" name="name" id="name"
                                value="{{ $data->name }}" />
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input class="form-control required" type="text" name="email" id="email"
                                value="{{ $data->email }}" />
                        </div>
                        <div class="form-group dp">
                            <label>Tanggal Lahir</label>
                            <input class="form-control required" type="text"
                                value="{{ CarbonParse($data->tanggal_lahir, 'd/m/Y') }}" name="tanggal_lahir"
                                id="tanggal_lahir" />
                            <div class="inpt-apend"></div>
                        </div>
                        <div class="form-group">
                            <label>Mobile No.</label>
                            <input class="form-control required number" value="{{ $data->telp }}" type="text"
                                name="telp" id="telp" />
                        </div>
                        <div class="form-group">
                            <label>Alamat</label>
                            <input class="form-control required number" value="{{ $data->alamat }}" type="text"
                                name="alamat" id="alamat" />
                        </div>
                        <div class="form-action text-right">
                            <button class="btn btn--primary btn--next btn--submit" type="button"
                                onclick="store()">Update</button>
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

            $('#role_id').val("{{ $data->role_id }}");
        })

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
            formData.append('id', '{{ $data->id }}');

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
                        url: '{{ route('store-setting') }}',
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
                                    location.href = '{{ route('setting') }}';
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
