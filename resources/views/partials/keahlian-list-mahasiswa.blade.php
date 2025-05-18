@foreach($listKeahlianMahasiswa as $keahlian)
    <div class="d-inline-flex align-items-center bg-primary text-white rounded-pill px-3 py-1 mb-1">
        <span>{{ $keahlian->keahlianTeknis->nama}} -
        @if($keahlian->level == 1)
                Pemula
            @elseif($keahlian->level == 2)
                Menengah
            @elseif($keahlian->level == 3)
                Mahir
            @endif
        </span>
        <form action="{{ route('mahasiswa.profil.keahlian.delete', $keahlian->id) }}" method="POST" class="ms-2">
            @csrf
            @method('DELETE')
            <button type="button" class="btn btn-close btn-close-white btn-sm text-white btn-delete-keahlian">X</button>
        </form>
    </div>
@endforeach
