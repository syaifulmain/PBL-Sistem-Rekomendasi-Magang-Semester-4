<a href="{{ route('mahasiswa.pengajuan-magang.show', $row->id) }}" class="btn btn-info btn-sm">
    <i class="fa fa-eye"></i> Detail
</a>
@if($row->status === 'diajukan')
    <button class="btn btn-danger btn-sm btn-delete" data-url="{{ route('mahasiswa.pengajuan-magang.delete', $row->id) }}">
        <i class="fa fa-trash"></i> Hapus
    </button>
@endif