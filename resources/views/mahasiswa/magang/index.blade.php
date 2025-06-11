@extends('layouts.template')
@section('content')
    @if($data->isEmpty())
        <div class="card">
            <div class="card-body">
                <div class="alert alert-danger">Anda belum mengikuti kegiatan magang.</div>
                {{--                Tidak ada data magang yang ditemukan--}}
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
    <style>
        #monitoring_table thead {
            display: none;
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
                {
                    data: 'action',
                    name: 'action',
                    width: '100%' // atau beri nilai besar seperti '400px'
                }            ],
            paging: false,
            info: false,
            ordering: false,
            searching: false,
            language: {
                url: '{{ asset("assets/js/datatables/language/id.json") }}'
            }
        });
    </script>
@endpush
