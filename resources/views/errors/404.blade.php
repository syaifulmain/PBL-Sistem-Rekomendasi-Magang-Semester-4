@extends('errors.template')

@section('content')
    <div class="content-wrapper d-flex align-items-center text-center error-page bg-primary">
        <div class="row flex-grow">
            <div class="col-lg-7 mx-auto text-white">
                <div class="row align-items-center d-flex flex-row">
                    <div class="col-lg-6 text-lg-right pr-lg-4">
                        <h1 class="display-1 mb-0">404</h1>
                    </div>
                    <div class="col-lg-6 error-page-divider text-lg-left pl-lg-4">
                        <h2 class="mb-3">MAAF!!</h2>
                        <h3 class="font-weight-light mb-3">Halaman yang Anda cari tidak ditemukan.</h3>
                        <a href="{{ url()->previous() }}" class="btn btn-light   mr-2">
                            <i class="ti-arrow-left mr-1"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
