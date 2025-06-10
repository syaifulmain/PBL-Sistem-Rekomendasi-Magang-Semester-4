@extends('layouts.template')

@section('content')
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Daftar Pengajuan Magang</h4>
            <a class="btn btn-primary mb-4" href="{{ route('mahasiswa.pengajuan-magang.create') }}">
                <i class="fa fa-plus"></i> Buat Pengajuan
            </a>
            <table id="pengajuan-table"
                class="display table table-hover expandable-table table-striped table-borderless"
                style="width:100%">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Judul Lowongan</th>
                    <th>Perusahaan</th>
                    <th>Tanggal Pengajuan</th>
                    <th class="text-nowrap" width="1">Status</th>
                    <th class="text-nowrap" width="1">Aksi</th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    @push('js')
        <script>
            let pengajuanTable;
            $(function () {
                pengajuanTable = $('#pengajuan-table').DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    ajax: "",
                    columns: [
                        {data: 'id', name: 'id', visible: false},
                        {data: 'judul_lowongan', name: 'lowonganMagang.judul'},
                        {data: 'perusahaan', name: 'lowonganMagang.perusahaan.nama'},
                        {data: 'tanggal_pengajuan', name: 'tanggal_pengajuan'},
                        {
                            data: 'status', name: 'status', render: function (data, type, row) {
                                let badge = {
                                    'diajukan': 'warning',
                                    'disetujui': 'success',
                                    'ditolak': 'danger',
                                    'batal': 'secondary'
                                };
                                let text = {
                                    'diajukan': 'Diajukan',
                                    'disetujui': 'Disetujui',
                                    'ditolak': 'Ditolak',
                                    'batal': 'Dibatalkan'
                                };
                                return `<span class="badge badge-${badge[data] ?? 'secondary'}">${text[data] ?? 'Dibatalkan'}</span>`;
                            }
                        },
                        {data: 'action', name: 'action', orderable: false, searchable: false}
                    ],
                    order: [0, 'desc'],
                    language: {
                        url: '{{ asset("assets/js/datatables/language/id.json") }}'
                    }
                });

                $(document).on('click', '.btn-delete', function (e) {
                    e.preventDefault();

                    swalAlertConfirm({
                        title: 'Batalkan pengajuan ini?',
                        text: 'Tindakan ini tidak dapat dibatalkan.',
                        url: $(this).data('url'),
                        onSuccess: function () {
                            pengajuanTable.ajax.reload();
                        }
                    });
                });
            });
        </script>
    @endpush
@endsection
