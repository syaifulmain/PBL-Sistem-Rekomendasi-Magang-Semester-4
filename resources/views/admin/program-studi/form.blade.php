<form id="formProdi">
    @csrf
    @if (isset($prodi))
        <input type="hidden" name="_method" value="PUT">
    @endif
    <input type="hidden" name="id" value="{{ $prodi->id ?? '' }}">

    <div class="modal-header border-bottom">
        <h5 class="modal-title">{{ isset($prodi) ? 'Edit' : 'Tambah' }} Program Studi</h5>
        <button type="button" class="close fw-bold fs-4" data-dismiss="modal" aria-label="Close" style="border: none; background: transparent;">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

    <div class="modal-body">
        <div class="mb-3">
            <label for="kode" class="form-label">Kode</label>
            <input type="text" name="kode" id="kode" class="form-control" value="{{ $prodi->kode ?? '' }}" required>
        </div>
        <div class="mb-3">
            <label for="nama" class="form-label">Nama</label>
            <input type="text" name="nama" id="nama" class="form-control" value="{{ $prodi->nama ?? '' }}" required>
        </div>
        <div class="mb-3">
            <label for="jenjang" class="form-label">Jenjang</label>
            <select name="jenjang" id="jenjang" class="form-control" required>
                <option value="">-- Pilih Jenjang --</option>
                <option value="D3" {{ isset($prodi) && $prodi->jenjang == 'D3' ? 'selected' : '' }}>D3</option>
                <option value="D4" {{ isset($prodi) && $prodi->jenjang == 'D4' ? 'selected' : '' }}>D4</option>
            </select>
        </div>
    </div>

    <div class="modal-footer">
        <button type="submit" class="btn btn-success">Simpan</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
    </div>
</form>

@push('js')
<script>
$(function () {
    $('#formProdi').on('submit', function (e) {
        e.preventDefault();

        let form = $(this);
        let actionUrl = form.attr('action') ?? "{{ isset($prodi) ? route('admin.program-studi.update', $prodi->id) : route('admin.program-studi.store') }}";
        let method = "{{ isset($prodi) ? 'POST' : 'POST' }}"; // Gunakan POST untuk semua, _method untuk PUT

        $.ajax({
            url: actionUrl,
            method: method,
            data: form.serialize(),
            success: function (res) {
                $('#modalProdi').modal('hide');
                $('#tabelProdi').DataTable().ajax.reload();

                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: 'Data berhasil disimpan!',
                    timer: 2000,
                    showConfirmButton: false
                });
            },
            error: function (xhr) {
                let err = xhr.responseJSON?.message || "Gagal menyimpan data";
                Swal.fire('Gagal', err, 'error');
            }
        });
    });

    // Fokus ke input pertama saat modal dibuka
    $('#modalProdi').on('shown.bs.modal', function () {
        $('#formProdi input[name="kode"]').focus();
    });
});
</script>
@endpush