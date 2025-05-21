@extends('layouts.template')

@section('content')
<div class="row">
    <div class="col-md-12 grid-margin">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Pengajuan Magang</h4>
                
                <!-- Section 1: Data Pengajuan -->
                <div class="mb-4">
                    <h5 class="mb-3">Data Pengajuan Magang</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Perusahaan</label>
                                <div class="col-sm-8">
                                    <p class="form-control-plaintext">{{ $pengajuan->lowonganMagang->perusahaan->nama ?? 'PT. Amerta Indah Otsuka' }}</p>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Nama</label>
                                <div class="col-sm-8">
                                    <p class="form-control-plaintext">{{ $pengajuan->mahasiswa->nama ?? 'Sekar Kendall Jenner' }}</p>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">NIM</label>
                                <div class="col-sm-8">
                                    <p class="form-control-plaintext">{{ $pengajuan->mahasiswa->nim ?? '2341720110' }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Program Studi</label>
                                <div class="col-sm-8">
                                    <p class="form-control-plaintext">D-IV Teknik Informatika</p>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Email</label>
                                <div class="col-sm-8">
                                    <p class="form-control-plaintext">{{ $pengajuan->mahasiswa->email ?? 'kendalljenner234@gmail.com' }}</p>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">No. Telepon</label>
                                <div class="col-sm-8">
                                    <p class="form-control-plaintext">{{ $pengajuan->no_telepon ?? '089346278362' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 2: Dokumen -->
                <div class="mb-4">
                    <h5 class="mb-3">Dokumen Pendukung</h5>
                    <div class="row">
                        @foreach([
                            'cv' => 'Daftar Riwayat Hidup/CV',
                            'transkip' => 'Transkip Nilai',
                            'ktp' => 'KTP',
                            'ktm' => 'KTM',
                            'sertifikat' => 'Sertifikat Kompetensi',
                            'proposal' => 'Proposal Magang'
                        ] as $field => $label)
                        <div class="col-md-6 mb-3">
                            <div class="card border">
                                <div class="card-body">
                                    <label class="form-label">{{ $label }}</label>
                                    <div class="d-flex align-items-center gap-2">
                                        @if($pengajuan->$field)
                                        <a href="{{ asset('storage/'.$pengajuan->$field) }}" 
                                           target="_blank"
                                           class="btn btn-outline-primary btn-sm">
                                            <i class="mdi mdi-file-eye"></i> Lihat
                                        </a>
                                        @else
                                        <span class="text-muted">Tidak ada dokumen</span>
                                        @endif
                                        <input type="file" name="{{ $field }}" class="form-control form-control-sm">
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Section 3: Status & Catatan -->
                <div class="mb-4">
                    <h5 class="mb-3">Persetujuan</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Status Pengajuan</label>
                                <select name="status" class="form-select" required>
                                    <option value="" disabled>Pilih Status</option>
                                    <option value="diajukan" {{ $pengajuan->status == 'diajukan' ? 'selected' : '' }}>Diajukan</option>
                                    <option value="disetujui" {{ $pengajuan->status == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                                    <option value="ditolak" {{ $pengajuan->status == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                                    <option value="batal" {{ $pengajuan->status == 'batal' ? 'selected' : '' }}>Batal</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Catatan</label>
                                <textarea name="catatan" class="form-control" rows="3" 
                                    placeholder="Masukkan catatan...">{{ $pengajuan->catatan }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tombol Aksi -->
                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('admin.pengajuan_magang.index') }}" class="btn btn-light">
                        Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        Kirim
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection