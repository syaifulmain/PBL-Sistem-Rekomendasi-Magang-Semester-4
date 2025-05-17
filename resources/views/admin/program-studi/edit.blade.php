<form id="formProdi" action="{{ isset($prodi) ? route('admin.program-studi.update', $prodi->id) : route('admin.program-studi.store') }}" method="POST">
    @csrf
    @if (isset($prodi))
        <input type="hidden" name="_method" value="PUT">
    @endif
    <input type="hidden" name="id" value="{{ $prodi->id ?? '' }}">

    <div class="modal-header border-bottom">
        <h5 class="modal-title" >{{ isset($prodi) ? 'Edit' : 'Tambah' }} Program Studi</h5>
        <button type="button" class="close fw-bold fs-4" data-dismiss="modal" aria-label="Close" style="background: transparent; border: none;">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

    <div class="modal-body px-4">
        <div class="mb-3">
            <label for="kode" class="form-label fw-semibold">Kode</label>
            <input type="text" name="kode" id="kode" class="form-control" value="{{ $prodi->kode ?? '' }}" required>
        </div>

        <div class="mb-3">
            <label for="nama" class="form-label fw-semibold">Nama</label>
            <input type="text" name="nama" id="nama" class="form-control" value="{{ $prodi->nama ?? '' }}" required>
        </div>

        <div class="mb-3">
            <label for="jenjang" class="form-label fw-semibold">Jenjang</label>
            <select name="jenjang" id="jenjang" class="form-control" required>
                <option value="">-- Pilih Jenjang --</option>
                <option value="D3" {{ isset($prodi) && $prodi->jenjang == 'D3' ? 'selected' : '' }}>D3</option>
                <option value="D4" {{ isset($prodi) && $prodi->jenjang == 'D4' ? 'selected' : '' }}>D4</option>
            </select>
        </div>
    </div>

    <div class="modal-footer border-top">
        <button type="button" class="btn btn-secondary" data-dismiss="modal"> Batal</button>
        <button type="submit" class="btn btn-success" style="background-color: #19376D; border-color: #19376D;">Simpan</button>
    </div>
</form>
