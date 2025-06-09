@extends('layouts.template')

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h4 class="card-title">{{$title}}</h4>
                    <a class="btn btn-primary mb-4"
                       href="{{ route('admin.manajemen-pengguna.create', ['level' => $level]) }}">
                        <i class="fa fa-plus"></i> Tambah</a>
                </div>
                <div>
                    <button onclick="modalAction('{{ route('admin.manajemen-pengguna.import.index',['level' => $level]) }}')" class="btn btn-success">
                        <i class="fa fa-file-excel-o mr-1"></i> Import
                    </button>
                </div>
            </div>

            <div class="table-responsive">
                <table id="manajemen-pengguna-table"
                       class="display table-hover expandable-table table-striped"
                       style="width:100%">
                    <thead>
                    <tr>
                        {{--                        <th class="text-center" width="5%">No</th>--}}
                        <th>Username</th>
                        <th>Nama</th>
                        @if($level === 'mahasiswa')
                            <th>Status</th>
                        @endif
                        <th>Aksi</th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Modal Import -->
    <div id="myModal" class="modal fade animate shake" tabindex="-1" data-backdrop="static"
         data-keyboard="false" data-width="75%"></div>
@endsection
@push('js')
    <script>
        function modalAction(url = '') {
            $('#myModal').load(url, function () {
                $('#myModal').modal('show');
            });
        }
    </script>
    <script>
        $(function () {
            $('#manajemen-pengguna-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '',
                columns: [
                    // {data: 'DT_RowIndex', className: 'text-center'},
                    {data: 'username', name: 'username'},
                    {data: 'nama', name: 'nama', orderable: false},
                        @if($level === 'mahasiswa')
                    {
                        data: 'status', name: 'status', orderable: false, searchable: false
                    },
                        @endif
                    {
                        data: 'action', name: 'action', orderable: false, searchable: false
                    }
                ],
                responsive: true,
                language: {
                    url: '{{ asset("assets/js/datatables/language/id.json") }}'
                }
            });

            // Delete handler
            $(document).on('click', '.btn-delete', function (e) {
                e.preventDefault();
                swalAlertConfirm({
                    title: 'Hapus data ini?',
                    text: 'Data yang dihapus tidak bisa dikembalikan!',
                    url: $(this).data('url'),
                    onSuccess: function () {
                        $('#manajemen-pengguna-table').DataTable().ajax.reload();
                    }
                });
            });
        })
        ;
    </script>
@endpush

