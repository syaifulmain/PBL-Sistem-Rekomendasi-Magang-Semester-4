@extends('layouts.template')
@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Statistik Pengajuan Card -->
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="mb-1">Total Pengajuan</p>
                            <h3 class="mb-0">{{ number_format($stats['lowongan_terdaftar']) }}</h3>
                        </div>
                        <div class="icon bg-primary p-2">
                            <i class="mdi mdi-48px mdi-file-document-box-multiple text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mahasiswa Aktif Card -->
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="mb-1">Pengajuan Sedang Diajukan</p>
                            <h3 class="mb-0">{{ number_format($stats['pengajuan_diajukan']) }}</h3>
                        </div>
                        <div class="icon bg-warning p-2">
                            <i class="mdi mdi-48px mdi-file-document-box-outline text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="mb-1">Pengajuan Disetujui</p>
                            <h3 class="mb-0">{{ number_format($stats['pengajuan_aktif']) }}</h3>
                        </div>
                        <div class="icon bg-success p-2">
                            <i class="mdi mdi-48px mdi-file-document-box-check text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
       
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="mb-1">Pengajuan Ditolak/Batal</p>
                            <h3 class="mb-0">{{ number_format($stats['pengajuan_ditolak']) }}</h3>
                        </div>
                        <div class="icon bg-danger p-2">
                            <i class="mdi mdi-48px mdi-file-document-box-remove text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
