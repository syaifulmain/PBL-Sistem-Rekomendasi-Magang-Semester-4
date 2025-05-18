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
                </div>

                <div class="form-group">
                    <label for="level_id">Level</label>
                    <div class="input-group">
                        <select id="level_id" name="level_id" class="form-control">
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

            <div id="keahlian-container">
                @include('partials.keahlian-list-mahasiswa')
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
                url: "{{ route('mahasiswa.profil.keahlian.store') }}",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {``
                        $('#keahlian-container').html(response.html);``
                        $('#keahlian_id-hidden').val('').trigger('change');
                        $('#keahlian_id-input').val('');
                    }
                },
                error: function(xhr) {
                    console.error(xhr.responseText);``
                }
            });
        });
        ``
        $(document).on('click', '.btn-delete-keahlian', function(e) {
            e.preventDefault();

            const deleteUrl = $(this).closest('form').attr('action');

            $.ajax({
                url: deleteUrl,
                type: "POST",
                data: {
                    '_token': '{{ csrf_token() }}',
                    '_method': 'DELETE'
                },
                success: function(response) {
                    if (response.success) {``
                        $('#keahlian-container').html(response.html);
                    }
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                }
            });
        });
    });
</script>
