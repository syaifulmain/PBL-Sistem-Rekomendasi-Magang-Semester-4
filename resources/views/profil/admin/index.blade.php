@extends('layouts.template')

@section('content')
    <div class="mb-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <p class="card-title mb-0">INFORMASI PENGGUNA</p>
                    <button onclick="modalAction('{{route('admin.profil.informasi-pengguna.index')}}')"
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
                    </div>
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
    </script>
@endpush
