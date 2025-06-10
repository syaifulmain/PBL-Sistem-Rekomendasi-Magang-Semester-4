<div class="modal-dialog" role="document">
    <form id="formProdi"
          action="{{ isset($prodi) ? route('admin.program-studi.update', $prodi->id) : route('admin.program-studi.store') }}"
          method="POST">

        @csrf
        @if(isset($prodi))
            @method('PUT')
        @endif
        <div class="modal-content">

            <div class="modal-header border-bottom">
                <h5 class="modal-title">{{ isset($prodi) ? 'Edit' : 'Tambah' }} Program Studi</h5>
                <button type="button" class="close fw-bold fs-4" data-dismiss="modal" aria-label="Close"
                        style="border: none; background: transparent;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="mb-3 form-group">
                    <label for="kode" class="form-label">Kode</label>
                    <input type="text" name="kode" id="kode" class="form-control @error('nama') is-invalid @enderror" value="{{ $prodi->kode ?? '' }}"
                           required>
                    <span id="error-kode" class="text-danger error-text"></span>
                    @error('kode')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3 form-group">
                    <label for="nama" class="form-label">Nama</label>
                    <input type="text" name="nama" id="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ $prodi->nama ?? '' }}"
                           required>
                    <span id="error-nama" class="text-danger error-text"></span>
                    @error('nama')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3 form-group">
                    <label for="jenjang" class="form-label">Jenjang</label>
                    <select name="jenjang" id="jenjang" class="form-control @error('nama') is-invalid @enderror" required>
                        <option value="">-- Pilih Jenjang --</option>
                        <option value="D3" {{ isset($prodi) && $prodi->jenjang == 'D3' ? 'selected' : '' }}>D3</option>
                        <option value="D4" {{ isset($prodi) && $prodi->jenjang == 'D4' ? 'selected' : '' }}>D4</option>
                    </select>
                    <span id="error-jenjang" class="text-danger error-text"></span>
                    @error('jenjang')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">
                    Simpan
                </button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
            </div>
        </div>
    </form>
</div>

<script>
    $(document).ready(function () {
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
                var formData = new FormData(form);
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        if (response.success) {
                            $('#myModal').modal('hide');
                            swal({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message
                            });
                            $('#tabelProdi').DataTable().ajax.reload();
                        } else {
                            console.log(response.error);
                            $('.error-text').text('');
                            $.each(response.msgField, function (prefix, val) {
                                $('#error-' + prefix).text(val[0]);
                            });
                            swal({
                                icon: 'error',
                                title: 'Terjadi Kesalahan',
                                text: response.message
                            });
                        }
                    },
                    error: function (xhr) {
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;

                            // Clear previous errors
                            $('.form-control').removeClass('is-invalid');
                            $('.invalid-feedback').remove();

                            // Show new errors
                            $.each(errors, function (field, messages) {
                                let input = $('[name="' + field + '"]');
                                input.addClass('is-invalid');
                                input.closest('.form-group').append('<div class="invalid-feedback">' + messages[0] + '</div>');
                            });

                            swal({
                                icon: 'error',
                                title: 'Validasi Gagal',
                                text: 'Silakan periksa input Anda.'
                            });
                        } else {
                            swal({
                                icon: 'error',
                                title: 'Error',
                                text: 'Terjadi kesalahan server. Silakan coba lagi.'
                            });
                        }
                    },
                });
                return false;
            },
            errorElement: 'span',
            errorPlacement: function (error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function (element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });
    });
</script>
