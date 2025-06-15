@extends('layouts.template')

@section('content')
    @isset($pesan)
        <div class="card h-100">
            <div class="card-body d-flex flex-column">
                <div class="alert alert-info">
                    {{$pesan}}
                </div>
            </div>
        </div>
    @else
        <div class="card h-100">

            <div class="card-body d-flex flex-column">
                @isset($all)
                    <div class="mb-3">
                        {{--                <div class="col-auto pr-0">--}}
                        {{--                    <button class="btn btn-outline-primary mr-2">Untuk Anda</button>--}}
                        {{--                </div>--}}
                        <div class="input-group">
                            <input type="text" id="search-lowongan" class="form-control"
                                   placeholder="Cari lowongan atau perusahaan...">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="button" id="btn-cari-lowongan">
                                    <i class="fa fa-search"></i> Cari
                                </button>
                            </div>
                        </div>
                    </div>
                @endisset

                <div class="row flex-grow-1">
                    <div class="col-md-5 mb-3 mb-md-0 pr-3 pr-md-0 border-right">
                        <div class="card h-100">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">Daftar Perusahaan</h5>
                            </div>
                            {{--                        <div class="alert alert-info text-center mb-0">--}}
                            {{--                            Apakah Anda puas dengan rekomendasi kami?--}}
                            {{--                            <button class="btn btn-success btn-sm ml-2" title="Puas">--}}
                            {{--                                <i class="fa fa-thumbs-up m-0"></i>--}}
                            {{--                            </button>--}}
                            {{--                            <button class="btn btn-danger btn-sm ml-1" title="Tidak Puas">--}}
                            {{--                                <i class="fa fa-thumbs-down m-0"></i>--}}
                            {{--                            </button>--}}
                            {{--                        </div>--}}
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
                            <div class="card-body p-0">
                                <div class="scrollable" id="detail-content">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

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
                    window.loadLowonganDetail = function (id) {
                        $.ajax({
                            url: '{{ route('mahasiswa.lowongan-magang.detail', ':id') }}?rekomendasi={{ $rekomendasi ?? false }}'.replace(':id', id),
                            method: 'GET',
                            success: function (response) {
                                $('#detail-content').html(response);
                            },
                            error: function () {
                                $('#detail-content').html('<p class="text-danger">Gagal memuat detail lowongan.</p>');
                            }
                        });
                    }

                    let lowonganTable = $('#lowongan-table').DataTable({
                        processing: true,
                        serverSide: true,
                        lengthChange: false,
                        ajax: {
                            url: '',
                            data: function (d) {
                                d.search_query = $('#search-lowongan').val();
                            }
                        },
                        columns: [
                            {data: 'action', name: 'action', orderable: false, searchable: false}
                        ],
                        // paging: false,
                        searching: false,
                        info: false,
                        ordering: false,
                        responsive: true,
                        language: {
                            url: '{{ asset("assets/js/datatables/language/id.json") }}'
                        }
                    });

                    @isset($all)
                    $('#btn-cari-lowongan').on('click', function () {
                        showEmptyLowonganMessage();
                        lowonganTable.ajax.reload();
                    });

                    $('#search-lowongan').on('keypress', function (e) {
                        if (e.which == 13) {
                            showEmptyLowonganMessage();
                            lowonganTable.ajax.reload();
                            return false;
                        }
                    });

                    let searchTimeout;
                    $('#search-lowongan').on('keyup', function () {
                        clearTimeout(searchTimeout);
                        searchTimeout = setTimeout(function () {
                            showEmptyLowonganMessage();
                            lowonganTable.ajax.reload();
                        }, 500);
                    });
                    @endisset

                    function showEmptyLowonganMessage() {
                        $('#detail-content').html('<div class="text-center p-5 text-muted">Pilih lowongan dari daftar untuk melihat detail atau gunakan pencarian.</div>');
                    }

                    if (lowonganTable.rows().count() === 0) {
                        showEmptyLowonganMessage();
                    }
                })
            </script>
        @endpush
    @endif
@endsection
