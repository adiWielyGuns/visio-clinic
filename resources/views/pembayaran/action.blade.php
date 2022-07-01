<a class="btn-edit" href="javascript:;"
    onclick="window.open('{{ route('print-pembayaran') }}?id={{ $data->id }}')"><img class="svg"
        src="{{ asset('images/ic-printer.svg') }}" /></a>
<a class="btn-edit" href="{{ route('edit-pembayaran') }}?id={{ $data->id }}"><img class="svg" src="{{ asset('images/ic-edit.svg') }}" /></a>
<a class="btn-edit" onclick="hapus('{{ $data->id }}')"><img class="svg"
        src="{{ asset('images/ic-delete.svg') }}" /></a>
