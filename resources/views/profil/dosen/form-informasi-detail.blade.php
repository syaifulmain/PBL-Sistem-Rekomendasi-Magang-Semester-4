<form action="{{ route('dosen.profil.informasi-detail.update') }}" method="POST" id="form-tambah"
      enctype="multipart/form-data">
    @csrf
    <div class="modal-dialog modal-dialog-centered" style="max-width: 50%;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{$title}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Jenis Kelamin</label>
                    <input class="form-control" value="{{$informasiDetail->getGenderName()}}" disabled>
                </div>
                <div class="form-group">
                    <label>No Telepon</label>
                    <input type="text" name="no_telepon" id="no_telepon" class="form-control" required
                           value="{{$informasiDetail->no_telepon}}">
                    @error('no_telepon')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-secondary">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</form>
