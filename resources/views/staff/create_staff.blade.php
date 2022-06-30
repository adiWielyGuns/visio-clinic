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
                            <h1 class="title--primary">Tambah Staff Baru</h1>
                        </div>
                        <div class="form-group">
                            <label>Role</label>
                            <select class="select select-contact-group" id="role_id" name="role_id"
                                title="Pilih Departemen" onchange="generateKode()">
                                @foreach (\App\Models\Role::where('name', '!=', 'SuperAdmin')->get() as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Id Staff</label>
                            <input readonly class="form-control required" type="text" name="user_id" id="user_id" />
                        </div>
                        <div class="form-group">
                            <label>Nama</label>
                            <input class="form-control required" type="text" name="name" id="name" />
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input class="form-control required" type="text" name="email" id="email" />
                        </div>
                        <div class="form-group dp">
                            <label>Tanggal Lahir</label>
                            <input class="form-control required" type="text" name="tanggal_lahir" id="tanggal_lahir" />
                            <div class="inpt-apend"></div>
                        </div>
                        <div class="form-group">
                            <label>Mobile No.</label>

                            <input class="form-control required number" type="text" name="telp" id="telp" />
                        </div>
                        <div class="form-group">
                            <label>Alamat</label>
                            <input class="form-control required number" type="text" name="alamat" id="alamat" />
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
        function generateKode() {
            $.ajax({
                url: '{{ route('generate-kode-staff') }}',
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
            if ($('#role_id').val() == null || $('#role_id').val() == '') {
                $('#role_id').addClass('is-invalid');
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
                        url: '{{ route('store-staff') }}',
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
                                    location.href = '{{ route('staff') }}';
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
