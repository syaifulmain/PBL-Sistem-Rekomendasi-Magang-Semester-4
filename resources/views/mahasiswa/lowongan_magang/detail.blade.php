<div class="content">
    <p><strong>Judul:</strong> {{ $data->judul }}</p>
    <p><strong>Perusahaan:</strong> {{ $data->perusahaan->nama ?? '-' }}</p>
    <p><strong>Periode Magang:</strong> {{ $data->periodeMagang->nama ?? '-' }}</p>
    <p><strong>Minimal IPK:</strong> {{ $data->minimal_ipk ?? '-' }}</p>
    <p><strong>Insentif:</strong> {{ $data->insentif ?? '-' }}</p>
    <p><strong>Deskripsi:</strong> {!! nl2br(e($data->deskripsi)) !!}</p>
    <p><strong>Persyaratan:</strong> {!! nl2br(e($data->persyaratan)) !!}</p>
    <p><strong>Kuota:</strong> {{ $data->kuota }}</p>
    <p><strong>Tanggal Mulai Daftar:</strong> {{ $data->tanggal_mulai_daftar }}</p>
    <p><strong>Tanggal Selesai Daftar:</strong> {{ $data->tanggal_selesai_daftar }}</p>
    <p><strong>Tanggal Mulai Magang:</strong> {{ $data->tanggal_mulai_magang }}</p>
    <p><strong>Tanggal Selesai Magang:</strong> {{ $data->tanggal_selesai_magang }}</p>
    <p><strong>Status:</strong> {{ ucfirst($data->status) }}</p>

    <hr>

    <h5>Bidang Keahlian yang Dibutuhkan</h5>
    @if($data->keahlian->count() > 0)
        <ul>
            @foreach($data->keahlian as $keahlian)
                <li>{{ $keahlian->nama }}</li>
            @endforeach
        </ul>
    @else
        <p>-</p>
    @endif

    <h5>Keahlian Teknis yang Dibutuhkan</h5>
    @if($data->teknis->count() > 0)
        <ul>
            @foreach($data->teknis as $teknis)
                <li>{{ $teknis->nama }} ({{ $teknis->pivot->level }})</li>
            @endforeach
        </ul>
    @else
        <p>-</p>
    @endif

    <h5>Dokumen yang Dibutuhkan</h5>
    @if($data->dokumen->count() > 0)
        <ul>
            @foreach($data->dokumen as $dokumen)
                <li>{{ $dokumen->nama }}</li>
            @endforeach
        </ul>
    @else
        <p>-</p>
    @endif

    <a href="#" class="btn btn-primary mt-3">Melamar</a>
    <a href="#" class="btn btn-outline-primary mt-3">Simpan</a>
</div>
