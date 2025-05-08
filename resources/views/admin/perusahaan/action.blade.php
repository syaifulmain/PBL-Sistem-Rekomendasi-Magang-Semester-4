@extends('layouts.template')
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="card-title">{{ucfirst($action)}} Data Mitra</h4>
                <div>
                    @if($action == 'detail')
                        <a type="button" class="btn btn-warning" id="btn-edit"
                           href="{{ route('admin.perusahaan.edit', $perusahaan->id) }}">
                            <i class="ti-pencil mr-1"></i> Edit
                        </a>
                    @endif
                    @if($action != 'detail')
                        <button type="button" class="btn btn-info mr-2" id="btn-reset" onclick="resetForm()">
                            <i class="ti-reload mr-1"></i> Reset
                        </button>
                    @endif
                    <a href="javascript:history.back()" class="btn btn-secondary mr-2">
                        <i class="ti-arrow-left mr-1"></i> Kembali
                    </a>
                </div>
            </div>
            <form class="forms-sample"
                  action="{{ $action == 'edit' ? route('admin.perusahaan.update', $perusahaan->id) : ($action == 'tambah' ? route('admin.perusahaan.store') : '#') }}"
                  method="POST"
                  id="form-perusahaan">
                @if($action != 'detail')
                    @csrf
                    @if($action == 'edit')
                        @method('PUT')
                    @endif
                @endif
                <div class="form-group">
                    <label for="nama">Nama Perusahaan</label>
                    <input type="text" class="form-control" id="nama" name="nama"
                           placeholder="Masukan Nama Perusahaan" value="{{$perusahaan->nama ?? ''}}"
                           @if($action == 'detail')
                               readonly
                            @endif>
                    <small id="error-nama" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label for="email">Email Perusahaan</label>
                    <input type="email" class="form-control" id="email" name="email"
                           placeholder="Masukan Email Perusahaan" value="{{$perusahaan->email ?? ''}}"
                           @if($action == 'detail')
                               readonly
                            @endif>
                    <small id="error-email" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label for="website">Website Perusahaan</label>
                    <input type="text" class="form-control" id="website" name="website"
                           placeholder="Masukan Website Perusahaan" value="{{$perusahaan->website ?? ''}}"
                           @if($action == 'detail')
                               readonly
                            @endif>
                    <small id="error-website" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label for="no_telepon">Nomor Telepon Perusahaan</label>
                    <input type="text" class="form-control" id="no_telepon" name="no_telopon"
                           placeholder="Masukan Nomor Telepon Perusahaan" value="{{$perusahaan->no_telepon ?? ''}}"
                           @if($action == 'detail')
                               readonly
                            @endif>
                    <small id="error-no_telepon" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group" id="select-provinsi">
                    <label for="provinsi_id">Provinsi</label>
                    <x-searchable-dropdown
                            id="provinsi_id"
                            name="provinsi_id"
                            placeholder="Cari Provinsi"
                            dataUrl="{{url('/api/provinsi')}}"
                            selectedId="{{$perusahaan->lokasi->provinsi_id ?? ''}}"
                            :readonly="$action == 'detail'"
                    />
                </div>
                <div class="form-group" id="select-kabupaten">
                    <label for="kabupaten_id">Kabupaten</label>
                    <x-searchable-dropdown
                            id="kabupaten_id"
                            name="kabupaten_id"
                            placeholder="Cari Kabupaten"
                            dataUrl="{{(isset($perusahaan->lokasi) ) ? url('/api/kabupaten') . '/' . $perusahaan->lokasi->provinsi_id : ''}}"
                            selectedId="{{$perusahaan->lokasi->kabupaten_id ?? ''}}"
                            :readonly="$action == 'detail'"/>
                </div>
                <div class="form-group" id="select-kecamatan">
                    <label for="kecamatan_id">Kecamatan</label>
                    <x-searchable-dropdown
                            id="kecamatan_id"
                            name="kecamatan_id"
                            placeholder="Cari Kecamatan"
                            dataUrl="{{(isset($perusahaan->lokasi) ) ? url('/api/kecamatan/') . '/' . $perusahaan->lokasi->kabupaten_id : ''}}"
                            selectedId="{{$perusahaan->lokasi->kecamatan_id ?? ''}}"
                            :readonly="$action == 'detail'"/>
                </div>
                <div class="form-group" id="select-desa">
                    <label for="desa_id">Desa</label>
                    <x-searchable-dropdown
                            id="desa_id"
                            name="desa_id"
                            placeholder="Cari Desa"
                            dataUrl="{{(isset($perusahaan->lokasi) ) ? url('/api/desa') . '/' . $perusahaan->lokasi->kecamatan_id : ''}}"
                            selectedId="{{$perusahaan->lokasi->desa_id ?? ''}}"
                            :readonly="$action == 'detail'"/>
                </div>
                <div class="form-group" id="select-alamat">
                    <label for="alamat">Alamat</label>
                    <input type="text" class="form-control" id="alamat" name="alamat"
                           placeholder="Alamat" value="{{$perusahaan->lokasi->alamat ?? ''}}"
                           @if($action == 'detail')
                               readonly
                            @endif>
                </div>
                @if($action != "detail")
                    <button type="submit" class="btn btn-primary mr-2">Simpan</button>
                    <a class="btn btn-light" href="{{route('admin.perusahaan.index')}}">Cancel</a>
                @endif
            </form>
        </div>
    </div>
@endsection
@push('js')
    <script>
        function resetForm() {
            $('#nama').val('');
            $('#email').val('');
            $('#website').val('');
            $('#no_telepon').val('');

            $('#provinsi_id-hidden').val('');
            $('#provinsi_id-input').val('');
            $('#kabupaten_id-hidden').val('');
            $('#kabupaten_id-input').val('');
            $('#kecamatan_id-hidden').val('');
            $('#kecamatan_id-input').val('');
            $('#desa_id-hidden').val('');
            $('#desa_id-input').val('');
            $('#alamat').val('');

            $('#select-kabupaten').addClass('d-none');
            $('#select-kecamatan').addClass('d-none');
            $('#select-desa').addClass('d-none');
            $('#select-alamat').addClass('d-none');
        }

        $(document).ready(function () {
            $("#form-perusahaan").validate({
                rules: {
                    nama: {
                        required: true,
                        minlength: 3
                    },
                    email: {
                        required: false,
                        email: true
                    },
                    website: {
                        required: false,
                    },
                    no_telepon: {
                        required: false,
                        digits: true
                    },
                    provinsi_id: {
                        required: true
                    },
                    "provinsi_id-input": {
                        required: true
                    },
                    kabupaten_id: {
                        required: true
                    },
                    "kabupaten_id-input": {
                        required: true
                    },
                    kecamatan_id: {
                        required: true
                    },
                    "kecamatan_id-input": {
                        required: true
                    },
                    desa_id: {
                        required: true
                    },
                    "desa_id-input": {
                        required: true
                    },
                    alamat: {
                        required: true
                    }
                },
                submitHandler: function (form) {
                    $.ajax({
                        url: form.action,
                        type: form.method,
                        data: $(form).serialize(),
                        success: function (response) {
                            if (response.status) {
                                swal({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: response.message
                                });
                                resetForm()
                            } else {
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
    <script>
        $(document).ready(function () {
            $('#provinsi_id-hidden').on('change', function () {
                let provinsiId = $(this).val();
                $('#select-kabupaten').removeClass('d-none');
                $('#select-kecamatan').addClass('d-none');
                $('#select-desa').addClass('d-none').val('');
                $('#select-alamat').addClass('d-none').val('');

                $('#kabupaten_id-hidden').val('');
                $('#kabupaten_id-input').val('');
                $('#kecamatan_id-hidden').val('');
                $('#kecamatan_id-input').val('');
                $('#desa_id-hidden').val('');
                $('#desa_id-input').val('');
                $('#alamat').val('');
                let newUrl = '{{url('/api/kabupaten')}}' + '/' + provinsiId;
                document.getElementById('kabupaten_id-search-container').dataset.url = newUrl;
                window.initSearchableDropdown('kabupaten_id', newUrl);
            });

            $('#kabupaten_id-hidden').on('change', function () {
                let kabupatenId = $(this).val();
                $('#select-kecamatan').removeClass('d-none');
                $('#select-desa').addClass('d-none').val('');
                $('#select-alamat').addClass('d-none').val('');

                $('#kecamatan_id-hidden').val('');
                $('#kecamatan_id-input').val('');
                $('#desa_id-hidden').val('');
                $('#desa_id-input').val('');
                $('#alamat').val('');
                let newUrl = '{{url('/api/kecamatan')}}' + '/' + kabupatenId;
                document.getElementById('kecamatan_id-search-container').dataset.url = newUrl;
                window.initSearchableDropdown('kecamatan_id', newUrl);
            });

            $('#kecamatan_id-hidden').on('change', function () {
                let kecamatanId = $(this).val();
                $('#select-desa').removeClass('d-none');
                $('#select-alamat').addClass('d-none').val('');

                $('#desa_id-hidden').val('');
                $('#desa_id-input').val('');
                $('#alamat').val('');
                let newUrl = '{{url('/api/desa')}}' + '/' + kecamatanId;
                document.getElementById('desa_id-search-container').dataset.url = newUrl;
                window.initSearchableDropdown('desa_id', newUrl);
            });

            $('#desa_id-hidden').on('change', function () {
                $('#select-alamat').removeClass('d-none');
                $('#alamat').val('');
            });
        });
    </script>
@endpush
