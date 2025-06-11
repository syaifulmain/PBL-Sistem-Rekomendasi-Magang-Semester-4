@extends('layouts.template')

@section('content')
    <div class="mb-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <p class="card-title mb-0">INFORMASI PENGGUNA</p>
                    <button onclick="modalAction('{{route('profil.informasi-pengguna.index')}}')"
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

                        @if (has_role('DOSEN'))
                            <p>{{$data->nip}}</p>
                            <p>{{$data->alamat}}</p>
                        @endif

                        @if (has_role('MAHASISWA'))
                            <p>{{$data->nim}}</p>
                            <p>{{$data->getProgramStudiNameAttribute()}}</p>
                            <p>{{$data->alamat}}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if (has_any_role('DOSEN', 'MAHASISWA'))
        <div class="mb-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <p class="card-title mb-0">INFORMASI DETAIL</p>
                        <button onclick="modalAction('{{route('profil.informasi-detail.index')}}')"
                                class="btn btn-outline-secondary btn-sm">
                            <i class="ti-pencil-alt"></i>
                        </button>
                    </div>
                    <hr>
                    @if (has_role('DOSEN'))
                        <p><strong>Jenis Kelamin</strong><br>{{$data->getGenderNameAttribute()}}</p>
                        <p><strong>No. Telp</strong><br>{{$data->no_telepon}}</p>
                    @endif

                    @if (has_role('MAHASISWA'))
                        <p><strong>Angkatan</strong><br>{{$data->angkatan}}</p>
                        <p><strong>Jenis Kelamin</strong><br>{{$data->getGenderNameAttribute()}}</p>
                        <p><strong>No. Telp</strong><br>{{$data->no_telepon}}</p>
                        <p><strong>Status</strong><br>{{$data->status}}</p>
                        <p><strong>IPK</strong><br>{{$data->ipk}}</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="mb-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <p class="card-title mb-0">MINAT</p>
                        <button onclick="modalAction('{{route('profil.minat.index')}}')"
                                class="btn btn-outline-secondary btn-sm">
                            <i class="ti-pencil-alt"></i>
                        </button>
                    </div>
                    <hr>
                    <div class="template-demo">
                        @foreach($data->minat as $minat)
                            <div class="alert alert-fill-primary d-inline-block mb-2 mr-2">
                                {{ $minat->bidangKeahlian->nama }}
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        @if (has_role('MAHASISWA'))
            <div class="mb-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <p class="card-title mb-0">KEAHLIAN</p>
                            <button onclick="modalAction('{{route('profil.keahlian.index')}}')"
                                    class="btn btn-outline-secondary btn-sm">
                                <i class="ti-pencil-alt"></i>
                            </button>
                        </div>
                        <hr>
                        <div class="template-demo">
                            @foreach($data->keahlian as $keahlian)
                                <div class="alert alert-fill-primary d-inline-block mb-2 mr-2">
                                    {{ $keahlian->getKeahlianTeknisNameAttribute() }}
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="mb-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <p class="card-title mb-0">PREFRENSI LOKASI</p>
                        <button onclick="modalAction('{{route('profil.preferensi-lokasi.index')}}')"
                                class="btn btn-outline-secondary btn-sm">
                            <i class="ti-pencil-alt"></i>
                        </button>
                    </div>
                    <hr>
                    <div class="template-demo">
                        @foreach($data->preferensiLokasi as $lokasi)
                            <div class="alert alert-fill-primary d-inline-block mb-2 mr-2">
                                {{ $lokasi->nama_tampilan }}
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        @if (has_role('MAHASISWA'))
            <div class="mb-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <p class="card-title mb-0">DOKUMEN WAJIB</p>
                        </div>
                        <hr>
                        <div class="template-demo col-md-6">
                            @foreach($data->getDokumenWajibAttribute() as $dokumen)
                                <div class="alert alert-fill-primary mb-2 mr-2">
                                    {{ $dokumen->nama }}
                                </div>

                                <div class="alert alert-danger d-none" id="error_dokumen_{{$dokumen->id}}">
                                    Format file tidak didukung! Gunakan format (.jpg, .jpeg, .png, .doc, .docx, .pdf)
                                </div>

                                <div class="mb-2 " style="cursor: pointer;">
                                    <a href="{{ $dokumen->getDokumenIdUser($data->user_id) ? route('dokumen.download-dokumen-user', $dokumen->getDokumenIdUser($data->user_id)) : '#' }}">
                                    <img id="preview_dokumen_{{$dokumen->id}}"
                                         src="{{$dokumen->getDokumenPathFromUser($data->user_id) ?? "#"}}"
                                         alt="Upload File"
                                         width="150" height="150">
                                    </a>
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
        @endif

{{--        <div class="mb-3">--}}
{{--            <div class="card">--}}
{{--                <div class="card-body">--}}
{{--                    <div class="d-flex justify-content-between align-items-center">--}}
{{--                        <p class="card-title mb-0">DOKUMEN TAMBAHAN</p>--}}
{{--                        <button onclick="modalAction('{{route('profil.dokumen.index')}}')"--}}
{{--                                class="btn btn-outline-secondary btn-sm">--}}
{{--                            <i class="ti-pencil-alt"></i>--}}
{{--                        </button>--}}
{{--                    </div>--}}
{{--                    <hr>--}}
{{--                    <div class="template-demo col-md-6">--}}
{{--                        @foreach($data->getDokumenTambahan() as $dokumen)--}}
{{--                            <div--}}
{{--                                class="alert alert-fill-primary mb-2 mr-2 d-flex justify-content-between align-items-center">--}}
{{--                                <span>{{ $dokumen->getLabelNamaAttribute() }}</span>--}}
{{--                                <button type="button"--}}
{{--                                        class="btn btn-close btn-close-white btn-sm text-white btn-delete ms-auto"--}}
{{--                                        data-url="{{ route('dokumen.delete-dokumen-user', $dokumen->id) }}">--}}
{{--                                    X--}}
{{--                                </button>--}}
{{--                            </div>--}}
{{--                            <a href="{{route('dokumen.download-dokumen-user', $dokumen->id)}}">--}}
{{--                                <img src="{{$dokumen->getDokumenPath()}}"--}}
{{--                                     alt="Dokumen"--}}
{{--                                     width="150" height="150">--}}
{{--                            </a>--}}
{{--                            <hr>--}}
{{--                        @endforeach--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
    @endif

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

        $(document).ready(function() {
            $(document).on('click', '[data-dismiss="modal"]', function() {
                var modal = $(this).closest('.modal');
                if (modal.length && modal.hasClass('show')) {
                    location.reload();
                } else if ($(this).hasClass('close') && $(this).closest('.modal').length) {
                    location.reload();
                }
            });
        });
    </script>
@endpush
