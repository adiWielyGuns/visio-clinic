@if (Carbon\Carbon::now()->format('D') == 'Sun' or Auth::user()->role->name == 'SuperAdmin')
    <a class="btn-edit" href="{{ route('edit-jadwal-dokter') }}?id={{ $data->id }}"><img class="svg"
            src="{{ asset('images/ic-edit.svg') }}" /></a>
@endif

@if (count($data->jadwal_dokter_log) == 0)
    <a class="btn-edit" onclick="hapus('{{ $data->id }}')" data-toggle="modal"><img class="svg"
            src="{{ asset('images/ic-delete.svg') }}" />
    </a>
@endif
