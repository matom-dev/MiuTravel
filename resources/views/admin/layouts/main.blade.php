<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('title', 'Quản trị Du lịch')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="{!! asset('admin/plugins/fontawesome-free/css/all.min.css') !!}">
    <!-- AdminLTE core -->
    <link rel="stylesheet" href="{!! asset('admin/plugins/daterangepicker/daterangepicker.css') !!}">
    <link rel="stylesheet" href="{!! asset('admin/plugins/icheck-bootstrap/icheck-bootstrap.min.css') !!}">
    <link rel="stylesheet" href="{!! asset('admin/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') !!}">
    <link rel="stylesheet" href="{!! asset('admin/plugins/jquery-confirm/dist/jquery-confirm.min.css') !!}">
    <link rel="stylesheet" href="{!! asset('admin/plugins/select2/css/select2.min.css') !!}">
    <link rel="stylesheet" href="{!! asset('admin/plugins/toastr/toastr.min.css') !!}">
    <link rel="stylesheet" href="{!! asset('admin/dist/css/adminlte.min.css') !!}">

    <!-- Custom styles (override AdminLTE) -->
    <link rel="stylesheet" href="{!! asset('admin/dist/css/admin_custom.css') !!}">

    <!-- CKEditor -->
    <script src="{!! asset('admin/ckeditor/ckeditor.js') !!}"></script>
    <script src="{!! asset('admin/ckfinder/ckfinder.js') !!}"></script>
    <script src="{!! asset('admin/dist/js/func_ckfinder.js') !!}"></script>
    <script>var baseURL = "{!! url('/')!!}"</script>

    @yield('style-css')
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

    <!-- ══ Navbar ══ -->
    @include('admin.common.navbar')

    <!-- ══ Sidebar ══ -->
    @include('admin.common.sidebar')

    <!-- ══ Content Wrapper ══ -->
    <div class="content-wrapper">
        @if($errors->any())
            <div class="container-fluid pt-3">
                <div class="alert alert-danger shadow-sm mb-0" role="alert">
                    <strong><i class="fas fa-exclamation-circle mr-1"></i> Vui lòng kiểm tra lại thông tin.</strong>
                    <ul class="mb-0 mt-2 pl-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif
        @yield('content')
    </div>

    <!-- ══ Footer ══ -->
    @include('admin.common.footer')

</div><!-- /.wrapper -->

<!-- jQuery -->
<script src="{!! asset('admin/plugins/jquery/jquery.min.js') !!}"></script>
<!-- Bootstrap 4 -->
<script src="{!! asset('admin/plugins/bootstrap/js/bootstrap.bundle.min.js') !!}"></script>
<!-- overlayScrollbars -->
<script src="{!! asset('admin/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') !!}"></script>
<!-- AdminLTE -->
<script src="{!! asset('admin/dist/js/adminlte.min.js') !!}"></script>
<!-- Plugins -->
<script src="{!! asset('admin/plugins/jquery-confirm/dist/jquery-confirm.min.js') !!}"></script>
<script src="{!! asset('admin/plugins/select2/js/select2.min.js') !!}"></script>
<script src="{!! asset('admin/plugins/toastr/toastr.min.js') !!}"></script>
<script src="{!! asset('admin/dist/js/demo.js') !!}"></script>
<script src="{!! asset('admin/dist/js/main.js') !!}"></script>

<script>
    // Toastr config
    toastr.options = {
        closeButton: true,
        progressBar: true,
        positionClass: 'toast-top-right',
        timeOut: 3500,
    };
    @if(session('success'))
        toastr.success("{{ session('success') }}");
    @endif
    @if(session('error'))
        toastr.error("{{ session('error') }}");
    @endif
    @if(session('warning'))
        toastr.warning("{{ session('warning') }}");
    @endif

    $(document).ready(function () {
        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });
        $('.select2').select2({ width: '100%' });
    });
</script>
@yield('script')
</body>
</html>
