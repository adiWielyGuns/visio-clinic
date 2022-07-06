@if ($data->status == 'Done')
    <a class="btn-edit" href="javascript:;"
        onclick="window.open('{{ route('show-pembayaran') }}?id={{ $data->id }}')"><img class="svg"
            src="{{ asset('images/ic-show.svg') }}" /></a>
@elseif($data->status == 'Waiting')
    <a class="btn-edit" href="javascript:;" title="Verifikasi Pembayaran"
        onclick="window.open('{{ route('show-pembayaran') }}?id={{ $data->id }}')"><img class="svg"
            src="{{ asset('images/ic-check.svg') }}" /></a>
@endif
<a class="btn-edit" href="javascript:;"
    onclick="window.open('{{ route('print-pembayaran') }}?id={{ $data->id }}')"><img class="svg"
        src="{{ asset('images/ic-printer.svg') }}" /></a>
@if ($data->status == 'Released')
    <a class="btn-edit" href="{{ route('edit-pembayaran') }}?id={{ $data->id }}"><img class="svg"
            src="{{ asset('images/ic-edit.svg') }}" /></a>
    <a class="btn-edit" onclick="hapus('{{ $data->id }}')"><img class="svg"
            src="{{ asset('images/ic-delete.svg') }}" /></a>
@endif
