<form id="formProdi" 
    action="{{ isset($prodi) ? route('admin.program-studi.update', $prodi->id) : route('admin.program-studi.store') }}" 
    method="POST">

    @csrf
    @if(isset($prodi))
        @method('PUT')
    @endif

    <div class="modal-header border-bottom">
        <h5 class="modal-title">{{ isset($prodi) ? 'Edit' : 'Tambah' }} Program Studi</h5>
        <button type="button" class="close fw-bold fs-4" data-dismiss="modal" aria-label="Close" style="border: none; background: transparent;">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

    <div class="modal-body">
        <div class="mb-3 form-group">
            <label for="kode" class="form-label">Kode</label>
            <input type="text" name="kode" id="kode" class="form-control" value="{{ $prodi->kode ?? '' }}" required>
            <span id="error-kode" class="text-danger error-text"></span>
        </div>
        <div class="mb-3 form-group">
            <label for="nama" class="form-label">Nama</label>
            <input type="text" name="nama" id="nama" class="form-control" value="{{ $prodi->nama ?? '' }}" required>
            <span id="error-nama" class="text-danger error-text"></span>
        </div>
        <div class="mb-3 form-group">
            <label for="jenjang" class="form-label">Jenjang</label>
            <select name="jenjang" id="jenjang" class="form-control" required>
                <option value="">-- Pilih Jenjang --</option>
                <option value="D3" {{ isset($prodi) && $prodi->jenjang == 'D3' ? 'selected' : '' }}>D3</option>
                <option value="D4" {{ isset($prodi) && $prodi->jenjang == 'D4' ? 'selected' : '' }}>D4</option>
            </select>
            <span id="error-jenjang" class="text-danger error-text"></span>
        </div>
    </div>

    <div class="modal-footer">
        <button type="submit" class="btn btn-success" style="background-color: #19376D; border-color: #19376D;" >Simpan</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
    </div>
</form>

@push('js')
<!-- SweetAlert2 -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(function () {
    $('#formProdi').validate({
        rules: {
            kode: {
                required: true,
                maxlength: 5,
                pattern: /^[A-Z0-9]+$/
            },
            nama: {
                required: true,
                minlength: 3,
                maxlength: 100,
                pattern: /^[a-zA-Z0-9\s]+$/
            },
            jenjang: {
                required: true
            }
        },
        messages: {
            kode: {
                required: "Kode wajib diisi.",
                pattern: "Kode hanya boleh huruf kapital dan angka.",
                maxlength: "Kode maksimal 5 karakter."
            },
            nama: {
                required: "Nama wajib diisi.",
                pattern: "Nama hanya boleh huruf, angka, dan spasi.",
                minlength: "Nama minimal 3 karakter.",
                maxlength: "Nama maksimal 100 karakter."
            },
            jenjang: {
                required: "Jenjang wajib dipilih."
            }
        },
        submitHandler: function (form) {
            const $form = $(form);
            const actionUrl = $form.attr('action');
            const method = $form.find('input[name="_method"]').val() || 'POST';

            // Optional: loading
            Swal.fire({
                title: 'Menyimpan...',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            $.ajax({
                url: actionUrl,
                method: method,
                data: form.serialize(),
                success: function (res) {
                    $('#modalProdi').modal('hide');
                    $('#tabelProdi').DataTable().ajax.reload();

                    // 🎉 Tampilan alert sukses menarik
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        html: '<strong>Program Studi berhasil disimpan 🎓</strong>',
                        showConfirmButton: false,
                        timer: 2500,
                        timerProgressBar: true,
                        position: 'center',
                        backdrop: `
                            rgba(0,0,0,0.4)
                            center left
                            no-repeat
                        `,
                        showClass: {
                            popup: 'animate__animated animate__fadeInDown'
                        },
                        hideClass: {
                            popup: 'animate__animated animate__fadeOutUp'
                        }
                    });
                },
                error: function (xhr) {
                    let err = xhr.responseJSON?.message || "Gagal menyimpan data";
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: err,
                        confirmButtonColor: '#d33'
                    });
                }
            });
            return false;
        },
        errorElement: 'span',
        errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-group').append(error);
        },
        highlight: function (element) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element) {
            $(element).removeClass('is-invalid');
        }
    });
});
</script>
@endpush