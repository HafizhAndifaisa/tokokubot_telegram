<!DOCTYPE HTML>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Created by Moch Diki Widianto, Powered By Bootstrap and Laravel">
    <meta name="author" content="Moch Diki Widianto">
    <title>Manajemen Toko Noor Electric</title>
    <!-- Bootstrap core CSS -->
    <link href="{{ asset('css/bootstrap.css') }}" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="{{ asset('vendor/font-awesome/css/all.min.css') }}" rel="stylesheet">

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
    <!-- Custom styles for this template -->
    <link href="{{ asset('css/custom/cover.css') }}" rel="stylesheet">
</head>
<body class="text-center">
    <div class="cover-container d-flex w-100 h-100 p-3 mx-auto flex-column">
        <header class="masthead mb-auto">
            <div class="inner">
                <h3 class="masthead-brand">Noor Electric</h3>
                <nav class="nav nav-masthead justify-content-center">
                    <a class="nav-link" href="{{ route('welcome') }}"><i class="fa fa-home" style="font-size:24px"></i></a>
                </nav>
            </div>
        </header>

    <main role="main" class="inner cover">
        <h1 class="cover-heading"><span style="font-family: 'Tokoku';">Manajemen Toko Noor Electric</span></h1>
        <p class="lead">Aplikasi manajemen toko yang digunakan pada toko Noor Electric. Aplikasi ini dibuat untuk membantu jalannya bisnis toko Noor Electric agar berjalan lebih lancar</p>
        <br />
        @if (Auth::check())
        <p class="lead">
            <a href="{{ route('home') }}" class="btn btn-lg btn-primary"><span class="fa fa-sign-in-alt"></span> Lanjutkan</a>
        </p>
        @else
        <p class="lead">
            <a href="{{ url('/login') }}" class="btn btn-lg btn-success"><span class="fa fa-sign-in-alt"></span> Login Sistem</a>
        </p>
        @endif
        
    </main>

    <footer class="mastfoot mt-auto">
        <div class="inner">
            <!-- <p>Diberdayakan <a href="https://laravel.com/">Laravel</a> dan <a href="https://getbootstrap.com/">Bootstrap</a>, oleh <a href="https://twitter.com/dikiwidia">@dikiwidia</a>.</p> -->
        </div>
    </footer>
</div>
</body>
</html>