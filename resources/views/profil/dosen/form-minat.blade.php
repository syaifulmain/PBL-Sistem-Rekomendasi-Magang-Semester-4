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
                    <div class="input-group">
                        <x-searchable-dropdown-modal
                            id="minat_id"
                            name="minat_id"
                            placeholder="Cari Minat"
                            :items="$listMinat"
                            class="flex-grow-1"
                        />
                        <div class="input-group-append">
                            <button class="btn btn-sm btn-primary" type="button" id="btn-tambah">Tambah</button>
                        </div>
                    </div>
                </div>
            </form>
            <hr>
            <div id="minat-container">
                @include('partials.minat-list-dosen')
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
                url: "{{ route('dosen.profil.minat.store') }}",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    if (response.success) {
                        $('#minat-container').html(response.html);
                        $('#minat_id-hidden').val('').trigger('change');
                        $('#minat_id-input').val('');
                    }
                },
                error: function (xhr) {
                    console.error(xhr.responseText);
                }
            });
        });

        $(document).on('click', '.btn-delete-minat', function (e) {
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
                        $('#minat-container').html(response.html);
                    }
                },
                error: function (xhr) {
                    console.error(xhr.responseText);
                }
            });
        });
    });
</script>
