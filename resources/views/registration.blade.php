@extends('nav.nav')

@section('content')
    <section id="home">
        <div class="overlay">
            <header id="main-header" class="register-header">
                <h2 class="register-title">Создать аккаунт</h2>
                <form action="" method="POST" class="register-form">
                    @csrf
                    <div class="form-group form-group-2">
                        <label for="name" class="form-label"><i class="fas fa-user"></i></label>
                        <input type="text" id="name" name="name" class="form-input" placeholder="Введите имя" required>
                    </div>
                    <div class="form-group form-group-1 form-group-email">
                        <label for="email" class="form-label"><i class="fas fa-envelope"></i></label>
                        <input type="email" id="email" name="email" class="form-input" placeholder="Введите почту">
                    </div>
                    <div class="form-group form-group-1 form-group-phone" style="display: none">
                        <label for="phone" class="form-label"><i class="fas fa-phone"></i></label>
                        <input type="text" id="phone" name="phone" class="form-input" placeholder="Введите телефон">
                    </div>
                    <span id="login-error" style="color: red; display: block; font-size: 1.2em">
                        @foreach ($errors->all() as $error)
                            <li class="register-error">{{ $error }}</li>
                        @endforeach

                        @if (isset($internal))
                            <li class="register-error">{{ $internal }}</li>
                        @endif
                    </span>

                    <div class="form-group checkbox-group">
                        <input type="checkbox" id="choose-input" onclick="toggleInput()">
                        <label for="choose-input" class="checkbox-custom">Использовать телефон</label>
                    </div>

                    <div class="form-group form-group-2">
                        <label for="password" class="form-label"><i class="fas fa-lock"></i></label>
                        <input type="password" id="password" name="password" class="form-input" placeholder="Введите пароль" required autocomplete="new-password">
                    </div>
                    <div class="form-group form-group-2">
                        <label for="password_confirmation" class="form-label"><i class="fas fa-lock"></i></label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-input" placeholder="Повторите пароль" required autocomplete="new-password">
                    </div>
                    <button type="button" onclick="checkIfLoginExists()" class="btn btn-register">Далее</button>
                    <div class="form-group">
                        <a href="{{ route('login') }}">Уже есть аккаунт?</a>
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

<script>
    function checkIfLoginExists() {
        const phone = document.getElementById('phone').value.trim();
        const email = document.getElementById('email').value.trim();

        const login = phone !== '' ? phone : email;

        fetch(`/auth/check-login-credential?login=${login}`)
            .then(response => response.json())
            .then(data => {
                console.log(data);
                if (data) {
                    showError();
                } else {
                    handleRegister();
                }
            })
            .catch(error => {
                console.error('Ошибка:', error);
            });
    }

    function showError() {
        const loginError = document.getElementById('login-error');
        loginError.textContent = 'Этот логин уже занят';
    }

    function handleRegister() {
        const emailInput = document.getElementById('email');
        const phoneInput = document.getElementById('phone');

        const emailValue = emailInput.value.trim();
        const phoneValue = phoneInput.value.trim();

        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        const isEmailValid = emailPattern.test(emailValue);

        const checkbox = document.getElementById('choose-input');

        if (checkbox.checked) {
            if (!phoneValue) {
                showError();
                return;
            }
        } else {
            if (!isEmailValid) {
                showError();
                return;
            }
        }

        next();
    }

    function toggleInput() {
        const emailInput = document.getElementsByClassName('form-group-email')[0];
        const phoneInput = document.getElementsByClassName('form-group-phone')[0];
        const checkbox = document.getElementById('choose-input');

        if (!checkbox.checked) {
            phoneInput.removeAttribute('required');
            phoneInput.classList.add('fade-in');
            phoneInput.style.display = 'none';

            emailInput.style.display = 'block';
        } else {
            emailInput.removeAttribute('required');
            emailInput.classList.add('fade-in');
            emailInput.style.display = 'none';

            phoneInput.style.display = 'block';
        }
    }

    function next() {
        const registerButton = document.querySelector('.btn-register');
        registerButton.textContent = 'Отправить';

        const formGroup1Elements = document.querySelectorAll('.form-group-1');

        formGroup1Elements.forEach(element => {
            element.classList.add('fade-out');
            element.style.display = 'none';
        });

        const formGroup2Elements = document.querySelectorAll('.form-group-2');

        formGroup2Elements.forEach(element => {
            element.classList.add('fade-in');
            element.style.display = 'block';
        });

        const form = document.querySelector('.register-form');
        form.action = "{{ route('register') }}";

        const btn = document.querySelector('.btn-register');
        btn.type = 'submit';

        const errors = document.querySelectorAll('.register-error');
        const loginError = document.getElementById('login-error');

        errors.forEach(element => {
            element.textContent = '';
            element.style.display = 'none';
        });

        const checkbox1 = document.querySelector('.checkbox-group');
        checkbox1.style.display = 'none';

        const checkbox2 = document.querySelector('.checkbox-custom');
        checkbox2.style.display = 'none';

        const checkbox3 = document.getElementById('choose-input');
        checkbox3.style.display = 'none';

        loginError.textContent = '';
        loginError.style.display = 'none';
    }
</script>

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
