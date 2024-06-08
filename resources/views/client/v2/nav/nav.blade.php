@php use App\Models\Cafe; use App\Models\Category;use App\Other\Authentication;use Illuminate\Database\Eloquent\Collection; @endphp
@php
    $cafe = Cafe::get();

    $vkontakteGroup = '1';
    $viberGroup = '1';
    /**
     * @var Collection<mixed, Category> $categories
     */
@endphp
    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0 maximum-scale=1.0">
    <title>Navigation Example</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="icon" href="{{ asset('storage/favicon.ico') }}" type="image/x-icon"/>
    <style>
        .nav-icon {
            font-size: 1.25rem;
            padding: 0.5rem;
            position: relative;
        }

        .badge-custom {
            position: absolute;
            top: 0;
            right: 0;
            transform: translate(10%, -10%);
            background-color: red;
            color: white;
        }

        .square-btn {
            width: 50px; /* Adjust size as needed */
            height: 50px; /* Adjust size as needed */
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0; /* Remove default padding */
            margin: 0; /* Remove default margin */
            border: none; /* Remove default border */
            background-color: transparent; /* Transparent background */
        }

        @media (max-width: 992px) {
            .desktop-nav {
                display: none;
            }
        }

        @media (min-width: 992px) {
            .mobile-nav {
                display: none;
            }
        }

        /* Flexbox layout for full height */
        html, body {
            height: 100%;
            margin: 0;
            display: flex;
            flex-direction: column;
        }

        .content-wrapper {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .footer {
            background-color: #f8f9fa;
            padding: 1rem 0;
            border-top: 1px solid #e7e7e7;
        }
    </style>
    @yield('style')
</head>
<body>
<div class="content-wrapper">
    <!-- Mobile Navigation -->
    <nav class="navbar navbar-expand-lg bg-light border mobile-nav">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('home.index') }}">Доставка еды</a>
            <div class="d-flex align-items-center ms-auto">
                <a class="nav-link nav-icon position-relative square-btn" href="{{ route('cart.index') }}">
                    <i class="fas fa-shopping-cart"></i>
                    <span id="item-count-mobile" class="badge rounded-pill bg-danger badge-custom">5</span>
                </a>
                <a class="nav-link nav-icon square-btn" href="{{ route('profile.index') }}"><i class="fas fa-user"></i></a>
            </div>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mobileNavbar"
                    aria-controls="mobileNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="mobileNavbar">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    @foreach($categories as $category)
                        <li class="nav-item"><a class="nav-link fs-5" href="#">{{ $category->name }}</a></li>
                    @endforeach
                </ul>
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item fs-5">
                        <a class="link-dark" href="tel:{{ $cafe->phone }}">{{ formatPhoneNumber($cafe->phone) }}</a>
                    </li>
                    <li class="nav-item fs-5 link-dark">8:00 - 23:00</li>
                    <li class="nav-item fs-5 link-dark">{{ $cafe->address }}</li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Desktop Navigation -->
    <nav class="navbar navbar-expand-lg bg-light border desktop-nav">
        <div class="container-fluid d-flex align-items-end">
            <a class="navbar-brand fs-3" href="{{ route('home.index') }}">
                @if ($cafe->settings['show_logo'])
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" style="height: 50px;">
                @else
                    {{ $cafe->name }}
                @endif
            </a>
            <div class="collapse navbar-collapse" id="desktopNavbar">
                <div class="contacts d-flex flex-row">
                    <a class="nav-link fs-5 mx-2" href="{{ $vkontakteGroup }}">
                        Группа ВКонтакте
                    </a>
                    <a class="nav-link fs-5 mx-2" href="{{ $viberGroup }}">
                        Группа Viber
                    </a>
                    <a class="nav-link fs-5 mx-2" href="#">
                        Акции
                    </a>
                </div>
                <div class="contacts d-flex flex-row ms-4">
                    <a href="tel:{{ $cafe->phone }}" class="navbar-text me-3 fs-5 link-dark">{{ formatPhoneNumber($cafe->phone) }}</a>
                    <span class="navbar-text me-3 fs-5 link-dark">8:00 - 23:00</span>
                    <span class="navbar-text me-3 fs-5 link-dark">{{ $cafe->address }}</span>
                </div>

                <div class="d-flex align-items-center ms-auto mx-2">
                    <a class="nav-link nav-icon position-relative square-btn" href="{{ route('cart.index') }}">
                        <i class="fas fa-shopping-cart fa-lg mx-2"></i>
                        <span id="item-count-desktop" class="badge rounded-pill bg-danger badge-custom">5</span>
                    </a>
                    <a class="nav-link nav-icon square-btn" href="{{ route('profile.index') }}">
                        <i class="fas fa-user fa-lg mx-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <nav class="navbar navbar-expand-lg bg-light border desktop-nav justify-content-center">
        <ul class="navbar-nav align-items-center justify-content-center">
            @foreach($categories as $category)
                <li class="nav-item">
                    <a class="nav-link" href="#">{{ $category->name }}</a>
                </li>
            @endforeach
        </ul>
    </nav>

    <div class="container">
        @yield('content')
    </div>
</div>

<!-- Footer -->
<footer class="footer bg-light text-center text-lg-start mt-lg-5 py-3 border">
    <div class="container d-flex align-items-center justify-content-between">
        <div class="row d-flex justify-content-center align-items-stretch w-100">
            <div class="col-md-4 d-flex flex-column align-items-center">
                <h5>Контакты</h5>
                <p><a class="link-dark" href="tel:{{ $cafe->phone }}">{{ formatPhoneNumber($cafe->phone) }}</a></p>
                <p>8:00 - 23:00</p>
                <p>{{ $cafe->address }}</p>
            </div>
            <div class="col-md-4 d-flex flex-column align-items-center">
                <h5>Социальные сети</h5>
                <p><a class="link-dark" href="{{ $vkontakteGroup }}">Группа ВКонтакте</a></p>
                <p><a class="link-dark" href="{{ $viberGroup }}">Группа Viber</a></p>
            </div>
            <div class="col-md-4 d-flex flex-column align-items-center">
                <h5>Дополнительно</h5>
                <p><a class="link-dark" href="#">Акции</a></p>
                <p><a class="link-dark" href="{{ route('home.index') }}">Главная</a></p>
            </div>
        </div>
    </div>
</footer>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

@yield('script')
</html>
