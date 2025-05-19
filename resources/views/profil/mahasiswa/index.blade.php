@extends('layouts.template')

@section('content')
    <div class="mb-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <p class="card-title mb-0">INFORMASI PENGGUNA</p>
                    <button onclick="modalAction('{{route('mahasiswa.profil.informasi-pengguna.index')}}')" class="btn btn-outline-secondary btn-sm">
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
                    <button onclick="modalAction('{{route('mahasiswa.profil.informasi-detail.index')}}')" class="btn btn-outline-secondary btn-sm">
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
                    <button onclick="modalAction('{{route('mahasiswa.profil.keahlian.index')}}')" class="btn btn-outline-secondary btn-sm">
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
                    <button onclick="modalAction('{{route('mahasiswa.profil.minat.index')}}')" class="btn btn-outline-secondary btn-sm">
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
                    <button onclick="modalAction('{{route('mahasiswa.profil.preferensi-lokasi.index')}}')" class="btn btn-outline-secondary btn-sm">
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
            </div>
        </div>
    </div>

    <div class="mb-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <p class="card-title mb-0">DOKUMEN</p>
{{--                    <button onclick="modalAction('{{route('mahasiswa.profil.preferensi-lokasi.index')}}')" class="btn btn-outline-secondary btn-sm">--}}
{{--                        <i class="ti-pencil-alt"></i>--}}
{{--                    </button>--}}
                </div>
                <hr>
                <div class="template-demo">
                </div>
            </div>
        </div>
    </div>

    <div id="myModal" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-hidden="true">

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
    </script>
@endpush
