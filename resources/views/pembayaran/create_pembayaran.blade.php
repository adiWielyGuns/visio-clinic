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
                            <input class="form-control required" disabled type="text" name="no_invoice"
                                id="no_invoice" />
                        </div>
                        <div class="form-group dp">
                            <label>Tgl Invoice</label>
                            <input class="form-control required" value="{{ CarbonParse(now(), 'd/m/Y') }}" type="text"
                                name="tanggal" id="tanggal" />
                            <div class="inpt-apend"></div>
                        </div>
                        <div class="form-group">
                            <label>Cari Pasien</label>
                            <select class="select select-contact-group required" onchange="pasienGenerate()"
                                id="rekam_medis_id" name="rekam_medis_id" title="Pilih Item">
                                @foreach ($rekamMedis as $d)
                                    <option data-tindakan="{{ $d->tindakan }}" data-keterangan="{{ $d->keterangan }}"
                                        data-pasien_id="{{ $d->pasien_id }}" value="{{ $d->id }}">
                                        {{ $d->pasien->name }} - {{ $d->id_rekam_medis }}
                                    </option>
                                @endforeach
                            </select>
                            <input type="hidden" id="pasien_id" name="pasien_id" class="required">
                        </div>
                        <div class="form-group rekam_medis_id_div collapse">
                            <label>Tindakan/Resep</label>
                            <input class="form-control required" type="text" disabled id="tindakan_rekam_medis" />
                        </div>
                        <div class="form-group rekam_medis_id_div collapse">
                            <label>Keterangan</label>
                            <input class="form-control required" type="text" disabled id="keterangan_rekam_medis" />
                        </div>
                        <div class="form-group rekam_medis_id_div collapse">
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
                        <div id="data-tagihan" class="collapse data-tagihan">
                            <div class="info-panel" id="item">
                                <label>
                                    <span>Biaya Penanganan</span>
                                    <b class="price">100.000</b>
                                </label>
                                <label>
                                    <span>Biaya Penanganan</span>
                                    <b class="price">100.000</b>
                                </label>
                            </div>
                        </div>
                        <div class="form-group loading-item collapse">
                            <label style="text-align: center" for="">Loading Item...</label>
                        </div>
                        <div class="form-group rekam_medis_id_div collapse">
                            <label>Metode Pembayaran</label>
                            <select class="select select-contact-group" id="metode_pembayaran" name="metode_pembayaran"
                                title="Pilih Metode Pembayaran">
                                @foreach (\App\Models\Pembayaran::$enumMetodePembayaran as $item)
                                    <option value="{{ $item }}">{{ $item }}</option>
                                @endforeach
                            </select>
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
            generateKode();


            $('#item_id').change(debounce(function() {
                callApiItem();
            }, 1000));
        })

        function loadingItem(loading) {
            if (loading) {
                $('.loading-item').addClass('show')
            } else {
                $('.loading-item').removeClass('show')
            }
        }

        function generateKode() {
            $.ajax({
                url: '{{ route('generate-kode-pembayaran') }}',
                type: 'get',
                data: {
                    tanggal: function() {
                        return $('#tanggal').val();
                    }
                },
                success: function(data) {
                    $('#no_invoice').val(data.kode);
                    $('#metode_pembayaran').val(data.kode);
                },
                error: function(data) {
                    generateKode();
                }
            });
        }

        $('#metode_pembayaran').change(function() {
            var metodePembayaran = $(this).val();
            if (metodePembayaran == 'Transfer Bank') {
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
                        total += d.harga * 1;
                        $('#item').append(item);
                    });


                    var item = '<hr><label>' +
                        '<span>Total</span>' +
                        '<b class="price">' + accounting.formatNumber(total, {
                            precision: 0
                        }) + '</b>' +
                        '<input type="hidden" name="total" class="item" value="' + total * 1 + '">' +
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
                        url: '{{ route('store-pembayaran') }}',
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
