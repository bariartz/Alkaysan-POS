<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.88.1">
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,shrink-to-fit=no,user-scalable=no" />
    <link rel="icon" href="https://app.alkaysan.co.id/assets/img/icon-alkaysan.png">
    <title>Cashier App | Alkaysan</title>
    <link rel="canonical" href="https://getbootstrap.com/docs/4.6/examples/sign-in/">
    <link href="/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
    <style>
        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
                font-size: 3.5rem;
            }
        }
    </style>
    <link href="/css/signin.css" rel="stylesheet">
</head>

<body class="text-center">
    <div class="form-signin" data-api="" data-token="{{ csrf_token() }}" style="max-width: 350px;">
        @csrf
        <img class="mb-4" src="https://app.alkaysan.co.id/assets/img/logo_alkaysan_karyawan.png" alt="" width="100%">
        <h1 class="h3 mb-3 font-weight-normal">Login Karyawan</h1>
        @if(session()->has('loginError'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Permissions!</strong> {{ session('loginError') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif
        <a href="/oauth/redirect" class="btn btn-lg btn-primary border-0 btn-block" type="submit" style="background-color: #ed3237; box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px;">Lanjutkan dengan Alkaysan App</a>
        <p class="mt-5 mb-3 text-muted">&copy; {{ date("Y") }}</p>
    </div>
</body>
</html>
