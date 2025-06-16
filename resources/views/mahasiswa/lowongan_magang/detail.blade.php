<div class="card-header bg-warning text-white">
    <h4 class="mb-0">{{ $data->judul }}</h4>
</div>
<div class="card-body">
    <div class="row">
        <div class="col-lg-6 col-md-12">
            <dl class="row mb-0">
                <dt class="col-sm-4 text-secondary">Perusahaan</dt>
                <dd class="col-sm-8 text-dark">{{ $data->perusahaan->nama ?? '-' }}</dd>

                <dt class="col-sm-4 text-secondary">Alamat</dt>
                <dd class="col-sm-8 text-dark">{{ $data->perusahaan->alamat ?? '-' }}</dd>

                <dt class="col-sm-4 text-secondary">Periode Magang</dt>
                <dd class="col-sm-8 text-dark">{{ $data->periodeMagang->nama ?? '-' }}</dd>

                <dt class="col-sm-4 text-secondary">Minimal IPK</dt>
                <dd class="col-sm-8 text-dark">{{ $data->minimal_ipk ?? '-' }}</dd>

                <dt class="col-sm-4 text-secondary">Insentif</dt>
                <dd class="col-sm-8 text-dark">{{ $data->insentif ?? '-' }}</dd>
            </dl>
        </div>
        <div class="col-lg-6 col-md-12 mt-md-3 mt-lg-0">
            <dl class="row mb-0">
                <dt class="col-sm-5 text-dark">Kuota</dt>
                <dd class="col-sm-7 text-dark">{{ $data->kuota }}</dd>

                <dt class="col-sm-5 text-dark">Mulai Daftar</dt>
                <dd class="col-sm-7 text-dark">{{ \Carbon\Carbon::parse($data->tanggal_mulai_daftar)->format('d F Y') }}</dd>

                <dt class="col-sm-5 text-dark">Selesai Daftar</dt>
                <dd class="col-sm-7 text-dark">{{ \Carbon\Carbon::parse($data->tanggal_selesai_daftar)->format('d F Y') }}</dd>

                <dt class="col-sm-5 text-dark">Mulai Magang</dt>
                <dd class="col-sm-7 text-dark">{{ \Carbon\Carbon::parse($data->tanggal_mulai_magang)->format('d F Y') }}</dd>

                <dt class="col-sm-5 text-dark">Selesai Magang</dt>
                <dd class="col-sm-7 text-dark">{{ \Carbon\Carbon::parse($data->tanggal_selesai_magang)->format('d F Y') }}</dd>

                <dt class="col-sm-5 text-dark">Status</dt>
                <dd class="col-sm-7 text-dark"><span
                        class="badge badge-pill badge-{{ $data->status == 'aktif' ? 'success' : 'warning' }}">{{ ucfirst($data->status) }}</span>
                </dd>
            </dl>
        </div>
    </div>

    <hr class="my-4">

    <h5 class="text-primary mb-3">Deskripsi Pekerjaan</h5>
    <div class="card card-body bg-light mb-4">
        <p class="card-text text-dark">{!! nl2br(e($data->deskripsi)) !!}</p>
    </div>

    <h5 class="text-primary mb-3">Persyaratan</h5>
    <div class="card card-body bg-light mb-4">
        <p class="card-text text-dark">{!! nl2br(e($data->persyaratan)) !!}</p>
    </div>

    <div class="row">
        <div class="col-md-4 col-sm-12"><h5 class="text-primary mb-3">Bidang Keahlian</h5>
            @if($data->keahlian->count() > 0)
                <ul class="list-group mb-3 shadow-sm">
                    @foreach($data->keahlian as $keahlian)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ $keahlian->nama }}
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-muted">Tidak ada data.</p>
            @endif
        </div>
        <div class="col-md-4 col-sm-12 mt-sm-3 mt-md-0"><h5 class="text-primary mb-3">Keahlian Teknis</h5>
            @if($data->teknis->count() > 0)
                <ul class="list-group mb-3 shadow-sm">
                    @foreach($data->teknis as $teknis)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ $teknis->nama }}
                            <span class="badge badge-info badge-pill">{{ $teknis->pivot->level }}</span>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-muted">Tidak ada data.</p>
            @endif
        </div>
        <div class="col-md-4 col-sm-12 mt-sm-3 mt-md-0"><h5 class="text-primary mb-3">Dokumen yang Dibutuhkan</h5>
            @if($data->dokumen->count() > 0)
                <ul class="list-group mb-3 shadow-sm">
                    @foreach($data->dokumen as $dokumen)
                        <li class="list-group-item">{{ $dokumen->nama }}</li>
                    @endforeach
                </ul>
            @else
                <p class="text-muted">Tidak ada data.</p>
            @endif
        </div>
    </div>

    <hr class="my-4">

    <div class="text-right">
        <a href="{{ route('mahasiswa.pengajuan-magang.create') }}?lowongan_id={{ $data->id }}{{ $rekomendasi ? '&rekomendasi='. $rekomendasi : '' }}"
           class="btn btn-primary btn-lg">Melamar Sekarang</a>
    </div>
</div>
