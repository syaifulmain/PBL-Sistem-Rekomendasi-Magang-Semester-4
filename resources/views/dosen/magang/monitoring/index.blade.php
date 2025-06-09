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
                                        <i class="mdi  mdi mdi-city  mr-2"></i>
                                        {{ $data->lowongan->perusahaan->nama }}
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
                                    <p class="mb-2">
                                        <i class="mdi  mdi mdi-tie  mr-2"></i>
                                        Pembimbing: {{ $data->magang->dosen->nama }}
                                    </p>
                                    <p class="mb-0">
                                        <i class="mdi mdi-account mr-2"></i>
                                        Mahasiswa: {{ $data->mahasiswa->nama }} ({{ $data->mahasiswa->nim }})
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
                                        <a href="{{route('dosen.bimbingan-magang.logbook.download.pdf', $data->magang->id)}}"
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
                                    @if($data->magang->status === 'aktif')
                                        <button class="btn btn-primary mr-2"
                                                onclick="openEvaluasiBimbinganModal('', '', '')">
                                            Tambah Evaluasi
                                        </button>
                                    @endif

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
                                                    <div class="d-flex justify-content-between align-items-center mb-4">

                                                        <h5 class="card-title mb-0">Evaluasi</h5>
                                                        @if($data->magang->status === 'aktif')

                                                            <button class="btn btn-sm btn-danger btn-delete"
                                                                    data-url="{{ route('dosen.bimbingan-magang.monitoring.delete', $evaluasi->id) }}">
                                                                Hapus
                                                            </button>
                                                        @endif
                                                    </div>
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($data->magang->status === 'aktif')
        <!-- Modal Tambah Evaluasi Bimbingan -->
        <div class="modal fade" id="modalEvaluasiBimbingan" tabindex="-1" role="dialog"
             aria-labelledby="modalEvaluasiBimbinganLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <form id="evaluasiBimbinganForm" method="POST">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header bg-gradient-info text-white">
                            <h5 class="modal-title" id="modalEvaluasiBimbinganLabel">Tambah Evaluasi Bimbingan</h5>
                            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="evaluasiBimbinganForm" method="POST">
                                @csrf
                                <input type="hidden" id="magang_id" name="magang_id"
                                       value="{{ $data->magang->id }}">
                                <div class="form-group d-none" id="referensiLogContainer">
                                    <input type="hidden" id="log_magang_mahasiswa_id" name="log_magang_mahasiswa_id"
                                           value="">
                                    <label for="referensi_log">Referensi Log Magan</label>
                                    <div class="alert alert-info">
                                        Referensi Aktivitas:
                                        <span id="referensiLogDate"></span>
                                        <br>
                                        <span id="referensiLogActivity"></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="tanggal_evaluasi">Tanggal Evaluasi</label>
                                    <input type="date" class="form-control" id="tanggal_evaluasi"
                                           name="tanggal_evaluasi"
                                           value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="catatan">Catatan Evaluasi</label>
                                    <textarea class="form-control" id="catatan" name="catatan" rows="4"
                                              required></textarea>
                                </div>

                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary"><i class="mdi mdi-save mr-2"></i>Simpan
                            </button>
                        </div>
                    </div>
                </form>
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
        $(document).on('click', '.btn-delete', function (e) {
            e.preventDefault();

            swalAlertConfirm({
                title: 'Hapus data ini?',
                text: 'Data yang dihapus tidak bisa dikembalikan!',
                url: $(this).data('url'),
                onSuccess: function () {
                    location.reload()
                }
            });
        });
    </script>
    <script src="{{ asset('assets/plugins/filepond/filepond.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/filepond/filepond-plugin-image-preview.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/filepond/filepond.jquery.js') }}"></script>
    <script>
        function openEvaluasiBimbinganModal(id, date, activity) {
            $('#evaluasiBimbinganForm')[0].reset();
            if (!id || !date || !activity) {
                $('#referensiLogContainer').addClass('d-none');
            } else {
                $('#referensiLogContainer').removeClass('d-none');
                $('#log_magang_mahasiswa_id').val(id);
                $('#referensiLogDate').text(date);
                $('#referensiLogActivity').text(activity);
            }

            $('#modalEvaluasiBimbingan').modal('show');
        }

    </script>

    <script>
        // FilePond Configuration
        $.fn.filepond.registerPlugin(FilePondPluginImagePreview);
        $.fn.filepond.setDefaults({
            allowMultiple: false,
            storeAsFile: true,
            instantUpload: false,
            labelIdle: 'Drag & Drop file atau <span class="filepond--label-action">Browse</span>',
            beforeAddFile: (fileItem) => {
                const file = fileItem.file;
                const validTypes = ['image/jpeg', 'image/png', 'image/jpg'];
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
            $('#evaluasiBimbinganForm').on('submit', function (e) {
                e.preventDefault();

                const formData = new FormData(this);
                $.ajax({
                    url: '{{ route('dosen.bimbingan-magang.monitoring.store', $data->magang->id) }}',
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
                                location.reload();
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

                const rating = $('.star-rating').data('rating');
                if (!rating) {
                    swal({
                        title: 'Rating Belum Dipilih',
                        text: 'Silakan berikan rating untuk pengalaman magang Anda.',
                        icon: 'warning',
                        button: {
                            text: 'OK',
                            closeModal: true
                        }
                    });
                    return;
                }

                swal({
                    title: 'Berhasil!',
                    text: 'Terima kasih atas feedback yang Anda berikan.',
                    icon: 'success',
                    button: {
                        text: 'OK',
                        closeModal: true
                    }
                }).then(() => {
                    this.reset();
                    $('.filepond').filepond('removeFiles');
                    $('.star-rating i').removeClass('active');
                    $('.rating-text').text('Pilih rating Anda');
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
