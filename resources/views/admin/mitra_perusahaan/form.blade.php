@extends('layouts.template')
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="card-title">{{ isset($data) ? 'Edit' : 'Tambah' }} {{$title}}</h4>
                <div>
                    <a href="{{ url()->previous() }}" class="btn btn-secondary mr-2">
                        <i class="ti-arrow-left mr-1"></i> Kembali
                    </a>
                </div>
            </div>
            <form
                action="{{ isset($data) ? route('admin.mitra-perusahaan.edit', $data->id) : route('admin.mitra-perusahaan.create') }}"
                method="POST" id="form-mitra-perusahaan">
                @csrf
                @if(isset($data))
                    @method('PUT')
                @endif

                <div class="form-group">
                    <label for="nama">Nama Perusahaan</label>
                    <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
                           value="{{ old('nama', $data->nama ?? '') }}">
                    @error('nama')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email">Email Perusahaan</label>
                    <input type="email" name="email" id="email"
                           class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email', $data->email ?? '') }}">
                    @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="website">Website Perusahaan</label>
                    <input type="url" name="website" class="form-control @error('website') is-invalid @enderror"
                           value="{{ old('website', $data->website ?? '') }}">
                    @error('website')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="no_telepon">No Telepon Perusahaan</label>
                    <input type="text" name="no_telepon" class="form-control @error('no_telepon') is-invalid @enderror"
                           value="{{ old('no_telepon', $data->no_telepon ?? '') }}">
                    @error('no_telepon')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="provinsi_id">Provinsi</label>
                    <select id="provinsi_id" name="provinsi_id"
                            class="form-control select2 @error('provinsi_id') is-invalid @enderror"
                            data-selected="{{ old('provinsi_id', $data->lokasi->provinsi_id ?? '') }}">
                        <option value="">Pilih Provinsi</option>
                    </select>
                    @error('provinsi_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="kabupaten_id">Kabupaten</label>
                    <select id="kabupaten_id" name="kabupaten_id"
                            class="form-control select2 @error('kabupaten_id') is-invalid @enderror"
                            data-selected="{{ old('kabupaten_id', $data->lokasi->kabupaten_id ?? '') }}">
                        <option value="">Pilih Kabupaten</option>
                    </select>
                    @error('kabupaten_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="kecamatan_id">Kecamatan</label>
                    <select id="kecamatan_id" name="kecamatan_id"
                            class="form-control select2 @error('kecamatan_id') is-invalid @enderror"
                            data-selected="{{ old('kecamatan_id', $data->lokasi->kecamatan_id ?? '') }}">
                        <option value="">Pilih Kecamatan</option>
                    </select>
                    @error('kecamatan_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="desa_id">Desa</label>
                    <select id="desa_id" name="desa_id"
                            class="form-control select2 @error('desa_id') is-invalid @enderror"
                            data-selected="{{ old('desa_id', $data->lokasi->desa_id ?? '') }}">
                        <option value="">Pilih Desa</option>
                    </select>
                    @error('desa_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="alamat">Alamat</label>
                    <input type="text" name="alamat" class="form-control @error('alamat') is-invalid @enderror"
                           value="{{ old('alamat', $data->lokasi->alamat ?? '') }}">
                    @error('alamat')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="text-right">
                    <button type="submit" class="btn btn-primary">
                        {{ isset($data) ? 'Update' : 'Simpan' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function () {
            // Inisialisasi Select2
            $('.select2').select2();

            // Variable untuk menyimpan selected values
            const selectedValues = {
                provinsi: $('#provinsi_id').data('selected'),
                kabupaten: $('#kabupaten_id').data('selected'),
                kecamatan: $('#kecamatan_id').data('selected'),
                desa: $('#desa_id').data('selected')
            };

            // Load provinsi saat halaman dimuat
            loadProvinsi();

            // Set initial state untuk kabupaten, kecamatan, dan desa
            $('#kabupaten_id, #kecamatan_id, #desa_id').prop('disabled', true);

            // Event handler untuk perubahan provinsi
            $('#provinsi_id').on('change', function () {
                let idProvinsi = $(this).val();
                if (idProvinsi) {
                    loadKabupaten(idProvinsi);
                } else {
                    resetSelect('#kabupaten_id', 'Pilih Kabupaten');
                    resetSelect('#kecamatan_id', 'Pilih Kecamatan');
                    resetSelect('#desa_id', 'Pilih Desa');
                    $('#kabupaten_id, #kecamatan_id, #desa_id').prop('disabled', true);
                }
            });

            // Event handler untuk perubahan kabupaten
            $('#kabupaten_id').on('change', function () {
                let idKabupaten = $(this).val();
                if (idKabupaten) {
                    loadKecamatan(idKabupaten);
                } else {
                    resetSelect('#kecamatan_id', 'Pilih Kecamatan');
                    resetSelect('#desa_id', 'Pilih Desa');
                    $('#kecamatan_id, #desa_id').prop('disabled', true);
                }
            });

            // Event handler untuk perubahan kecamatan
            $('#kecamatan_id').on('change', function () {
                let idKecamatan = $(this).val();
                if (idKecamatan) {
                    loadDesa(idKecamatan);
                } else {
                    resetSelect('#desa_id', 'Pilih Desa');
                    $('#desa_id').prop('disabled', true);
                }
            });

            // Function untuk load provinsi
            function loadProvinsi() {
                $.get('/api/provinsi')
                    .done(function (data) {
                        $('#provinsi_id').empty().append('<option value="">Pilih Provinsi</option>');
                        data.forEach(function (provinsi) {
                            let selected = (provinsi.id == selectedValues.provinsi) ? 'selected' : '';
                            $('#provinsi_id').append(`<option value="${provinsi.id}" ${selected}>${provinsi.nama}</option>`);
                        });

                        // Jika ada provinsi yang terpilih, load kabupaten
                        if (selectedValues.provinsi) {
                            $('#provinsi_id').trigger('change');
                        }
                    })
                    .fail(function () {
                        console.error('Gagal memuat data provinsi');
                    });
            }

            // Function untuk load kabupaten
            function loadKabupaten(idProvinsi) {
                $('#kabupaten_id').prop('disabled', true).empty().append('<option value="">Memuat...</option>');
                $('#kecamatan_id, #desa_id').prop('disabled', true);
                resetSelect('#kecamatan_id', 'Pilih Kecamatan');
                resetSelect('#desa_id', 'Pilih Desa');

                $.get(`/api/kabupaten/${idProvinsi}`)
                    .done(function (data) {
                        $('#kabupaten_id').empty().append('<option value="">Pilih Kabupaten</option>');
                        data.forEach(function (kabupaten) {
                            let selected = (kabupaten.id == selectedValues.kabupaten) ? 'selected' : '';
                            $('#kabupaten_id').append(`<option value="${kabupaten.id}" ${selected}>${kabupaten.nama}</option>`);
                        });
                        $('#kabupaten_id').prop('disabled', false);

                        // Jika ada kabupaten yang terpilih, load kecamatan
                        if (selectedValues.kabupaten) {
                            $('#kabupaten_id').trigger('change');
                        }
                    })
                    .fail(function () {
                        console.error('Gagal memuat data kabupaten');
                        $('#kabupaten_id').empty().append('<option value="">Error loading data</option>');
                    });
            }

            // Function untuk load kecamatan
            function loadKecamatan(idKabupaten) {
                $('#kecamatan_id').prop('disabled', true).empty().append('<option value="">Memuat...</option>');
                $('#desa_id').prop('disabled', true);
                resetSelect('#desa_id', 'Pilih Desa');

                $.get(`/api/kecamatan/${idKabupaten}`)
                    .done(function (data) {
                        $('#kecamatan_id').empty().append('<option value="">Pilih Kecamatan</option>');
                        data.forEach(function (kecamatan) {
                            let selected = (kecamatan.id == selectedValues.kecamatan) ? 'selected' : '';
                            $('#kecamatan_id').append(`<option value="${kecamatan.id}" ${selected}>${kecamatan.nama}</option>`);
                        });
                        $('#kecamatan_id').prop('disabled', false);

                        // Jika ada kecamatan yang terpilih, load desa
                        if (selectedValues.kecamatan) {
                            $('#kecamatan_id').trigger('change');
                        }
                    })
                    .fail(function () {
                        console.error('Gagal memuat data kecamatan');
                        $('#kecamatan_id').empty().append('<option value="">Error loading data</option>');
                    });
            }

            // Function untuk load desa
            function loadDesa(idKecamatan) {
                $('#desa_id').prop('disabled', true).empty().append('<option value="">Memuat...</option>');

                $.get(`/api/desa/${idKecamatan}`)
                    .done(function (data) {
                        $('#desa_id').empty().append('<option value="">Pilih Desa</option>');
                        data.forEach(function (desa) {
                            let selected = (desa.id == selectedValues.desa) ? 'selected' : '';
                            $('#desa_id').append(`<option value="${desa.id}" ${selected}>${desa.nama}</option>`);
                        });
                        $('#desa_id').prop('disabled', false);
                    })
                    .fail(function () {
                        console.error('Gagal memuat data desa');
                        $('#desa_id').empty().append('<option value="">Error loading data</option>');
                    });
            }

            // Function untuk reset select
            function resetSelect(selector, placeholder) {
                $(selector).empty().append(`<option value="">${placeholder}</option>`);
            }
        });
    </script>

    <script>
        $(document).ready(function () {
            $("#form-mitra-perusahaan").validate({
                rules: {
                    nama: {
                        required: true,
                        minlength: 3,
                        maxlength: 255
                    },
                    email: {
                        required: false,
                        email: true,
                        maxlength: 255
                    },
                    website: {
                        required: false,
                        url: true,
                        maxlength: 255
                    },
                    no_telepon: {
                        required: false,
                        minlength: 10,
                        maxlength: 20,
                    },
                    provinsi_id: {
                        required: true,
                        digits: true
                    },
                    kabupaten_id: {
                        required: true,
                        digits: true
                    },
                    kecamatan_id: {
                        required: true,
                        digits: true
                    },
                    desa_id: {
                        required: true,
                        digits: true
                    },
                    alamat: {
                        required: true,
                        minlength: 5,
                        maxlength: 255
                    }
                },
                submitHandler: function (form) {
                    // Disable submit button untuk mencegah double submit
                    $('button[type="submit"]').prop('disabled', true).text('Menyimpan...');

                    $.ajax({
                        url: form.action,
                        type: form.method,
                        data: $(form).serialize(),
                        success: function (response) {
                            if (response.success) {
                                swal({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: response.message
                                }).then(function () {
                                    window.location.href = response.redirect;
                                });
                            } else {
                                $('.error-text').text('');
                                if (response.msgField) {
                                    $.each(response.msgField, function (prefix, val) {
                                        $('#error-' + prefix).text(val[0]);
                                    });
                                }
                                swal({
                                    icon: 'error',
                                    title: 'Terjadi Kesalahan',
                                    text: response.message || 'Terjadi kesalahan saat menyimpan data'
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
                        complete: function () {
                            // Re-enable submit button
                            $('button[type="submit"]').prop('disabled', false).text('{{ isset($data) ? "Update" : "Simpan" }}');
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
                },
            });
        });
    </script>
@endpush
