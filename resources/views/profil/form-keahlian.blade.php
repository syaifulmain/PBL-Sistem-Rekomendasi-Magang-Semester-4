<button type="button" class="btn btn-close btn-close-white btn-sm ms-2 text-white">X</button>
<div class="modal-dialog modal-dialog-centered" style="max-width: 50%;">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">{{$title}}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body">
            <form id="form-tambah" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="keahlian_id">Keahlian</label>
                    <x-searchable-dropdown-modal
                        id="keahlian_id"
                        name="keahlian_id"
                        placeholder="Cari Keahlian"
                        :items="$listKeahlain"
                        class="flex-grow-1"
                    />
                    <small id="error-keahlian-input" class="text-danger d-none"></small>
                </div>

                <div class="form-group">
                    <label for="level_id">Level</label>
                    <div class="input-group">
                        <select id="level_id" name="level_id" class="form-control" required>
                            <option value="1">Pemula</option>
                            <option value="2">Menengah</option>
                            <option value="3">Mahir</option>
                        </select>
                        <div class="input-group-append">
                            <button class="btn btn-sm btn-primary" type="button" id="btn-tambah">Tambah</button>
                        </div>
                    </div>
                </div>
            </form>
            <hr>
            <div id="tag-cross-delete">
                @include('partials._tag_cross_delete')
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {``
        $('#btn-tambah').click(function(e) {
            e.preventDefault();

            const formData = new FormData($('#form-tambah')[0]);

            $.ajax({
                url: "{{ route('profil.keahlian.store') }}",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {``
                        $('#tag-cross-delete').html(response.html);``
                        $('#keahlian_id-hidden').val('').trigger('change');
                        $('#keahlian_id-input').val('');
                    }else {
                        $('#error-keahlian-input').removeClass('d-none').text(response.message);
                    }
                },
                error: function (xhr) {
                    console.error(xhr.responseText);
                    $('#error-keahlian-input').removeClass('d-none').text('Periksa kembali input Anda.');
                }
            });
        });
    });
</script>
