<div class="modal-dialog modal-dialog-centered" style="max-width: 50%;">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">{{$title}}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body">
            <form id="form-tambah">
                @csrf
                <div class="form-group">
                    <div class="input-group">
                        <x-searchable-wilayah-dropdown-modal
                            id="preferensi_lokasi_id"
                            name="preferensi_lokasi_id"
                            class="flex-grow-1"
                        />
                        <div class="input-group-append">
                            <button class="btn btn-sm btn-primary" type="button" id="btn-tambah">Tambah</button>
                        </div>
                    </div>
                </div>
            </form>
            <hr>
            <div id="preferensi-lokasi-container">
                @include('partials.preferensi-lokasi-list-mahasiswa')
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#btn-tambah').click(function (e) {
            e.preventDefault();

            const formData = new FormData($('#form-tambah')[0]);

            $.ajax({
                url: "{{ route('mahasiswa.profil.preferensi-lokasi.store') }}",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    if (response.success) {
                        $('#preferensi-lokasi-container').html(response.html);
                        $('#preferensi_lokasi_id-hidden').val('').trigger('change');
                        $('#preferensi_lokasi_id-input').val('');
                        $('#preferensi_lokasi_id-type').val('');
                    }
                },
                error: function (xhr) {
                    console.error(xhr.responseText);
                }
            });
        });

        $(document).on('click', '.btn-delete-preferensi-lokasi', function (e) {
            e.preventDefault();

            const deleteUrl = $(this).closest('form').attr('action');

            $.ajax({
                url: deleteUrl,
                type: "POST",
                data: {
                    '_token': '{{ csrf_token() }}',
                    '_method': 'DELETE'
                },
                success: function (response) {
                    if (response.success) {
                        $('#preferensi-lokasi-container').html(response.html);
                    }
                },
                error: function (xhr) {
                    console.error(xhr.responseText);
                }
            });
        });
    });
</script>
