@extends('layouts.template')

@section('content')
<div class="card">
    <div class="card-body">
        <h4 class="card-title">Data Program Studi</h4>
        <button class="btn mb-4 text-white rounded-md" id="btnTambah" style="background-color: #19376D; border-color: #19376D;">
            <i class="fa fa-plus"></i> Tambah
        </button>

        <table class="table table-striped table-bordered" id="tabelProdi">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama</th>
                    <th>Jenjang</th>
                    <th width="1%">Aksi</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalProdi" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" id="modalContent">
            {{-- Konten form akan dimuat via AJAX --}}
        </div>
    </div>
</div>
@endsection

@push('js')
<!-- Tambahkan SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(function () {
    let table = $('#tabelProdi').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("admin.program-studi.index") }}',
        columns: [
            { data: 'kode', name: 'kode' },
            { data: 'nama', name: 'nama' },
            { data: 'jenjang', name: 'jenjang' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });

    // Tambah data
    $('#btnTambah').click(function () {
        $.get("{{ route('admin.program-studi.create') }}", function (res) {
            $('#modalContent').html(res);
            $('#modalProdi').modal('show');
        });
    });

    // Edit data
    $('#tabelProdi').on('click', '.edit', function () {
        let id = $(this).data('id');
        $.get(`{{ url('admin/program-studi/edit') }}/${id}`, function (res) {
            $('#modalContent').html(res);
            $('#modalProdi').modal('show');
        });
    });

    // Hapus data
    $('#tabelProdi').on('click', '.delete', function () {
        let id = $(this).data('id');

        Swal.fire({
            title: 'Apakah kamu yakin?',
            text: "Data ini akan dihapus secara permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `{{ url('admin/program-studi/delete') }}/${id}`,
                    method: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function (res) {
                        $('#tabelProdi').DataTable().ajax.reload();

                        Swal.fire({
                            title: 'Berhasil!',
                            text: res.success,
                            icon: 'success',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    },
                    error: function () {
                        Swal.fire({
                            title: 'Gagal!',
                            text: 'Terjadi kesalahan saat menghapus data.',
                            icon: 'error',
                            confirmButtonText: 'Tutup'
                        });
                    }
                });
            }
        });
    });

    // Fokus ke input pertama setelah modal terbuka
    $('#modalProdi').on('shown.bs.modal', function () {
        $('#formProdi input:first').focus();
    });
});
</script>
@endpush