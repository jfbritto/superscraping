<!DOCTYPE html>
<html>
<head>
    <title>Indscraping @if (!empty($titulo)) | {{ $titulo }} @endif </title>
    
    <meta name="theme-color" content="#010005">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name=”keywords” content="ATUALIZAÇÃO MONETÁRA" />
    <meta property="og:url" content="https://indscraping.herokuapp.com/" />
    <meta property="type" content="website" />
    <meta property="og:title" content="Indscraping">
    <meta property="og:description" content="Atualização Monetária">
    <meta property="og:image" content="https://cdn.icon-icons.com/icons2/620/PNG/512/ascendant-bars-graphic_icon-icons.com_56869.png">
    <meta property="og:locale" content="pt_BR">
    <meta property="og:image:type" content="image/png">
    <meta property="og:image:width" content="640">
    <meta property="og:image:height" content="480">

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
        .nav-link-active {
            color: #fff;
            background-color: #5c636a;
        }

    </style>
</head>
<body>
    <div class="wrapper">
        <div id="sidebar">
            <div class="text-center mb-1" style="top:15px">
                <a href="/">
                    <img src="{{ asset('img/logo.png') }}" class="img rounded" width="140">
                </a>
            </div>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="btn btn-secondary nav-link  @if ($titulo == 'TJSP') nav-link-active @endif mb-1" href="{{ route('tjsp') }}">TJSP</a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-secondary nav-link  @if ($titulo == 'ORTN') nav-link-active @endif mb-1" href="{{ route('ortn') }}">ORTN</a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-secondary nav-link  @if ($titulo == 'UFIR') nav-link-active @endif mb-1" href="{{ route('ufir') }}">UFIR</a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-secondary nav-link  @if ($titulo == 'POUPANÇA') nav-link-active @endif mb-1" href="{{ route('caderneta') }}">POUPANÇA</a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-secondary nav-link  @if ($titulo == 'IGPDI') nav-link-active @endif mb-1" href="{{ route('igpdi') }}">IGPDI</a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-secondary nav-link  @if ($titulo == 'IGPM') nav-link-active @endif mb-1" href="{{ route('igpm') }}">IGPM</a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-secondary nav-link  @if ($titulo == 'INPC') nav-link-active @endif mb-1" href="{{ route('inpc') }}">INPC</a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-secondary nav-link  @if ($titulo == 'IPCA') nav-link-active @endif mb-1" href="{{ route('ipca') }}">IPCA</a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-secondary nav-link  @if ($titulo == 'SELIC') nav-link-active @endif mb-1" href="{{ route('selic') }}">SELIC</a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-secondary nav-link  @if ($titulo == 'IPC FIPE') nav-link-active @endif mb-1" href="{{ route('ipc') }}">IPC FIPE</a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-secondary nav-link  @if ($titulo == 'TR') nav-link-active @endif mb-1" href="{{ route('tr') }}">TR</a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-secondary nav-link  @if ($titulo == 'TJMG') nav-link-active @endif mb-1" href="{{ route('tjmg') }}">TJMG</a>
                </li>
            </ul>
            <div class="text-center" style="position: absolute; bottom:15px; margin-left: 35px">
                <a href="https://github.com/jfbritto/superscraping" target="_blank"><img src="https://github.com/jfbritto.png" class="img rounded-circle" width="70"></a>
            </div>
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
