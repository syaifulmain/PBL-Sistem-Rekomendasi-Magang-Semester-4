<form action="{{ route('mahasiswa.profil.informasi-pengguna.update')}}" method="POST" id="form-tambah"
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
                <div class="text-center mb-3">
                    <label for="foto_profil" style="cursor: pointer;">
                        <img id="preview_foto_profil" src="{{$informasiPengguna->getFotoProfilPath()}}" alt="Profile Picture" class="rounded-circle"
                             width="150" height="150" style="object-fit: cover;">
                        <input type="file" id="foto_profil" name="foto_profil" class="d-none" accept="image/*" onchange="previewImage(this);">
                    </label>
                    <small class="d-block mt-2">Klik untuk mengganti foto profil</small>
                    <script>
                        function previewImage(input) {
                            if (input.files && input.files[0]) {
                                var reader = new FileReader();
                                reader.onload = function(e) {
                                    document.getElementById('preview_foto_profil').src = e.target.result;
                                }
                                reader.readAsDataURL(input.files[0]);
                            }
                        }
                    </script>
                </div>
                <div class="form-group">
                    <label>Nama</label>
                    <input class="form-control" value="{{$informasiPengguna->nama}}" disabled>
                </div>
                <div class="form-group">
                    <label>NIM</label>
                    <input class="form-control" value="{{$informasiPengguna->nim}}" disabled>
                </div>
                <div class="form-group">
                    <label>Program Studi</label>
                    <input class="form-control" value="{{$informasiPengguna->programStudi->jenjang}} {{$informasiPengguna->programStudi->nama}}" disabled>
                </div>
                <div class="form-group">
                    <label>Alamat</label>
                    <input type="text" name="alamat" id="alamat" class="form-control" required value="{{$informasiPengguna->alamat}}">
                    <small id="error-alamat" class="error-text form-text text-danger"></small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-secondary">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</form>
