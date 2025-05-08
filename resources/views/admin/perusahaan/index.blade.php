@extends('layouts.template')
@section('content')
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Data Mitra</h4>
            <div class="mb-3">
                <form class="d-flex flex-wrap align-items-center" id="search-form">
                    <div class="flex-grow-1 me-2 mr-2">
                        <input type="text" class="form-control w-100" id="cari-perusahaan"
                               placeholder="Ketik Data Yang Anda Cari">
                    </div>
                    <div class="d-flex flex-nowrap">
                        <button class="btn btn-primary me-2 mr-2" type="submit">
                            <i class="ti-search icon-md"></i>
                        </button>
                        <button class="btn btn-warning me-2 mr-2" type="button" id="reset-search">
                            <i class="ti-reload icon-md"></i>
                        </button>
                        <a class="btn btn-success" href="{{route('admin.perusahaan.create')}}" id="tambah-data">
                            <i class="ti-plus icon-md"></i>
                        </a>
                    </div>
                </form>
            </div>

            <div class="table-responsive">
                <table id="perusahaan-table" class="table table-hover table-striped table-bordered">
                    <thead>
                    <tr>
                        <th>Perusahaan</th>
                        <th>Alamat Perusahaan</th>
                        <th>Aksi</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        $(document).ready(function () {
            let table = $('#perusahaan-table').DataTable({
                "processing": true,
                "serverSide": true,
                "searching": false,
                "lengthChange": false,
                "ajax": {
                    "url": "{{ route('admin.perusahaan.list') }}",
                    "data": function (d) {
                        d.search = $('#cari-perusahaan').val();
                    }
                },
                "columns": [
                    {
                        "data": "nama",
                        "orderable": false,
                        "searchable": false,
                    },
                    {
                        "data": "alamat",
                        "orderable": false,
                        "searchable": false,
                    },
                    {
                        "data": "id",
                        "orderable": false,
                        "searchable": false,
                        "render": function (data) {
                            return `
                            <div class="d-flex justify-content-center">
                                <a href="/admin/perusahaan/detail/${data}" class="btn btn-primary pt-1 pb-1 mr-2">Detail</a>
                                <button class="btn btn-danger delete-btn pt-1 pb-1" data-id="${data}">Hapus</button>
                            </div>
                            `
                        }
                    }
                ],
                "language": {
                    "zeroRecords": "Tidak ada data yang ditemukan",
                    "info": "_START_ sampai _END_ dari _TOTAL_ data",
                    "infoEmpty": "Tidak ada data tersedia",
                    "processing": "Memuat...",
                    "paginate": {
                        "first": "Pertama",
                        "last": "Terakhir",
                        "next": "Selanjutnya",
                        "previous": "Sebelumnya"
                    }
                }
            });

            $('#search-form').on('submit', function (e) {
                e.preventDefault();
                table.ajax.reload();
            });

            $('#reset-search').on('click', function () {
                $('#cari-perusahaan').val('');
                table.ajax.reload();
            });


            // Delete confirmation with SweetAlert
            $(document).on('click', '.delete-btn', function () {
                const id = $(this).data('id');
                swal({
                    title: "Apakah Anda yakin?",
                    text: "Data yang dihapus tidak dapat dikembalikan!",
                    icon: "warning",
                    buttons: {
                        cancel: {
                            text: "Batal",
                            visible: true,
                            closeModal: true,
                        },
                        confirm: {
                            text: "Ya, hapus!",
                            value: true,
                            closeModal: false,
                        },
                    },
                    dangerMode: true,
                }).then((result) => {
                    if (result) {
                        $.ajax({
                            url: `/admin/perusahaan/delete/${id}`,
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function (response) {
                                if (response.status) {
                                    swal({
                                        'title': 'Terhapus!',
                                        'text': response.message,
                                        'icon': 'success'
                                    });
                                    table.ajax.reload();
                                } else {
                                    swal({
                                        'title': 'Gagal!',
                                        'text': response.message,
                                        'icon': 'error'
                                    });
                                }
                            },
                            error: function (xhr) {
                                swal({
                                    'title': 'Gagal!',
                                    'text': 'Terjadi kesalahan saat menghapus data.',
                                    'icon': 'error'
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush
