@extends('nav.nav')

@section('content')
    <section id="home">
        <div class="overlay">
            <header id="main-header" class="register-header">
                <h2 class="register-title">Восстановление</h2>
                <form action="{{route('forget.post')}}" method="POST" class="register-form">
                    @csrf
                    <div class="form-group form-group-1 form-group-email">
                        <label for="login" class="form-label"><i id="label-icon" class="fas fa-envelope"></i></label>
                        <input type="email" id="login" name="login" class="form-input" placeholder="Введите почту" required>
                    </div>

                    <div class="form-group checkbox-group">
                        <input type="checkbox" id="choose-input" onclick="toggleInput()">
                        <label for="choose-input" class="checkbox-custom">
                            <span id="checkbox-text">Использовать телефон</span>
                        </label>
                    </div>
                    <button type="submit" class="btn btn-register">Далее</button>
                    <div class="form-group">
                        <a href="{{ route('login') }}">Вернуться обратно</a>
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

    <script>
        let s = true;

        function toggleInput() {
            const labelIcon = document.getElementById('label-icon');
            const login = document.getElementById('login');

            labelIcon.classList.remove('fade-in');
            login.classList.remove('fade-in');

            if (s) {
                labelIcon.className = 'fas fa-phone';
                login.type = 'tel';
                login.placeholder = 'Введите телефон';
            } else {
                labelIcon.className = 'fas fa-envelope';
                login.type = 'email';
                login.placeholder = 'Введите почту';
            }

            void labelIcon.offsetWidth;

            labelIcon.classList.add('fade-in');
            login.classList.add('fade-in');

            s = !s;
        }
    </script>
@endsection

<style>
    .fade-in {
        animation: fadeIn ease 0.5s;
    }

    @keyframes fadeIn {
        0% { opacity: 0; }
        100% { opacity: 1; }
    }

    .fade-out {
        animation: fadeOut ease 0.5s;
    }

    @keyframes fadeOut {
        0% { opacity: 1; }
        100% { opacity: 0; }
    }

    .form-group-1 {
        display: block;
    }

    .form-group-2 {
        display: none;
    }

    .register-header {
        background: rgba(0, 0, 0, 0.7);
        border-top-left-radius: 30px;
        border-top-right-radius: 30px;
        padding: 30px;
        align-items: center;
        display: flex;
        flex-direction: column;
        justify-content: center;
        margin: 0 auto;
    }

    .register-title {
        color: white;
        font-size: 2em;
        margin-bottom: 20px;
        text-transform: uppercase;
        text-shadow: 2px 2px 4px rgba(200, 200, 200, 0.4);
        position: relative;
        display: inline-block;
    }

    .register-header::after {
        content: '';
        position: absolute;
        bottom: -5px;
        left: 0;
        width: 100%;
        height: 5px;
        background-color: white;
    }

    .register-form {
        max-width: 35vw;
        width: 35vw;
    }

    .form-group {
        margin-bottom: 20px;
        position: relative;
    }

    .form-group .form-label {
        position: absolute;
        left: 50px;
        top: 50%;
        transform: translateY(-50%);
        color: white;
        font-size: 1.2em;
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
        width: 70%;
        max-width: 70%;
        padding: 10px;
        font-size: 1em;
        max-height: 200px;
        border-radius: 10px;
    }

    .btn-register {
        background: #666;
        color: white;
        padding: 10px 20px;
        border: none;
        cursor: pointer;
        font-size: 1.6em;
        text-transform: uppercase;
        width: 50%;
        max-width: 50%;
        border-radius: 10px;
        margin-top: 20px;
        opacity: 0.7;
    }

    .btn:hover.btn-register:hover {
        background: linear-gradient(to right, #e6342a, #e88f2a);
        color: rgb(200, 200, 200);
        opacity: 0.7;
    }

    .btn:active.btn-register:active {
        background: linear-gradient(to right, #e6342a, #e88f2a);
        color: rgb(0, 0, 0);
    }

    @media (max-width: 767px) {
        .register-form {
            width: 120%;
            max-width: 120%;
        }

        .form-group .form-label {
            left: 15px;
        }

        .register-header {
            width: 80%;
        }

        .register-header {
            padding: 30px;
        }
    }

    @media (max-width: 500px) {
        .form-input {
            font-size: 1em;
        }

        .btn-register {
            font-size: 1.3em;
        }

        .register-title {
            font-size: 2em;
        }
    }

    @media (min-width: 768px) and (max-width: 1025px) {
        .form-group .form-label {
            left: 20px;
        }
    }

    .checkbox-group {
        display: ruby-text;
        align-items: center;
        margin-bottom: 0;
    }

    .checkbox-group input[type="checkbox"] {
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

    .checkbox-group input[type="checkbox"]:checked {
        background: linear-gradient(to right, #e6342a, #e88f2a);
    }

    .checkbox-custom {
        color: white;
        display: block;
        margin-bottom: 5px;
        text-transform: uppercase;
        font-size: 0.95em;
        vertical-align: middle;
    }

</style>
