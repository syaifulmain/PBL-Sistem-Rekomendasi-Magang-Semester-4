@extends('layouts.template')
@section('content')
<div class="card">
    <div class="card-body">
        <h4>Form Pengajuan Magang</h4>
        <div class="wizard">
            <div class="d-flex justify-content-between mt-3 wizard-steps">
                <div class="step-item active text-center">
                    <div class="step-icon mb-1"><i class="fa fa-graduation-cap fa-lg"></i></div>
                    <div class="step-text">Data Diri</div>
                </div>
                <div class="step-item text-center">
                    <div class="step-icon mb-1"><i class="fa fa-upload fa-lg"></i></div>
                    <div class="step-text">Dokumen</div>
                </div>
                <div class="step-item text-center">
                    <div class="step-icon mb-1"><i class="fa fa-check-circle fa-lg"></i></div>
                    <div class="step-text">Konfirmasi</div>
                </div>
            </div>
            <div class="progress" style="height: 2px;">
                <div class="progress-bar bg-primary" style="width: 33%"></div>
            </div>

            <form class="mt-4" id="pengajuan-form" method="POST" action="{{ route('mahasiswa.pengajuan-magang.create') }}" enctype="multipart/form-data">
                @csrf
                <div class="step-content active">
                    {{-- Step 1 --}}
                    <div class="form-group">
                        <label for="lowongan_id">Lowongan</label>
                        <select id="lowongan_id" name="lowongan_id"
                            class="form-control select2-ajax @error('lowongan_id') is-invalid @enderror"
                            data-url="{{ route('mahasiswa.pengajuan-magang.data-lowongan') }}" required></select>
                            @error('lowongan_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </select>
                        @if ($lowonganId)
                            <input type="hidden" name="lowongan_id" value="{{ $lowonganId }}">
                        @endif
                        <input type="hidden" name="rekomendasi" value="{{ $rekomendasi ? true : false }}" readonly>
                    </div>
                    <div class="form-group">
                        <label>Nama</label>
                        <input type="text" class="form-control" value="{{ auth()->user()->mahasiswa->nama }}" disabled>
                    </div>
                    <div class="form-group">
                        <label>NIM</label>
                        <input type="text" class="form-control" value="{{ auth()->user()->mahasiswa->nim }}" disabled>
                    </div>
                    <div class="form-group">
                        <label>Program Studi</label>
                        <input type="text" class="form-control" value="{{ auth()->user()->mahasiswa->programStudi->nama ?? '-' }}" disabled>
                    </div>
                    <div class="form-group">
                        <label>Angkatan</label>
                        <input type="text" class="form-control" value="{{ auth()->user()->mahasiswa->angkatan }}" disabled>
                    </div>
                    <div class="form-group">
                        <label>Jenis Kelamin</label>
                        <input type="text" class="form-control" value="{{ auth()->user()->mahasiswa->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}" disabled>
                    </div>
                    <div class="form-group">
                        <label>IPK</label>
                        <input type="text" class="form-control" value="{{ auth()->user()->mahasiswa->ipk ?? '-' }}" disabled>
                    </div>
                    <div class="form-group">
                        <label>No. Telepon</label>
                        <input type="text" class="form-control" value="{{ auth()->user()->mahasiswa->no_telepon ?? '-' }}" disabled>
                    </div>
                    <div class="form-group">
                        <label>Alamat</label>
                        <textarea class="form-control" rows="2" disabled>{{ auth()->user()->mahasiswa->alamat }}</textarea>
                    </div>

                    <button type="button" class="btn btn-primary next-step float-right">Selanjutnya</button>
                </div>

                <div class="step-content d-none">
                    {{-- Step 2 --}}
                    <div id="dokumen-container" class="row">

                    </div>
                    <button type="button" class="btn btn-secondary prev-step float-left">Sebelumnya</button>
                    <button type="button" class="btn btn-primary next-step float-right">Selanjutnya</button>
                </div>

                <div class="step-content d-none">
                    {{-- Step 3 --}}
                    <div id="konfirmasi-container">
                        <p>Memuat data konfirmasi...</p>
                    </div>
                    <button type="button" class="btn btn-secondary prev-step float-left">Sebelumnya</button>
                    <button type="button" id="btn-submit" class="btn btn-success float-right">Kirim Pengajuan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@push('css')
<link href="{{ asset('assets/plugins/filepond/filepond.min.css') }}" rel="stylesheet">
<link href="{{ asset('assets/plugins/filepond/filepond-plugin-image-preview.min.css') }}" rel="stylesheet">
@endpush

@push('js')
<script src="{{ asset('assets/plugins/filepond/filepond.min.js') }}"></script>
<script src="{{ asset('assets/plugins/filepond/filepond-plugin-image-preview.min.js') }}"></script>
<script src="{{ asset('assets/plugins/filepond/filepond.jquery.js') }}"></script>
<script>
    let step = 0;
    const steps = $(".step-content");
    const stepIndicators = $(".step");
    const lowonganId = {{ $lowonganId ?? 'null' }};

    $(function() {
        if (lowonganId) {
            const $select = $('#lowongan_id');
            
            // Fetch single option text (optional)
            $.ajax({
                url: $select.data('url'),
                data: { id: lowonganId },
                success: function (data) {
                    const option = data.find(item => item.id == lowonganId);
                    if (option) {
                        const newOption = new Option(option.text, option.id, true, true);
                        $select.append(newOption).trigger('change');

                        $select.prop('disabled', true);
                    }
                }
            });
        }

        $('#btn-submit').click(function () {
            const form = $('#pengajuan-form');
            let isValid = true;
            let invalidFields = [];

            form.find('input[required], select[required], textarea[required]').each(function () {
                const value = $(this).val();
                let label = $(this).closest('.form-group').find('label').text().trim();

                if (label.includes('Klik atau seret file ke sini')) {
                    label = label.replace('Klik atau seret file ke sini', '').trim();   
                }

                if (!value || value.trim() === '') {
                    isValid = false;
                    invalidFields.push(label);
                }
            });

            if (!isValid) {
                swal({
                    title: "Validasi Gagal",
                    text: "Mohon lengkapi data berikut:\n" + invalidFields.join('\n'),
                    icon: "error",
                    button: {
                        text: "Periksa Lagi",
                        closeModal: true
                    }
                });
                return;
            }

            swal({
                title: "Kirim Pengajuan?",
                text: "Pastikan semua data sudah benar.",
                icon: "warning",
                buttons: {
                    cancel: {
                        text: "Batal",
                        visible: true,
                        closeModal: true,
                    },
                    confirm: {
                        text: "Ya, kirim",
                        value: true,
                        closeModal: false,
                    }
                }
            }).then((willSubmit) => {
                if (willSubmit) {
                    form.submit();
                }
            });
        });

        $.fn.filepond.registerPlugin(
            FilePondPluginImagePreview
        );
        $.fn.filepond.setDefaults({
            allowMultiple: false,
            storeAsFile: true,
            instantUpload: true,
            beforeAddFile: (fileItem) => {
                const file = fileItem.file;
                const validTypes = [
                    'application/pdf',
                    'image/jpeg',
                    'image/png',
                    'image/jpg'
                ];
                const maxSize = 2 * 1024 * 1024; // 2MB

                if (!validTypes.includes(file.type)) {
                    swal({
                        title: 'Peringatan',
                        text: 'Tipe file tidak diperbolehkan. Hanya file dengan tipe ' + validTypes.map(type => type.split('/').pop().toUpperCase()).join(', ') + ' yang diperbolehkan.',
                        icon: 'warning',
                        button: {
                            text: 'Tutup',
                            closeModal: true
                        }
                    });
                    return false;
                }

                if (file.size > maxSize) {
                    swal({
                        title: 'Peringatan',
                        text: 'Ukuran file terlalu besar. Maksimum 2MB.',
                        icon: 'warning',
                        button: {
                            text: 'Tutup',
                            closeModal: true
                        }
                    });
                    return false;
                }

                return true;
            }
        });


        $(".next-step").click(function () {
            const currentStep = $(".step-content.active");

            const requiredFields = currentStep.find("input[required], select[required], textarea[required]");

            let allValid = true;


            // Jika semua valid, lanjut ke step berikutnya
            if (allValid && step < steps.length - 1) {
                step++;
                showStep(step);
                if (step === 2) {
                    renderConfirmation(); // Panggil render konfirmasi di step terakhir
                }
            }
        });

        $(".prev-step").click(function () {
            if (step > 0) {
                step--;
                showStep(step);
            }
        });

        showStep(step); // Tampilkan langkah pertama saat halaman dimuat
    
        $('.select2-ajax').select2({
            width: '100%',
            placeholder: 'Pilih data',
            allowClear: true,
            ajax: {
                delay: 250,
                url: function (params) {
                    return $(this).data('url');
                },
                data: function (params) {
                    return {
                        q: params.term
                    };
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        });

        $('#lowongan_id').on('change', function () {
            const lowonganId = $(this).val();

            if (!lowonganId) return;

            $('#dokumen-container').html('<p>Loading dokumen...</p>');

            $.get(`{{ url('/mahasiswa/pengajuan-magang/data-lowongan-dokumen') }}/${lowonganId}`, function (dokumenList) {
                let html = '';

                if (dokumenList.length === 0) {
                    html = '<p>Tidak ada dokumen yang dibutuhkan.</p>';
                } else {
                    dokumenList.forEach(function (dok) {
                        html += `
                            <div class="form-group col-md-6">
                                <label for="dokumen[${dok.id}]">${dok.nama}</label>
                                <input type="file" id="dokumen[${dok.id}]" name="dokumen[${dok.id}]" class="filepond" accept=".jpg,.jpeg,.png,.doc,.docx,.pdf" required>
                            </div>
                        `;
                    });
                }

                $('#dokumen-container').html(html);

                // Inisialisasi semua input file
                $('.filepond').filepond();
            });
        });
    })

    function showStep(index) {
        steps.each(function(i, el) {
            if (i === index) {
                $(el).removeClass("d-none").addClass("active");
            } else {
                $(el).addClass("d-none").removeClass("active");
            }
        });

        stepIndicators.each(function(i, el) {
            $(el).toggleClass("active", i === index);
        });

        $(".progress-bar").css("width", ((index + 1) / steps.length * 100) + "%");
    }

    function renderConfirmation () {
        const lowonganText = $("#lowongan_id option:selected").text();
        const nama     = "{{ auth()->user()->mahasiswa->nama }}";
        const nim      = "{{ auth()->user()->mahasiswa->nim }}";
        const prodi    = "{{ auth()->user()->mahasiswa->programStudi->nama ?? '-' }}";
        const angkatan = "{{ auth()->user()->mahasiswa->angkatan }}";
        const jk       = "{{ auth()->user()->mahasiswa->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}";
        const ipk      = "{{ auth()->user()->mahasiswa->ipk ?? '-' }}";
        const nohp     = "{{ auth()->user()->mahasiswa->no_telepon ?? '-' }}";
        const alamat   = `{{ auth()->user()->mahasiswa->alamat }}`;

        let html = `
            <h5>Konfirmasi Data</h5>
            <table class="table table-profile mb-3">
                <tr><th width="30%">Lowongan</th><td>${lowonganText}</td></tr>
                <tr><th width="30%">Nama</th><td>${nama}</td></tr>
                <tr><th width="30%">NIM</th><td>${nim}</td></tr>
                <tr><th width="30%">Program Studi</th><td>${prodi}</td></tr>
                <tr><th width="30%">Angkatan</th><td>${angkatan}</td></tr>
                <tr><th width="30%">Jenis Kelamin</th><td>${jk}</td></tr>
                <tr><th width="30%">IPK</th><td>${ipk}</td></tr>
                <tr><th width="30%">No. Telepon</th><td>${nohp}</td></tr>
                <tr><th width="30%">Alamat</th><td>${alamat}</td></tr>
            </table>

            <h5>Dokumen Terlampir</h5>
            <table class="table table-profile mb-0"><tbody>
        `;

        /* -------- LAMPIRAN -------- */
        $("input[name^='dokumen']").each(function () {

            const pond = $(this).filepond('instance');

            const labelText = $(`label[for="${this.name}"]`).text() || '(tanpa label)';

            let cellContent = '<span class="text-danger">❌ Belum dipilih</span>';

            if ($(this).val()) {
                cellContent = `✔️ Diunggah`;
            }

            html += `<tr><th width="30%">${labelText}</th><td>${cellContent}</td></tr>`;
        });

        html += '</tbody></table>';

        $("#konfirmasi-container").html(html);
    }
</script>
@endpush