@extends('layouts.template')

@section('content')
<div class="card">
    <div class="card-body">
        <h4 class="card-title">Daftar Riwayat Pengajuan</h4>
        <table class="table table-striped table-bordered" id="pengajuan-table">
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
                { data: 'id', name: 'id', visible: false },
                { data: 'judul_lowongan', name: 'lowonganMagang.judul' },
                { data: 'perusahaan', name: 'lowonganMagang.perusahaan.nama' },
                { data: 'tanggal_pengajuan', name: 'tanggal_pengajuan' },
                { data: 'status', name: 'status', render: function(data, type, row) {
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
                }},
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ],
            order: [0, 'desc']
        });
    });
</script>
@endpush
@endsection
