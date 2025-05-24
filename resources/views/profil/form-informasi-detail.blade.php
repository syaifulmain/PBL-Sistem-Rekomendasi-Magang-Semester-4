<form action="{{ route('profil.informasi-detail.update') }}" method="POST" id="form-tambah"
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

                @if (has_role('DOSEN'))
                    <div class="form-group">
                        <label>Jenis Kelamin</label>
                        <input class="form-control" value="{{$data->getGenderNameAttribute()}}" disabled>
                    </div>
                    <div class="form-group">
                        <label>No Telepon</label>
                        <input type="text" name="no_telepon" id="no_telepon" class="form-control" required
                               value="{{$data->no_telepon}}">
                        @error('no_telepon')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                @endif

                @if (has_role('MAHASISWA'))
                    <div class="form-group">
                        <label>Angkatan</label>
                        <input class="form-control" value="{{$data->angkatan}}" disabled>
                    </div>
                    <div class="form-group">
                        <label>Jenis Kelamin</label>
                        <input class="form-control" value="{{$data->getGenderNameAttribute()}}" disabled>
                    </div>
                    <div class="form-group">
                        <label>No Telepon</label>
                        <input type="text" name="no_telepon" id="no_telepon" class="form-control" required
                               value="{{$data->no_telepon}}">
                        @error('no_telepon')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <input class="form-control" value="{{$data->status}}" disabled>
                    </div>
                    <div class="form-group">
                        <label>IPK</label>
                        <input type="text" name="ipk" id="ipk" class="form-control" required
                               value="{{$data->ipk ?? ''}}">
                        @error('ipk')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                @endif

            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-secondary">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</form>
