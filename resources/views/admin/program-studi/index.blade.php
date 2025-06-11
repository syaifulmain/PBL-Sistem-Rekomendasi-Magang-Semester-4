@extends('layouts.template')

@section('content')
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Data Program Studi</h4>
            <button onclick="modalAction('{{ route('admin.program-studi.create') }}')" class="btn btn-primary mb-3">
                <i class="fa fa-plus"></i> Tambah
            </button>

            <table id="tabelProdi"
                   class="display table table-hover expandable-table table-striped table-borderless"
                   style="width:100%">
                <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama</th>
                    <th>Jenjang</th>
                    <th>Aksi</th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
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
            let table = $('#tabelProdi').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route("admin.program-studi.index") }}',
                columns: [
                    {data: 'kode', name: 'kode'},
                    {data: 'nama', name: 'nama'},
                    {data: 'jenjang', name: 'jenjang'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                responsive: true,
                language: {
                    url: '{{ asset("assets/js/datatables/language/id.json") }}'
                }
            });

            {{--// Edit data--}}
            {{--$('#tabelProdi').on('click', '.edit', function () {--}}
            {{--    let id = $(this).data('id');--}}
            {{--    $.get(`{{ url('admin/program-studi/edit') }}/${id}`, function (res) {--}}
            {{--        $('#modalContent').html(res);--}}
            {{--        $('#modalProdi').modal('show');--}}
            {{--    });--}}
            {{--});--}}

            // Delete handler
            $(document).on('click', '.btn-delete', function (e) {
                e.preventDefault();
                swalAlertConfirm({
                    title: 'Hapus data ini?',
                    text: 'Data yang dihapus tidak bisa dikembalikan!',
                    url: $(this).data('url'),
                    onSuccess: function () {
                        $('#tabelProdi').DataTable().ajax.reload();
                    }
                });
            });
        });
    </script>
@endpush
