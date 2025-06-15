@extends('layouts.template')

@section('content')
<div class="card shadow-sm">
    <div class="card-body">
        <h4 class="card-title mb-4">Detail Lowongan Magang</h4>

        <div class="row">
            <div class="col-lg-6 col-md-12">
                <dl class="row mb-0">
                    <dt class="col-sm-4 text-dark p-0">Judul</dt>
                    <dd class="col-sm-8 text-dark p-0">{{ $data->judul }}</dd>
        
                    <dt class="col-sm-4 text-dark p-0">Perusahaan</dt>
                    <dd class="col-sm-8 text-dark p-0">{{ $data->perusahaan->nama ?? '-' }}</dd>
        
                    <dt class="col-sm-4 text-dark p-0">Alamat</dt>
                    <dd class="col-sm-8 text-dark p-0">{{ $data->perusahaan->alamat ?? '-' }}</dd>
        
                    <dt class="col-sm-4 text-dark p-0">Periode Magang</dt>
                    <dd class="col-sm-8 text-dark p-0">{{ $data->periodeMagang->nama ?? '-' }}</dd>
        
                    <dt class="col-sm-4 text-dark p-0">Minimal IPK</dt>
                    <dd class="col-sm-8 text-dark p-0">{{ $data->minimal_ipk ?? '-' }}</dd>
        
                    <dt class="col-sm-4 text-dark p-0">Insentif</dt>
                    <dd class="col-sm-8 text-dark p-0">{{ $data->insentif ?? '-' }}</dd>
                </dl>
            </div>
            <div class="col-lg-6 col-md-12 mt-md-3 mt-lg-0">
                <dl class="row mb-0">
                    <dt class="col-sm-5 text-dark p-0">Kuota</dt>
                    <dd class="col-sm-7 text-dark p-0">{{ $data->kuota }}</dd>
        
                    <dt class="col-sm-5 text-dark p-0">Mulai Daftar</dt>
                    <dd class="col-sm-7 text-dark p-0">{{ \Carbon\Carbon::parse($data->tanggal_mulai_daftar)->translatedFormat('d F Y') }}</dd>
        
                    <dt class="col-sm-5 text-dark p-0">Selesai Daftar</dt>
                    <dd class="col-sm-7 text-dark p-0">{{ \Carbon\Carbon::parse($data->tanggal_selesai_daftar)->translatedFormat('d F Y') }}</dd>
        
                    <dt class="col-sm-5 text-dark p-0">Mulai Magang</dt>
                    <dd class="col-sm-7 text-dark p-0">{{ \Carbon\Carbon::parse($data->tanggal_mulai_magang)->translatedFormat('d F Y') }}</dd>
        
                    <dt class="col-sm-5 text-dark p-0">Selesai Magang</dt>
                    <dd class="col-sm-7 text-dark p-0">{{ \Carbon\Carbon::parse($data->tanggal_selesai_magang)->translatedFormat('d F Y') }}</dd>
        
                    <dt class="col-sm-5 text-dark p-0">Status</dt>
                    <dd class="col-sm-7 text-dark p-0">
                        <span class="badge badge-pill badge-{{ $data->status == 'buka' ? 'success' : 'warning' }}">
                            {{ ucfirst($data->status) }}
                        </span>
                    </dd>
                </dl>
            </div>
        </div>
        

        <div class="mt-4">
            <h5>Deskripsi</h5>
            <div class="border p-3 rounded bg-light">{!! nl2br(e($data->deskripsi)) !!}</div>
        </div>

        <div class="mt-3">
            <h5>Persyaratan</h5>
            <div class="border p-3 rounded bg-light">{!! nl2br(e($data->persyaratan)) !!}</div>
        </div>

        <div class="row">
            <div class="col-md-4 mb-2 mt-4">
                <h5>Bidang Keahlian yang Dibutuhkan</h5>
                @if($data->keahlian->count() > 0)
                    <ul class="list-group list-group-flush">
                        @foreach($data->keahlian as $keahlian)
                            <li class="list-group-item">{{ $keahlian->nama }}</li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-muted">-</p>
                @endif
            </div>
    
            <div class="col-md-4 mb-2 mt-4">
                <h5>Keahlian Teknis yang Dibutuhkan</h5>
                @if($data->teknis->count() > 0)
                    <ul class="list-group list-group-flush">
                        @foreach($data->teknis as $teknis)
                            <li class="list-group-item">{{ $teknis->nama }} <span class="badge badge-info">{{ $teknis->pivot->level }}</span></li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-muted">-</p>
                @endif
            </div>
    
            <div class="col-md-4 mb-2 mt-4">
                <h5>Dokumen yang Dibutuhkan</h5>
                @if($data->dokumen->count() > 0)
                    <ul class="list-group list-group-flush">
                        @foreach($data->dokumen as $dokumen)
                            <li class="list-group-item">{{ $dokumen->nama }}</li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-muted">-</p>
                @endif
            </div>
        </div>

        <div class="mt-4">
            <a href="{{ route('admin.lowongan-magang.index') }}" class="btn btn-secondary">
                <i class="fa fa-arrow-left mr-1"></i> Kembali
            </a>
        </div>
    </div>
</div>
@endsection
