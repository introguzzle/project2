<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Кафе</title>
    <style>
        /* Стили для header */
        header {
            background-color: rgb(0, 0, 0);
            color: white;
            padding: 20px;
            text-align: center;
        }

        /* Стили для footer */
        footer {
            background-color: rgb(0, 0, 0);
            color: white;
            padding: 10px 0;
            text-align: center;
            position: page;
            left: 0;
            bottom: 0;
            width: 100%;
        }
    </style>

    <link rel="icon" type="image/png" href="https://clipart-library.com/images/di4oxapjT.png">
    <style>
        *, *:before, *:after {
            -moz-box-sizing: border-box;
            -webkit-box-sizing: border-box;
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            background: #bdc3c7;
            line-height: 1.5;
            font-family: sans-serif;
            text-transform: uppercase;
            font-size: 18px;
            color: #fff;
        }
        a {
            text-decoration: none;
            color: #ffffff;
        }
        #header {
            background: #000000;
            height: fit-content;
            width: 100%;
            position: relative;
        }
        #header:after {
            content: "";
            clear: both;
            display: block;
        }
        .search {
            float: right;
            padding: 30px;
        }
        input {
            border: none;
            padding: 10px;
            border-radius: 20px;
        }
        .logo {
            float: left;
            padding: 26px 0 26px;
        }
        .logo a {
            font-size: 28px;
            display: block;
            padding: 0 0 0 20px;
        }

        .logo img {
            height: 40px;
            width: 90px;
        }
        nav {
            float: right;
        }
        nav > ul {
            float: left;
            position: relative;
        }
        nav li {
            list-style: none;
            float: left;
        }
        nav .dropdown {
            position: relative;
        }
        nav li a {
            width: 100%;
            float: left;
            padding: 35px;
        }
        nav li a:hover {
            width: 100%;
            background: rgb(255,133,35);
        }
        nav li ul {
            display: none;
        }
        nav li:hover ul {
            display: inline;
        }
        nav li li {
            width: 100%;
            float: none;
        }
        nav .dropdown ul {
            position: absolute;
            left: 0;
            top: 100%;
            width: 100%;
            max-width: 100%;
            background: #fff;
            padding: 20px 0;
            border-bottom: 3px solid #ffffff;
        }
        nav .dropdown li {
            white-space: nowrap;
        }
        nav .dropdown li a {
            padding: 10px 35px;
            font-size: 14px;
            max-width: 100%
        }

        nav li li a {
            float: none;
            color: #333;
            display: block;
            padding: 8px 10px;
            border-radius: 3px;
            font-size: 14px;
        }

        nav li li a:hover {
            background: #ffffff;
        }

        #menu-icon span {
            border: 2px solid #fff;
            width: 30px;
            margin-bottom: 5px;
            display: block;
            -webkit-transition: all .2s;
            transition: all .1s;
        }
        @media only screen and (max-width: 1170px) {
            nav > ul > li > a {
                padding: 35px 15px;
            }
        }
        @media only screen and (min-width: 960px) {
            nav {
                display: block!important;
            }
        }
        @media only screen and (max-width: 959px) {
            nav {
                display: none;
                width: 100%;
                clear: both;
                float: none;
                max-height: 400px;
                overflow-y: scroll;
            }

            .search {
                float: none;
            }
            .search input {
                width: 100%;
            }
            nav {
                padding: 10px;
            }
            nav ul {
                float: none;
            }
            nav li {
                float: none;
            }
            nav ul li a {
                float: none;
                padding: 8px;
                display: block;
            }
            #header nav ul ul {
                display: block;
                position: static;
                background: none;
                border: none;
                padding: 0;
            }
            #header nav a {
                color: #fff;
                padding: 8px;
            }
            #header nav a:hover {
                background: #fff;
                color: #333;
                border-radius: 3px;
            }
            #header nav ul li li a:before {
                content: "- ";
            }
        }
    </style>

</head>
<div id="header">
    <div class="logo">
        <a>
            С огоньком
        </a>
    </div>
    <nav>
        <form class="search" action="">
            <label>
                <input name="q" placeholder="Search..." type="search">
            </label>
        </form>
        <ul>
            <li><a href="/cart">Cart</a></li>
            <li><a href="/catalog">Catalog</a></li>

            <li class="dropdown">
                <a href="">Contact</a>
                <ul>
                    <li><a href="#">About Version</a></li>
                    <li><a href="#">Contact Us</a></li>
                </ul>
            </li>

            <form id="logoutForm" method="POST" action="/logout" style="display: none;">
                <!-- Поле для токена CSRF, если это необходимо -->
                <!-- <input type="hidden" name="_token" value="значение_токена"> -->
            </form>

            <li class="dropdown">
                <a href="/profile" id="profile-link">Profile</a>
                <ul>
                    <li><a href="/login">Login </a> </li>
                    <li><a href="/register">Register </a></li>
                    <li><a href="/login" onclick="logout()">Logout</a></li>
                </ul>
            </li>
        </ul>
    </nav>
</div>

<style>
    .biglogo img {
        width: 50%;
        height: 50%;
    }

    .body {
        background-color: #1a202c;
    }

    header {
        background-color: #1a202c;
    }

    .grid-container {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 20px;
        padding: 20px;
    }

    .grid-item {
        background-color: #fff;
        padding: 20px;
        text-align: center;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .grid-item img {
        max-width: 100%;
        height: auto;
    }
</style>

<header class="header2">
    <div class="biglogo">
        <img src="https://i.postimg.cc/mrk7PKtQ/crop-image-online-com-1713747213-64714b6318d9e66dd501-Y1vm-I11-J.png" alt="logo">
    </div>
    <h1>Добро пожаловать в кафе "Наше место"</h1>
    <p>Лучшее место для вашего отдыха и встреч с друзьями</p>
</header>

<body class="body">
<h2>Меню</h2>
<div class="grid-container">
    <div class="grid-item">
        <img src="https://via.placeholder.com/150" alt="Товар 1">
        <h3>Товар 1</h3>
        <p>Описание товара 1</p>
    </div>
    <div class="grid-item">
        <img src="https://via.placeholder.com/150" alt="Товар 2">
        <h3>Товар 2</h3>
        <p>Описание товара 2</p>
    </div>
    <div class="grid-item">
        <img src="https://via.placeholder.com/150" alt="Товар 3">
        <h3>Товар 3</h3>
        <p>Описание товара 3</p>
    </div>
    <div class="grid-item">
        <img src="https://via.placeholder.com/150" alt="Товар 4">
        <h3>Товар 4</h3>
        <p>Описание товара 4</p>
    </div>
    <div class="grid-item">
        <img src="https://via.placeholder.com/150" alt="Товар 4">
        <h3>Товар 4</h3>
        <p>Описание товара 4</p>
    </div>
    <div class="grid-item">
        <img src="https://via.placeholder.com/150" alt="Товар 4">
        <h3>Товар 4</h3>
        <p>Описание товара 4</p>
    </div>
    <div class="grid-item">
        <img src="https://via.placeholder.com/150" alt="Товар 4">
        <h3>Товар 4</h3>
        <p>Описание товара 4</p>
    </div>
    <div class="grid-item">
        <img src="https://via.placeholder.com/150" alt="Товар 4">
        <h3>Товар 4</h3>
        <p>Описание товара 4</p>
    </div>
    <div class="grid-item">
        <img src="https://via.placeholder.com/150" alt="Товар 4">
        <h3>Товар 4</h3>
        <p>Описание товара 4</p>
    </div>
    <div class="grid-item">
        <img src="https://via.placeholder.com/150" alt="Товар 4">
        <h3>Товар 4</h3>
        <p>Описание товара 4</p>
    </div>
    <div class="grid-item">
        <img src="https://via.placeholder.com/150" alt="Товар 4">
        <h3>Товар 4</h3>
        <p>Описание товара 4</p>
    </div>
</div>
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

<style>
    footer {
        background-color: #ffffff;
        color: #000;
        padding: 30px 0;
        text-align: center;
    }

    .footer-container {
        display: flex;
        justify-content: space-around;
    }

    .footer-section {
        flex: 1;
        margin: 0 20px;
    }

    .footer-section h2 {
        margin-bottom: 10px;
    }

    .footer-section ul {
        list-style-type: none;
        padding: 0;
    }

    .footer-section ul li {
        margin-bottom: 5px;
    }

    .footer-section ul li a {
        color: #000;
        text-decoration: none;
    }

    .footer-section ul li a:hover {
        color: #000;
    }
</style>
