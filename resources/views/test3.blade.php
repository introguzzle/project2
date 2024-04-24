<!DOCTYPE html>
<html lang="en">

<link rel="stylesheet" href="{{ asset('test.css') }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

<body>
<nav class="mobile-nav main-nav">
    <div class="logo">
        <a href="#">
            <div class="logo-name">
                <a href="#home">
                    <img src="https://i.postimg.cc/mrk7PKtQ/crop-image-online-com-1713747213-64714b6318d9e66dd501-Y1vm-I11-J.png" alt="logo" >
                </a>
                <div class="dostavka-edi-desktop">
                    <a href="#">
                        Доставка еды
                    </a>
                </div>
                <a href="#home">
                    <h3></h3>
                </a>
            </div>
        </a>
    </div>
    <div class="brand-logo logo-name">
        <a><h1>Всегда вкусно</h1></a>
    </div>

    <div class="main-nav">
        <ul class="nav-links">
            <a href="#menu">
                <li class="btn btn-active">Меню</li>
            </a>
            <a href="#gallery">
                <li class="btn">Галерея</li>
            </a>
            <a href="#home">
                <li class="btn">Акции</li>
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
                <li class="contact-info">ул. Ленина, 97</li>
                <li class="contact-info">+79949192939</li>
        </ul>
        <div class="social">
            <a href="#home" class="btn btn-social"><i class="fab fa-facebook-f"></i></a>
            <a href="#home" class="btn btn-social"><i class="fab fa-twitter"></i></a>
            <a href="#home" class="btn btn-social"><i class="fab fa-instagram"></i></a>
            <p>&copy; Copyright 2019</p>
        </div>
    </div>

    <i class="hamburger btn fas fa-bars"></i>
</nav>

<div class="mobile-nav--reveal">
    <ul class="mobile-nav--links">
        <a href="#home">
            <li class="btn btn-active">Меню</li>
        </a>
        <a href="#about">
            <li class="btn">Галерея</li>
        </a>
        <a href="#menu">
            <li class="btn">Акции</li>
        </a>
        <a href="#gallery">
            <li class="btn">Блог</li>
        </a>
        <a href="#home">
            <li class="btn">Контакты</li>
        </a>
        <a href="#home">
            <li class="btn">О нас</li>
        </a>
    </ul>
    <ul class="mobile-contacts">
        <li class="contact-info">ул. Ленина, 97</li>
        <li class="contact-info">+79949192939</li>
    </ul>
    <div class="social">
        <a href="#home" class="btn btn-social"><i class="fab fa-facebook-f"></i></a>
        <a href="#home" class="btn btn-social"><i class="fab fa-twitter"></i></a>
        <a href="#home" class="btn btn-social"><i class="fab fa-instagram"></i></a>
        <p>&copy; Copyright 2019</p>
    </div>
</div>

<section id="home">
    <div class="overlay">
        <header id="main-header">
            <h3 class="subtitle-script">Cafe & Restaraunt</h3>
            <h1 class="subtitle-primary">Welcome!</h1>
            <h3 class="subtitle-secondary">The Best Coffee Around</h3>
            <a href="#menu" class="btn btn-home btn-cta">Меню</a>
            <a href="#home" class="btn btn-home btn-other">Забронировать</a>
        </header>
    </div>
</section>

<section id="about">
    <header class="section-header">
        <h4 class="subtitle-script">Welcome</h4>
        <h1 class="subtitle-primary--alt">About Us</h1>
        <h3 class="subtitle-secondary--alt">Cafe & Restaraunt</h3>
    </header>
    <hr>
    <p class="text-primary">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Stress, for the United States
        element ante. Duis
        cursus, mi quis viverra ornare, eros pain, sometimes none at all, freedom of the living creature was as the
        profit and financial security. Jasmine neck adapter and just running it lorem makeup hairstyle. Now sad
        smile of the television set.</p>
</section>
<!-- Services -->
<section id="services">
    <div class="overlay">
        <header class="section-header">
            <h1 class="subtitle-primary">Did You Know?</h1>
            <h3 class="subtitle-secondary--alt">About Our Restaraunt</h3>
        </header>
        <div class="flex-wrapper">
            <div class="service-container">
                <div class="service-container--icon">
                    <i class="fas fa-coffee"></i>
                </div>
                <h4 class="service-container--text">Famous for <br> Our Coffee</h4>
            </div>
            <div class="service-container">
                <div class="service-container--icon">
                    <i class="fas fa-mobile-alt"></i>
                </div>
                <h4 class="service-container--text">Phone <br> Reservations</h4>
            </div>
            <div class="service-container">
                <div class="service-container--icon">
                    <i class="far fa-clock"></i>
                </div>
                <h4 class="service-container--text">Open Everyday <br> 08:00 - 01:00</h4>
            </div>
            <div class="service-container">
                <div class="service-container--icon">
                    <i class="fas fa-map-signs"></i>
                </div>
                <h4 class="service-container--text">Located in <br> City Center</h4>
            </div>
        </div>
    </div>
</section>
<!-- Menu -->




<section id="menu">
    <header class="section-header">
        <h1 class="subtitle-primary--alt">Our Menu</h1>
        <h3 class="subtitle-secondary--alt">These are our specials:</h3>
    </header>

    <div class="flex-wrapper flex-center">
        <a href="#menu" class="btn btn-active btn-menu">Бургеры</a>
        <a href="#menu" class="btn btn-menu btn-inactive">Сэндвичи</a>
        <a href="#menu" class="btn btn-menu btn-inactive">Супы</a>
        <a href="#menu" class="btn btn-menu btn-inactive">Пицца</a>
        <a href="#menu" class="btn btn-menu btn-inactive">Категория</a>
        <a href="#menu" class="btn btn-menu btn-inactive">Категория</a>
        <a href="#menu" class="btn btn-menu btn-inactive">Категория</a>
        <a href="#menu" class="btn btn-menu btn-inactive">Категория</a>
        <a href="#menu" class="btn btn-menu btn-inactive">Категория</a>
        <a href="#menu" class="btn btn-menu btn-inactive">Категория</a>
    </div>

    <div class="menu-wrapper">

        <div class="grid-container">
            <div class="grid-item">
                <img src="https://media.dodostatic.net/image/r:584x584/11EE7D61BB2BD856BD5DFD71FB7D4210.avif" alt="Товар 1">
                <h3>Товар 1</h3>
                <p>Описание товара 1</p>
                <div class="item-info">
                    <p class="item-price">700 руб.</p>
                    <button class="order-btn">Выбрать</button>
                </div>
            </div>
            <div class="grid-item">
                <img src="https://media.dodostatic.net/image/r:584x584/11EE7D610D2925109AB2E1C92CC5383C.avif" alt="Товар 1">
                <h3>Товар 2</h3>
                <p>Описание товара 2</p>
                <div class="item-info">
                    <p class="item-price">700 руб.</p>
                    <button class="order-btn">Выбрать</button>
                </div>
            </div>
            <div class="grid-item">
                <img src="https://media.dodostatic.net/image/r:584x584/11EE7D612FC7B7FCA5BE822752BEE1E5.avif" alt="Товар 1">
                <h3>Товар 3</h3>
                <p>Описание товара 3</p>
                <div class="item-info">
                    <p class="item-price">700 руб.</p>
                    <button class="order-btn">Выбрать</button>
                </div>
            </div>
            <div class="grid-item">
                <img src="https://media.dodostatic.net/image/r:584x584/11EE7D614CBE0530B7234B6D7A6E5F8E.avif" alt="Товар 1">
                <h3>Товар 4</h3>
                <p>Описание товара 4</p>
                <div class="item-info">
                    <p class="item-price">700 руб.</p>
                    <button class="order-btn">Выбрать</button>
                </div>
            </div>
            <div class="grid-item">
                <img src="https://media.dodostatic.net/image/r:584x584/11EE7D60FDA22358AC33C6A44EB093A2.avif" alt="Товар 1">
                <h3>Товар 5</h3>
                <p>Описание товара 5</p>
                <div class="item-info">
                    <p class="item-price">700 руб.</p>
                    <button class="order-btn">Выбрать</button>
                </div>
            </div>
            <div class="grid-item">
                <img src="https://media.dodostatic.net/image/r:584x584/11EE7D61BB2BD856BD5DFD71FB7D4210.avif" alt="Товар 1">
                <h3>Товар 6</h3>
                <p>Описание товара 6</p>
                <div class="item-info">
                    <p class="item-price">700 руб.</p>
                    <button class="order-btn">Выбрать</button>
                </div>
            </div>
            <div class="grid-item">
                <img src="https://media.dodostatic.net/image/r:584x584/11EE7D61BB2BD856BD5DFD71FB7D4210.avif" alt="Товар 1">
                <h3>Товар 7</h3>
                <p>Описание товара 7</p>
                <div class="item-info">
                    <p class="item-price">700 руб.</p>
                    <button class="order-btn">Выбрать</button>
                </div>
            </div>
            <div class="grid-item">
                <img src="https://media.dodostatic.net/image/r:584x584/11EE7D61BB2BD856BD5DFD71FB7D4210.avif" alt="Товар 1">
                <h3>Товар 8</h3>
                <p>Описание товара 8</p>
                <div class="item-info">
                    <p class="item-price">700 руб.</p>
                    <button class="order-btn">Выбрать</button>
                </div>
            </div>
            <div class="grid-item">
                <img src="https://media.dodostatic.net/image/r:584x584/11EE7D61BB2BD856BD5DFD71FB7D4210.avif" alt="Товар 1">
                <h3>Товар 9</h3>
                <p>Описание товара 9</p>
                <div class="item-info">
                    <p class="item-price">700 руб.</p>
                    <button class="order-btn">Выбрать</button>
                </div>
            </div>
            <div class="grid-item">
                <img src="https://media.dodostatic.net/image/r:584x584/11EE7D61BB2BD856BD5DFD71FB7D4210.avif" alt="Товар 1">
                <h3>Товар 10</h3>
                <p>Описание товара 10</p>
                <div class="item-info">
                    <p class="item-price">700 руб.</p>
                    <button class="order-btn">Выбрать</button>
                </div>
            </div>
            <div class="grid-item">
                <img src="https://media.dodostatic.net/image/r:584x584/11EE7D61BB2BD856BD5DFD71FB7D4210.avif" alt="Товар 1">
                <h3>Товар 11</h3>
                <p>Описание товара 11</p>
                <div class="item-info">
                    <p class="item-price">700 руб.</p>
                    <button class="order-btn">Выбрать</button>
                </div>
            </div>
        </div>


    </div>
</section>

<section id="gallery">
    <div class="desktop-gallery">
        <header class="section-header">
            <h4 class="subtitle-script">Authentic</h4>
            <h1 class="subtitle-primary--alt">Tasteful</h1>
            <h3 class="subtitle-secondary--alt">Cafe & Restaraunt</h3>
            <p class="text-primary">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse varius enim in
                eros
                elementum tristique. Duis cursus, mi quis viverra ornare, eros dolor.</p>
        </header>
        <div class="photo-gallery">
            <img src="https://gas-kvas.com/grafic/uploads/posts/2023-09/1695925240_gas-kvas-com-p-kartinki-burger-11.jpg" alt=""
                 class="gallery-photo">
            <img src="https://foxboar.ru/wp-content/uploads/2021/09/headerImage.jpg"
                 alt="" class="gallery-photo">
            <img src="https://intermountainhealthcare.org/-/media/images/modules/blog/posts/2015/health-benefits-drinking-coffee.jpg?la=en&h=461&w=700&mw=896&hash=24FC7736F38D6ADAC480DCB45239DD5F92CE6679"
                 alt="" class="gallery-photo">
            <img src="https://s3.amazonaws.com/grazecart/naturesrootsfarm/images/1486599107_589bb3c32b4ed.jpg"
                 alt="" class="gallery-photo">
        </div>
    </div>
</section>

<script src="js/app.js"></script>
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

<script>
    const hamburger = document.querySelector(".hamburger");
    const mobileNav = document.querySelector(".mobile-nav--reveal");
    const home = document.querySelector("#home");
    const about = document.querySelector("#about");


    let navClosed = true;
    hamburger.addEventListener("click", () => {
        console.log("clicked");
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
</script>
