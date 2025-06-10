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
                action="{{ isset($data) ? route('admin.manajemen-pengguna.edit', $data->id) : route('admin.manajemen-pengguna.create') }}"
                method="POST"
                id="form-manajemen-pengguna">
                @csrf
                @if(isset($data))
                    @method('PUT')
                @endif

                <div class="form-group">
                    <label for="level">Level</label>
                    <select name="level"
                            class="form-control @error('level') is-invalid @enderror" {{ (isset($data) || isset($level)) ? 'disabled' : '' }}>
                        <option value="">-- Pilih Role --</option>
                        <option value="ADMIN"
                            {{ old('level', $level ?? strtolower($data->level->value) ?? '') == 'admin' ? 'selected' : '' }}>
                            ADMIN
                        </option>
                        <option value="DOSEN"
                            {{ old('level', $level ?? strtolower($data->level->value) ?? '') == 'dosen' ? 'selected' : '' }}>
                            DOSEN
                        </option>
                        <option value="MAHASISWA"
                            {{ old('level', $level ?? strtolower($data->level->value) ?? '') == 'mahasiswa' ? 'selected' : '' }}>
                            MAHASISWA
                        </option>
                    </select>
                    @if(isset($level))
                        <input type="hidden" name="level" value="{{ strtoupper($level) }}">
                    @endif
                </div>


                @if(!isset($data) || $data->level == \App\Enums\UserRole::ADMIN)
                    <div class="admin-fields" style="display: none;">
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" name="username"
                                   class="form-control @error('username') is-invalid @enderror"
                                   value="{{ old('username', $data->username ?? '') }}"
                                {{ isset($data) ? 'readonly disabled' : '' }}>
                            @error('username')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="nama">Nama</label>
                            <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
                                   value="{{ old('nama', $data->admin->nama ?? '') }}">
                            @error('nama')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                @endif

                @if(!isset($data) || $data->level == \App\Enums\UserRole::MAHASISWA)
                    <div class="mahasiswa-fields" style="display: none;">
                        <div class="form-group">
                            <label for="nim">NIM</label>
                            <input type="text" name="nim" class="form-control @error('nim') is-invalid @enderror"
                                   value="{{ old('nim', $data->mahasiswa->nim ?? '') }}"
                                {{ isset($data) ? 'readonly disabled' : '' }}>
                            @error('nim')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="nama">Nama</label>
                            <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
                                   value="{{ old('nama', $data->mahasiswa->nama ?? '') }}">
                            @error('nama')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="program_studi">Program Studi</label>
                            <select name="program_studi"
                                    class="form-control @error('program_studi') is-invalid @enderror">
                                <option value="">-- Pilih Program Studi --</option>
                                @foreach($programStudi as $prodi)
                                    <option
                                        value="{{ $prodi->id }}" {{ old('program_studi_id', $data->mahasiswa->program_studi_id ?? '') == $prodi->id ? 'selected' : '' }}>
                                        {{ $prodi->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('program_studi')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="angkatan">Angkatan</label>
                            <input type="number" name="angkatan"
                                   class="form-control @error('angkatan') is-invalid @enderror"
                                   value="{{ old('angkatan', $data->mahasiswa->angkatan ?? '') }}">
                            @error('angkatan')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="status">Status Mahasiswa</label>
                            <select name="status"
                                    class="form-control @error('status') is-invalid @enderror">
                                <option value="">-- Pilih Status Mahasiswa --</option>
                                <option value="aktif"
                                    {{ old('status', $data->mahasiswa->status ?? '') == 'aktif' ? 'selected' : '' }}>
                                    aktif
                                </option>
                                <option value="nonaktif"
                                    {{ old('status', $data->mahasiswa->status ?? '') == 'nonaktif' ? 'selected' : '' }}>
                                    nonaktif
                                </option>
                            </select>
                            @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                @endif

                @if(!isset($data) || $data->level == \App\Enums\UserRole::DOSEN)
                    <div class="dosen-fields" style="display: none;">
                        <div class="form-group">
                            <label for="nip">NIP</label>
                            <input type="text" name="nip" class="form-control @error('nip') is-invalid @enderror"
                                   value="{{ old('nip', $data->dosen->nip ?? '') }}"
                                {{ isset($data) ? 'readonly disabled' : '' }}>
                            @error('nip')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="nama">Nama</label>
                            <input type="text" name="nama"
                                   class="form-control @error('nama') is-invalid @enderror"
                                   value="{{ old('nama', $data->dosen->nama ?? '') }}">
                            @error('nama')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                @endif

                <div class="text-right">
                    @isset($data)
                        <button data-url="{{ route('password.reset', $data->id) }}"
                                class="btn btn-warning btn-reset-password">
                            Reset Password
                        </button>
                    @endisset
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
            $('select[name="level"]').on('change', function () {
                const level = $(this).val();

                $('.mahasiswa-fields, .admin-fields, .dosen-fields').hide();

                $('.mahasiswa-fields input, .mahasiswa-fields select').prop('disabled', true);
                $('.dosen-fields input').prop('disabled', true);
                $('.admin-fields input').prop('disabled', true);

                if (level === 'MAHASISWA') {
                    $('.mahasiswa-fields').show();
                    $('.mahasiswa-fields input, .mahasiswa-fields select').prop('disabled', false);
                } else if (level === 'DOSEN') {
                    $('.dosen-fields').show();
                    $('.dosen-fields input').prop('disabled', false);
                } else if (level === 'ADMIN') {
                    $('.admin-fields').show();
                    $('.admin-fields input').prop('disabled', false);
                }
            });

            $('select[name="level"]').trigger('change');

            $('form').on('submit', function () {
                const level = $('select[name="level"]').val();

                if (!level) {
                    alert('Silakan pilih Level terlebih dahulu');
                    return false;
                }
            });
        });
    </script>
    <script>
        $(document).ready(function () {
            let rules
            if ($('select[name="level"]').val() === 'MAHASISWA') {
                rules = {
                    nim: {
                        required: true,
                        minlength: 8,
                        maxlength: 15,
                        digits: true
                    },
                    nama: {
                        required: true,
                        minlength: 3,
                        maxlength: 100
                    },
                    program_studi: {
                        required: true
                    },
                    angkatan: {
                        required: true,
                        digits: true,
                        minlength: 4,
                        maxlength: 4
                    },
                    status: {
                        required: true,
                    }
                };
            } else if ($('select[name="level"]').val() === 'DOSEN') {
                rules = {
                    nip: {
                        required: true,
                        digits: true,
                        minlength: 15,
                        maxlength: 25,
                    },
                    nama: {
                        required: true,
                        minlength: 3,
                        maxlength: 100
                    }
                };
            } else if ($('select[name="level"]').val() === 'ADMIN') {
                rules = {
                    username: {
                        required: true,
                        minlength: 3,
                        maxlength: 50,
                    },
                    nama: {
                        required: true,
                        minlength: 3,
                        maxlength: 100
                    }
                };
            }
            initFormValidation("#form-manajemen-pengguna", rules, '{{ isset($data) ? 'Update' : 'Simpan' }}');

            $(document).on('click', '.btn-reset-password', function (e) {
                e.preventDefault();
                swalAlertConfirm({
                    title: 'Reset Password?',
                    text: 'Apakah Anda yakin ingin mereset password pengguna ini?',
                    url: $(this).data('url'),
                    method: 'POST',
                    confirmButtonText: 'Ya, Reset',
                    onSuccess: function () {
                        $('#manajemen-pengguna-table').DataTable().ajax.reload();
                    }
                });
            });
        });
    </script>
@endpush
