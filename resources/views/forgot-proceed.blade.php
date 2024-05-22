@extends('nav.nav')

@section('content')
    <section id="home">
        <div class="overlay">
            <header id="main-header" class="reset-header">
                <h2 class="reset-title">Сброс пароля</h2>
                <form action="{{ route('password.reset.post') }}" method="POST" class="reset-form">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">
                    <div class="form-group">
                        <label for="password" class="form-label"><i class="fas fa-lock"></i></label>
                        <input type="password" id="password" name="password" class="form-input" placeholder="Введите новый пароль" required>
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation" class="form-label"><i class="fas fa-lock"></i></label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-input" placeholder="Подтвердите новый пароль" required>
                    </div>
                    <span class="reset-error">
                    @if (isset($internal))
                            <li>{{$internal}}</li>
                        @endif

                        @foreach($errors->all() as $error)
                            <li>{{$error}}</li>
                        @endforeach

                    </span>
                    <span class="success-message">
                    @if (session()->has('success'))
                            <li>{{session('success')}}</li>
                        @endif
                    </span>

                    <button type="submit" class="btn btn-reset">Отправить</button>

                    <input type="hidden" value="{{session()->get('token')}}">
                    <div class="form-group">
                        <a href="{{ route('login') }}">Вернуться к входу</a>
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

        .reset-error {
            color: red;
            font-size: 1.2em;
            display: block;
        }
    </style>
@endsection

<style>
    .reset-header {
        background: rgba(0, 0, 0, 0.7);
        border-top-left-radius: 30px;
        border-top-right-radius: 30px;
        padding: 20px;
        align-items: center;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .reset-header::after {
        content: '';
        position: absolute;
        bottom: -5px;
        left: 0;
        width: 100%;
        height: 5px;
        background-color: white;
    }

    .reset-title {
        color: white;
        font-size: 2.5em;
        margin-bottom: 20px;
        text-transform: uppercase;
        text-shadow: 2px 2px 4px rgba(200, 200, 200, 0.4);
    }

    .reset-form {
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

    .btn-reset {
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

    .btn:hover.btn-reset:hover {
        background: linear-gradient(to right, #e6342a, #e88f2a);
        color: rgb(200, 200, 200);
        opacity: 0.7;
    }

    .btn:active.btn-reset:active {
        background: linear-gradient(to right, #e6342a, #e88f2a);
        color: rgb(0, 0, 0);
    }

    @media (max-width: 800px) {
        .reset-header {
            width: 80vw;
        }

        .reset-form {
            max-width: 70vw;
            width: 70vw;
        }

        .reset-header {
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

        .btn-reset {
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

