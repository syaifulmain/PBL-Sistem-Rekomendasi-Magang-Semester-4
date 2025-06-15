@extends('layouts.template')
@section('content')
<div class="card">
    <div class="card-body">
        <h4 class="card-title">{{ isset($data) ? 'Edit' : 'Tambah' }} Lowongan Magang</h4>
        <form action="{{ isset($data) ? route('admin.lowongan-magang.edit', $data->id) : route('admin.lowongan-magang.create') }}" method="POST">
            @csrf
            @if(isset($data))
                @method('PUT')
            @endif

            <div class="form-group">
                <label for="perusahaan_id">Perusahaan</label>
                <select id="perusahaan_id" name="perusahaan_id"
                    class="form-control select2-ajax @error('perusahaan_id') is-invalid @enderror"
                    data-url="{{ route('admin.lowongan-magang.perusahaan') }}" required></select>
                @error('perusahaan_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="periode_magang_id">Periode Magang</label>
                <select id="periode_magang_id" name="periode_magang_id"
                    class="form-control select2-ajax @error('periode_magang_id') is-invalid @enderror"
                    data-url="{{ route('admin.lowongan-magang.periode-magang') }}" required></select>
                @error('periode_magang_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="judul">Judul Lowongan</label>
                <input required type="text" name="judul" class="form-control @error('judul') is-invalid @enderror"
                    value="{{ old('judul', $data->judul ?? '') }}">
                @error('judul')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="deskripsi">Deskripsi</label>
                <textarea required name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror">{{ old('deskripsi', $data->deskripsi ?? '') }}</textarea>
                @error('deskripsi')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="persyaratan">Persyaratan</label>
                <textarea name="persyaratan" class="form-control @error('persyaratan') is-invalid @enderror">{{ old('persyaratan', $data->persyaratan ?? '') }}</textarea>
                @error('persyaratan')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="keahlian_ids">Bidang Keahlian</label>
                <select id="keahlian_ids" name="keahlian_ids[]" multiple
                    class="form-control select2-ajax @error('keahlian_ids') is-invalid @enderror"
                    data-url="{{ route('admin.lowongan-magang.keahlian') }}" required>
                </select>
                @error('keahlian_ids')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Daftar Keahlian Teknis</label>
                <table class="table table-bordered mb-3" id="keahlian-table">
                    <thead>
                        <tr>
                            <th>Keahlian Teknis</th>
                            <th>Level</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Baris dinamis -->
                    </tbody>
                </table>
                <button type="button" class="btn btn-sm btn-primary" id="btn-tambah-keahlian">+ Tambah Keahlian</button>
            </div>


            <div class="form-group">
                <label for="dokumen_ids">Dokumen yang Dibutuhkan</label>
                <select id="dokumen_ids" name="dokumen_ids[]" multiple
                    class="form-control select2-ajax @error('dokumen_ids') is-invalid @enderror"
                    data-url="{{ route('admin.lowongan-magang.dokumen') }}" required>
                </select>
                @error('dokumen_ids')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="kuota">Kuota</label>
                <input required type="number" name="kuota" class="form-control @error('kuota') is-invalid @enderror"
                    value="{{ old('kuota', $data->kuota ?? 1) }}">
                @error('kuota')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="minimal_ipk">Minimal IPK</label>
                <input required type="number" step="0.01" min="0" max="4.00"
                    name="minimal_ipk" class="form-control @error('minimal_ipk') is-invalid @enderror"
                    value="{{ old('minimal_ipk', $data->minimal_ipk ?? '') }}">
                @error('minimal_ipk')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="insentif">Insentif (opsional)</label>
                <input type="text" name="insentif" class="form-control @error('insentif') is-invalid @enderror"
                    value="{{ old('insentif', $data->insentif ?? '') }}">
                @error('insentif')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>


            <div class="form-group">
                <label for="tanggal_mulai_daftar">Tanggal Mulai Daftar</label>
                <input required type="text" name="tanggal_mulai_daftar" class="form-control datepicker @error('tanggal_mulai_daftar') is-invalid @enderror"
                    value="{{ old('tanggal_mulai_daftar', $data->tanggal_mulai_daftar ?? '') }}">
                @error('tanggal_mulai_daftar')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="tanggal_selesai_daftar">Tanggal Selesai Daftar</label>
                <input required type="text" name="tanggal_selesai_daftar" class="form-control datepicker @error('tanggal_selesai_daftar') is-invalid @enderror"
                    value="{{ old('tanggal_selesai_daftar', $data->tanggal_selesai_daftar ?? '') }}">
                @error('tanggal_selesai_daftar')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="tanggal_mulai_magang">Tanggal Mulai Magang</label>
                <input required type="text" name="tanggal_mulai_magang" class="form-control datepicker @error('tanggal_mulai_magang') is-invalid @enderror"
                    value="{{ old('tanggal_mulai_magang', $data->tanggal_mulai_magang ?? '') }}">
                @error('tanggal_mulai_magang')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="tanggal_selesai_magang">Tanggal Selesai Magang</label>
                <input required type="text" name="tanggal_selesai_magang" class="form-control datepicker @error('tanggal_selesai_magang') is-invalid @enderror"
                    value="{{ old('tanggal_selesai_magang', $data->tanggal_selesai_magang ?? '') }}">
                @error('tanggal_selesai_magang')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="status">Status Lowongan</label>
                <select required name="status" class="form-control @error('status') is-invalid @enderror">
                    <option value="buka" {{ old('status', $data->status ?? '') == 'buka' ? 'selected' : '' }}>Buka</option>
                    <option value="tutup" {{ old('status', $data->status ?? '') == 'tutup' ? 'selected' : '' }}>Tutup</option>
                    <option value="dibatalkan" {{ old('status', $data->status ?? '') == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
                @error('status')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="text-right">
                <button type="submit" class="btn btn-primary">
                    {{ isset($data) ? 'Update' : 'Simpan' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('js')
<script>
    @if (!empty($data))
    const selectedKeahlian = @json($data->keahlian->map(fn($k) => ['id' => $k->id, 'text' => $k->nama]));
    const selectedDokumen  = @json($data->dokumen->map(fn($d) => ['id' => $d->id, 'text' => $d->nama]));
    const selectedTeknis = @json($data->teknis->map(fn($k) => ['id' => $k->id, 'text' => $k->nama, 'level' => $k->pivot->level]));
    @endif
    const oldPerusahaan = @json(old('perusahaan_id') ? ['id' => old('perusahaan_id'), 'text' => ''] : null);
    const oldPeriode    = @json(old('periode_magang_id') ? ['id' => old('periode_magang_id'), 'text' => ''] : null);
    
    let keahlianIndex = 0;
    const teknisOptions = @json($levelKeahlianTeknis);
    $(function () {
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

        @if (!empty($data))
        // Render data lama (jika edit)
        selectedTeknis.forEach(item => {
            renderKeahlianRow(item.id, item.text, item.level);
        });
        @endif

        $('#btn-tambah-keahlian').click(function () {
            renderKeahlianRow();
        });

        // Tombol hapus
        $(document).on('click', '.btn-hapus-baris', function () {
            const id = $(this).data('id');
            $(`#row-${id}`).remove();
        });

        @if (!empty($data))
            initSelect2WithPreselected('#keahlian_ids', selectedKeahlian);
            initSelect2WithPreselected('#dokumen_ids', selectedDokumen);
            $('#perusahaan_id').append(new Option("{{ $data->perusahaan->nama }}", "{{ $data->perusahaan_id }}", true, true)).trigger('change');
            $('#periode_magang_id').append(new Option("{{ $data->periodeMagang->nama }}", "{{ $data->periode_magang_id }}", true, true)).trigger('change');
        @else
            if (oldPerusahaan) {
                $('#perusahaan_id').append(new Option('Memuat...', oldPerusahaan.id, true, true)).trigger('change');
                fetchLabel('{{ route('admin.lowongan-magang.perusahaan') }}', oldPerusahaan.id, '#perusahaan_id');
            }
            if (oldPeriode) {
                $('#periode_magang_id').append(new Option('Memuat...', oldPeriode.id, true, true)).trigger('change');
                fetchLabel('{{ route('admin.lowongan-magang.periode-magang') }}', oldPeriode.id, '#periode_magang_id');
            }
        @endif
    });

    function buildLevelOptions(selected = "") {
        let html = '<option value="">-- Pilih Level --</option>';
        teknisOptions.forEach(level => {
            const isSelected = level === selected ? 'selected' : '';
            html += `<option value="${level}" ${isSelected}>${level.charAt(0).toUpperCase() + level.slice(1)}</option>`;
        });
        return html;
    }

    function renderKeahlianRow(keahlianId = '', keahlianText = '', level = '') {
        keahlianIndex++;

        const row = `
            <tr id="row-${keahlianIndex}">
                <td>
                    <select name="keahlian_teknis_ids[]" class="form-control select2-keahlian" data-index="${keahlianIndex}" required>
                        ${keahlianId ? `<option value="${keahlianId}" selected>${keahlianText}</option>` : ''}
                    </select>
                </td>
                <td>
                    <select name="keahlian_teknis_levels[]" class="form-control" required>
                        ${buildLevelOptions(level)}
                    </select>
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm btn-hapus-baris" data-id="${keahlianIndex}">Hapus</button>
                </td>
            </tr>
        `;

        $('#keahlian-table tbody').append(row);

        $(`select[data-index="${keahlianIndex}"]`).select2({
            width: '100%',
            placeholder: '-- Pilih Keahlian Teknis --',
            ajax: {
                url: '{{ route("admin.lowongan-magang.teknis") }}',
                dataType: 'json',
                data: function (params) {
                    return {
                        q: params.term,
                        selected: selectedTeknisForm()
                    };
                },
                delay: 250,
                processResults: function (data) {
                    return {
                        results: data.map(item => ({
                            id: item.id,
                            text: item.text
                        }))
                    };
                },
                cache: true
            }
        });
    }

    function initSelect2WithPreselected(selector, preselectedData = []) {
        const $el = $(selector);

        if (preselectedData && Array.isArray(preselectedData)) {
            preselectedData.forEach(item => {
                const option = new Option(item.text, item.id, true, true);
                $el.append(option).trigger('change');
            });
        }
    }

    function fetchLabel(url, id, selector) {
        $.ajax({
            url: url,
            data: { q: '' },
            success: function (data) {
                const item = data.find(d => d.id == id);
                if (item) {
                    const option = new Option(item.text, item.id, true, true);
                    $(selector).append(option).trigger('change');
                }
            }
        });
    }

    function selectedTeknisForm() {
        const selected = [];
        $('select.select2-keahlian').each(function () {
            const id = $(this).val();
            if (id) {
                selected.push(id);
            }
        });
        return selected;
    }
</script>

@endpush