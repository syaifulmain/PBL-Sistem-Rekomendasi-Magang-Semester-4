@foreach($listMinatMahasiswa as $minat)
    <div class="d-inline-flex align-items-center bg-primary text-white rounded-pill px-3 py-1 mb-1">
        <span>{{ $minat->bidangKeahlian->nama }}</span>
        <form action="{{ route('mahasiswa.profil.minat.delete', $minat->id) }}" method="POST" class="ms-2">
            @csrf
            @method('DELETE')
            <button type="button" class="btn btn-close btn-close-white btn-sm text-white btn-delete-minat">X</button>
        </form>
    </div>
@endforeach
