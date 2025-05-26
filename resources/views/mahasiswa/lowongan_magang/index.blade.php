@extends('layouts.template')
@section('content')
    <div class="card h-100">
        <div class="card-body d-flex flex-column">
            <div class="row mb-3">
{{--                <div class="col-auto pr-0">--}}
{{--                    <button class="btn btn-outline-primary mr-2">Untuk Anda</button>--}}
{{--                </div>--}}
            </div>
            <div class="row flex-grow-1">
                <div class="col-md-5 mb-3 mb-md-0 pr-3 pr-md-0 border-right">
                    <div class="card h-100">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Daftar Perusahaan</h5>
                        </div>
                        <div class="alert alert-info text-center mb-0">
                            Apakah Anda puas dengan rekomendasi kami?
                            <button class="btn btn-success btn-sm ml-2" title="Puas">
                                <i class="fa fa-thumbs-up m-0"></i>
                            </button>
                            <button class="btn btn-danger btn-sm ml-1" title="Tidak Puas">
                                <i class="fa fa-thumbs-down m-0"></i>
                            </button>
                        </div>
                        <div class="card-body p-0">
                            <div class="scrollable" id="companies-list">
                                <table class="table table-hover table-bordered" id="lowongan-table">
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-7 pl-3 pl-md-0">
                    <div class="card h-100">
                        <div class="card-header bg-warning text-white">
                            <h5 class="mb-0">Detail</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="scrollable p-3" id="detail-content">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('css')
    <style>
        .vh-100 {
            height: 100vh;
        }

        .scrollable {
            overflow-y: auto;
            max-height: calc(100vh - 140px);
        }

        /* Hide table header */
        #lowongan-table thead {
            display: none;
        }

        /* Remove table borders for cleaner look */
        #lowongan-table,
        #lowongan-table td {
            border: none;
        }
    </style>
@endpush
@push('js')
    <script>
        $(document).ready(function () {
            window.loadLowonganDetail = function(id) {
                $.ajax({
                    url: '{{ route('mahasiswa.lowongan-magang.detail', ':id') }}'.replace(':id', id),
                    method: 'GET',
                    success: function (response) {
                        $('#detail-content').html(response);
                    },
                    error: function () {
                        $('#detail-content').html('<p class="text-danger">Gagal memuat detail lowongan.</p>');
                    }
                });
            }

            $('#lowongan-table').DataTable({
                processing: true,
                serverSide: true,
                lengthChange: false,
                ajax: '{{ route("mahasiswa.lowongan-magang.index") }}', // Make sure this route exists
                columns: [
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ],
                // paging: false,
                searching: false,
                info: false,
                ordering: false,
                language: {
                    processing: "Memuat data..."
                }
            });
        })
    </script>
@endpush
