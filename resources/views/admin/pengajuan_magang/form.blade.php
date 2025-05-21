@extends('layouts.template')

@section('content')
<div class="row">
    <div class="col-md-12 grid-margin">
        <div class="card shadow">
            <div class="card-header bg-primary">
                <h4 class="mb-0 text-white">Form Pengajuan Magang</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.pengajuan_magang.update', $pengajuan->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row mb-4">
                        <!-- Kolom Kiri -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Perusahaan</label>
                                <div class="form-control-plaintext bg-light p-2 rounded">
                                    {{ $pengajuan->lowonganMagang->perusahaan->nama ?? 'PT. Amerta Indah Otsuka' }}
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">NIM</label>
                                <div class="form-control-plaintext bg-light p-2 rounded">
                                    {{ $pengajuan->mahasiswa->nim ?? '2341720110' }}
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Email</label>
                                <div class="form-control-plaintext bg-light p-2 rounded">
                                    {{ $pengajuan->mahasiswa->email ?? 'kendalljenner234@gmail.com' }}
                                </div>
                            </div>
                        </div>

                        <!-- Kolom Kanan -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Nama Mahasiswa</label>
                                <div class="form-control-plaintext bg-light p-2 rounded">
                                    {{ $pengajuan->mahasiswa->nama ?? 'Sekar Kendall Jenner' }}
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Program Studi</label>
                                <div class="form-control-plaintext bg-light p-2 rounded">
                                    D-IV Teknik Informatika
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">No. Telepon</label>
                                <div class="form-control-plaintext bg-light p-2 rounded">
                                    {{ $pengajuan->no_telepon ?? '089346278362' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Dokumen Upload -->
                    <div class="row mb-4">
                        @foreach([
                            'cv' => 'Daftar Riwayat Hidup/CV',
                            'transkip' => 'Transkip Nilai',
                            'ktp' => 'KTP',
                            'ktm' => 'KTM',
                            'sertifikat' => 'Sertifikat Kompetensi',
                            'proposal' => 'Proposal Magang'
                        ] as $field => $label)
                        <div class="col-md-4 mb-3">
                            <div class="card border">
                                <div class="card-body">
                                    <label class="form-label">{{ $label }}</label>
                                    <div class="d-flex align-items-center gap-2">
                                        @if($pengajuan->$field)
                                        <a href="{{ asset('storage/'.$pengajuan->$field) }}" 
                                           target="_blank"
                                           class="btn btn-sm btn-outline-primary">
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

                    <hr class="my-4">

                    <!-- Status dan Catatan -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Status Pengajuan</label>
                                <select name="status" class="form-select" required>
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
                                    placeholder="Masukkan catatan jika diperlukan">{{ $pengajuan->catatan }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('admin.pengajuan_magang.index') }}" class="btn btn-light">
                            <i class="mdi mdi-arrow-left"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="mdi mdi-content-save"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection