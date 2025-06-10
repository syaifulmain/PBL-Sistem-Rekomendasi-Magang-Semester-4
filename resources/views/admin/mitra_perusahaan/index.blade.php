@extends('layouts.template')

@section('content')
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">{{$title}}</h4>
            <a class="btn btn-primary mb-4" href="{{ route('admin.mitra-perusahaan.create') }}"><i
                    class="fa fa-plus"></i> Tambah</a>
            <div class="table-responsive">
                <table id="mitra-perusahaan-table"
                       class="display table-hover expandable-table table-striped"
                       style="width:100%">
                    <thead>
                    <tr>
                        <th>Perusahaan</th>
                        <th>Alamat Perusahaan</th>
                        <th>Aksi</th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        $(function () {
            $('#mitra-perusahaan-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '',
                columns: [
                    {data: 'nama', name: 'nama'},
                    {data: 'alamat', name: 'alamat'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                responsive: true,
                language: {
                    url: '{{ asset("assets/js/datatables/language/id.json") }}'
                }
            });

            $(document).on('click', '.btn-delete', function (e) {
                e.preventDefault();

                swalAlertConfirm({
                    title: 'Hapus data ini?',
                    text: 'Data yang dihapus tidak bisa dikembalikan!',
                    url: $(this).data('url'),
                    onSuccess: function () {
                        $('#mitra-perusahaan-table').DataTable().ajax.reload();
                    }
                });
            });
        });
    </script>
@endpush

