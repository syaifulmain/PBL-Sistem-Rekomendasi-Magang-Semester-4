@extends('layouts.template')
@section('content')
    @if($data->isEmpty())
        <div class="card">
            <div class="card-body">
                <div class="alert alert-danger">Tidak Ada</div>
            </div>
        </div>
    @else
        <table class="table table-borderless" id="monitoring_table" style="width: 100%;">
            <tbody>
            </tbody>
        </table>
    @endif
@endsection
@push('css')
    <style>
        .card-hover {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }
    </style>
@endpush
@push('js')
    <script>
        $('#monitoring_table').DataTable({
            processing: true,
            serverSide: true,
            lengthChange: false,
            ajax: '',
            columns: [
                { data: 'mahasiswa', name: 'mahasiswa.nama', visible: false, searchable: true},
                { data: 'lowongan', name: 'lowongan.judul', visible: false, searchable: true },
                { data: 'perusahaan', name: 'perusahaan.nama', visible: false, searchable: true },
                { data: 'status', name: 'status', visible: false, searchable: true},
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    width: '100%' // atau beri nilai besar seperti '400px'
                }            ],
            // paging: false,
            info: false,
            ordering: false,
            language: {
                processing: "Memuat data..."
            }
        });
    </script>

@endpush
