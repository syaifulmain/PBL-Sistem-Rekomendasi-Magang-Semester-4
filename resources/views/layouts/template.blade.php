<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>{{ $page->title ?? $title ?? 'INI TITLE PAGE' }}</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="{{asset('skydash-v.01/vendors/feather/feather.css')}}">
    <link rel="stylesheet" href="{{asset('skydash-v.01/vendors/ti-icons/css/themify-icons.css')}}">
    <link rel="stylesheet" href="{{asset('skydash-v.01/vendors/css/vendor.bundle.base.css')}}">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="{{asset('skydash-v.01/vendors/datatables.net-bs4/dataTables.bootstrap4.css')}}">
    <link rel="stylesheet" href="{{asset('skydash-v.01/vendors/ti-icons/css/themify-icons.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('skydash-v.01/js/select.dataTables.min.css')}}">
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet" href="{{asset('skydash-v.01/css/vertical-layout-light/style.css')}}">

    @stack('css')

    <!-- endinject -->
    <link rel="shortcut icon" href="{{asset('skydash-v.01/images/favicon.png')}}"/>
</head>
<body>
<div class="container-scroller">

    @include('layouts.header')

    <div class="container-fluid page-body-wrapper">

        @include('layouts.setting-panel')

        @include('layouts.sidebar')

        <div class="main-panel">
            <div class="content-wrapper">

                @include('layouts.breadcrumb')

                @yield('content')

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
<script src="{{asset('skydash-v.01/js/dataTables.select.min.js')}}"></script>

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

@stack('js')

<!-- End custom js for this page-->
</body>
</html>
