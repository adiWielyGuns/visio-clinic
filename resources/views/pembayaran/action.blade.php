<a class="btn-edit" href="{{ route('show-staff') }}?id={{ $data->id }}"><img class="svg"
        src="{{ asset('images/ic-show.svg') }}" /></a><a class="btn-edit"
    href="{{ route('edit-staff') }}?id={{ $data->id }}"><img class="svg"
        src="{{ asset('images/ic-edit.svg') }}" /></a>


@if ($data->status == 'Released')
    <a class="btn-edit" onclick="hapus('{{ $data->id }}')" data-toggle="modal"><img class="svg"
            src="{{ asset('images/ic-delete.svg') }}" />
    </a>
@endif
