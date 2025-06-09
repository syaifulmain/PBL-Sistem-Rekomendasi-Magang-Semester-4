@extends('layouts.template')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <!-- Header Card -->
                        <div class="card bg-gradient-primary rounded-lg p-4 mb-4 text-white">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <h3 class="mb-2 font-weight-bold">{{ $data->lowongan->judul }}</h3>
                                    <h5 class="mb-2 opacity-90">
                                        <i class="mdi mdi-city mr-2"></i>{{ $data->lowongan->perusahaan->nama }}
                                    </h5>

                                    @php
                                        $waktuMulai = $data->magang->getWaktuMulaiMagangAttribute();
                                        $sisaWaktu = $data->magang->status === 'aktif'
                                            ? ($data->magang->getSisaWaktuMangangAttribute() . ' hari tersisa')
                                            : ($waktuMulai > 0 ? ($waktuMulai . ' hari lagi akan dimulai') : '');
                                    @endphp
                                    
                                    @if($sisaWaktu && $data->magang->status !== 'selesai')
                                        <p class="mb-2">
                                            <i class="mdi mdi-calendar-clock mr-2"></i>{{ $sisaWaktu }}
                                        </p>
                                    @endif

                                    <p class="mb-0">
                                        <i class="mdi mdi-tie mr-2"></i>Pembimbing: {{ $data->magang->dosen->nama }}
                                    </p>
                                </div>
                                <div class="col-md-4 text-md-right d-flex flex-column align-items-md-end">
                                    <span class="badge badge-{{ $data->magang->status === 'selesai' ? 'success' :
                                            ($data->magang->status === 'aktif' ? 'warning' : 'primary') }}
                                            badge-lg px-3 py-2 mb-3">
                                        <i class="mdi mdi-{{ $data->magang->status === 'selesai' ? 'check-circle' :
                                            ($data->magang->status === 'aktif' ? 'clock' : 'calendar') }} mr-1"></i>
                                        {{ ucfirst(str_replace('_', ' ', $data->magang->status)) }}
                                    </span>
                                    <p class="mb-0">{{ $data->lowongan->periodeMagang->nama }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Navigation Tabs -->
                        <ul class="nav nav-pills nav-fill mb-4 border-0" id="internshipTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" id="log-tab" data-toggle="pill" href="#log-content"
                                   role="tab">
                                    <i class="mdi mdi-clipboard-list mr-2"></i>Log Magang
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="evaluasi-bimbingan-tab" data-toggle="pill"
                                   href="#evaluasi-bimbingan-content" role="tab">
                                    <i class="mdi mdi-comments mr-2"></i>Evaluasi Bimbingan
                                </a>
                            </li>
                            @if($data->magang->status === 'selesai')
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" id="evaluasi-mahasiswa-tab" data-toggle="pill"
                                       href="#evaluasi-mahasiswa-content" role="tab">
                                        <i class="mdi mdi-user-graduate mr-2"></i>Evaluasi Mahasiswa
                                    </a>
                                </li>
                            @endif
                        </ul>

                        <!-- Tab Content -->
                        <div class="tab-content pt-0 border-0" id="internshipTabContent">
                            <!-- Log Magang Tab -->
                            <div class="tab-pane fade show active" id="log-content" role="tabpanel">
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <h4 class="card-title mb-0">
                                        <i class="mdi mdi-clipboard-list text-primary mr-2"></i>Log Kegiatan Magang
                                    </h4>
                                    <div class="d-flex">
                                        @if($data->magang->status === 'aktif')
                                            <button class="btn btn-primary mr-2" data-toggle="modal"
                                                    data-target="#modalLog">
                                                Tambah Log Baru
                                            </button>
                                        @endif
                                        <a href="{{route('mahasiswa.evaluasi-magang.logbook.download.pdf', $data->magang->id)}}"
                                           class="btn btn-success">
                                            Unduh
                                        </a>
                                    </div>
                                </div>

                                <div class="card">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table id="logTable"
                                                   class="display table-hover expandable-table table-striped table-bordered"
                                                   style="width:100%">
                                                <thead>
                                                <tr>
                                                    <th class="text-center" width="5%">No</th>
                                                    <th width="15%">Tanggal</th>
                                                    <th width="20%">Aktivitas</th>
                                                    <th width="25%">Kendala</th>
                                                    <th width="45%">Keterangan</th>
                                                    <th class="text-center" width="20%">Aksi</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Evaluasi Bimbingan Tab -->
                            <div class="tab-pane fade" id="evaluasi-bimbingan-content" role="tabpanel">
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <h4 class="card-title mb-0">
                                        <i class="mdi mdi-comments text-primary mr-2"></i>Riwayat Evaluasi Bimbingan
                                    </h4>
                                    <span class="badge badge-info badge-lg">{{ count($data->magang->evaluasiBimbingan ?? []) }} Evaluasi</span>
                                </div>

                                <div class="row">
                                    @forelse($data->magang->evaluasiBimbingan->reverse()->values() ?? [] as $index => $evaluasi)
                                        <div class="col-md-6 mb-4">
                                            <div class="card evaluation-card h-100" data-toggle="collapse"
                                                 data-target="#collapseEvaluasiBimbingan{{ $index }}"
                                                 aria-expanded="false"
                                                 aria-controls="collapseEvaluasiBimbingan{{ $index }}"
                                                 style="cursor:pointer;">
                                                <div class="card-header bg-gradient-info text-white">
                                                    <h5 class="card-title mb-0">Evaluasi</h5>
                                                </div>
                                                <div class="card-body">
                                                    <p class="card-text">
                                                        <i class="mdi mdi-clock text-muted mr-2"></i>
                                                        {{ $evaluasi->tanggal_evaluasi ? \Carbon\Carbon::parse($evaluasi->tanggal_evaluasi)->translatedFormat('d F Y') : 'tidak ada' }}
                                                    </p>
                                                    @if(!empty($evaluasi->logMagangMahasiswa->aktivitas))
                                                        <div class="alert alert-info">
                                                            Referensi Aktivitas:
                                                            {{ \Carbon\Carbon::parse($evaluasi->logMagangMahasiswa->tanggal)->translatedFormat('d F Y') }}
                                                            <br>
                                                            {{ $evaluasi->logMagangMahasiswa->aktivitas }}
                                                        </div>
                                                    @endif

                                                    <!-- Konten Singkat (akan hilang saat expanded) -->
                                                    <div class="short-content" id="shortEvaluasi{{ $index }}">
                                                        <p class="card-text text-muted mb-0">
                                                            {{ Str::limit($evaluasi->catatan ?? '...', 80) }}
                                                        </p>
                                                    </div>

                                                    <!-- Konten Lengkap (collapse) -->
                                                    <div class="collapse full-content"
                                                         id="collapseEvaluasiBimbingan{{ $index }}">
                                                        <p class="card-text text-muted mb-0">
                                                            {{ $evaluasi->catatan ?? '....' }}
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="card-footer text-muted">
                                                    <small><i class="mdi mdi-eye mr-1"></i>Klik untuk melihat
                                                        detail</small>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="col-12">
                                            <div class="text-center py-5">
                                                <i class="mdi mdi-comments fa-3x text-muted mb-3"></i>
                                                <h5 class="text-muted">Belum Ada Evaluasi Bimbingan</h5>
                                                <p class="text-muted">Evaluasi bimbingan akan muncul setelah sesi
                                                    bimbingan dengan dosen.</p>
                                            </div>
                                        </div>
                                    @endforelse
                                </div>
                            </div>

                            <!-- Evaluasi Mahasiswa Tab -->
                            @if($data->magang->status === 'selesai')
                                <div class="tab-pane fade" id="evaluasi-mahasiswa-content" role="tabpanel">
                                    <h4 class="card-title mb-4">
                                        <i class="mdi mdi-user-graduate text-primary mr-2"></i>Evaluasi Akhir Mahasiswa
                                    </h4>

                                    <div class="row">
                                        <div class="col-md-8">
                                            <form id="evaluasiMahasiswaForm"
                                                  method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <input type="hidden" name="magang_id" value="{{ $data->magang->id }}">
                                                <div class="card">
                                                    <div class="card-header bg-gradient-success text-white">
                                                        <h5 class="mb-0"><i class="mdi mdi-certificate mr-2"></i>Upload
                                                            Sertifikat & Feedback</h5>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="form-group">
                                                            @if (!empty($data->magang->evaluasiMagangMahasiswa->sertifikat_path))
                                                                <div class="mb-2">
                                                                    <img
                                                                        src="{{ $data->magang->evaluasiMagangMahasiswa->getDokumenPath() }}"
                                                                        alt="Sertifikat"
                                                                        class="img-thumbnail mb-2"
                                                                        style="max-width: 200px;">
                                                                </div>
                                                                <a href="{{ asset('storage/' . $data->magang->evaluasiMagangMahasiswa->sertifikat_path) }}"
                                                                   target="_blank">
                                                                    Download Sertifikat
                                                                </a>
                                                            @else
                                                                <label for="sertifikat_path" class="font-weight-bold">
                                                                    <i class="mdi mdi-upload text-primary mr-2"></i>Upload
                                                                    Sertifikat Magang
                                                                </label>
                                                                <input type="file" id="sertifikat_path"
                                                                       name="sertifikat_path"
                                                                       class="filepond"
                                                                       accept=".jpg,.jpeg,.png,.pdf" required>
                                                                <small class="form-text text-muted">
                                                                    Format yang didukung: JPG, PNG, PDF (Max. 5MB)
                                                                </small>
                                                            @endif
                                                        </div>

                                                        <div class="form-group">
                                                            <label for="umpan_balik_mahasiswa" class="font-weight-bold">
                                                                <i class="mdi mdi-comment-dots text-primary mr-2"></i>Umpan
                                                                Balik untuk Perusahaan
                                                            </label>
                                                            <textarea class="form-control" id="umpan_balik_mahasiswa"
                                                                      name="umpan_balik_mahasiswa"
                                                                      rows="6"
                                                                      @if(!empty($data->magang->evaluasiMagangMahasiswa->umpan_balik_mahasiswa)) disabled @endif
                                                                      >{{$data->magang->evaluasiMagangMahasiswa->umpan_balik_mahasiswa ?? ''}}</textarea>
                                                            @if(!empty($data->magang->evaluasiMagangMahasiswa->umpan_balik_mahasiswa))
                                                                <small class="form-text text-muted">
                                                                    Umpan balik ini tidak dapat diubah setelah disimpan.
                                                                </small>
                                                            @else
                                                                <small class="form-text text-muted">
                                                                    Feedback Anda akan membantu perusahaan meningkatkan
                                                                    program magang mereka.
                                                                </small>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    @if(empty($data->magang->evaluasiMagangMahasiswa->umpan_balik_mahasiswa))
                                                        <div class="card-footer text-right">
                                                            <button type="submit" class="btn btn-primary">
                                                                <i class="mdi mdi-content-save mr-2"></i>Simpan Evaluasi
                                                            </button>
                                                        </div>
                                                    @endif
                                                </div>
                                            </form>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="card bg-light">
                                                <div class="card-header">
                                                    <h5 class="mb-0"><i class="mdi mdi-info-circle mr-2"></i>Informasi
                                                    </h5>
                                                </div>
                                                <div class="card-body">
                                                    <ul class="list-unstyled">
                                                        <li class="mb-2">
                                                            <i class="mdi mdi-check text-success mr-2"></i>
                                                            Upload sertifikat resmi dari perusahaan
                                                        </li>
                                                        <li class="mb-2">
                                                            <i class="mdi mdi-check text-success mr-2"></i>
                                                            Berikan feedback yang konstruktif
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($data->magang->status === 'aktif')

        <!-- Modal Log Magang -->
        <div id="modalLog" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            Tambah Log Kegiatan Magang
                        </h5>
                        <button type="button" class="close text-white" data-dismiss="modal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="logForm" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="tanggal" class="font-weight-bold">
                                    Tanggal Kegiatan
                                </label>
                                <input type="text" name="tanggal" id="tanggal"
                                       class="form-control datepicker">
                            </div>
                            <div class="form-group">
                                <label for="aktivitas" class="font-weight-bold">
                                    Aktivitas / Kegiatan
                                </label>
                                <textarea class="form-control" id="aktivitas" name="aktivitas" rows="4"
                                          placeholder="Deskripsikan aktivitas yang Anda lakukan hari ini..."
                                          required></textarea>
                            </div>

                            <div class="form-group">
                                <label for="kendala" class="font-weight-bold">
                                    Kendala / Tantangan
                                </label>
                                <textarea class="form-control" id="kendala" name="kendala" rows="3"
                                          placeholder="Kendala yang dihadapi (opsional)"></textarea>
                            </div>

                            <div class="form-group">
                                <label for="keterangan" class="font-weight-bold">
                                    Keterangan
                                </label>
                                <textarea class="form-control" id="keterangan" name="keterangan" rows="3"
                                          placeholder="Keterangan yang dihadapi (opsional)"></textarea>
                            </div>

                            <div class="form-group">
                                <label for="dokumentasi" class="font-weight-bold">
                                    Upload Dokumentasi
                                </label>
                                <input type="file" id="dokumentasi" name="dokumentasi" class="filepond"
                                       accept=".jpg,.jpeg,.png,.pdf" required>
                                <small class="form-text text-muted">
                                    Upload foto atau dokumen yang relevan dengan kegiatan hari ini
                                </small>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                <i class="mdi mdi-times mr-2"></i>Batal
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="mdi mdi-save mr-2"></i>Simpan Log
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endsection

@push('css')
    <link href="{{ asset('assets/plugins/filepond/filepond.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/filepond/filepond-plugin-image-preview.min.css') }}" rel="stylesheet">
    <style>
        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .bg-gradient-info {
            background: linear-gradient(135deg, #74b9ff 0%, #0984e3 100%);
        }

        .bg-gradient-success {
            background: linear-gradient(135deg, #00b894 0%, #00a085 100%);
        }

        .evaluation-card {
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .evaluation-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .nav-pills .nav-link {
            border-radius: 25px;
            margin: 0 5px;
            transition: all 0.3s ease;
        }

        .nav-pills .nav-link.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .badge-lg {
            font-size: 0.875rem;
            padding: 0.5rem 1rem;
        }

        .star-rating i {
            font-size: 1.5rem;
            color: #ddd;
            cursor: pointer;
            transition: color 0.2s ease;
        }

        .star-rating i:hover,
        .star-rating i.active {
            color: #ffc107;
        }

        .table th {
            border-top: none;
            font-weight: 600;
        }

        .filepond--root {
            margin-bottom: 0;
        }

        .opacity-90 {
            opacity: 0.9;
        }

        .evaluation-card .card-footer {
            border-bottom-left-radius: 20px;
            border-bottom-right-radius: 20px;
        }
    </style>
@endpush

@push('js')
    <script>
        $(document).ready(function () {
            $('.evaluation-card').on('show.bs.collapse', function (e) {
                var target = $(e.target);
                target.closest('.card-body').find('.short-content').hide();
            });

            $('.evaluation-card').on('hide.bs.collapse', function (e) {
                var target = $(e.target);
                target.closest('.card-body').find('.short-content').show();
            });
        });
    </script>
    <script src="{{ asset('assets/plugins/filepond/filepond.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/filepond/filepond-plugin-image-preview.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/filepond/filepond.jquery.js') }}"></script>
    <script>
        $(document).on('click', '.btn-delete', function (e) {
            e.preventDefault();

            swalAlertConfirm({
                title: 'Hapus data ini?',
                text: 'Data yang dihapus tidak bisa dikembalikan!',
                url: $(this).data('url'),
                onSuccess: function () {
                    $('#logTable').DataTable().ajax.reload();
                }
            });
        });
        // FilePond Configuration
        $.fn.filepond.registerPlugin(FilePondPluginImagePreview);
        $.fn.filepond.setDefaults({
            allowMultiple: false,
            storeAsFile: true,
            instantUpload: false,
            labelIdle: 'Drag & Drop file atau <span class="filepond--label-action">Browse</span>',
            beforeAddFile: (fileItem) => {
                const file = fileItem.file;
                const validTypes = [
                    'application/pdf',
                    'image/jpeg',
                    'image/png',
                    'image/jpg'
                ];
                const maxSize = 5 * 1024 * 1024; // 5MB

                if (!validTypes.includes(file.type)) {
                    swal({
                        title: 'Format File Tidak Valid',
                        text: 'Hanya file JPG, dan PNG yang diperbolehkan.',
                        icon: 'warning',
                        button: {
                            text: 'OK',
                            closeModal: true
                        }
                    });
                    return false;
                }

                if (file.size > maxSize) {
                    swal({
                        title: 'File Terlalu Besar',
                        text: 'Ukuran file maksimal 5MB.',
                        icon: 'warning',
                        button: {
                            text: 'OK',
                            closeModal: true
                        }
                    });
                    return false;
                }

                return true;
            }
        });

        $('.filepond').filepond();

        $(document).ready(function () {
            // Initialize DataTable
            $('#logTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '',
                columns: [
                    {data: 'DT_RowIndex', className: 'text-center'},
                    {data: 'tanggal'},
                    {data: 'aktivitas'},
                    {data: 'kendala'},
                    {data: 'keterangan'},
                    {data: 'action', orderable: false, searchable: false, className: 'text-center'}
                ],
                responsive: true,
                autoWidth: false,
                pageLength: 10,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/id.json'
                }
            });

            // Star Rating System
            $('.star-rating i').on('click', function () {
                const rating = $(this).data('value');
                const $starGroup = $(this).parent();

                $starGroup.data('rating', rating);
                $starGroup.find('i').removeClass('active');

                for (let i = 1; i <= rating; i++) {
                    $starGroup.find(`i[data-value="${i}"]`).addClass('active');
                }

                const ratingText = ['Sangat Buruk', 'Buruk', 'Cukup', 'Baik', 'Sangat Baik'];
                $(this).parent().next('.rating-text').text(ratingText[rating - 1]);
            });

            // Form Submissions
            $('#logForm').on('submit', function (e) {
                e.preventDefault();

                const formData = new FormData(this);
                $.ajax({
                    url: '{{ route('mahasiswa.evaluasi-magang.monitoring.log.store', $data->magang->id) }}',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        if (response.success) {
                            swal({
                                title: 'Berhasil!',
                                text: response.message || 'Log kegiatan berhasil disimpan.',
                                icon: 'success',
                                button: {
                                    text: 'OK',
                                    closeModal: true
                                }
                            }).then(() => {
                                $('#modalLog').modal('hide');
                                $('#logTable').DataTable().ajax.reload();
                                $('#logForm')[0].reset();
                                $('.filepond').filepond('removeFiles');
                            });
                        } else {
                            swal({
                                title: 'Gagal!',
                                text: response.message || 'Terjadi kesalahan saat menyimpan log.',
                                icon: 'error',
                                button: {
                                    text: 'OK',
                                    closeModal: true
                                }
                            });
                        }
                    },
                    error: function (xhr) {
                        swal({
                            title: 'Gagal!',
                            text: xhr.responseJSON.message || 'Terjadi kesalahan saat menyimpan log.',
                            icon: 'error',
                            button: {
                                text: 'OK',
                                closeModal: true
                            }
                        });
                    }
                });
            });

            $('#evaluasiMahasiswaForm').on('submit', function (e) {
                e.preventDefault();

                const formData = new FormData(this);
                $.ajax({
                    url: '{{ route('mahasiswa.evaluasi-magang.evaluasi.store', $data->magang->id) }}',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        if (response.success) {
                            swal({
                                title: 'Berhasil!',
                                text: response.message || 'Evaluasi berhasil disimpan.',
                                icon: 'success',
                                button: {
                                    text: 'OK',
                                    closeModal: true
                                }
                            }).then(() => {
                                $('#evaluasiMahasiswaForm')[0].reset();
                                $('.filepond').filepond('removeFiles');
                                location.reload();
                            });
                        } else {
                            swal({
                                title: 'Gagal!',
                                text: response.message || 'Terjadi kesalahan saat menyimpan evaluasi.',
                                icon: 'error',
                                button: {
                                    text: 'OK',
                                    closeModal: true
                                }
                            });
                        }
                    },
                    error: function (xhr) {
                        swal({
                            title: 'Gagal!',
                            text: xhr.responseJSON.message || 'Terjadi kesalahan saat menyimpan evaluasi.',
                            icon: 'error',
                            button: {
                                text: 'OK',
                                closeModal: true
                            }
                        });
                    }
                });
            });
        });

        // Function to view documentation
        function viewDoc(filename) {
            swal({
                title: 'Dokumentasi',
                content: {
                    element: "div",
                    attributes: {
                        innerHTML: `<img src="${filename}" class="img-fluid" alt="Dokumentasi" style="max-width: 100%; height: auto;">`
                    }
                },
                button: {
                    text: 'Tutup',
                    closeModal: true
                }
            });
        }

        // Smooth scrolling for tabs
        $('a[data-toggle="pill"]').on('shown.bs.tab', function (e) {
            $('html, body').animate({
                scrollTop: $("#internshipTabs").offset().top - 20
            }, 500);
        });

        // Initialize tooltips
        $('[data-toggle="tooltip"]').tooltip();

        // File upload progress simulation
        $('.filepond').on('FilePond:addfile', function (e) {
            console.log('File added', e.detail.file.file.name);
        });

        // Form validation enhancement
        $('form').on('submit', function () {
            $(this).find('button[type="submit"]').prop('disabled', true).html('<i class="mdi mdi-spinner fa-spin mr-2"></i>Menyimpan...');
        });

        // Reset form buttons on modal close
        $('.modal').on('hidden.bs.modal', function () {
            $(this).find('button[type="submit"]').prop('disabled', false).html(function () {
                return $(this).data('original-text') || $(this).html().replace('<i class="mdi mdi-spinner fa-spin mr-2"></i>Menyimpan...', '<i class="mdi mdi-save mr-2"></i>Simpan');
            });
        });

        // Store original button text
        $('button[type="submit"]').each(function () {
            $(this).data('original-text', $(this).html());
        });
    </script>
@endpush
