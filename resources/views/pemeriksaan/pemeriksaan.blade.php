@extends('../layouts/main')
@section('css')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/jq-3.6.0/dt-1.12.1/datatables.min.css" />
@endsection
@section('content')
    <main>
        <div class="page-title">
            <div class="page-title__left">
                <h1>Pemeriksaan<span class="total"></span></h1>
            </div>
            {{-- <div class="page-title__action"><a class="btn btn--primary" href="{{ route('create-pemeriksaan') }}">Tambah Pemeriksaan</a>
            </div> --}}
        </div>
        <div class="page-tabs">
            <ul class="nav">
                <li class="nav__item"><a
                        class="nav__link {{ isset($req->tab) ? ($req->tab == 'on_site' ? 'active' : '') : 'active' }}"
                        href="#mekarians" data-toggle="tab">On Site
                        ({{ count($onSite) }})</a></li>
                <li class="nav__item"><a
                        class="nav__link {{ isset($req->tab) ? ($req->tab == 'panggilan' ? 'active' : '') : '' }}"
                        href="#outsiders" data-toggle="tab">Panggilan
                        ({{ count($panggilan) }})</a></li>
                <li class="nav__item"><a
                        class="nav__link {{ isset($req->tab) ? ($req->tab == 'history' ? 'active' : '') : '' }}"
                        href="#history" data-toggle="tab">History
                        ({{ count($history) }})</a></li>
            </ul>
        </div>
        <div class="page-main">
            <div class="tab-content">
                <div class="tab-pane fade  {{ isset($req->tab) ? ($req->tab == 'on_site' ? 'show active' : '') : 'show active' }}"
                    id="mekarians">
                    <div class="page-filter">
                        <div
                            class="item bulk-false collapse {{ isset($req->tab) ? ($req->tab == 'on_site' ? 'show' : '') : 'show' }}">
                            <div class="row">
                                <div class="col-1">
                                    <div class="form-group dp">
                                        <label>Tanggal Reservasi</label>
                                        <div class="datepicker">
                                            <input class="form-control required" type="text" name="tanggal_on_site"
                                                value="{{ isset($req->tanggal_on_site) ? $req->tanggal_on_site : '' }}"
                                                id="tanggal_on_site" />
                                        </div>
                                        <div class="inpt-apend"></div>
                                    </div>
                                </div>
                                <div class="col-1">
                                    <label>&nbsp;</label> <a class="btn btn--primary"
                                        href="{{ route('pemeriksaan') }}">Reset
                                        Tanggal</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table">
                        <table>
                            <thead>
                                <tr>
                                    <th class="check-all" width="5%"><span>No.</span></th>
                                    <th><span>Tgl. Antrian</span></th>
                                    <th><span>Nama Pasien</span></th>
                                    <th><span>No. Antrian</span></th>
                                    <td class="has-action"></td>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($onSite->sortBy('tanggal') as $i => $item)
                                    <tr>
                                        <td class="index-antrian">{{ $i + 1 }}</td>
                                        <td>{{ CarbonParse($item->tanggal, 'd/m/Y') }}</td>
                                        <td>{{ $item->pasien->name }}</td>
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
                                        <td colspan="5" style="text-align: center">Tidak ada reservasi</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade  {{ isset($req->tab) ? ($req->tab == 'panggilan' ? 'show active' : '') : '' }}"
                    id="outsiders">
                    <div class="page-filter">
                        <div
                            class="item bulk-false collapse  {{ isset($req->tab) ? ($req->tab == 'panggilan' ? 'show' : '') : '' }}">
                            <div class="row">
                                <div class="col-1">
                                    <div class="form-group dp">
                                        <label>Tanggal Reservasi</label>
                                        <div class="datepicker">
                                            <input class="form-control required" type="text" name="tanggal_panggilan"
                                                value="{{ isset($req->tanggal_panggilan) ? $req->tanggal_panggilan : '' }}"
                                                id="tanggal_panggilan" />
                                        </div>
                                        <div class="inpt-apend"></div>
                                    </div>
                                </div>
                                <div class="col-1">
                                    <label>&nbsp;</label> <a class="btn btn--primary"
                                        href="{{ route('pemeriksaan') }}">Reset
                                        Tanggal</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table">
                        <table>
                            <thead>
                                <tr>
                                    <th class="check-all" width="5%"><span>No.</span></th>
                                    <th><span>Tgl. Antrian</span></th>
                                    <th><span>Nama Pasien</span></th>
                                    <th><span>Antrian Saat Ini</span></th>
                                    <th><span>No. Antrian</span></th>
                                    <td class="has-action"></td>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($panggilan->sortBy('tanggal') as $i => $item)
                                    <tr>
                                        <td class="index-antrian">{{ $i + 1 }}</td>
                                        <td>{{ CarbonParse($item->tanggal, 'd/m/Y') }}</td>
                                        <td>{{ $item->jadwal_dokter->dokter->name }}</td>
                                        <td>{{ $item->no_reservasi }}</td>
                                        <td>{{ $item->no_reservasi }}</td>
                                        <td>
                                            @if (dateStore() >= $item->tanggal)
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
                                        <td colspan="6" style="text-align: center">Tidak ada reservasi</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="tab-pane fade  {{ isset($req->tab) ? ($req->tab == 'history' ? 'show active' : '') : '' }}"
                    id="history">
                    <div class="page-filter">
                        <div
                            class="item bulk-false collapse  {{ isset($req->tab) ? ($req->tab == 'panggilan' ? 'show' : '') : '' }}">
                            <div class="row">
                                <div class="col-1">
                                    <div class="form-group dp">
                                        <label>Tanggal Reservasi</label>
                                        <div class="datepicker">
                                            <input class="form-control required" type="text" name="tanggal_history"
                                                value="{{ isset($req->tanggal_history) ? $req->tanggal_history : '' }}"
                                                id="tanggal_history" />
                                        </div>
                                        <div class="inpt-apend"></div>
                                    </div>
                                </div>
                                <div class="col-1">
                                    <label>&nbsp;</label> <a class="btn btn--primary"
                                        href="{{ route('pemeriksaan') }}">Reset
                                        Tanggal</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table">
                        <table id="table-pasien">
                            <thead>
                                <tr>
                                    <th class="check-all"><span>No.</span></th>
                                    <th><span>No. Rekam Medis</span></th>
                                    <th><span>Tgl. Berobat</span></th>
                                    @if (Auth::user()->role->id != 1)
                                        <th><span>Dokter</span></th>
                                    @endif
                                    <th><span>Tindakan</span></th>
                                    <th><span>Keterangan</span></th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- {{ dd($history) }} --}}
                                @forelse ($history as $i => $item)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>{{ $item->pasien_rekam_medis ? $item->pasien_rekam_medis->id_rekam_medis : '' }}
                                        </td>
                                        <td>{{ CarbonParse($item->pasien_rekam_medis ? $item->pasien_rekam_medis->tanggal : now(), 'd/m/Y') }}
                                        </td>
                                        @if (Auth::user()->role->id != 1)
                                            <td>{{ $item->pasien_rekam_medis ? $item->pasien_rekam_medis->dokter->name : '' }}
                                            </td>
                                        @endif
                                        <td>{{ $item->pasien_rekam_medis ? $item->pasien_rekam_medis->tindakan : '' }}
                                        </td>
                                        <td>{{ $item->pasien_rekam_medis ? $item->pasien_rekam_medis->keterangan : '' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" style="text-align: center">Tidak ada history</td>
                                    </tr>
                                @endforelse
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
            table = $('#table-pemeriksaan').DataTable({
                // searching: false,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('datatable-pemeriksaan') }}",
                },
                columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    class: 'text-center'
                }, {
                    data: 'user_id',
                    name: 'user_id',
                }, {
                    data: 'name',
                    name: 'name'
                }, {
                    data: 'email',
                    name: 'email'
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
        $(document).ready(function() {
            $('#tanggal_on_site').datepicker({
                format: 'dd/mm/yyyy',
                autoclose: true,
                todayHighlight: true
            }).on('changeDate', function() {
                location.href = '{{ route('pemeriksaan') }}?tanggal_on_site=' + $('#tanggal_on_site')
                    .val() + '&tanggal_panggilan=' + $('#tanggal_panggilan')
                    .val() + '&tab=on_site';
            });

            $('#tanggal_panggilan').datepicker({
                format: 'dd/mm/yyyy',
                autoclose: true,
                todayHighlight: true
            }).on('changeDate', function() {
                location.href = '{{ route('pemeriksaan') }}?tanggal_on_site=' + $('#tanggal_on_site')
                    .val() + '&tanggal_panggilan=' + $('#tanggal_panggilan')
                    .val() + '&tab=panggilan';
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
                        url: '{{ route('delete-pemeriksaan') }}',
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
