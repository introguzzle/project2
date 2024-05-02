<!DOCTYPE html>
<html lang="en">
<link rel="stylesheet" href="{{ asset('test.css') }}">
<script src="../../js/app.js"></script>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0 maximum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="stylesheet" href="{{ asset('test.css') }}">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/animate.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Merriweather|Oswald|Satisfy&display=swap" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fira+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <title>С огоньком!</title>
</head>

<nav class="mobile-nav main-nav">
    <div class="logo">
        <a href="#">
            <div class="logo-name">
                <a href="{{route('home')}}">
                    <img src="https://i.postimg.cc/mrk7PKtQ/crop-image-online-com-1713747213-64714b6318d9e66dd501-Y1vm-I11-J.png" alt="logo" >
                </a>
                <div class="dostavka-edi-desktop">
                    <a href="#">
                        Доставка еды
                    </a>
                </div>
                <a href="#">
                    <h3></h3>
                </a>
            </div>
        </a>
    </div>
    <div class="brand-logo logo-name">
        <a>
            <h1 class="desktop-text">Всегда вкусно</h1>
            <h1 class="mobile-text">Доставка еды</h1>
        </a>
    </div>

    <div class="main-nav">
        <ul class="nav-links">
            <a href="#menu">
                <li class="btn btn-active">Меню</li>
            </a>
            @if (!\Illuminate\Support\Facades\Auth::check())
                <a href="{{ route('login') }}">
                    <li class="btn">Аккаунт</li>
                </a>
            @else
                <a href="{{ route('profile') }}">
                    <li class="btn">Аккаунт</li>
                </a>
            @endif
            <a href="{{route('cart')}}">
                <li class="btn">
                    <span class="btn-cart">
                        Корзина
                        <span id="cart-count-1" class="cart-count-desktop">0</span>
                    </span>
                </li>
            </a>
            <a href="#home">
                <li class="btn">Блог</li>
            </a>
            <a href="#home">
                <li class="btn">Контакты</li>
            </a>
            <a href="#about">
                <li class="btn">О нас</li>
            </a>
        </ul>
        <hr>
        <ul class="desktop-contacts">
            <li class="contact-info">ул. Ленина, 74</li>
            <li class="contact-info">+79949192939</li>
        </ul>
        <div class="social">
            <a href="#home" class="btn btn-social"><i class="fab fa-facebook-f"></i></a>
            <a href="#home" class="btn btn-social"><i class="fab fa-twitter"></i></a>
            <a href="#home" class="btn btn-social"><i class="fab fa-instagram"></i></a>
            <p>&copy; Copyright 2019</p>
        </div>
    </div>

    <div class="mobile-cart">
        <i class="btn fas fa-shopping-cart"></i>
        <span id="cart-count-2" class="cart-count-hamburger">0</span>
    </div>

    <div class="hamburger">
        <i class="btn fas fa-bars"></i>
    </div>
</nav>

<style>
    .cart-count-mobile-menu {
        font-size: 0.7em;
        background-color: rgb(255, 0, 0, 0.7);
        color: white;
        border-radius: 70%;
        padding: 0.1em 0.4em;
        vertical-align: top;
        line-height: 1.5;
        position: absolute;
        right: -20px;
    }

    .cart-count-hamburger {
        position: absolute;
        top: -8px;
        background-color: rgb(255, 0, 0, 0.7);
        color: white;
        border-radius: 50%;
        padding: 4px 8px; /* Измените размер отступов, чтобы увеличить размер span */
        font-size: 16px; /* Увеличьте размер шрифта */
        transform: scale(1.2); /* Увеличьте размер текста без изменения размера круга */
        transform-origin: center; /* Установите точку преобразования в центр элемента */
        right: auto;
    }

    .cart-count-desktop {
        position: absolute;
        top: -1px;
        background-color: rgb(255, 0, 0, 0.7);
        color: white;
        border-radius: 50%;
        padding: 0 7px;
        font-size: 16px;
        transform: scale(1);
        transform-origin: center;
        right: -15px;
    }

    @media (min-width: 801px) {
        .mobile-cart {
            display: none;
        }
    }

    @media (max-width: 800px) {
        .mobile-cart {
            float: right;
            position: relative;
            cursor: pointer;
            display: inline-block;

            background: #fff;
            color: #111;
            padding: 14px 11px;
            font-size: 1.4rem;
            margin-right: 13px;
            border-radius: 5px;
        }

        .mobile-cart:hover {
            background: #666;
        }

        .mobile-cart:active {
            background: rgb(66, 66, 66);
        }
    }

    .item-count-animation {
        animation: pulse 0.5s;
    }

</style>

<div class="mobile-nav--reveal">
    <ul class="mobile-nav--links">
        <a href="#home">
            <li class="btn btn-reveal btn-active">Меню</li>
        </a>
        <a href="#gallery">
            <li class="btn">
                    <span class="btn btn-reveal btn-cart">
                        Корзина
                        <span id="cart-count-3" class="cart-count-mobile-menu">0</span>
                    </span>
            </li>
        </a>
        <a href="#menu">
            <li class="btn btn-reveal">Акции</li>
        </a>
        <a href="#gallery">
            <li class="btn btn-reveal">Блог</li>
        </a>
        <a href="#home">
            <li class="btn btn-reveal">Контакты</li>
        </a>
        <a href="#home">
            <li class="btn btn-reveal">О нас</li>
        </a>
    </ul>
    <ul class="mobile-contacts">
        <li class="contact-info contact-info-reveal">ул. Ленина, 97</li>
        <li class="contact-info contact-info-reveal">+79949192939</li>
    </ul>
    <div class="social">
        <a href="#home" class="btn btn-social"><i class="fab fa-facebook-f"></i></a>
        <a href="#home" class="btn btn-social"><i class="fab fa-twitter"></i></a>
        <a href="#home" class="btn btn-social"><i class="fab fa-instagram"></i></a>
        <p>&copy; Copyright 2019</p>
    </div>
</div>

<script>
    const hamburger = document.querySelector(".hamburger");
    const mobileNav = document.querySelector(".mobile-nav--reveal");
    const home = document.querySelector("#home");
    const about = document.querySelector("#about");


    let navClosed = true;
    hamburger.addEventListener("click", () => {
        if (navClosed) {
            mobileNav.style.display = "block";
            mobileNav.style.animation = "slideInDown 1s";
            navClosed = false;
        } else {
            mobileNav.style.animation = "slideOutUp 1s";
            setTimeout(() => {
                mobileNav.style.display = "none";
                navClosed = true;
            }, 1000);

        }
    });

    document.addEventListener("DOMContentLoaded", async function() {
        try {
            const cartQuantityResponse = await fetch(`/get/cart-quantity`);
            const cartQuantityData = await cartQuantityResponse.json();
            setTotalQuantity(cartQuantityData);
        } catch (error) {
            console.error('Error fetching cart quantity:', error);
        }
    });

    function changeCount(spanItemCount, count) {
        spanItemCount.textContent = count;
        spanItemCount.classList.add('item-count-animation', 'item-count-color');

        setTimeout(() => {
            spanItemCount.classList.remove('item-count-animation', 'item-count-color');
        }, 500);
    }

    function setTotalQuantity(count) {
        const spanItemCount1 = document.getElementById('cart-count-1');
        const spanItemCount2 = document.getElementById('cart-count-2');
        const spanItemCount3 = document.getElementById('cart-count-3');

        changeCount(spanItemCount1, count);
        changeCount(spanItemCount2, count);
        changeCount(spanItemCount3, count);
    }
</script>

<body>
    @yield('content')
</body>
<footer>
    <div class="footer-container">
        <div class="footer-section">
            <h2>Company</h2>
            <ul>
                <li><a href="#">About Us</a></li>
                <li><a href="#">Contact Us</a></li>
                <li><a href="#">Careers</a></li>
            </ul>
        </div>
        <div class="footer-section">
            <h2>Support</h2>
            <ul>
                <li><a href="#">FAQ</a></li>
                <li><a href="#">Help Center</a></li>
                <li><a href="#">Terms of Service</a></li>
            </ul>
        </div>
        <div class="footer-section">
            <h2>Connect</h2>
            <ul>
                <li><a href="#">Facebook</a></li>
                <li><a href="#">Twitter</a></li>
                <li><a href="#">Instagram</a></li>
            </ul>
        </div>
    </div>
</footer>
</html>
