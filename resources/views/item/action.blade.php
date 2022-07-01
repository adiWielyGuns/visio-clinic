    <a class="btn-edit" href="{{ route('edit-item') }}?id={{ $data->id }}"><img class="svg"
            src="{{ asset('images/ic-edit.svg') }}" /></a>

    @if (count($data->pembayaran_detail) == 0)
        <a class="btn-edit" onclick="hapus('{{ $data->id }}')" data-toggle="modal"><img class="svg"
                src="{{ asset('images/ic-delete.svg') }}" />
        </a>
    @endif
