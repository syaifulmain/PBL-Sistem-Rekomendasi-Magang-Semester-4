<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Skydash Admin</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="{{asset('skydash-v.01/vendors/feather/feather.css')}}">
  <link rel="stylesheet" href="{{asset('skydash-v.01/vendors/ti-icons/css/themify-icons.css')}}">
  <link rel="stylesheet" href="{{asset('skydash-v.01/vendors/css/vendor.bundle.base.css')}}">
  <!-- endinject -->
  <!-- Plugin css for this page -->
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="{{asset('skydash-v.01/css/vertical-layout-light/style.css')}}">
  <!-- endinject -->
  <link rel="shortcut icon" href="{{asset('skydash-v.01/images/favicon.png')}}" />
</head>

<body>
  <div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">

          @yield('content')

    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->
  <!-- plugins:js -->
  <script src="{{asset('skydash-v.01/vendors/js/vendor.bundle.base.js')}}"></script>
  <!-- endinject -->
  <!-- Plugin js for this page -->
  <!-- End plugin js for this page -->
  <!-- inject:js -->
  <script src="{{asset('skydash-v.01/js/off-canvas.js')}}"></script>
  <script src="{{asset('skydash-v.01/js/hoverable-collapse.js')}}"></script>
  <script src="{{asset('skydash-v.01/js/template.js')}}"></script>
  <script src="{{asset('skydash-v.01/js/settings.js')}}"></script>
  <script src="{{asset('skydash-v.01/js/todolist.js')}}"></script>
  <!-- endinject -->
</body>

</html>
