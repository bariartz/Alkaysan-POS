<?php 
    $getcurrenturl = $_SERVER['REQUEST_URI'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, maximum-scale=1">
  <meta name="description" content="">
  <meta name="author" content="">
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <link rel="icon" type="image/x-icon" href="https://app.alkaysan.com/assets/img/icon-alkaysan.png">
  <title>Pilih Cabang | {{ env('WEB_NAME') }}</title>
  <link href="/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link
    href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
    rel="stylesheet">
  <link href="/css/sb-admin-2.min.css" rel="stylesheet">
  <link href="/css/app.css?{{ time() }}" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.3.1/semantic.min.css" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
  <link rel="stylesheet" type="text/css" href="/vendor/jquery-ui/jquery-ui.min.css" />
  <style>
    body {
      background: #4527A0;
    }

    .list-group {
      width: 400px !important;

    }

    .list-group-item {
      margin-top: 10px;
      border-radius: none;
      background: #fff;
      cursor: pointer;
      transition: all 0.3s ease-in-out;
      box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px;
    }

    .list-group-item:hover {
      transform: scaleX(1.1);
    }

    .check {
      opacity: 0;
      transition: all 0.6s ease-in-out;
    }

    .list-group-item:hover .check {
      opacity: 1;
    }

    .about span {
      font-size: 12px;
      margin-right: 10px;
    }

    input[type=checkbox] {
      position: relative;
      cursor: pointer;
    }

    input[type=checkbox]:before {
      content: "";
      display: block;
      position: absolute;
      width: 20px;
      height: 20px;
      top: 0px;
      left: 0;
      border: 1px solid #10a3f9;
      border-radius: 3px;
      background-color: white;

    }

    input[type=checkbox]:checked:after {
      content: "";
      display: block;
      width: 7px;
      height: 12px;
      border: solid #007bff;
      border-width: 0 2px 2px 0;
      -webkit-transform: rotate(45deg);
      -ms-transform: rotate(45deg);
      transform: rotate(45deg);
      position: absolute;
      top: 2px;
      left: 6px;
    }

    input[type="checkbox"]:checked+.check {
      opacity: 1;
    }
  </style>
</head>

<body id="page-top">
  <div id="wrapper">
    <div id="content-wrapper" class="d-flex flex-column" style="height: 100vh;">
      <div id="content">

        <div class="container-fluid">

          <div class="container d-flex align-items-center" style="flex-direction: column;flex-direction: column;justify-content: center;margin: auto;height: 90vh;">
            <h1 class="h3 mb-0 text-gray-800">Pilih Cabang</h1>
            <ul class="list-group mt-3 text-white">

              @foreach($cabangData['data'] as $cab)
              <li class="list-group-item">
                <a href="/{{ strtolower($cab['folder']) }}/dashboard">
                  <div class="text-center">
                    <div class="ml-2 center">
                      <h3 class="text-dark">{{ $cab['nama_cabang'] }}</h3>
                    </div>
                  </div>
                </a>
              </li>
              @endforeach
            </ul>

          </div>
        </div>
      </div>

      <footer class="sticky-footer">
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
            <span>Copyright &copy; Alkaysan {{ date("Y") }}</span>
          </div>
        </div>
      </footer>

    </div>

  </div>

  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <!-- Logout Modal-->
  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Yakin ingin keluar?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">Pilih keluar untuk mengakhiri sesi Anda.</div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
          <a class="btn btn-primary" href="login.html">Keluar</a>
        </div>
      </div>
    </div>
  </div>

  <script src="/vendor/jquery/v3.6.0/jquery.min.js"></script>
  <script src="/vendor/jquery-ui/jquery-ui.min.js"></script>
  <script src="/vendor/jquery-rotate/jquery.rotate.js"></script>
  <script src="/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="/vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/mobile-detect@1.4.5/mobile-detect.min.js"></script>
  <script src="/js/sb-admin-2.min.js"></script>
  @if($getcurrenturl == "/")
  <script src="/vendor/chart.js/Chart.min.js"></script>
  <script src="/js/demo/chart-area-demo.js"></script>
  <script src="/js/demo/chart-pie-demo.js"></script>
  @endif
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/additional-methods.min.js"></script>
  <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
  <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
  <script type="text/javascript" src="/js/app.js?{{ time() }}"></script>
</body>

</html>