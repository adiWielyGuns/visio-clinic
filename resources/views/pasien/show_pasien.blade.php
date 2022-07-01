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
                                                <label>ID Pasien</label>
                                            </div>
                                            <div class="col-8"><span>{{ $data->id_pasien }}</span></div>
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
                                                <span>{{ CarbonParse($data->tanggal_lahir, 'd F Y') }}</span>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-4">
                                                <label>Jenis Kelamin</label>
                                            </div>
                                            <div class="col-8">
                                                <span>{{ $data->jenis_kelamin }}</span>
                                            </div>
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
                <div class="col-lg-4 col-md-6">
                    <div class="panel">
                        <div class="panel-body">
                            <h2>Kunjungan</h2>
                            <div class="detail-wrap">
                                <div class="row">
                                    <div class="col-5">
                                        <label>Terakhir Berkunjung</label>
                                    </div>
                                    <div class="col-7">
                                        <span>{{ $tanggalTerakhirPeriksa ? CarbonParse($tanggalTerakhirPeriksa->tanggal, 'd F Y') : '-' }}</span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-5">
                                        <label>Reservasi</label>
                                    </div>
                                    <div class="col-7">
                                        <span>{{ $tanggalReservasi ? CarbonParse($tanggalReservasi->tanggal, 'd F Y') : '-' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12">
                    <h2>Rekam Medis</h2>
                    <div class="table">
                        <table id="table-pasien">
                            <thead>
                                <tr>
                                    <th class="check-all"><span>No.</span></th>
                                    <th><span>No. Rekam Medis</span></th>
                                    <th><span>Tgl. Berobat</span></th>
                                    <th><span>Dokter</span></th>
                                    <th><span>Tindakan</span></th>
                                    <th><span>Keterangan</span></th>
                                    <th class="has-edit text-right"><span class="sr-only"></span></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($rm as $i => $item)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>{{ $item->id_rekam_medis }}</td>
                                        <td>{{ CarbonParse($item->tanggal, 'd/m/Y') }}</td>
                                        <td>{{ $item->dokter->name }}</td>
                                        <td>{{ $item->tindakan }}</td>
                                        <td>{{ $item->keterangan }}</td>
                                    </tr>
                                @endforeach
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
            table = $('#table-pasien').DataTable({
                // searching: false,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('datatable-rekam-medis-pasien') }}",
                    data: {
                        id: '{{ $data->id }}',
                    }
                },
                columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    class: 'text-center'
                }, {
                    data: 'id_rekam_medis',
                    name: 'id_rekam_medis',
                }, {
                    data: 'tanggal',
                    name: 'tanggal'
                }, {
                    data: 'dokter',
                    name: 'dokter'
                }, {
                    data: 'tindakan',
                    name: 'tindakan'
                }, {
                    data: 'keterangan',
                    name: 'keterangan'
                }, {
                    data: 'aksi',
                    class: 'text-center',
                }, ]
            });
        }())
    </script>
@endsection
