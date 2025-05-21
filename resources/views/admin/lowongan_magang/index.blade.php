@extends('layouts.template')

@section('content')
<div class="card">
    <div class="card-body">
        <h4 class="card-title">Data Lowongan Magang</h4>
        <a class="btn btn-primary mb-4" href="{{ route('admin.lowongan-magang.create') }}">
            <i class="fa fa-plus"></i> Tambah
        </a>
        <table class="table table-striped table-bordered" id="lowongan-table">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Judul</th>
                    <th>Perusahaan</th>
                    <th>Periode</th>
                    <th>Tgl Mulai Daftar</th>
                    <th>Tgl Selesai Daftar</th>
                    <th>Tgl Mulai Magang</th>
                    <th>Tgl Selesai Magang</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

@push('js')
<script>
    let lowonganTable;
    $(function () {
        lowonganTable = $('#lowongan-table').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: "{{ route('admin.lowongan-magang.index') }}",
            columns: [
                { data: 'id', name: 'id', visible: false},
                { data: 'judul', name: 'judul' },
                { data: 'perusahaan', name: 'perusahaan', defaultContent: '-' },
                { data: 'periode_magang', name: 'periode_magang', defaultContent: '-' },
                { data: 'tanggal_mulai_daftar', name: 'tanggal_mulai_daftar' },
                { data: 'tanggal_selesai_daftar', name: 'tanggal_selesai_daftar' },
                { data: 'tanggal_mulai_magang', name: 'tanggal_mulai_magang' },
                { data: 'tanggal_selesai_magang', name: 'tanggal_selesai_magang' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ],
            order: [0, 'DESC']
        });

        // Hapus data
        $(document).on('click', '.btn-delete', function (e) {
            e.preventDefault();

            swalAlertConfirm({
                title: 'Hapus data ini?',
                text: 'Data yang dihapus tidak bisa dikembalikan!',
                url: $(this).data('url'),
                onSuccess: function () {
                    lowonganTable.ajax.reload();
                }
            });
        });
    });
</script>
@endpush
@endsection
