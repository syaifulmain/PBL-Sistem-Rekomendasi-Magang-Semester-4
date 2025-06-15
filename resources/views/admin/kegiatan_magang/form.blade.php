@extends('layouts.template')
@section('content')
<div class="card">
    <div class="card-body">
        <h4>Form Proses Kegiatan Magang</h4>
        <form class="mt-4" id="pengajuan-form" method="POST" action="{{ route('admin.kegiatan-magang.process', ['id' => $pengajuan->id]) }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="id" value="{{ $pengajuan->id }}" readonly>
            <!-- Lowongan Information -->
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">Informasi Lowongan</h5>
                </div>
                <div class="card-body p-0">
                    <table class="table table-profile mb-3">
                        <tr>
                            <th width="30%">Lowongan</th>
                            <td>{{ $pengajuan->lowongan->judul }}</td>
                        </tr>
                        <tr>
                            <th width="30%">Perusahaan</th>
                            <td>{{ $pengajuan->lowongan->perusahaan->nama }}</td>
                        </tr>
                    </table>
                </div>
            </div>
            <!-- Informasi Mahasiswa -->
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">Informasi Mahasiswa</h5>
                </div>
                <div class="card-body p-0">
                    <table class="table table-profile mb-3">
                        <tr>
                            <th width="30%">Nama</th>
                            <td>{{ $pengajuan->mahasiswa->nama }}</td>
                        </tr>
                        <tr>
                            <th width="30%">NIM</th>
                            <td>{{ $pengajuan->mahasiswa->nim }}</td>
                        </tr>
                        <tr>
                            <th width="30%">Program Studi</th>
                            <td>{{ $pengajuan->mahasiswa->programStudi->nama ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th width="30%">Angkatan</th>
                            <td>{{ $pengajuan->mahasiswa->angkatan }}</td>
                        </tr>
                        <tr>
                            <th width="30%">Jenis Kelamin</th>
                            <td>{{ $pengajuan->mahasiswa->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                        </tr>
                        <tr>
                            <th width="30%">IPK</th>
                            <td>{{ $pengajuan->mahasiswa->ipk ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th width="30%">No. Telepon</th>
                            <td>{{ $pengajuan->mahasiswa->no_telepon ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th width="30%">Alamat</th>
                            <td>{{ $pengajuan->mahasiswa->alamat }}</td>
                        </tr>
                    </table>
                </div>
            </div>
 
            <!-- Lampiran -->
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">Lampiran</h5>
                </div>
                <div class="card-body p-0">
                    @if($pengajuan->dokumen)
                        <div class="list-group list-group-flush">
                            @foreach($pengajuan->dokumen as $dokumen)
                                <div class="list-group-item list-group-item-action d-flex align-items-center">
                                    <div class="icon-wrapper me-3">
                                        <i class="mdi mdi-file-document-outline text-primary mdi-24px"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <a href="{{ Storage::url($dokumen->path) }}" target="_blank" class="text-decoration-none">
                                            <h6 class="mb-0">{{ $dokumen->jenisDokumen->nama }}</h6>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="p-3 text-center text-muted">
                            <i class="mdi mdi-file-outline mdi-48px mb-2"></i>
                            <p class="mb-0">Tidak ada lampiran yang tersedia</p>
                        </div>
                    @endif
                </div>
            </div>
             
            <!-- Status -->
            <div class="form-group">
                <label for="status">Status</label>
                <select name="status" id="status" class="form-control" required>
                    <option value="">-- Pilih Status --</option>
                    <option value="disetujui">Disetujui</option>
                    <option value="ditolak">Ditolak</option>
                </select>
            </div>

            <!-- Dosen -->
            <div class="form-group dosen-group" style="display: none;">
                <label for="dosen_id">Dosen Pembimbing</label>
                <select name="dosen_id" id="dosen_id" class="form-control" required>
                    <option value="">-- Pilih Dosen --</option>
                    @foreach ($dosen as $item)
                        <option value="{{ $item->id }}" {{ $pengajuan->dosen_id == $item->id ? 'selected' : '' }}>
                            {{ $item->nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Catatan -->
            <div class="form-group catatan-group" style="display: none;">
                <label for="catatan">Catatan</label>
                <textarea name="catatan" id="catatan" class="form-control" rows="3" 
                        placeholder="Wajib diisi jika status ditolak" 
                        required></textarea>
            </div>
            
            <button type="submit" id="btn-submit" class="btn btn-success float-right">Simpan</button>
        </form>
    </div>
</div>
@endsection
@push('js')
    <script>
        $(document).ready(function() {
            let status = $('#status').val();
            let dosenGroup = $('.dosen-group');
            let catatanGroup = $('.catatan-group');
            let dosenSelect = $('#dosen_id');
            let catatanTextarea = $('#catatan');

            // Fungsi untuk mengatur required
            function setRequiredFields() {
                if (status === 'disetujui') {
                    dosenSelect.prop('required', true);
                    catatanTextarea.prop('required', false);
                } else if (status === 'ditolak') {
                    dosenSelect.prop('required', false);
                    catatanTextarea.prop('required', true);
                } else {
                    dosenSelect.prop('required', false);
                    catatanTextarea.prop('required', false);
                }
            }

            // Set initial state
            setRequiredFields();

            // Event handler untuk perubahan status
            $('#status').change(function() {
                status = $(this).val();
                setRequiredFields();

                if (status === 'ditolak') {
                    dosenGroup.hide();
                    catatanGroup.show();
                } else if (status === 'disetujui') {
                    dosenGroup.show();
                    catatanGroup.hide();
                } else {
                    dosenGroup.hide();
                    catatanGroup.hide();
                }
            });
        });
    </script>
@endpush