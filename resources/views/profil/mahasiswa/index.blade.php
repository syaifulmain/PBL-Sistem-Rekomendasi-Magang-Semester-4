@extends('layouts.template')

@section('content')
    <div class="mb-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <p class="card-title mb-0">INFORMASI PENGGUNA</p>
                    <button onclick="modalAction('{{route('mahasiswa.profil.informasi-pengguna.index')}}')"
                            class="btn btn-outline-secondary btn-sm">
                        <i class="ti-pencil-alt"></i>
                    </button>
                </div>
                <hr>
                <div class=" d-flex align-items-center">
                    <div class="mr-4">
                        <img src="{{$data->user->getFotoProfilPath()}}" alt="Profile Picture" class="rounded-circle"
                             width="150" height="150" style="object-fit: cover;">
                    </div>
                    <div>
                        <h5 class="card-title">{{$data->nama}}</h5>
                        <p>{{$data->nim}}</p>
                        <p>{{$data->programStudi->jenjang}} {{$data->programStudi->nama}}</p>
                        <p>{{$data->alamat}}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mb-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <p class="card-title mb-0">INFORMASI DETAIL</p>
                    <button onclick="modalAction('{{route('mahasiswa.profil.informasi-detail.index')}}')"
                            class="btn btn-outline-secondary btn-sm">
                        <i class="ti-pencil-alt"></i>
                    </button>
                </div>
                <hr>
                <p><strong>Angkatan</strong><br>{{$data->angkatan}}</p>
                <p><strong>Jenis Kelamin</strong><br>{{$data->getGenderName()}}</p>
                <p><strong>No. Telp</strong><br>{{$data->no_telepon}}</p>
                <p><strong>Status</strong><br>{{$data->status}}</p>
                <p><strong>IPK</strong><br>{{$data->ipk}}</p>
            </div>
        </div>
    </div>

    <div class="mb-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <p class="card-title mb-0">KEAHLIAN</p>
                    <button onclick="modalAction('{{route('mahasiswa.profil.keahlian.index')}}')"
                            class="btn btn-outline-secondary btn-sm">
                        <i class="ti-pencil-alt"></i>
                    </button>
                </div>
                <hr>
                <div class="template-demo">
                    @foreach($data->keahlianMahasiswa as $keahlian)
                        <div class="alert alert-fill-primary d-inline-block mb-2 mr-2">
                            {{ $keahlian->keahlianTeknis->nama }} {{ $keahlian->level }}
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="mb-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <p class="card-title mb-0">MINAT</p>
                    <button onclick="modalAction('{{route('mahasiswa.profil.minat.index')}}')"
                            class="btn btn-outline-secondary btn-sm">
                        <i class="ti-pencil-alt"></i>
                    </button>
                </div>
                <hr>
                <div class="template-demo">
                    @foreach($data->minatMahasiswa as $minat)
                        <div class="alert alert-fill-primary d-inline-block mb-2 mr-2">
                            {{ $minat->bidangKeahlian->nama }}
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="mb-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <p class="card-title mb-0">PREFRENSI LOKASI</p>
                    <button onclick="modalAction('{{route('mahasiswa.profil.preferensi-lokasi.index')}}')"
                            class="btn btn-outline-secondary btn-sm">
                        <i class="ti-pencil-alt"></i>
                    </button>
                </div>
                <hr>
                <div class="template-demo">
                    @foreach($data->preferensiLokasiMahasiswa as $lokasi)
                        <div class="alert alert-fill-primary d-inline-block mb-2 mr-2">
                            {{ $lokasi->nama_tampilan }}
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="mb-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <p class="card-title mb-0">GANTI PASSWORD</p>
                </div>
                <hr>
                <form action="{{route('password.update') }}" method="POST" id="ganti-passeord">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label>Password Saat Ini</label>
                        <input type="password" name="current_password" id="current_password" class="form-control"
                               required>
                        @error('current_password')
                        <small id="error-current_password"
                               class="error-text form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Password Baru</label>
                        <input type="password" name="password" id="password" class="form-control" required>
                        @error('password')
                        <small id="error-password" class="error-text form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                               class="form-control" required>
                        @error('password_confirmation')
                        <small id="error-password_confirmation"
                               class="error-text form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="mb-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <p class="card-title mb-0">DOKUMEN WAJIB</p>
                </div>
                <hr>
                <div class="template-demo col-md-6">
                    @foreach($dokumenWajib as $dokumen)
                        <div class="alert alert-fill-primary mb-2 mr-2">
                            {{ $dokumen->nama }}
                        </div>

                        <div class="alert alert-danger d-none" id="error_dokumen_{{$dokumen->id}}">
                            Format file tidak didukung! Gunakan format (.jpg, .jpeg, .png, .doc, .docx, .pdf)
                        </div>

                        <div class="mb-2 " style="cursor: pointer;">
                            <img id="preview_dokumen_{{$dokumen->id}}"
                                 src="{{$dokumen->getDokumenPathFromUser($data->user_id) ?? "#"}}"
                                 alt="Upload File"
                                 width="150" height="150">
                        </div>

                        <form
                            action="{{ $dokumen->getDokumenIdUser($data->user_id) !== null ? route('dokumen.update-dokumen-user', $dokumen->getDokumenIdUser($data->user_id)) : route('dokumen.upload-dokumen-user') }}"
                            enctype="multipart/form-data"
                            method="POST">
                            @csrf
                            @if($dokumen->getDokumenIdUser($data->user_id) !== null)
                                @method('PUT')
                            @endif
                            <div class="input-group">
                                <input type="file" class="form-control" id="file"
                                       onchange="previewImage(this, {{$dokumen->id}});"
                                       name="file">
                                <input type="hidden" name="default" value="1">
                                <input type="hidden" name="jenis_dokumen_id" value="{{$dokumen->id}}">

                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="submit">Simpan</button>
                                </div>
                            </div>
                        </form>
                        <small>
                            Ukuran (Max: 5000Kb) Ekstensi (.jpg,.jpeg,.png,.doc,.docx,.pdf)
                        </small>
                        <hr>
                    @endforeach
                    <script>
                        function previewImage(input, id) {
                            if (input.files && input.files[0]) {
                                var reader = new FileReader();
                                var previewId = 'preview_dokumen_' + id;
                                var errorId = 'error_dokumen_' + id;
                                var extension = input.files[0].name.split('.').pop().toLowerCase();
                                var allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'pdf', 'doc', 'docx'];

                                document.getElementById(errorId).classList.add('d-none');

                                if (allowedExtensions.includes(extension)) {
                                    reader.onload = function (e) {
                                        var previewElement = document.getElementById(previewId);

                                        if (['jpg', 'jpeg', 'png', 'gif', 'bmp'].includes(extension)) {
                                            previewElement.src = e.target.result;
                                        } else if (extension === 'pdf') {
                                            previewElement.src = "{{ asset('images/pdf_file_icon.svg') }}";
                                        } else if (['doc', 'docx'].includes(extension)) {
                                            previewElement.src = "{{ asset('images/doc_file_icon.svg') }}";
                                        }
                                    };
                                    reader.readAsDataURL(input.files[0]);
                                } else {
                                    document.getElementById(errorId).classList.remove('d-none');
                                    input.value = '';
                                }
                            }
                        }
                    </script>
                </div>
            </div>
        </div>
    </div>

    <div class="mb-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <p class="card-title mb-0">DOKUMEN TAMBAHAN</p>
                    <button onclick="modalAction('{{route('mahasiswa.profil.dokumen.index')}}')"
                            class="btn btn-outline-secondary btn-sm">
                        <i class="ti-pencil-alt"></i>
                    </button>
                </div>
                <hr>
                <div class="template-demo col-md-6">
                    @foreach($dokumenTambahan as $dokumen)
                        @if($dokumen->getDokumenIdUser($data->user_id) !== null)
                            <div class="alert alert-fill-primary mb-2 mr-2">
                                {{ $dokumen->getDokumenLabelUser($data->user_id) }} - {{ $dokumen->nama }}
                            </div>

                            <label style="cursor: pointer;">
                                <div class="mb-2 ">
                                    <img src="{{$dokumen->getDokumenPathFromUser($data->user_id)}}"
                                         alt="Dokumen"
                                         width="150" height="150">
                                </div>
                            </label>

                            <div class="input-group">
                                <div class="btn-group" role="group" aria-label="Basic example">
                                    <button type="button" class="btn btn-warning"
                                            onclick="modalAction('{{ route('mahasiswa.profil.dokumen.edit', $dokumen->getDokumenIdUser($data->user_id)) }}')">
                                        Edit
                                    </button>
                                    <button type="button" class="btn btn-danger btn-delete"
                                            data-url="{{ route('dokumen.delete-dokumen-user', $dokumen->getDokumenIdUser($data->user_id)) }}"
                                    >
                                        Hapus
                                    </button>
                                </div>
                            </div>
                            <hr>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div id="myModal" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false"
         aria-hidden="true">

    </div>
@endsection

@push('css')

@endpush
@push('js')
    <script>
        function modalAction(url = '') {
            $('#myModal').load(url, function () {
                $('#myModal').modal('show');
            });
        }

        $(document).on('click', '.btn-delete', function (e) {
            e.preventDefault();

            swalAlertConfirm({
                title: 'Hapus data ini?',
                text: 'Data yang dihapus tidak bisa dikembalikan!',
                url: $(this).data('url'),
                onSuccess: function () {
                    location.reload();
                }
            });
        });
    </script>
@endpush
