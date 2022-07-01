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
            <h1>Detail Pasien</h1>
            <div class="row">
                <div class="col-lg-6 col-md-6">
                    <div class="panel">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="detail-wrap">
                                        <div class="row">
                                            <div class="col-4">
                                                <label>ID Staff</label>
                                            </div>
                                            <div class="col-8"><span>{{ $data->user_id }}</span></div>
                                        </div>
                                        <div class="row">
                                            <div class="col-4">
                                                <label>Nama</label>
                                            </div>
                                            <div class="col-8"><span>{{ $data->name }}</span></div>
                                        </div>
                                        <div class="row">
                                            <div class="col-4">
                                                <label>Tgl. Lahir</label>
                                            </div>
                                            <div class="col-8">
                                                <span>{{ CarbonParse($data->tanggal_lahir, 'd F Y') }}</span></div>
                                        </div>
                                        <div class="row">
                                            <div class="col-4">
                                                <label>Mobile No.</label>
                                            </div>
                                            <div class="col-8"><span>{{ $data->telp }}</span></div>
                                        </div>
                                        <div class="row">
                                            <div class="col-4">
                                                <label>Alamat</label>
                                            </div>
                                            <div class="col-8"><span>{{ $data->alamat }}</span></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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
