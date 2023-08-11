<!DOCTYPE html>
<html>
<head>
    <title>Indscraping @if (!empty($titulo)) | {{ $titulo }} @endif </title>

    <meta name="theme-color" content="#010005">
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
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
    <link rel="stylesheet" href="/font-awesome-4.7.0/css/font-awesome.min.css">
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

        .check-true {
            color: #198754 !important
        }
        .check-false {
            color: #dc3545 !important
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
                    <a class="btn btn-secondary nav-link  @if ($titulo == 'TJSP') nav-link-active @endif mb-1" href="{{ route('tjsp') }}">
                        TJSP
                        <i class="fa fa-check-circle check-true" aria-hidden="true" id="icon-tjsp-true" style="display: none !important"></i>
                        <i class="fa fa-times-circle check-false" aria-hidden="true" id="icon-tjsp-false" style="display: none !important"></i>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-secondary nav-link  @if ($titulo == 'ORTN') nav-link-active @endif mb-1" href="{{ route('ortn') }}">
                        ORTN
                        <i class="fa fa-check-circle check-true" aria-hidden="true" id="icon-ortn-true" style="display: none !important"></i>
                        <i class="fa fa-times-circle check-false" aria-hidden="true" id="icon-ortn-false" style="display: none !important"></i>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-secondary nav-link  @if ($titulo == 'UFIR') nav-link-active @endif mb-1" href="{{ route('ufir') }}">
                        UFIR
                        <i class="fa fa-check-circle check-true" aria-hidden="true" id="icon-ufir-true" style="display: none !important"></i>
                        <i class="fa fa-times-circle check-false" aria-hidden="true" id="icon-ufir-false" style="display: none !important"></i>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-secondary nav-link  @if ($titulo == 'POUPANÇA') nav-link-active @endif mb-1" href="{{ route('caderneta') }}">
                        POUPANÇA
                        <i class="fa fa-check-circle check-true" aria-hidden="true" id="icon-caderneta-true" style="display: none !important"></i>
                        <i class="fa fa-times-circle check-false" aria-hidden="true" id="icon-caderneta-false" style="display: none !important"></i>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-secondary nav-link  @if ($titulo == 'IGPDI') nav-link-active @endif mb-1" href="{{ route('igpdi') }}">
                        IGPDI
                        <i class="fa fa-check-circle check-true" aria-hidden="true" id="icon-igpdi-true" style="display: none !important"></i>
                        <i class="fa fa-times-circle check-false" aria-hidden="true" id="icon-igpdi-false" style="display: none !important"></i>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-secondary nav-link  @if ($titulo == 'IGPM') nav-link-active @endif mb-1" href="{{ route('igpm') }}">
                        IGPM
                        <i class="fa fa-check-circle check-true" aria-hidden="true" id="icon-igpm-true" style="display: none !important"></i>
                        <i class="fa fa-times-circle check-false" aria-hidden="true" id="icon-igpm-false" style="display: none !important"></i>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-secondary nav-link  @if ($titulo == 'INPC') nav-link-active @endif mb-1" href="{{ route('inpc') }}">
                        INPC
                        <i class="fa fa-check-circle check-true" aria-hidden="true" id="icon-inpc-true" style="display: none !important"></i>
                        <i class="fa fa-times-circle check-false" aria-hidden="true" id="icon-inpc-false" style="display: none !important"></i>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-secondary nav-link  @if ($titulo == 'IPCA') nav-link-active @endif mb-1" href="{{ route('ipca') }}">
                        IPCA
                        <i class="fa fa-check-circle check-true" aria-hidden="true" id="icon-ipca-true" style="display: none !important"></i>
                        <i class="fa fa-times-circle check-false" aria-hidden="true" id="icon-ipca-false" style="display: none !important"></i>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-secondary nav-link  @if ($titulo == 'SELIC') nav-link-active @endif mb-1" href="{{ route('selic') }}">
                        SELIC
                        <i class="fa fa-check-circle check-true" aria-hidden="true" id="icon-selic-true" style="display: none !important"></i>
                        <i class="fa fa-times-circle check-false" aria-hidden="true" id="icon-selic-false" style="display: none !important"></i>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-secondary nav-link  @if ($titulo == 'IPC FIPE') nav-link-active @endif mb-1" href="{{ route('ipc') }}">
                        IPC FIPE
                        <i class="fa fa-check-circle check-true" aria-hidden="true" id="icon-ipc-true" style="display: none !important"></i>
                        <i class="fa fa-times-circle check-false" aria-hidden="true" id="icon-ipc-false" style="display: none !important"></i>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-secondary nav-link  @if ($titulo == 'IPC FGV') nav-link-active @endif mb-1" href="{{ route('ipcfgv') }}">
                        IPC FGV
                        <i class="fa fa-check-circle check-true" aria-hidden="true" id="icon-ipcfgv-true" style="display: none !important"></i>
                        <i class="fa ftimes-check-cirfalse check-true" aria-hidden="true" id="icon-ipcfgv-false" style="display: none !important"></i>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-secondary nav-link  @if ($titulo == 'TR') nav-link-active @endif mb-1" href="{{ route('tr') }}">
                        TR
                        <i class="fa fa-check-circle check-true" aria-hidden="true" id="icon-tr-true" style="display: none !important"></i>
                        <i class="fa fa-times-circle check-false" aria-hidden="true" id="icon-tr-false" style="display: none !important"></i>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-secondary nav-link  @if ($titulo == 'TJMG') nav-link-active @endif mb-1" href="{{ route('tjmg') }}">
                        TJMG
                        <i class="fa fa-check-circle check-true" aria-hidden="true" id="icon-tjmg-true" style="display: none !important"></i>
                        <i class="fa fa-times-circle check-false" aria-hidden="true" id="icon-tjmg-false" style="display: none !important"></i>
                    </a>
                </li>
            </ul>
            <div class="text-center" style="position: absolute; bottom:15px; margin-left: 35px">
                <a href="https://github.com/jfbritto/superscraping" target="_blank"><img src="https://github.com/jfbritto.png" class="img rounded-circle" width="70"></a>
            </div>
        </div>
        <div id="content">
            <h2>{{ $titulo }}</h2>
            @foreach($resultados as $key => $item)
                <label style="color:@if($key == (count($resultados)-2)) green @elseif($key == (count($resultados)-1)) blue @else black @endif">{{ $item }}</label> <br>
            @endforeach
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
    <script src="/js/welcome.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
</body>
</html>
