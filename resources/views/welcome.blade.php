<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Super IndsScraping</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

    <!-- Styles -->
    <style>
        html, body {
            background-color: #fff;
            color: #636b6f;
            font-family: 'Nunito', sans-serif;
            font-weight: 200;
            height: 100vh;
            margin: 0;
        }

        .full-height {
            height: 100vh;
        }

        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
        }

        .position-ref {
            position: relative;
        }

        .top-right {
            position: absolute;
            right: 10px;
            top: 18px;
        }

        .content {
            text-align: center;
        }

        .title {
            font-size: 84px;
        }

        .links > a {
            color: #636b6f;
            padding: 0 25px;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
        }

        .m-b-md {
            margin-bottom: 30px;
        }

        .completed {
            color: white !important;
            background-color: green;
            border-radius: 5px;
        }

    </style>
</head>
<body>
<div class="flex-center position-ref full-height">
    <div class="content">
        <div class="title m-b-md">

        </div>

        <div class="links">
            <a class="completed" target="_blank" href="{{ route('tjsp') }}">TJSP</a><br><br>
            <a class="completed" target="_blank" href="{{ route('ortn') }}">ORTN</a><br><br>
            <a class="completed" target="_blank" href="{{ route('ufir') }}">UFIR</a><br><br>
            <a class="completed" target="_blank" href="{{ route('caderneta-poupanca') }}">CADERNETA POUPANÇA</a><br><br>
            <a class="completed" target="_blank" href="{{ route('igpdi') }}">IGPDI</a><br><br>
            <a class="completed" target="_blank" href="{{ route('igpm') }}">IGPM</a><br><br>
            <a class="completed" target="_blank" href="{{ route('inpc') }}">INPC</a><br><br>
            <a target="_blank" href="{{ route('ipca') }}">IPCA</a><br><br>
            <a class="completed" target="_blank" href="{{ route('selic') }}">SELIC</a><br><br>
            <a class="completed" target="_blank" href="{{ route('ipc-fipe') }}">IPC</a><br><br>
            <a class="completed" target="_blank" href="{{ route('tr') }}">TR</a><br><br>
            <a class="completed" target="_blank" href="{{ route('tjmg') }}">TJMG</a>
        </div>
    </div>
</div>
</body>
</html>
