@extends('layouts.template')

@push('styles')
<style>
    .card-chart {
        height: 100%;
    }
    .chart-container {
        position: relative;
        height: 300px;
        width: 100%;
    }
    .stat-card {
        transition: transform 0.3s ease;
    }
    .stat-card:hover {
        transform: translateY(-5px);
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Stats Row -->
    <div class="row">
        <!-- Mahasiswa Magang -->
        <div class="col-md-6 mt-3">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="mb-1 text-muted">Mahasiswa Magang</p>
                            <h3 class="mb-0">{{ number_format($totalMagang) }}</h3>
                        </div>
                        <div class="icon bg-success rounded-circle p-3 d-flex align-items-center justify-content-center">
                            <i class="mdi mdi-36px mdi-school text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    
        <!-- Dosen Pembimbing -->
        <div class="col-md-6 mt-3">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="mb-1 text-muted">Dosen Pembimbing</p>
                            <h3 class="mb-0">{{ number_format($totalDosen) }}</h3>
                        </div>
                        <div class="icon bg-primary rounded-circle p-3 d-flex align-items-center justify-content-center">
                            <i class="mdi mdi-36px mdi-account-tie text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    
        <!-- Rasio Dosen:Mahasiswa Chart -->
        <div class="col-md-6 mt-3">
            <div class="card card-chart">
                <div class="card-header">
                    <h4 class="card-title">Rasio Dosen Pembimbing dan Mahasiswa</h4>
                    <p class="card-subtitle">Perbandingan jumlah dosen dengan mahasiswa magang</p>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="rasioChart"></canvas>
                    </div>
                </div>
                <div class="text-center mt-3">
                    <h4 class="text-primary">Rasio: {{ $rasioDosen }}:1</h4>
                </div>
            </div>
        </div>
        
        <!-- Efektivitas Rekomendasi -->
        <div class="col-md-6 mt-3">
            <div class="card card-chart">
                <div class="card-header">
                    <h4 class="card-title">Efektivitas Rekomendasi</h4>
                    <p class="card-subtitle">Perbandingan pengajuan yang disetujui dan tidak disetujui</p>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="efektivitasChart"></canvas>
                    </div>
                    <div class="text-center mt-3">
                        <h4 class="text-primary">Tingkat Keberhasilan: {{ $efektivitasRekomendasi }}%</h4>
                        <small class="text-muted">Dari total {{ $totalPengajuan }} pengajuan</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row 1 -->
    <div class="row mt-3">
        <!-- Status Pengajuan Pie Chart -->
        <div class="col-md-6">
            <div class="card card-chart">
                <div class="card-header">
                    <h4 class="card-title">Status Pengajuan Magang</h4>
                    <p class="card-subtitle">Distribusi status pengajuan magang</p>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="statusChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tren Peminatan Bar Chart -->
        <div class="col-md-6">
            <div class="card card-chart">
                <div class="card-header">
                    <h4 class="card-title">Top 5 Peminatan</h4>
                    <p class="card-subtitle">Berdasarkan judul lowongan</p>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="trenPeminatanChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row 2 -->
    <div class="row mt-4 mb-4">
        <!-- Mahasiswa per Angkatan -->
        <div class="col-md-6">
            <div class="card card-chart">
                <div class="card-header">
                    <h4 class="card-title">Distribusi Mahasiswa per Angkatan</h4>
                    <p class="card-subtitle">Berdasarkan tahun masuk</p>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="angkatanChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mahasiswa per Perusahaan -->
        <div class="col-md-6">
            <div class="card card-chart">
                <div class="card-header">
                    <h4 class="card-title">Top 5 Perusahaan Tujuan</h4>
                    <p class="card-subtitle">Berdasarkan jumlah mahasiswa</p>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="perusahaanChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script src="{{ asset('assets/plugins/chartjs/chart.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Status Pengajuan Pie Chart
        const statusCtx = document.getElementById('statusChart').getContext('2d');
        const statusLabels = @json($chartData['statusPengajuan']['labels']);
        const statusData = @json($chartData['statusPengajuan']['data']);
        const statusColors = @json($chartData['statusPengajuan']['colors']);
        
        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: statusLabels.map(label => label.charAt(0).toUpperCase() + label.slice(1)),
                datasets: [{
                    data: statusData,
                    backgroundColor: statusLabels.map(label => statusColors[label] || '#6c757d'),
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: {
                    duration: 0
                },
                plugins: {
                    legend: {
                        position: 'right',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = Math.round((value / total) * 100);
                                return `${label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });

        // Tren Peminatan Bar Chart
        const trenCtx = document.getElementById('trenPeminatanChart').getContext('2d');
        new Chart(trenCtx, {
            type: 'bar',
            data: {
                labels: @json($chartData['trenPeminatan']['labels']),
                datasets: [{
                    label: 'Jumlah Mahasiswa',
                    data: @json($chartData['trenPeminatan']['data']),
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: {
                    duration: 0
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });

        // Mahasiswa per Angkatan Line Chart
        const angkatanCtx = document.getElementById('angkatanChart').getContext('2d');
        new Chart(angkatanCtx, {
            type: 'line',
            data: {
                labels: @json($chartData['mahasiswaPerAngkatan']['labels']),
                datasets: [{
                    label: 'Jumlah Mahasiswa',
                    data: @json($chartData['mahasiswaPerAngkatan']['data']),
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    tension: 0.3,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: {
                    duration: 0
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });

        // Rasio Dosen:Mahasiswa Pie Chart
        const rasioCtx = document.getElementById('rasioChart').getContext('2d');
        const rasioLabels = @json($chartData['rasioDosenMahasiswa']['labels']);
        const rasioData = @json($chartData['rasioDosenMahasiswa']['data']);
        const rasioColors = @json($chartData['rasioDosenMahasiswa']['colors']);
        
        new Chart(rasioCtx, {
            type: 'pie',
            data: {
                labels: rasioLabels,
                datasets: [{
                    data: rasioData,
                    backgroundColor: rasioColors,
                    borderWidth: 1,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: {
                    duration: 0
                },
                plugins: {
                    legend: {
                        position: 'right',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = Math.round((value / total) * 100);
                                return `${label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });

        // Efektivitas Rekomendasi Pie Chart
        const efektivitasCtx = document.getElementById('efektivitasChart').getContext('2d');
        const efektivitasLabels = @json($chartData['efektivitas']['labels']);
        const efektivitasData = @json($chartData['efektivitas']['data']);
        const efektivitasColors = @json($chartData['efektivitas']['colors']);
        
        new Chart(efektivitasCtx, {
            type: 'doughnut',
            data: {
                labels: efektivitasLabels,
                datasets: [{
                    data: efektivitasData,
                    backgroundColor: efektivitasColors,
                    borderWidth: 1,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                animation: {
                    duration: 0
                },
                plugins: {
                    legend: {
                        position: 'right',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = Math.round((value / total) * 100);
                                return `${label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });

        // Mahasiswa per Perusahaan Horizontal Bar Chart
        const perusahaanCtx = document.getElementById('perusahaanChart').getContext('2d');
        new Chart(perusahaanCtx, {
            type: 'bar',
            data: {
                labels: @json($chartData['mahasiswaPerPerusahaan']['labels']),
                datasets: [{
                    label: 'Jumlah Mahasiswa',
                    data: @json($chartData['mahasiswaPerPerusahaan']['data']),
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.6)',
                        'rgba(54, 162, 235, 0.6)',
                        'rgba(255, 206, 86, 0.6)',
                        'rgba(75, 192, 192, 0.6)',
                        'rgba(153, 102, 255, 0.6)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                animation: {
                    duration: 0
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    });
</script>
@endpush
