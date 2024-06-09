@php use App\Models\Cafe; use App\Models\Category;
use Illuminate\Database\Eloquent\Collection; @endphp
@php
    $cafe = Cafe::get();

    $vkontakteGroup = '1';
    $viberGroup = '1';
    /**
     * @var Collection<mixed, Category> $categories
     */

    $categories = Category::ordered('id');
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <title>@yield('title', $cafe->name)</title>

    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0 maximum-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="icon" href="{{ asset('storage/favicon.ico') }}?" type="image/x-icon"/>
    <link href="https://cdn.jsdelivr.net/npm/jquery-datetime-picker@2.5.11/jquery.datetimepicker.min.css" rel="stylesheet">

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
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0;
            margin: 0;
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
            border-top: 1px solid #e7e7e7;
        }

        @media(max-width: 992px) {
            .min-height-wrap {
                min-height: 100vh;
            }
        }

        @media(min-width: 992px) {
            .min-height-wrap {
                margin-top: 3rem;
            }
        }

        .bg-custom {
            background-color: #e2e6ea; /* Чуть темнее, чем bg-light */
        }
    </style>
    @yield('style')
</head>
<body>
<div class="content-wrapper">
    <!-- Mobile Navigation -->
    <nav class="navbar navbar-expand-lg bg-custom border mobile-nav">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('home.index') }}">Доставка еды</a>
            <div class="d-flex align-items-center ms-auto">
                <a class="nav-link nav-icon position-relative square-btn navbar-toggler mx-1" href="{{ route('cart.index') }}">
                    <i class="fas fa-shopping-cart text-dark"></i>
                    <span id="item-count-mobile" class="badge rounded-pill bg-danger badge-custom">0</span>
                </a>
                <a class="nav-link nav-icon square-btn navbar-toggler mx-1" href="{{ route('profile.index') }}">
                    <i class="fas fa-user text-dark"></i>
                </a>
            </div>

            <button class="square-btn navbar-toggler mx-1" type="button" data-bs-toggle="collapse" data-bs-target="#mobileNavbar"
                    aria-controls="mobileNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon text-dark"></span>
            </button>

            <div class="collapse navbar-collapse" id="mobileNavbar">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    @foreach($categories as $category)
                        <li class="nav-item"><a class="nav-link fs-5 text-dark" href="{{ route('home.index', ['category-id' => $category->id]) }}">{{ $category->name }}</a></li>
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
    <nav class="navbar navbar-expand-lg bg-custom border desktop-nav">
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
                    <a href="tel:{{ $cafe->phone }}" class="navbar-text me-3 fs-6 link-dark">{{ formatPhoneNumber($cafe->phone) }}</a>
                    <span class="navbar-text me-3 fs-6 link-dark">8:00 - 23:00</span>
                    <span class="navbar-text me-3 fs-6 link-dark">{{ $cafe->address }}</span>
                </div>

                <div class="d-flex align-items-center ms-auto mx-2">
                    <a class="nav-link nav-icon position-relative square-btn" href="{{ route('cart.index') }}">
                        <i class="fas fa-shopping-cart fa-lg mx-2"></i>
                        <span id="item-count-desktop" class="badge rounded-pill bg-danger badge-custom">0</span>
                    </a>
                    <a class="nav-link nav-icon square-btn" href="{{ route('profile.index') }}">
                        <i class="fas fa-user fa-lg mx-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <nav class="navbar navbar-expand-lg bg-light border desktop-nav justify-content-center">
        <div class="container">
            <div class="row w-100 justify-content-center">
                <ul class="navbar-nav d-flex flex-wrap justify-content-center align-items-center w-100">
                    @foreach($categories as $index => $category)
                        @php($route = route('home.index', ['category-id' => $category->id]))
                        @if ($index < 16)
                            <li class="nav-item col-6 col-md-4 col-lg-3 d-flex justify-content-center px-1" style="max-width: 12.5%;">
                                <a class="nav-link text-nowrap" href="{{ $route }}">{{ $category->name }}</a>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </div>
        </div>
    </nav>

    <div class="container py-2 px-4">
        <div class="p-1 mx-1 w-100">
            @if (session('notification'))
                <div class="alert alert-success">
                    {{ session('notification') }}
                </div>
            @endif
        </div>

        @yield('content')
    </div>
</div>

<!-- Footer -->
<footer class="footer bg-custom text-center text-lg-start mt-lg-5 py-4 px-1 border w-100">
    <div class="container d-flex align-items-center justify-content-between footer-container w-100">
        <div class="row d-flex justify-content-center align-items-stretch w-100">
            <div class="col-md-4 d-flex flex-column align-items-center">
                <h5>Контакты</h5>
                <p><a class="link-dark text-decoration-none" href="tel:{{ $cafe->phone }}">{{ formatPhoneNumber($cafe->phone) }}</a></p>
                <p class="text-decoration-none">8:00 - 23:00</p>
                <p class="text-decoration-none">{{ $cafe->address }}</p>
            </div>
            <div class="col-md-4 d-flex flex-column align-items-center">
                <h5>Социальные сети</h5>
                <p><a class="link-dark text-decoration-none" href="{{ $vkontakteGroup }}">Группа ВКонтакте</a></p>
                <p><a class="link-dark text-decoration-none" href="{{ $viberGroup }}">Группа Viber</a></p>
            </div>
            <div class="col-md-4 d-flex flex-column align-items-center">
                <h5>Дополнительно</h5>
                <p><a class="link-dark text-decoration-none" href="#">Акции</a></p>
                <p><a class="link-dark text-decoration-none" href="{{ route('home.index') }}">Главная</a></p>
            </div>
        </div>
    </div>
</footer>
</body>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-datetime-picker@2.5.11/build/jquery.datetimepicker.full.min.js"></script>

@yield('script')

<script>
    function setItemQuantity(count) {
        $('#item-count-mobile').text(count);
        $('#item-count-desktop').text(count);
    }

    function appendItemQuantity(change) {
        const mobile = $('#item-count-mobile');
        const desktop = $('#item-count-desktop');

        mobile.text(parseInt(mobile.text(), 10) + parseInt(change, 10));
        desktop.text(parseInt(desktop.text(), 10) + parseInt(change, 10));
    }

    $(document).ready(function() {
        $.ajax({
            url: '{{ route('api.cart.total-quantity') }}',
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                setItemQuantity(data);
            },
            error: function(xhr, status, error) {
                console.error('Ошибка получения данных: ', error);
            }
        });
    });
</script>

</html>
