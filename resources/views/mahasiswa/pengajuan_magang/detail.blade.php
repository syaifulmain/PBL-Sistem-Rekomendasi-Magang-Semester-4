@extends('layouts.template')

@section('content')
<div class="card">
    <div class="card-body">
        <h4 class="card-title">Detail Pengajuan Magang</h4>

        <!-- Informasi Pengajuan -->
        <div class="card mb-3">
            <div class="card-header bg-primary">
                <h5 class="card-title text-white mb-0">Informasi Pengajuan</h5>
            </div>
            <div class="card-body p-0">
                <table class="table table-profile mb-3">
                    <tr>
                        <th width="30%">Tanggal Pengajuan</th>
                        <td>{{ \Carbon\Carbon::parse($data->tanggal_pengajuan)->translatedFormat('d F Y') }}</td>
                    </tr>
                    <tr>
                        <th width="30%">Status</th>
                        <td>
                            <span class="badge badge-{{ get_pengajuan_status_badge($data->status)['class'] }}">
                                {{ get_pengajuan_status_badge($data->status)['text'] }}
                            </span>
                        </td>
                    </tr>
                    @if($data->catatan)
                        <tr>
                            <th width="30%">Catatan</th>
                            <td>{{ $data->catatan }}</td>
                        </tr>
                    @endif
                </table>
            </div>
        </div>

        <!-- Lowongan Information -->
        <div class="card mb-3">
            <div class="card-header bg-primary">
                <h5 class="card-title text-white mb-0">Informasi Lowongan</h5>
            </div>
            <div class="card-body p-0">
                <table class="table table-profile mb-3">
                    <tr>
                        <th width="30%">Lowongan</th>
                        <td>{{ $data->lowongan->judul }}</td>
                    </tr>
                    <tr>
                        <th width="30%">Perusahaan</th>
                        <td>{{ $data->lowongan->perusahaan->nama }}</td>
                    </tr>
                </table>
            </div>
        </div>
        <!-- Informasi Mahasiswa -->
        <div class="card mb-3">
            <div class="card-header bg-primary">
                <h5 class="card-title text-white mb-0">Informasi Mahasiswa</h5>
            </div>
            <div class="card-body p-0">
                <table class="table table-profile mb-3">
                    <tr>
                        <th width="30%">Nama</th>
                        <td>{{ $data->mahasiswa->nama }}</td>
                    </tr>
                    <tr>
                        <th width="30%">NIM</th>
                        <td>{{ $data->mahasiswa->nim }}</td>
                    </tr>
                    <tr>
                        <th width="30%">Program Studi</th>
                        <td>{{ $data->mahasiswa->programStudi->nama ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th width="30%">Angkatan</th>
                        <td>{{ $data->mahasiswa->angkatan }}</td>
                    </tr>
                    <tr>
                        <th width="30%">Jenis Kelamin</th>
                        <td>{{ $data->mahasiswa->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                    </tr>
                    <tr>
                        <th width="30%">IPK</th>
                        <td>{{ $data->mahasiswa->ipk ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th width="30%">No. Telepon</th>
                        <td>{{ $data->mahasiswa->no_telepon ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th width="30%">Alamat</th>
                        <td>{{ $data->mahasiswa->alamat }}</td>
                    </tr>
                </table>
            </div>
        </div>
        
        <!-- Lampiran -->
        <div class="card mb-3">
            <div class="card-header bg-primary">
                <h5 class="card-title text-white mb-0">Lampiran</h5>
            </div>
            <div class="card-body p-0">
                @if($data->dokumen)
                    <div class="list-group list-group-flush">
                        @foreach($data->dokumen as $dokumen)
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
        
        <a href="{{ route('admin.riwayat-pengajuan.index') }}" class="btn btn-secondary mt-3">
            <i class="fa fa-arrow-left mr-1"></i>Kembali
        </a>
    </div>
</div>
@endsection
