<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Catatan Harian Magang</title>
    <style>
        body { font-family: Arial, sans-serif; }
        h2, h3 { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 6px; font-size: 12px; vertical-align: top; }
        th { background-color: #f2f2f2; }
        img { max-width: 100px; }
    </style>
</head>
<body>

<h2>CATATAN HARIAN (LOG BOOK)<br>MAGANG KERJA</h2>
<h3>{{$data->pengajuanMagang->lowongan->getNamaPerusahaan()}}</h3>

<table style="margin-top: 10px;">
    <tr><td><strong>Lokasi MAGANG KERJA</strong></td><td>: {{$data->pengajuanMagang->lowongan->perusahaan->alamat}}</td></tr>
    <tr><td><strong>DPL</strong></td><td>: {{$data->dosen->nama}}</td></tr>
    <tr><td><strong>Nama Mahasiswa</strong></td><td>: {{$data->pengajuanMagang->mahasiswa->nama}}</td></tr>
    <tr><td><strong>NIM</strong></td><td>: {{$data->pengajuanMagang->mahasiswa->nim}}</td></tr>
</table>

<table>
    <thead>
    <tr>
        <th>No</th>
        <th>Tanggal</th>
        <th>Aktivitas</th>
        <th>Kendala</th>
        <th>Keterangan</th>
        <th>Dokumen</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data->logMagangMahasiswa as $index => $log)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ \Carbon\Carbon::parse($log->tanggal)->translatedFormat('l, d M Y') }}</td>
            <td>{{ $log->aktivitas }}</td>
            <td>{{ $log->kendala }}</td>
            <td>{{ $log->keterangan }}</td>
            <td>
                @if($log->dokumentasi)
                    <img src="{{ public_path('storage/' . $log->dokumentasi) }}" alt="Dokumen">
                @else
                    Tidak ada
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

</body>
</html>
