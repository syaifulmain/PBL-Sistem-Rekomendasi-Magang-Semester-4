@extends('layouts.template')

@section('content')
<div class="card">
    <div class="card-body">
        <h4 class="card-title">Data Kegiatan Magang</h4>
        <table id="kegiatan-table"
               class="display table table-hover expandable-table table-striped table-borderless"
                style="width:100%">
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
                { data: 'mahasiswa', name: 'mahasiswa.nama' },
                { data: 'lowongan', name: 'lowongan.judul' },
                { data: 'perusahaan', name: 'perusahaan.nama', defaultContent: '-' },
                { data: 'tanggal_pengajuan', name: 'tanggal_pengajuan' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ],
            order: [0, 'DESC'],
            language: {
                url: '{{ asset("assets/js/datatables/language/id.json") }}'
            }
        });
    });
</script>
@endpush
@endsection
