@extends('layouts.template')
@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Total Magang Card -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="mb-1">Total Mahasiswa Bimbingan</p>
                            <h3 class="mb-0">{{ number_format($stats['total_magang']) }}</h3>
                        </div>
                        <div class="icon bg-primary p-2">
                            <i class="mdi mdi-48px mdi-account-group text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mahasiswa Aktif Card -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="mb-1">Mahasiswa Bimbingan Aktif</p>
                            <h3 class="mb-0">{{ number_format($stats['mahasiswa_aktif']) }}</h3>
                        </div>
                        <div class="icon bg-success p-2">
                            <i class="mdi mdi-48px mdi-account-check text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    {{-- <div class="card mt-4">
        <div class="card-body">
            <h5 class="card-title mb-4">Aktivitas Terbaru</h5>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Nama Mahasiswa</th>
                            <th>Status</th>
                            <th>Diperbarui</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $recentActivities = auth()->user()->dosen
                                ->magang
                                ->sortByDesc('updated_at')
                                ->take(5);
                        @endphp
                        @foreach($recentActivities as $magang)
                            <tr>
                                <td>{{ $magang->mahasiswa->nama }}</td>
                                <td>
                                    @php
                                        $statusBadge = get_magang_status_badge($magang->status);
                                    @endphp
                                    <span class="badge badge-{{ $statusBadge['class'] }}">
                                        {{ $statusBadge['text'] }}
                                    </span>
                                </td>
                                <td>{{ Carbon\Carbon::parse($magang->updated_at)->translatedFormat('d M Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div> --}}
</div>
@endsection
