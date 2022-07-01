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
                        <span>Kembali ke Index Pembayaran</span>
                    </div>
                </a></div>
        </div>
        <div class="page-main">
            <div class="add-contact">
                <div class="container--small">
                    <form class="form--add-contact" id="form-data">
                        @csrf
                        <div class="add-contact__title">
                            <h1 class="title--primary">Tambah Pembayaran Baru</h1>
                        </div>
                        <div class="form-group ">
                            <label>Kode Invoice</label>
                            <input class="form-control required" disabled value="{{ $data->nomor_invoice }}"
                                type="text" name="no_invoice" id="no_invoice" />

                            <input type="hidden" id="id" name="id" value="{{ $data->id }}">
                        </div>
                        <div class="form-group dp">
                            <label>Tgl Invoice</label>
                            <input class="form-control required" disabled
                                value="{{ CarbonParse($data->tanggal, 'd/m/Y') }}" type="text" name="tanggal"
                                id="tanggal" />
                            <div class="inpt-apend"></div>
                        </div>
                        <div class="form-group">
                            <label>Pasien</label>
                            <input type="text" class="form-control required" disabled
                                value="{{ $data->pasien_rekam_medis->pasien->name }} - {{ $data->pasien_rekam_medis->id_rekam_medis }}">
                        </div>
                        <div class="form-group ">
                            <label>Tindakan/Resep</label>
                            <input class="form-control required" value="{{ $data->pasien_rekam_medis->tindakan }}"
                                type="text" disabled id="tindakan_rekam_medis" />
                        </div>
                        <div class="form-group ">
                            <label>Keterangan</label>
                            <input class="form-control required" value="{{ $data->pasien_rekam_medis->keterangan }}"
                                type="text" disabled id="keterangan_rekam_medis" />
                        </div>
                        <div class="form-group ">
                            <label>Item</label>
                            <select class="select select-contact-group" onchange="loadingItem(true)" multiple id="item_id"
                                name="item_id" title="Pilih Item">
                                @foreach ($item as $d)
                                    <option value="{{ $d->id }}">
                                        {{ $d->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div id="data-tagihan" class="collapse show data-tagihan">
                            <div class="info-panel" id="item">
                                @foreach ($data->pembayaran_detail as $item)
                                    <label>
                                        <span>{{ $item->item->name }}</span>
                                        <b class="price">{{ number_format($item->total) }}</b>
                                        <input type="hidden" name="item[]" value="{{ $item->item_id }}">
                                    </label>
                                @endforeach
                                <hr>
                                <label>
                                    <span>Total</span>
                                    <b class="price">{{ number_format($data->total) }}</b>
                                    <input type="hidden" name="total" value="{{ $data->total }}">
                                </label>
                            </div>
                        </div>
                        <div class="form-group loading-item collapse">
                            <label style="text-align: center" for="">Loading Item...</label>
                        </div>
                        <div class="form-group rekam_medis_id_div collapse show">
                            <label>Metode Pembayaran</label>
                            <select class="select select-contact-group" id="metode_pembayaran" name="metode_pembayaran"
                                title="Pilih Metode Pembayaran">
                                @foreach (\App\Models\Pembayaran::$enumMetodePembayaran as $item)
                                    <option value="{{ $item }}">{{ $item }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group metode_pembayaran_div collapse show">
                            <label>Bank</label>
                            <select class="select select-contact-group metode_pembayaran" id="bank" name="bank"
                                title="Pilih Bank">
                                @foreach (\App\Models\Pembayaran::$enumBank as $item)
                                    <option value="{{ $item }}">{{ $item }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group metode_pembayaran_div collapse show">
                            <label>No. Rekening</label>
                            <input class="form-control required metode_pembayaran" type="text" name="no_rekening"
                                id="no_rekening" />
                        </div>
                        <div class="form-group metode_pembayaran_div collapse show">
                            <label>No. Transaksi</label>
                            <input class="form-control required metode_pembayaran" type="text" name="no_transaksi"
                                id="no_transaksi" />
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
        var xhr = [];
        $(document).ready(function() {
            $('#tanggal').datepicker({
                format: 'dd/mm/yyyy',
                autoclose: true,
                todayHighlight: true
            });


            $('#item_id').change(debounce(function() {
                callApiItem();
            }, 1000));
            var selectedItem = [];
            @foreach ($data->pembayaran_detail as $item)
                selectedItem.push('{{ $item->item_id }}');
            @endforeach

            $('#item_id').val(selectedItem);
            $('#metode_pembayaran').val('{{ $data->metode_pembayaran }}');

            @if ($data->metode_pembayaran == 'Non Tunai')
                $('#bank').val('{{ $data->bank }}');
                $('#no_rekening').val('{{ $data->no_rekening }}');
                $('#no_transaksi').val('{{ $data->no_transaksi }}');
                $('.metode_pembayaran_div').addClass('show');
            @else
                $('.metode_pembayaran_div').removeClass('show');
            @endif
        })

        function loadingItem(loading) {
            if (loading) {
                $('.loading-item').addClass('show')
            } else {
                $('.loading-item').removeClass('show')
            }
        }

        $('#metode_pembayaran').change(function() {
            var metodePembayaran = $(this).val();
            if (metodePembayaran == 'Non Tunai') {
                $('.metode_pembayaran_div').addClass('show');
            } else {
                $('.metode_pembayaran_div').removeClass('show');
            }
            $('.metode_pembayaran').val(null);
        });

        function pasienGenerate() {
            $('.rekam_medis_id_div').addClass('show');

            var tindakan = $('#rekam_medis_id').find('option:selected').data('tindakan');
            var keterangan = $('#rekam_medis_id').find('option:selected').data('keterangan');
            var pasien_id = $('#rekam_medis_id').find('option:selected').data('pasien_id');

            $('#tindakan_rekam_medis').val(tindakan);
            $('#keterangan_rekam_medis').val(keterangan);
            $('#pasien_id').val(pasien_id);
        }

        function debounce(func, wait, immediate) {
            var timeout;
            console.log('tes')

            return function executedFunction() {
                var context = this;
                var args = arguments;

                var later = function() {
                    timeout = null;
                    if (!immediate) func.apply(context, args);
                };

                var callNow = immediate && !timeout;

                clearTimeout(timeout);

                timeout = setTimeout(later, wait);

                if (callNow) func.apply(context, args);
            };


        };

        function callApiItem() {
            $('#item').html('');
            xhr.forEach((d, i) => {
                console.log(d);
                d.abort();
            });
            $.ajax({
                url: '{{ route('item-generate-pembayaran') }}',
                data: {
                    item: function() {
                        return $('#item_id').val();
                    }
                },
                beforeSend: function(jqXHR) {
                    xhr.push(jqXHR);
                },
                type: 'get',
                success: function(data) {
                    if (data.item.length == 0) {
                        $('#data-tagihan').removeClass('show');
                    } else {
                        $('#data-tagihan').addClass('show');
                    }
                    var total = 0;
                    data.item.forEach(d => {
                        var item = '<label>' +
                            '<span>' + d.name + '</span>' +
                            '<b class="price">' + accounting.formatNumber(d.harga, {
                                precision: 0
                            }) + '</b>' +

                            '<input type="hidden" name="item[]" class="item" value="' +
                            d.id + '">' +
                            '</label>';
                        total += d.harga;
                        $('#item').append(item);
                    });


                    var item = '<hr><label>' +
                        '<span>Total</span>' +
                        '<b class="price">' + accounting.formatNumber(total, {
                            precision: 0
                        }) + '</b>' +
                        '<input type="hidden" name="total" class="item" value="' + total + '">' +
                        '</label>';
                    $('#item').append(item);

                    $('.loading-item').removeClass('show')
                },
                error: function(data) {
                    itemGenerate();
                }
            });
        }

        function cetak(id) {
            window.open('{{ route('print-pembayaran') }}?id=' + id);
        }

        function store() {
            var validation = 0;
            $('#form-data .required').each(function() {
                var par = $(this).parents('.form-group');
                if ($(this).val() == '' || $(this).val() == null) {
                    if ($(par).hasClass('show')) {
                        console.log($(this));
                        $(this).addClass('is-invalid');
                        validation++
                    }
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
                        url: '{{ route('update-pembayaran') }}',
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
                                    cetak(data.id);
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
