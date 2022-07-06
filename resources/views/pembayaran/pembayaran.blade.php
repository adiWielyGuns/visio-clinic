@extends('../layouts/main')
@section('css')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/jq-3.6.0/dt-1.12.1/datatables.min.css" />
@endsection
@section('content')
    <main>
        <div class="page-title">
            <div class="page-title__left">
                <h1>Pembayaran</h1>
            </div>
            <div class="page-title__action"><a class="btn btn--primary" href="{{ route('create-pembayaran') }}">
                    Tambah
                    Pembayaran</a></div>
        </div>
        <div class="page-main">
            <div class="tab-content">
                <div class="tab-pane fade active show" id="dokters">
                    <div class="page-filter">
                        <div class="item bulk-false collapse show">
                            <div class="row">
                                <div class="col-3">
                                    <label>Jenis Pembayaran</label>
                                    <select class="select select-contact-group" id="metode_pembayaran"
                                        name="metode_pembayaran" onchange="table.ajax.reload()">
                                        <option selected value="">Semua Metode Pembayaran</option>
                                        @foreach (\App\Models\Pembayaran::$enumMetodePembayaran as $item)
                                            <option value="{{ $item }}">{{ $item }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-1">
                                    <div class="form-group dp">
                                        <label>Tanggal Awal</label>
                                        <div class="datepicker">
                                            <input class="form-control tanggal_pembayaran" id="tanggal_awal"
                                                name="tanggal_awal" type="text" />
                                        </div>
                                        <div class="inpt-apend"></div>
                                    </div>
                                </div>
                                <div class="col-1">
                                    <div class="form-group dp">
                                        <label>Tanggal Akhir</label>
                                        <div class="datepicker">
                                            <input class="form-control tanggal_pembayaran" id="tanggal_akhir"
                                                name="tanggal_akhir" type="text" />
                                        </div>
                                        <div class="inpt-apend"></div>
                                    </div>
                                </div>
                                <div class="col-1">
                                    <label>&nbsp;</label>
                                    <a class="btn btn--primary" href="{{ route('laporan-pembayaran') }}">
                                        Cetak Laporan
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table">
                        <table id="table-pembayaran">
                            <thead>
                                <tr>
                                    <th class="check-all"><span>No.</span></th>
                                    <th><span>Kode Invoice</span></th>
                                    <th><span>Jenis Pembayaran</span></th>
                                    <th><span>Tgl. Pembayaran</span></th>
                                    <th><span>Terima Dari</span></th>
                                    <th class="text-right" width><span>Jumlah Uang (Rp)</span></th>
                                    <th class="text-right" width><span>Status</span></th>
                                    <th class="has-edit">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
@section('script_content')
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/jq-3.6.0/dt-1.12.1/datatables.min.js"></script>

    <script>
        var table;
        (function() {
            table = $('#table-pembayaran').DataTable({
                // searching: false,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('datatable-pembayaran') }}",
                    data: {
                        metode_pembayaran: function() {
                            return $('#metode_pembayaran').val();
                        },
                        tanggal_awal: function() {
                            return $('#tanggal_awal').val();
                        },
                        tanggal_akhir: function() {
                            return $('#tanggal_akhir').val();
                        }
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        class: 'text-center'
                    }, {
                        data: 'nomor_invoice',
                        name: 'nomor_invoice',
                    }, {
                        data: 'metode_pembayaran',
                        name: 'metode_pembayaran'
                    }, {
                        data: 'tanggal',
                        name: 'tanggal'
                    }, {
                        data: 'pasien',
                        name: 'pasien'
                    }, {
                        data: 'total',
                        name: 'total',
                        class: 'text-right'
                    },
                    {
                        data: 'status',
                        name: 'status',
                        class: 'text-center'
                    },
                    {
                        data: 'aksi',
                        class: 'text-center',
                    },
                ]
            });



        }())
        $(document).ready(function() {
            $('.tanggal_pembayaran').datepicker({
                format: 'dd/mm/yyyy',
                autoclose: true,
                todayHighlight: true
            }).on('changeDate', function() {
                table.ajax.reload();
            });
        })

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
                        url: '{{ route('delete-pembayaran') }}',
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
