@php use Illuminate\Support\ViewErrorBag; @endphp
@php
    /**
     * @var ViewErrorBag $errors
     */
@endphp
@extends('client.v2.nav.nav')
@section('title', 'Регистрация')
@section('content')
    <div class="d-flex align-items-center justify-content-center min-height-wrap">
        <div class="bg-white p-4 rounded border">
            <h3 class="login-title text-center text-dark mb-4">Регистрация</h3>
            <form action="{{ route('register') }}" method="POST" class="login-form">
                @csrf
                <div class="form-group mb-3">
                    <label for="name" class="form-label text-dark"><span class="fs-6">Имя</span></label>
                    <input type="text" id="name" name="name" class="form-control" placeholder="Введите имя" required>
                </div>

                <div class="form-group mb-3">
                    <label for="email" class="form-label text-dark"><span class="fs-6">Email</span></label>
                    <input type="email" id="email" name="email" class="form-control" placeholder="Введите email" required>
                </div>

                <div class="form-group mb-3">
                    <label for="password" class="form-label text-dark"><span class="fs-6">Пароль</span></label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="Введите пароль" required>
                </div>

                <div class="form-group mb-3">
                    <label for="password_confirmation" class="form-label text-dark"><span class="fs-6">Подтвердите пароль</span></label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="Подтвердите пароль" required>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session()->has('fail'))
                    <div class="alert alert-danger">{{ session()->get('fail') }}</div>
                @endif

                @if (session()->has('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <button type="submit" class="btn btn-primary w-100 my-3">Зарегистрироваться</button>

                <div class="text-center mb-3">
                    <a href="{{ route('login') }}" class="text-dark text-decoration-none">Уже есть аккаунт?</a>
                </div>

                <div class="text-center">
                    <h3 class="text-dark">Зарегистрироваться через сторонние сервисы</h3>
                    <a href="{{ route('vk.auth') }}" class="btn btn-lg btn-social btn-vkontakte social-login-btn">
                        <img src="{{ asset('storage/images/social-vkontakte.svg') }}" alt="VKontakte">
                    </a>
                    <a href="{{ route('google.auth') }}" class="btn btn-lg btn-social btn-google social-login-btn">
                        <img src="{{ asset('storage/images/social-google.svg') }}" alt="Google">
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('style')
    <style>
        .login-title {
            color: black;
            font-size: 2.5em;
        }

        .login-form {
            max-width: 100%;
        }

        .form-label {
            color: black;
            margin-bottom: 0.5rem;
            font-size: 1.2em;
        }

        .btn-social {
            margin-right: 5px;
        }
    </style>
@endsection
