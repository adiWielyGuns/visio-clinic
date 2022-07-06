@extends('../layouts/main')
@section('css')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/jq-3.6.0/dt-1.12.1/datatables.min.css" />
@endsection
@section('content')
    <main>
        <div class="page-title">
            <div class="page-title__left"><a class="btn btn--icon btn--back" href="{{ route('pembayaran') }}">
                    <div class="btn--wrap">
                        <div class="icon"><img class="svg" src="{{ asset('images/ic-left.svg') }}" /></div>
                        <span>Kembali ke Pembayaran</span>
                    </div>
                </a></div>
        </div>
        <div class="page-main">
            <h1>Detail Pembayaran</h1>
            <div class="row">
                <div class="col-lg-6 col-md-6">
                    <div class="panel">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="detail-wrap">
                                        <div class="row">
                                            <div class="col-4">
                                                <label>No Inv</label>
                                            </div>
                                            <div class="col-8"><span>{{ $data->nomor_invoice }}</span></div>
                                        </div>
                                        <div class="row">
                                            <div class="col-4">
                                                <label>Tanggal</label>
                                            </div>
                                            <div class="col-8"><span>{{ CarbonParse($data->tanggal, 'd/m/Y') }}</span>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-4">
                                                <label>Total</label>
                                            </div>
                                            <div class="col-8">
                                                <span>{{ number_format($data->total) }}</span>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-4">
                                                <label>Metode Pembayaran</label>
                                            </div>
                                            <div class="col-8"><span>{{ $data->metode_pembayaran }}</span></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <h1>Detail Item</h1>
                    <div class="panel">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="detail-wrap">
                                        <div class="row">
                                            @foreach ($data->pembayaran_detail as $item)
                                                <div class="col-sm-12">
                                                    <b>{{ $item->item->name }}</b>
                                                    &nbsp;&nbsp;&nbsp;<span>{{ number_format($item->total) }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if ($data->status == 'Waiting')
                        <div class="row">
                            <div class="col-md-12">
                                <div class="detail-wrap">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <button class="btn btn--primary btn--next btn--submit" style="display: inline"
                                                type="button" onclick="store('Done')">Setujui Pembayaran</button>
                                            <button class="btn btn--danger btn--next btn--submit" style="display: inline"
                                                type="button" onclick="store('Rejected')">Tolak Pembayaran</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                </div>
                @if ($data->metode_pembayaran != 'Tunai')
                    <div class="col-lg-6 col-md-6">
                        <div class="panel">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="detail-wrap">
                                            <div class="row">
                                                <div class="col-12">
                                                    <label>Bukti Transfer</label>
                                                    <img style="width: 100%" src="{{ $data->upload_bukti_transfer }}"
                                                        alt="">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-4">
                                                    <label>Nama Rekening</label>
                                                </div>
                                                <div class="col-8">
                                                    <span>{{ $data->no_transaksi }}</span>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-4">
                                                    <label>No Rekening</label>
                                                </div>
                                                <div class="col-8">
                                                    <span>{{ $data->no_rekening }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

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

        function store(param) {

            var formData = new FormData();

            var data = $('#form-data').serializeArray();



            formData.append('status', param);
            formData.append('pembayaran_id', '{{ $data->id }}');
            formData.append('param', 'validasi');
            formData.append('_token', '{{ csrf_token() }}');

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
                        url: '{{ route('verifikasi-pembayaran') }}',
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
                                    location.href = '{{ route('pembayaran') }}';
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
