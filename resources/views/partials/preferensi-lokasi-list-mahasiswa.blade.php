@foreach($preferensiLokasiMahasiswa as $lokasi)
    <div class="d-inline-flex align-items-center bg-primary text-white rounded-pill px-3 py-1 mb-1">
        <span>{{ $lokasi->nama_tampilan }}</span>
        <form action="{{ route('mahasiswa.profil.preferensi-lokasi.delete', $lokasi->id) }}" method="POST" class="ms-2">
            @csrf
            @method('DELETE')
            <button type="button" class="btn btn-close btn-close-white btn-sm text-white btn-delete-preferensi-lokasi">X</button>
        </form>
    </div>
@endforeach
