@if (isset($detailUrl))
    <a href="{{ $detailUrl }}" class="btn btn-info btn-sm">
        <i class="fa fa-eye"></i> Detail
    </a>
@endif

@if(isset($editUrlModal))
    <button onclick="modalAction('{{ $editUrlModal }}')" class="btn btn-warning btn-sm">
        <i class="fa fa-edit"></i> Edit
    </button>
@endif

@if (isset($editUrl))
    <a href="{{ $editUrl }}" class="btn btn-warning btn-sm">
        <i class="fa fa-edit"></i> Edit
    </a>
@endif

@if (isset($deleteUrl))
    <button class="btn btn-danger btn-sm btn-delete" data-url="{{ $deleteUrl }}">
        <i class="fa fa-trash"></i> Hapus
    </button>
@endif

@if (isset($prosesUrl))
    <a href="{{ $prosesUrl }}" class="btn btn-primary btn-sm">
        <i class="fa fa-cog"></i> Proses
    </a>
@endif

