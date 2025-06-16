<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $page->title ?? $title ?? 'INI TITLE PAGE' }}</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="{{asset('skydash-v.01/vendors/feather/feather.css')}}">
    <link rel="stylesheet" href="{{asset('skydash-v.01/vendors/ti-icons/css/themify-icons.css')}}">
    <link rel="stylesheet" href="{{asset('skydash-v.01/vendors/css/vendor.bundle.base.css')}}">
    <link rel="stylesheet" href="{{asset('skydash-v.01/vendors/font-awesome/css/font-awesome.min.css')}}">
    <link rel="stylesheet" href="{{asset('skydash-v.01/vendors/mdi/css/materialdesignicons.min.css')}}">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="{{asset('skydash-v.01/vendors/datatables.net-bs4/dataTables.bootstrap4.css')}}">
    <link rel="stylesheet" href="{{asset('skydash-v.01/vendors/datatables.net-bs4/dataTables.responsive.min.css')}}"/>
    <link rel="stylesheet" href="{{asset('skydash-v.01/vendors/ti-icons/css/themify-icons.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('skydash-v.01/js/select.dataTables.min.css')}}">
    <link rel="stylesheet" type="text/css"
          href="{{asset('skydash-v.01/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('skydash-v.01/vendors/select2/select2.min.css')}}">
    <link rel="stylesheet" type="text/css"
          href="{{asset('skydash-v.01/vendors/select2-bootstrap-theme/select2-bootstrap.min.css')}}">
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet" href="{{asset('skydash-v.01/css/vertical-layout-light/style.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/custom.css')}}">

    @stack('css')

    <!-- endinject -->
    <link rel="shortcut icon" href="{{asset('images/logo-mini.svg')}}"/>
</head>
<body>
<div class="container-scroller">

    @include('layouts.header')

    <div class="container-fluid page-body-wrapper">

        @include('layouts.sidebar')

        <div class="main-panel">
            <div class="content-wrapper">

                @include('layouts.breadcrumb')

                {{-- Flash Messages --}}
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Tutup">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Tutup">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <!-- Spinner -->
                <div id="loading-spinner" style="position: relative; top: 40%; left: 0; right: 0; text-align: center;">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>

                <!-- Konten -->
                <div id="main-content" style="display: none;">
                    @yield('content')
                </div>
            </div>
            <!-- content-wrapper ends -->
            @include('layouts.footer')
        </div>
        <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
</div>
<!-- container-scroller -->

<!-- plugins:js -->
<script src="{{asset('skydash-v.01/vendors/js/vendor.bundle.base.js')}}"></script>
<!-- endinject -->
<!-- Plugin js for this page -->
<script src="{{asset('skydash-v.01/vendors/chart.js/Chart.min.js')}}"></script>
<script src="{{asset('skydash-v.01/vendors/datatables.net/jquery.dataTables.js')}}"></script>
<script src="{{asset('skydash-v.01/vendors/datatables.net-bs4/dataTables.bootstrap4.js')}}"></script>
<script src="{{asset('skydash-v.01/vendors/datatables.net-bs4/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('skydash-v.01/js/dataTables.select.min.js')}}"></script>
<script src="{{asset('skydash-v.01/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
<script src="{{asset('skydash-v.01/vendors/select2/select2.min.js')}}"></script>

<!-- End plugin js for this page -->
<!-- inject:js -->
<script src="{{asset('skydash-v.01/js/off-canvas.js')}}"></script>
<script src="{{asset('skydash-v.01/js/hoverable-collapse.js')}}"></script>
<script src="{{asset('skydash-v.01/js/template.js')}}"></script>
<script src="{{asset('skydash-v.01/js/settings.js')}}"></script>
<script src="{{asset('skydash-v.01/js/todolist.js')}}"></script>
<!-- endinject -->
<!-- Custom js for this page-->
<script src="{{asset('skydash-v.01/js/dashboard.js')}}"></script>
<script src="{{asset('skydash-v.01/js/Chart.roundedBarCharts.js')}}"></script>

{{-- Sweet Alert --}}
<script src="{{asset('skydash-v.01/vendors/sweetalert/sweetalert.min.js')}}"></script>

<!-- jquery-validation -->
<script src="{{ asset('assets/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
<script src="{{ asset('assets/plugins/jquery-validation/additional-methods.min.js') }}"></script>
{{-- Localization --}}
<script src="{{ asset('assets/plugins/jquery-validation/localization/messages_id.js') }}"></script>

<script>
    $(function () {
        $('#loading-spinner').hide();
        $('#main-content').show();
    })

    $('.datepicker').datepicker({
        format: 'dd-mm-yyyy',
        autoclose: true,
        todayHighlight: true
    });

    function swalAlertConfirm(options) {
        const {
            title = 'Apakah Anda yakin?',
            text = 'Tindakan ini tidak dapat dibatalkan!',
            icon = 'warning',
            confirmButtonText = 'Ya, Hapus!',
            cancelButtonText = 'Batal',
            url,
            method = 'DELETE',
            data = {},
            onSuccess = function () {
            },
            onError = function () {
            }
        } = options;

        swal({
            title: title,
            text: text,
            icon: icon,
            buttons: {
                cancel: {
                    text: cancelButtonText,
                    visible: true,
                    closeModal: true,
                },
                confirm: {
                    text: confirmButtonText,
                    value: true,
                    closeModal: false,
                },
            }
        }).then((result) => {
            if (result) {
                $.ajax({
                    url: url,
                    type: method,
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        ...data
                    },
                    success: function (response) {
                        swal('Berhasil!', response.success ?? 'Tindakan berhasil dilakukan.', 'success');
                        onSuccess(response);
                    },
                    error: function (xhr) {
                        swal('Gagal!', 'Terjadi kesalahan saat memproses data.', 'error');
                        onError(xhr);
                    }
                });
            }
        });
    }
</script>
<script>
    function initFormValidation(formSelector, rules, btnSubmitText = 'Simpan') {
        $(formSelector).validate({
            rules: rules,
            submitHandler: function (form) {
                // Disable submit button untuk mencegah double submit
                $('button[type="submit"]').prop('disabled', true).text('Menyimpan...');

                // Cek apakah form ada file
                var hasFile = $(form).find('input[type="file"]').length > 0;
                var ajaxOptions = {
                    url: form.action,
                    type: form.method,
                    success: function (response) {
                        if (response.success) {
                            swal({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message
                            }).then(function () {
                                window.location.href = response.redirect;
                            });
                        } else {
                            $('.error-text').text('');
                            if (response.msgField) {
                                $.each(response.msgField, function (prefix, val) {
                                    $('#error-' + prefix).text(val[0]);
                                });
                            }
                            swal({
                                icon: 'error',
                                title: 'Terjadi Kesalahan',
                                text: response.message || 'Terjadi kesalahan saat menyimpan data'
                            });
                            console.log(response.error)
                        }
                    },
                    error: function (xhr) {
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;

                            // Clear previous errors
                            $('.form-control').removeClass('is-invalid');
                            $('.invalid-feedback').remove();

                            // Show new errors
                            $.each(errors, function (field, messages) {
                                let input = $('[name="' + field + '"]');
                                input.addClass('is-invalid');
                                input.closest('.form-group').append('<div class="invalid-feedback">' + messages[0] + '</div>');
                            });

                            swal({
                                icon: 'error',
                                title: 'Validasi Gagal',
                                text: 'Silakan periksa input Anda.'
                            });
                        } else {
                            swal({
                                icon: 'error',
                                title: 'Error',
                                text: 'Terjadi kesalahan server. Silakan coba lagi.'
                            });
                        }
                    },
                    complete: function () {
                        // Re-enable submit button
                        $('button[type="submit"]').prop('disabled', false).text(btnSubmitText);
                    }
                };

                if (hasFile) {
                    var formData = new FormData(form);
                    ajaxOptions.data = formData;
                    ajaxOptions.processData = false;
                    ajaxOptions.contentType = false;
                } else {
                    ajaxOptions.data = $(form).serialize();
                }

                $.ajax(ajaxOptions);
                return false;
            },
            errorElement: 'span',
            errorPlacement: function (error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function (element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            },
        });
    }
</script>

@stack('js')

<!-- End custom js for this page-->
</body>
</html>
