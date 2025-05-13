@extends('layouts.template')

@section('content')
<div class="card">
    <div class="card-body">
        <h4 class="card-title">Data Periode Magang</h4>
        <!-- Filter Pengguna -->
        <div class="row mb-2">
            <div class="col">
                <div class="form-group">
                    <label for="filter-level">Filter Level</label>
                    <select id="filter-level" class="form-control">
                        <option value="">Semua Level</option>
                        <option value="ADMIN">Admin</option>
                        <option value="DOSEN">Dosen</option>
                        <option value="MAHASISWA">Mahasiswa</option>
                    </select>
                </div>
            </div>
        </div>
        <a class="btn btn-primary mb-4" href="{{ route('admin.manajemen-pengguna.create') }}"><i class="fa fa-plus"></i> Tambah</a>
        <table class="table table-striped table-bordered" id="periode-table">
            <thead>
                <tr>
                    <th>Username</th>
                    <th class="text-nowrap">Nama</th>
                    <th class="text-nowrap">Level</th>
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
                ajax: {
                    data: function (d) {
                        d.level = $('#filter-level').val();
                        return d;
                    },
                },
                columns: [
                    { data: 'username', name: 'username' },
                    { data: 'nama', name: 'nama' },
                    { data: 'level', name: 'level' },
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

            // Filter handler
            $('#filter-level').on('change', function () {
                $('#periode-table').DataTable().ajax.reload();
            });
        });
    </script>
@endpush
@endsection
