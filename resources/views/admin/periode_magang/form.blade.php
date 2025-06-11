@extends('layouts.template')
@section('content')
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">{{ isset($data) ? 'Edit' : 'Tambah' }} Periode Magang</h4>
            <form action="{{ isset($data) ? route('admin.periode-magang.edit', $data->id) : route('admin.periode-magang.create') }}" method="POST">
                @csrf
                @if(isset($data))
                    @method('PUT')
                @endif

                <div class="form-group">
                    <label for="nama">Nama Periode</label>
                    <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
                        value="{{ old('nama', $data->nama ?? '') }}">
                    @error('nama')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="tanggal_mulai">Tanggal Mulai</label>
                    <input type="text" name="tanggal_mulai" id="tanggal_mulai"
                        class="form-control datepicker @error('tanggal_mulai') is-invalid @enderror"
                        value="{{ old('tanggal_mulai', $data->tanggal_mulai ?? '') }}">
                    @error('tanggal_mulai')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="tanggal_selesai">Tanggal Selesai</label>
                    <input type="text" name="tanggal_selesai" id="tanggal_selesai"
                        class="form-control datepicker @error('tanggal_selesai') is-invalid @enderror"
                        value="{{ old('tanggal_selesai', $data->tanggal_selesai ?? '') }}">
                    @error('tanggal_selesai')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="tahun_akademik">Tahun Akademik</label>
                    <input placeholder="yyyy/yyyy" type="text" id="tahun_akademik" name="tahun_akademik" class="form-control @error('tahun_akademik') is-invalid @enderror"
                        value="{{ old('tahun_akademik', $data->tahun_akademik ?? '') }}">
                    @error('tahun_akademik')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="semester">Semester</label>
                    <select name="semester" class="form-control @error('semester') is-invalid @enderror">
                        <option value="">-- Pilih Semester --</option>
                        <option value="Ganjil" {{ old('semester', $data->semester ?? '') == 'Ganjil' ? 'selected' : '' }}>Ganjil</option>
                        <option value="Genap" {{ old('semester', $data->semester ?? '') == 'Genap' ? 'selected' : '' }}>Genap</option>
                    </select>
                    @error('semester')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- <div class="form-group">
                    <label for="tanggal_pendaftaran_mulai">Tanggal Pendaftaran Mulai</label>
                    <input type="text" name="tanggal_pendaftaran_mulai" id="tanggal_pendaftaran_mulai"
                        class="form-control datepicker @error('tanggal_pendaftaran_mulai') is-invalid @enderror"
                        value="{{ old('tanggal_pendaftaran_mulai', $data->tanggal_pendaftaran_mulai ?? '') }}">
                    @error('tanggal_pendaftaran_mulai')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="tanggal_pendaftaran_selesai">Tanggal Pendaftaran Selesai</label>
                    <input type="text" name="tanggal_pendaftaran_selesai" id="tanggal_pendaftaran_selesai"
                        class="form-control datepicker @error('tanggal_pendaftaran_selesai') is-invalid @enderror"
                        value="{{ old('tanggal_pendaftaran_selesai', $data->tanggal_pendaftaran_selesai ?? '') }}">
                    @error('tanggal_pendaftaran_selesai')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div> --}}
                
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
    <script src="{{ asset('skydash-v.01/vendors/inputmask/jquery.inputmask.bundle.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#tahun_akademik').inputmask('9999/9999');
        });
    </script>
@endpush