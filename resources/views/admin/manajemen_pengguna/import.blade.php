<div class="modal-dialog" role="document">
    <form action="{{ route('admin.manajemen-pengguna.import', ['level' => $level]) }}" method="POST"
          enctype="multipart/form-data"
          id="formImport">
        @csrf
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalImportLabel">Import Data Pengguna {{ucfirst($level)}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="file_import">Pilih File Excel untuk Diimpor</label>
                    <div class="input-group mb-3">
                        <input type="file" class="form-control @error('file_import') is-invalid @enderror"
                               id="file_import" name="file_import">
                    </div>
                    @error('file_import')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <input type="hidden" name="level" value="{{ strtoupper($level) }}">
                <p class="text-muted">
                    Format data yang dapat diimpor: file Excel (.xlsx, .xls) dengan kolom sesuai template.
                    Silakan <a href="{{ asset('template_excel/template_export_level_'.$level.'.xlsx') }}"
                               target="_blank">unduh template</a> untuk memastikan format data sudah benar.
                </p>
            </div>
            <div class="modal-footer">
                {{--                <a href="{{ asset('template_excel/template_export_level_'.$level.'.xlsx') }}"--}}
                {{--                   class="btn btn-info">--}}
                {{--                    <i class="fa fa-download"></i> Download Template Excel--}}
                {{--                </a>--}}
                <button type="submit" class="btn btn-success">Import</button>
            </div>
        </div>
    </form>
</div>
<script>
    $(document).ready(function () {
        $("#formImport").validate({
            rules: {
                file_import: {
                    required: true,
                    extension: "xlsx,xls"
                },
                level: {
                    required: true
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
                        if (response.status) {
                            $('#myModal').modal('hide');
                            swal({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message
                            });
                            $('#manajemen-pengguna-table').DataTable().ajax.reload();
                        } else {
                            console.log(response.errors);
                            $('.error-text').text('');
                            $.each(response.msgField, function (prefix, val) {
                                $('#error-' + prefix).text(val[0]);
                            });
                            swal({
                                icon: 'error',
                                title: 'Terjadi Kesalahan',
                                text: response.message + (response.errors ? '\n' + Object.entries(response.errors).map(([key, val]) => key + ': ' + val.join(', ')).join('\n') : '')
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

                                swal({
                                    icon: 'error',
                                    title: 'Validasi Gagal',
                                    text: 'Silakan periksa input Anda.'
                                });

                            })
                        } else {
                            swal({
                                icon: 'error',
                                title: 'Error',
                                text: 'Terjadi kesalahan server. Silakan coba lagi.'
                            });
                        }
                    }
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
