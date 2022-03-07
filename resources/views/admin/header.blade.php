<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Theme Made By www.w3schools.com - No Copyright -->
    <title>Profit180 Admin</title>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">


    <script src="{{asset('assets/js/jquery3.min.js?v='.time())}}"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

    <style>
    body {
        font: 400 15px Open, sans-serif;
        color: #777;
    }


    .container {
        padding-top: 50px;
    }

    .person {
        border: 10px solid transparent;
        margin-bottom: 25px;
        width: 80%;
        height: 80%;
        opacity: 0.7;
    }

    .person:hover {
        border-color: #f1f1f1;
    }

    .carousel-inner img {
        -webkit-filter: grayscale(90%);
        filter: grayscale(90%);
        /* make all photos black and white */
        width: 100%;
        /* Set width to 100% */
        margin: auto;
    }

    .carousel-caption h3 {
        color: #fff !important;
    }


    .bg-1 {
        background: #2d2d30;
        color: #bdbdbd;
    }

    .bg-1 h3 {
        color: #fff;
    }

    .bg-1 p {
        font-style: italic;
    }

    .list-group-item:first-child {
        border-top-right-radius: 0;
        border-top-left-radius: 0;
    }

    .list-group-item:last-child {
        border-bottom-right-radius: 0;
        border-bottom-left-radius: 0;
    }

    .thumbnail {
        padding: 0 0 15px 0;
        border: none;
        border-radius: 0;
    }

    .thumbnail p {
        margin-top: 15px;
        color: #555;
    }

    .btn {
        padding: 10px 20px;
        background-color: #f2f2f2;
        color: #000;
        border-radius: 0;
        transition: .2s;
    }

    .btn:hover,
    .btn:focus {
        border: 1px solid #f2f2f2;
        background-color: #fff;
        color: #000;
    }

    .modal-header,
    h4,
    .close {
        background-color: #f2f2f2;
        color: #fff !important;
        text-align: center;
        font-size: 30px;
    }

    .modal-header,
    .modal-body {
        padding: 40px 50px;
    }

    .nav-tabs li a {
        color: #777;
    }

    #googleMap {
        width: 100%;
        height: 400px;
        -webkit-filter: grayscale(100%);
        filter: grayscale(100%);
    }

    table {
        border-collapse: collapse;
        border-spacing: 0;
        width: 100%;
        border: 1px solid #ddd;
    }

    th,
    td {
        text-align: center;
        /* border-bottom-style: hidden; */
        border-left-style: hidden;
        border-right-style: hidden;
        font-size: 13px;
        padding: 8px;
    }



    tr:nth-child(even) {
        background-color: #f2f2f2
    }

    .table_div {
        overflow-x: auto;
    }

    .navbar {
        font-family: Helvetica, sans-serif;
        margin-bottom: 0;
        /* background-color: #2d2d30; */
        border: 0;
        font-size: 11px !important;

        opacity: 0.9;
    }

    .navbar li a,
    .navbar .navbar-brand {
        color: #000 !important;
    }

    .navbar-nav li a:hover {
        color: #000 !important;
    }

    .navbar-nav li.active a {
        color: #000 !important;
        /* background-color: #29292c !important; */
    }

    .navbar-default .navbar-toggle {
        border-color: transparent;
    }

    .open .dropdown-toggle {
        color: #fff;
        background-color: #555 !important;
    }

    .dropdown-menu li a {
        color: #000 !important;
    }

    .dropdown-menu li a:hover {
        background-color: red !important;
    }

    footer {
        background-color: #f1f1f1;
        color: #000;
        padding: 32px;
    }

    footer a {
        color: #000;
    }

    footer a:hover {
        color: #777;
        text-decoration: none;
    }

    .form-control {
        border-radius: 0;
    }

    textarea {
        resize: none;
    }

    .modal-header,
    h4,
    .close {
        background-color: #f2f2f2;
        color: #444444 !important;
        text-align: center;
        font-size: 30px;
    }
    article, aside, details, figcaption, figure, footer, header, hgroup, main, menu, nav, section, summary {
    display: block;
    text-align: center;
}.tooltip {
  position: relative;
  display: inline-block;
  border-bottom: 1px dotted black;
}

.tooltip .tooltiptext {
  visibility: hidden;
  width: 120px;
  background-color: black;
  color: #fff;
  text-align: center;
  border-radius: 6px;
  padding: 5px 0;

  /* Position the tooltip */
  position: absolute;
  z-index: 1;
}

.tooltip:hover .tooltiptext {
  visibility: visible;
}

    @media (max-width: 600px) {
        .carousel-caption {
            display: none;
            /* Hide the carousel text when the screen is less than 600 pixels wide */
        }

        .navbar-default .navbar-toggle .icon-bar {
            /* background-color: #fff; */
        }

        .table_div {
            overflow-x: auto;
        }
    }
    </style> <link rel="stylesheet" href="{{asset('/assets/css/adminTable.css?v='.time())}}">
</head>

<body id="myPage" data-spy="scroll" data-target=".navbar" data-offset="50">

    <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="{{route('admin')}}">Profit180 Admin</a>
            </div>
            <div class="collapse navbar-collapse" id="myNavbar">
                <ul class="nav navbar-nav navbar-right">
                    <!-- Authentication Links -->
                    @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                    </li>
                    @if (Route::has('register'))
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                    </li>
                    @endif
                    @else
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ Auth::user()->name }} <span class="caret"></span>
                        </a>

                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </li>
                    @endguest
                </ul>

                <ul class="nav navbar-nav navbar-right">
                    <li><a href="{{url('/manageEmails')}}/?search=''">Manage Contact</a></li>
                    <li><a href="{{url('/')}}">Technical Analysis</a></li>
                    <li><a href="{{ url('/useful_links') }}">Useful Links</a></li>
                    <li><a href="{{ url('/newsletter') }}">News Letter</a></li>
                </ul>
            </div>
        </div>
    </nav>

   
</body>

</html>