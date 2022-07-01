@extends('../layouts/main')
@section('css')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/jq-3.6.0/dt-1.12.1/datatables.min.css" />
@endsection
@section('content')
    <main>
        <div class="page-title">
            <div class="page-title__left">
                <h1>Item<span class="total"></span></h1>
            </div>
            <div class="page-title__action"><a class="btn btn--primary" href="{{ route('create-item') }}">Tambah
                    Item</a>
            </div>
        </div>
        <div class="page-main">
            <div class="table">
                <table id="table-item">
                    <thead>
                        <tr>
                            <th class="check-all"><span>No.</span></th>
                            <th width="17%"><span>Item</span></th>
                            <th width="17%"><span>Jenis</span></th>
                            <th width="17%"><span>Harga</span></th>
                            <th width="17%"><span>Keterangan</span></th>
                            <th width="17%"><span>Status</span></th>
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
            table = $('#table-item').DataTable({
                // searching: false,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('datatable-item') }}",
                },
                columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    class: 'text-center'
                }, {
                    data: 'name',
                    name: 'name',
                }, {
                    data: 'jenis',
                    name: 'jenis',
                }, {
                    data: 'harga',
                    name: 'harga',
                }, {
                    data: 'keterangan',
                    name: 'keterangan'
                }, {
                    data: 'status',
                    name: 'status'
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
                        url: '{{ route('delete-item') }}',
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

        function gantiStatus(param, id) {
            $.ajax({
                url: "{{ route('status-item') }}",
                data: {
                    id,
                    param
                },
                type: 'get',
                success: function(data) {
                    table.ajax.reload(null, false);
                    Swal.fire({
                        title: data.message,
                        icon: "success",
                    });

                },
                error: function(data) {
                    var html = '';
                    Object.keys(data.responseJSON).forEach(element => {
                        html += data.responseJSON[element][0] + '<br>';
                    });
                    Swal.fire({
                        title: 'Oops Something Wrong!',
                        text: data.responseJSON.message == undefined ? html : data
                            .responseJSON.message,
                        type: "error",
                        html: true,
                    });
                }
            });
        }
    </script>
@endsection
