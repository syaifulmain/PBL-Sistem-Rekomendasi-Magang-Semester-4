@extends('layouts.template')

@section('content')
<div class="card">
    <div class="card-body">
        <h4 class="card-title">Data Kegiatan Magang</h4>
        <table class="table table-striped table-bordered" id="kegiatan-table">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Mahasiswa</th>
                    <th>Lowongan</th>
                    <th>Perusahaan</th>
                    <th>Tanggal Pengajuan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

@push('js')
<script>
    let kegiatanTable;
    $(function () {
        kegiatanTable = $('#kegiatan-table').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: "",
            columns: [
                { data: 'id', name: 'id', visible: false},
                { data: 'mahasiswa', name: 'mahasiswa' },
                { data: 'lowongan', name: 'lowongan' },
                { data: 'perusahaan', name: 'perusahaan', defaultContent: '-' },
                { data: 'tanggal_pengajuan', name: 'tanggal_pengajuan' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ],
            order: [0, 'DESC']
        });
    });
</script>
@endpush
@endsection
