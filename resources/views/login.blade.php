@extends('nav.nav')

@section('content')
    <section id="home">
        <div class="overlay">
            <header id="main-header" class="login-header">
                <h2 class="login-title">Вход</h2>
                <form action="{{ route('login') }}" method="POST" class="login-form">
                    @csrf
                    <div class="form-group">
                        <label for="email" class="form-label"><i class="fas fa-envelope"></i></label>
                        <input type="email" id="email" name="email" class="form-input" placeholder="Введите логин" required>
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label"><i class="fas fa-lock"></i></label>
                        <input type="password" id="password" name="password" class="form-input" placeholder="Введите пароль" required>
                    </div>
                    <span class="login-error">
                    @if (isset($internal))
                            <li>{{$internal}}</li>
                    @endif

                    @if ($errors?->has('login'))
                            <li>{{$errors->first('login')}}</li>
                    @endif

                    </span>
                    <span class="success-message">
                    @if (session()->has('success'))
                        <li>{{session('success')}}</li>
                    @endif
                    </span>

                    <div class="form-group remember-me">
                        <input type="checkbox" id="remember" name="remember">
                        <label for="remember" class="remember-label">Запомнить меня</label>
                    </div>

                    <button type="submit" class="btn btn-login">Войти</button>

                    <div class="form-group">
                        <a href="{{ route('forgot-password') }}">Забыли пароль?</a>
                    </div>
                    <div class="form-group">
                        <a href="{{ route('register') }}">Не зарегистрированы?</a>
                    </div>
                </form>
            </header>
        </div>
    </section>

    <style>
        #home {
            background:
                linear-gradient(
                    rgba(0, 0, 0, 0.7),
                    rgba(0, 0, 0, 0.9)
                ),

                url("https://mebel-blog.ru/wp-content/uploads/2022/08/dizayn-restorana-13-1536x1024.jpg");
            background-size: cover;
        }
    </style>
@endsection

<style>
    .login-error {
        color: red;
        font-size: 1.2em;
        display: block;
    }

    .success-message {
        color: green;
        font-size: 1.2em;
        display: block;
    }

    .login-header {
        background: rgba(0, 0, 0, 0.7);
        border-top-left-radius: 30px;
        border-top-right-radius: 30px;
        padding: 20px;
        align-items: center;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .login-header::after {
        content: '';
        position: absolute;
        bottom: -5px;
        left: 0;
        width: 100%;
        height: 5px;
        background-color: white;
    }

    .login-title {
        color: white;
        font-size: 2.5em;
        margin-bottom: 20px;
        text-transform: uppercase;
        text-shadow: 2px 2px 4px rgba(200, 200, 200, 0.4);
    }

    .login-form {
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

    .form-group {
        margin-bottom: 20px;
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

    .btn-login {
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

    .btn:hover.btn-login:hover {
        background: linear-gradient(to right, #e6342a, #e88f2a);
        color: rgb(200, 200, 200);
        opacity: 0.7;
    }

    .btn:active.btn-login:active {
        background: linear-gradient(to right, #e6342a, #e88f2a);
        color: rgb(0, 0, 0);
    }

    .remember-me {
        display: ruby-text;
        align-items: center;
        margin-bottom: 0;
    }

    .remember-me input[type="checkbox"] {
        border-radius: 50%;
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        width: 1.3em;
        height: 1.3em;
        border: 2px solid #999;
        transition: all 0.3s;
        outline: none;
        cursor: pointer;
        margin-right: 5px;
        vertical-align: middle;
        margin-bottom: 4px;
    }

    .remember-me input[type="checkbox"]:checked {
        background: linear-gradient(to right, #e6342a, #e88f2a);
    }

    .remember-label {
        color: white;
        display: block;
        margin-bottom: 5px;
        text-transform: uppercase;
        font-size: 0.95em;
        vertical-align: middle;
    }

    @media (max-width: 800px) {
        .login-header {
            width: 80vw;
        }

        .login-form {
            max-width: 70vw;
            width: 70vw;
        }

        .login-header {
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

        .btn-login {
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
