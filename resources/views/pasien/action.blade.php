<a class="btn-edit" href="{{ route('show-pasien') }}?id={{ $data->id }}"><img class="svg"
        src="{{ asset('images/ic-show.svg') }}" /></a><a class="btn-edit"
    href="{{ route('edit-pasien') }}?id={{ $data->id }}"><img class="svg"
        src="{{ asset('images/ic-edit.svg') }}" /></a>


<a class="btn-edit" onclick="hapus('{{ $data->id }}')" data-toggle="modal"><img class="svg"
        src="{{ asset('images/ic-delete.svg') }}" />
</a>
