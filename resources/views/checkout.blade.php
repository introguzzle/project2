@extends('nav.nav')

@section('content')
    <section id="home">
        <div class="overlay checkout-overlay">
            <header id="main-header" class="checkout-header">
                <h2 class="checkout-title">Оформление заказа</h2>
                <form action="{{ route('checkout') }}" method="POST" class="checkout-form">
                    @csrf
                    <div class="form-group">
                        <label for="name" class="form-label"><i class="fas fa-user"></i></label>
                        <input type="text" id="name" name="name" class="form-input" placeholder="Ваше имя" required>
                    </div>

                    <div class="form-group">
                        <label for="email" class="form-label"><i class="fas fa-envelope"></i></label>
                        <input type="email" id="email" name="email" class="form-input" placeholder="Ваш email" required>
                    </div>

                    <div class="form-group">
                        <label for="phone" class="form-label"><i class="fas fa-phone"></i></label>
                        <input type="text" id="phone" name="phone" class="form-input" placeholder="Ваш номер телефона" required>
                    </div>

                    <div class="form-group">
                        <label for="address" class="form-label"><i class="fas fa-map-marker-alt"></i></label>
                        <input type="text" id="address" name="address" class="form-input" placeholder="Ваш адрес" required>
                    </div>

                    <button type="submit" class="btn btn-checkout">Оформить заказ</button>

                    <div class="form-group">
                        <a href="{{ route('home') }}">Вернуться на главную</a>
                    </div>
                </form>
            </header>
        </div>
    </section>
@endsection

<style>
    #home .checkout-overlay {
        background-color: rgba(0, 0, 0, 0.8) !important;
    }

    .checkout-header {
        background: rgba(0, 0, 0, 0.7);
        border-top-left-radius: 30px;
        border-top-right-radius: 30px;
        padding: 20px;
        align-items: center;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .checkout-header::after {
        content: '';
        position: absolute;
        bottom: -5px;
        left: 0;
        width: 100%;
        height: 5px;
        background-color: white;
    }

    .checkout-title {
        color: white;
        font-size: 2.5em;
        margin-bottom: 20px;
        text-transform: uppercase;
        text-shadow: 2px 2px 4px rgba(200, 200, 200, 0.4);
    }

    .checkout-form {
        max-width: 35vw;
        width: 35vw;
    }

    .form-group {
        margin-bottom: 20px;
        position: relative;
    }

    .form-group .form-label {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: white;
        font-size: 1.2em;
        padding: 0 10px;
    }

    .form-group .form-input {
        padding-left: 10px;
        font-size: 1em;
    }

    .form-label {
        color: white;
        display: block;
        margin-bottom: 5px;
        text-transform: uppercase;
        font-size: 1.2em;
    }

    .form-input {
        width: 80%;
        max-width: 80%;
        padding: 10px;
        font-size: 0.8em;
        max-height: 200px;
        border-radius: 10px;
    }

    .btn-checkout {
        background: #666;
        color: white;
        padding: 10px 20px;
        border: none;
        cursor: pointer;
        font-size: 1.6em;
        text-transform: uppercase;
        width: 60%;
        max-width: 80%;
        border-radius: 10px;
        margin-top: 20px;
        opacity: 0.7;
    }

    .btn-checkout:hover {
        background: linear-gradient(to right, #e6342a, #e88f2a);
        color: rgb(200, 200, 200);
        opacity: 0.7;
    }

    .btn-checkout:active {
        background: linear-gradient(to right, #e6342a, #e88f2a);
        color: rgb(0, 0, 0);
    }

    .form-group a {
        color: #666666;
        font-size: 1em;
    }

    .form-group a:hover {
        color: #FFFFFF;
    }

    .form-group a:active {
        color: dodgerblue;
    }

    @media (max-width: 800px) {
        .checkout-header {
            width: 80vw;
        }

        .checkout-form {
            max-width: 70vw;
            width: 70vw;
        }

        .checkout-header {
            padding: 30px;
        }

        .form-group .form-label {
            left: -10px;
        }
    }

    @media (max-width: 500px) {
        .form-input {
            font-size: 1em;
        }

        .btn-checkout {
            width: 60%;
            font-size: 1.3em;
        }

        .profile-title {
            font-size: 2em;
        }
    }

    @media (min-height: 1081px) {
        .form-group .form-label {
            left: -5px;
        }
    }
</style>