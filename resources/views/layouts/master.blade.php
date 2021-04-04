<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ config('app.name', 'Laravel') }}</title>

  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="hold-transition sidebar-mini">
    <div id="app">
        <div class="wrapper">
            <!-- Navbar -->
            @include('layouts.partials.navbar')

            <!-- Main Sidebar Container -->
            @include('layouts.partials.sidebar')

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">

                <!-- Main content -->
                <div class="content">
                    <div class="container-fluid">

                        <router-view></router-view>
                        <vue-progress-bar></vue-progress-bar>

                        <div class="row">

                        </div>
                        <!-- /.row -->
                    </div><!-- /.container-fluid -->
                </div>
                <!-- /.content -->
            </div>
            <!-- /.content-wrapper -->

            <!-- Main Footer -->
            @include('layouts.partials.footer')
        </div>
        <!-- ./wrapper -->
    </div>

<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
