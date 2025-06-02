@extends('layouts.template')
@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Total Lowongan Card -->
        <div class="col-md-3 mt-3 mt-md-0">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="mb-1">Lowongan Buka</p>
                            <h3 class="mb-0">{{ number_format($stats['total_lowongan']) }}</h3>
                        </div>
                        <div class="icon bg-primary rounded-full p-2">
                            <i class="mdi mdi-48px mdi-briefcase text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Pengajuan Diproses Card -->
        <div class="col-md-3 mt-3 mt-md-0">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="mb-1">Pengajuan Diproses</p>
                            <h3 class="mb-0">{{ number_format($stats['total_pengajuan_diproses']) }}</h3>
                        </div>
                        <div class="icon bg-warning rounded-full p-2">
                            <i class="mdi mdi-48px mdi-cogs text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pengajuan Pending Card -->
        <div class="col-md-3 mt-3 mt-md-0">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="mb-1">Pengajuan Pending</p>
                            <h3 class="mb-0">{{ number_format($stats['pengajuan_pending']) }}</h3>
                        </div>
                        <div class="icon bg-danger rounded-full p-2">
                            <i class="mdi mdi-48px mdi-clock-alert text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Magang Card -->
        <div class="col-md-3 mt-3 mt-md-0">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="mb-1">Total Magang</p>
                            <h3 class="mb-0">{{ number_format($stats['total_magang']) }}</h3>
                        </div>
                        <div class="icon bg-success rounded-full p-2">
                            <i class="mdi mdi-48px mdi-account-group text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mahasiswa Aktif Card -->
        <div class="col-md-3 mt-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="mb-1">Magang Aktif</p>
                            <h3 class="mb-0">{{ number_format($stats['mahasiswa_aktif']) }}</h3>
                        </div>
                        <div class="icon bg-info rounded-full p-2">
                            <i class="mdi mdi-48px mdi-account-check text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
