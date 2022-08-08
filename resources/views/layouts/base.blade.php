<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="csrf_token" content="{{ csrf_token() }}">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  {{-- make favicon --}}
  <link rel="shortcut icon" href="{{ asset('assets/img/favicon.ico') }}">
  <title>SILADA &mdash; {{ $title ?? "" }}</title>

  <!-- General CSS Files -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">

  {{-- Sweetalert --}}
  <link rel="stylesheet" href="{{ asset('sweetalert2/sweetalert2.min.css') }}">

  <!-- Fonts -->
    {{-- <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet"> --}}

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600&display=swap');
        *{
            font-family: 'Poppins', sans-serif;
        }

        .custom-navbar {
          background-color: #fff !important;
        }

        .custom-navbar-bg {
          background-color: #eeeeee !important;
        }

        .btn-primary-custom {
          padding: 6px 9px 6px 9px !important;
          border-radius: 5px !important;
        }
    </style>
  <!-- Template CSS -->
  <link rel="stylesheet" href="{{ asset('/') }}assets/css/style.css">
  <link rel="stylesheet" href="{{ asset('/') }}assets/css/components.css">
  <link rel="stylesheet" href="{{ asset('assets/css/daterangepicker/daterangepicker.css') }}">

  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap4.css"/>
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.0.1/css/buttons.bootstrap4.css"/>
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/fixedcolumns/4.0.0/css/fixedColumns.bootstrap4.css"/>
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap4.css"/>

  @stack('style')
</head>

<body>
  @yield('modal')
  <div id="app">
    <div class="main-wrapper">

      @include('layouts.modules.topbar')

      @include('layouts.modules.sidebar')

      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            @yield('section-header')
          </div>

          <div class="section-body">
              @yield('content')
          </div>
        </section>
      </div>
      @include('layouts.modules.footer')
    </div>
  </div>

  <!-- General JS Scripts -->
  {{-- <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script> --}}
  <script src="{{ asset('assets/js/jquery/jquery-3.6.0.min.js') }}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.nicescroll/3.7.6/jquery.nicescroll.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
  <script src="{{ asset('/') }}assets/js/stisla.js"></script>

  <!-- JS Libraies -->

  <!-- Template JS File -->
  <script src="{{ asset('/') }}assets/js/scripts.js"></script>
  <script src="{{ asset('/') }}assets/js/custom.js"></script>
  <script src="{{ asset('assets/js/daterangepicker/daterange-picker.js') }}"></script>
  <script src="{{ asset('assets/js/moment/moment.min.js') }}"></script>

  {{-- sweetalert --}}
  <script type="text/javascript" src="{{ asset('sweetalert2/sweetalert2.min.js') }}"></script>

  <!-- Page Specific JS File -->
  <script type="text/javascript" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.js"></script>
  <script type="text/javascript" src="https://cdn.datatables.net/1.11.3/js/dataTables.bootstrap4.js"></script>
  <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.0.1/js/dataTables.buttons.js"></script>
  <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.bootstrap4.js"></script>
  <script type="text/javascript" src="https://cdn.datatables.net/fixedcolumns/4.0.0/js/dataTables.fixedColumns.js"></script>
  <script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.js"></script>
  <script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.9/js/responsive.bootstrap4.js"></script>

  @stack('script')
</body>
</html>