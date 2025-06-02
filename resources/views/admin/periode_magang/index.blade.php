@extends('layouts.template')

@section('content')
<div class="card">
    <div class="card-body">
        <h4 class="card-title">Data Periode Magang</h4>
        <a class="btn btn-primary mb-4" href="{{ route('admin.periode-magang.create') }}"><i class="fa fa-plus"></i> Tambah</a>
        <table class="table table-striped table-bordered" id="periode-table">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th width="1" class="text-nowrap">Tanggal Mulai</th>
                    <th width="1" class="text-nowrap">Tanggal Selesai</th>
                    <th>Tahun Akademik</th>
                    <th>Semester</th>
                    {{-- <th width="1" class="text-nowrap">Tanggal Pendaftaran Mulai</th>
                    <th width="1" class="text-nowrap">Tanggal Pendaftaran Selesai</th> --}}
                    <th width="1">Aksi</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

@push('js')
    <script>
        $(function () {
            $('#periode-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '',
                columns: [
                    { data: 'nama', name: 'nama' },
                    { data: 'tanggal_mulai', name: 'tanggal_mulai' },
                    { data: 'tanggal_selesai', name: 'tanggal_selesai' },
                    { data: 'tahun_akademik', name: 'tahun_akademik' },
                    { data: 'semester', name: 'semester' },
                    // { data: 'tanggal_pendaftaran_mulai', name: 'tanggal_pendaftaran_mulai' },
                    // { data: 'tanggal_pendaftaran_selesai', name: 'tanggal_pendaftaran_selesai' },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ]
            });

            // Delete handler
            $(document).on('click', '.btn-delete', function (e) {
                e.preventDefault();

                swalAlertConfirm({
                    title: 'Hapus data ini?',
                    text: 'Data yang dihapus tidak bisa dikembalikan!',
                    url: $(this).data('url'),
                    onSuccess: function () {
                        $('#periode-table').DataTable().ajax.reload();
                    }
                });
            });
        });
    </script>
@endpush
@endsection
