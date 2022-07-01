@extends('../layouts/main')
@section('css')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/jq-3.6.0/dt-1.12.1/datatables.min.css" />
@endsection
@section('content')
    <main>
        <div class="page-title">
            <div class="page-title__left">
                <h1>Pasien<span class="total">{{ \App\Models\Pasien::count() }}</span></h1>
            </div>
            <div class="page-title__action"><a class="btn btn--primary" href="{{ route('create-pasien') }}">Tambah Pasien</a>
            </div>
        </div>
        <div class="page-main">
            <div class="table">
                <table id="table-pasien">
                    <thead>
                        <tr>
                            <th class="check-all"><span>No.</span></th>
                            <th width="17%"><span>ID Pasien</span></th>
                            <th width="17%"><span>Nama Pasien</span></th>
                            <th width="23%"><span>Tgl Lahir</span></th>
                            <th><span>Jenis Kelamin</span></th>
                            <th><span>Mobile No.</span></th>
                            <th>Alamat</th>
                            <th class="has-edit">Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </main>
@endsection
@section('script_content')
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/jq-3.6.0/dt-1.12.1/datatables.min.js"></script>

    <script>
        var table;
        (function() {
           table = $('#table-pasien').DataTable({
                // searching: false,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('datatable-pasien') }}",
                },
                columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    class: 'text-center'
                }, {
                    data: 'id_pasien',
                    name: 'id_pasien',
                }, {
                    data: 'name',
                    name: 'name'
                }, {
                    data: 'tanggal_lahir',
                    name: 'tanggal_lahir'
                }, {
                    data: 'jenis_kelamin',
                    name: 'jenis_kelamin'
                }, {
                    data: 'telp',
                    name: 'telp'
                }, {
                    data: 'alamat',
                    name: 'alamat'
                }, {
                    data: 'aksi',
                    class: 'text-center',
                }, ]
            });
        }())

        function hapus(id) {
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
                        url: '{{ route('delete-pasien') }}',
                        data: {
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
                            } else if (data.status == 2) {
                                Swal.fire({
                                    title: data.message,
                                    icon: "warning",
                                });
                            }
                            table.ajax.reload(null, false);
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
