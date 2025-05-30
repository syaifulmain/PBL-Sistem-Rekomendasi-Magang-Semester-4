@extends('layouts.template')
@section('content')
    @if($data->isEmpty())
        <div class="card">
            <div class="card-body">
                <div class="alert alert-danger">Tidak Ada</div>
            </div>
        </div>
    @else
        @foreach($data as $item)
            <a href="{{ route('dosen.bimbingan-magang.monitoring', $item->pengajuanMagang->id) }}"
               class="text-decoration-none text-dark">
                <div class="card mb-3 card-hover cursor-pointer">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h3 class="mb-2 font-weight-bold">{{ $item->pengajuanMagang->lowongan->judul }}</h3>
                                <h5 class="mb-2 opacity-90">
                                    <i class="mdi  mdi mdi-city  mr-2"></i>
                                    {{ $item->pengajuanMagang->lowongan->perusahaan->nama }}
                                </h5>
                                @if($item->status === 'aktif')
                                    <p class="mb-2">
                                        <i class="mdi mdi-calendar-clock  mr-2"></i>
                                        {{ $item->getSisaWaktuMangangAttribute() }} hari tersisa
                                    </p>
                                @endif
                                <p class="mb-0">
                                    <i class=" mdi mdi-account  mr-2"></i>
                                    Mahasiswa: {{ $item->pengajuanMagang->mahasiswa->nama }}
                                </p>
                            </div>
                            <div class="col-md-4 text-md-right">
                                    <span
                                        class="badge badge-{{ $item->status === 'selesai' ? 'success' : 'warning' }} badge-lg px-3 py-2">
                                        <i class="mdi mdi-{{ $item->status === 'selesai' ? 'check-circle' : 'clock' }} mr-1"></i>
                                        {{ ucfirst($item->status) }}
                                    </span>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        @endforeach
    @endif
@endsection
@push('css')
    <style>
        .card-hover {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }
    </style>
@endpush
@push('js')

@endpush
