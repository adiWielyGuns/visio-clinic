@extends('../layouts/main')
@section('css')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/jq-3.6.0/dt-1.12.1/datatables.min.css" />
@endsection

@section('content')
    <main>
        <div class="page-title">
            <div class="page-title__left">
                <h1>Laporan</h1>
            </div>
        </div>
        <div class="page-main">
            <div class="tab-content">
                <div class="tab-pane fade active show" id="dokters">
                    <div class="page-filter">
                        <div class="item bulk-false collapse show">
                            <form id="filter-form" class="row" method="GET" action="{{ route('laporan') }}">
                                @csrf
                                <div class="col-3">
                                    <label>Jenis Laporan</label>
                                    <select class="select select-contact-group" id="jenis_laporan" name="jenis_laporan">
                                        <option {{ $req->jenis_laporan == 'laporan_jumlah_pasien' ? 'selected' : '' }}
                                            value="laporan_jumlah_pasien">Laporan Jumlah pasien</option>
                                        <option {{ $req->jenis_laporan == 'laporan_jadwal_terapi' ? 'selected' : '' }}
                                            value="laporan_jadwal_terapi">Laporan Jadwal Terapi</option>
                                    </select>
                                </div>
                                <div class="col-1">
                                    <div class="form-group dp">
                                        <label>Tanggal Awal</label>
                                        <div class="datepicker">
                                            <input class="form-control tanggal_pembayaran" id="tanggal_awal"
                                                name="tanggal_awal" type="text" value="{{ $req->tanggal_awal }}" />
                                        </div>
                                        <div class="inpt-apend"></div>
                                    </div>
                                </div>
                                <div class="col-1">
                                    <div class="form-group dp">
                                        <label>Tanggal Akhir</label>
                                        <div class="datepicker">
                                            <input class="form-control tanggal_pembayaran" id="tanggal_akhir"
                                                name="tanggal_akhir" type="text" value="{{ $req->tanggal_akhir }}" />
                                        </div>
                                        <div class="inpt-apend"></div>
                                    </div>
                                </div>
                                <div class="col-1">
                                    <label>&nbsp;</label>
                                    <a class="btn btn--primary" onclick="$('#filter-form').submit()">
                                        Search
                                    </a>
                                </div>
                                <div class="col-12">
                                    <label>&nbsp;</label>
                                    <a class="btn btn--primary" onclick="excel()">
                                        Export To Excel
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="table" id="isi">
                        @if ($req->jenis_laporan == 'laporan_jumlah_pasien')
                            <table>
                                <thead>
                                    <tr>
                                        <th class="check-all" width="5%"><span>No.</span></th>
                                        <th><span>Tgl. Periksa</span></th>
                                        <th><span>Nama Pasien</span></th>
                                        <th><span>Dokter</span></th>
                                        <th><span>Jenis Layanan</span></th>
                                        <th><span>Lama Terapi</span></th>
                                        <th class="has-action">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $index = 1;
                                    @endphp
                                    @forelse ($data->sortBy('tanggal') as $i => $item)
                                        <tr>

                                            <td class="index-antrian"> {{ $index }}</td>
                                            <td>{{ CarbonParse($item->tanggal, 'd/m/Y') }}</td>
                                            <td>{{ $item->pasien ? $item->pasien->name : '-' }}</td>
                                            <td>{{ $item->jadwal_dokter->dokter->name }}</td>
                                            <td>{{ $item->jenis }}</td>
                                            <td>{{ $item->pasien_rekam_medis->lama_terapi }}</td>
                                            <td>
                                                {{ $item->status }}
                                            </td>
                                        </tr>
                                        @php
                                            $index++;
                                        @endphp
                                    @empty
                                        <tr>
                                            <td colspan="{{ Auth::user()->role->id != 1 ? '6' : '5' }}"
                                                style="text-align: center">Tidak ada reservasi</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        @elseif ($req->jenis_laporan == 'laporan_jadwal_terapi')
                            <table>
                                <thead>
                                    <tr>
                                        <th class="check-all" width="5%"><span>No.</span></th>
                                        <th><span>Tgl. Antrian</span></th>
                                        <th><span>Nama Pasien</span></th>
                                        @if (Auth::user()->role->id != 1)
                                            <th><span>Dokter</span></th>
                                        @endif
                                        <th><span>Jenis</span></th>
                                        <th><span>No. Antrian</span></th>
                                        <td class="has-action"></td>
                                    </tr>
                                </thead>
                                <tbody>

                                    @forelse ($data->sortBy('tanggal') as $i => $item)
                                        <tr>

                                            <td class="index-antrian"> {{ $i + 1 }}</td>
                                            <td>{{ CarbonParse($item->tanggal, 'd/m/Y') }}</td>
                                            <td>{{ $item->pasien ? $item->pasien->name : '-' }}</td>
                                            @if (Auth::user()->role->id != 1)
                                                <td>{{ $item->jadwal_dokter->dokter->name }}</td>
                                            @endif
                                            <td>{{ $item->jenis }}</td>
                                            <td>{{ $item->no_reservasi }}</td>
                                            <td>
                                                @if (dateStore() == $item->tanggal)
                                                    <a class="btn btn--primary"
                                                        href="{{ route('create-pemeriksaan', ['id' => $item->id, 'jadwal_dokter_id' => $item->jadwal_dokter_id]) }}">Periksa
                                                        Pasien</a>
                                                @else
                                                    H{{ diffdate($item->tanggal, dateStore()) }}
                                                @endif

                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="{{ Auth::user()->role->id != 1 ? '6' : '5' }}"
                                                style="text-align: center">Tidak ada reservasi</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </main>
    <div id="xlsDownload" style="display: none"></div>
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

        function excel(argument) {
            var blob = b64toBlob(btoa($('div[id=isi]').html().replace(/[\u00A0-\u2666]/g, function(c) {
                return '&#' + c.charCodeAt(0) + ';';
            })), "application/vnd.ms-excel");
            var blobUrl = URL.createObjectURL(blob);
            var dd = new Date()
            var ss = '' + dd.getFullYear() + "-" +
                (dd.getMonth() + 1) + "-" +
                (dd.getDate()) +
                "_" +
                dd.getHours() +
                dd.getMinutes() +
                dd.getSeconds()

            $("#xlsDownload").html("<a href=\"" + blobUrl + "\" download=\"Print_Data\_" + ss +
                "\.xls\" id=\"xlsFile\">Downlaod</a>");
            $("#xlsFile").get(0).click();

            function b64toBlob(b64Data, contentType, sliceSize) {
                contentType = contentType || '';
                sliceSize = sliceSize || 512;

                var byteCharacters = atob(b64Data);
                var byteArrays = [];


                for (var offset = 0; offset < byteCharacters.length; offset += sliceSize) {
                    var slice = byteCharacters.slice(offset, offset + sliceSize);

                    var byteNumbers = new Array(slice.length);
                    for (var i = 0; i < slice.length; i++) {
                        byteNumbers[i] = slice.charCodeAt(i);
                    }

                    var byteArray = new Uint8Array(byteNumbers);

                    byteArrays.push(byteArray);
                }

                var blob = new Blob(byteArrays, {
                    type: contentType
                });
                return blob;
            }
        }
    </script>
@endsection
