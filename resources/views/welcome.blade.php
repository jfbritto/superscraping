<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#1bb0be">
    <title>Indscraping @if (!empty($titulo)) | {{ $titulo }} @endif </title>
    <link rel="shortcut icon" href="https://cdn.icon-icons.com/icons2/620/PNG/512/ascendant-bars-graphic_icon-icons.com_56869.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <style>

        html, body {
            height: 100%;
            background-color: #f7f7f8;
        }
        .wrapper {
            display: flex;
            height: 100%;
        }
        
        #sidebar {
            width: 180px;
            background-color: #202123; /* Cor de fundo escura */
            color: #fff !important; /* Cor do texto */
            padding: 20px;
        }
        
        #content {
            flex: 1;
            padding: 20px;
        }

        .nav-link {
            color: #fff;
        }
        .nav-link:hover {
            color: #fff;
        }

    </style>
</head>
<body>
    <div class="wrapper">
        <div id="sidebar">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="btn btn-secondary nav-link" href="{{ route('tjsp') }}">TJSP</a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-secondary nav-link" href="{{ route('ortn') }}">ORTN</a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-secondary nav-link" href="{{ route('ufir') }}">UFIR</a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-secondary nav-link" href="{{ route('caderneta') }}">POUPANÇA</a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-secondary nav-link" href="{{ route('igpdi') }}">IGPDI</a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-secondary nav-link" href="{{ route('igpm') }}">IGPM</a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-secondary nav-link" href="{{ route('inpc') }}">INPC</a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-secondary nav-link" href="{{ route('ipca') }}">IPCA</a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-secondary nav-link" href="{{ route('selic') }}">SELIC</a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-secondary nav-link" href="{{ route('ipc') }}">IPC</a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-secondary nav-link" href="{{ route('tr') }}">TR</a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-secondary nav-link" href="{{ route('tjmg') }}">TJMG</a>
                </li>
            </ul>
        </div>
        <div id="content">
            <h2>{{ $titulo }}</h2>
            @foreach($resultados as $item)
                {{ $item }}<br>
            @endforeach
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
</body>
</html>
