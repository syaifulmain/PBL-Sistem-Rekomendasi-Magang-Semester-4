@extends('layouts.template')

@section('content')
<div class="card">
    <div class="card-body">
        <h4 class="card-title">Detail Pengajuan Magang</h4>
        <p><strong>Mahasiswa:</strong> {{ $data->mahasiswa->nama }}</p>
        <p><strong>NIM:</strong> {{ $data->mahasiswa->nim }}</p>
        <p><strong>Judul Lowongan:</strong> {{ $data->lowongan->judul }}</p>
        <p><strong>Perusahaan:</strong> {{ $data->lowongan->perusahaan->nama ?? '-' }}</p>
        <p><strong>Tanggal Pengajuan:</strong> {{ $data->tanggal_pengajuan }}</p>
        <p><strong>Status:</strong> {{ ucfirst($data->status) }}</p>
        @if ($data->status === 'ditolak')
            <p><strong>Catatan:</strong> {{ $data->catatan }}</p>
        @endif
        <p><strong>Lampiran:</strong></p>
        @if($data->dokumen)
            <ul>
                @foreach($data->dokumen as $dokumen)
                    <li><a href="{{ Storage::url($dokumen->path) }}" target="_blank">{{ $dokumen->jenisDokumen->nama }}</a></li>
                @endforeach
            </ul>
        @else
            <p>-</p>
        @endif
        
        <a href="{{ route('mahasiswa.pengajuan-magang.index') }}" class="btn btn-secondary mt-3">Kembali</a>
    </div>
</div>
@endsection
