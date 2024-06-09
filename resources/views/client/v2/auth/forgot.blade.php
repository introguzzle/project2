@php use Illuminate\Support\ViewErrorBag; @endphp
@php
    /**
     * @var ViewErrorBag $errors
     */
@endphp
@extends('client.v2.nav.nav')
@section('title', 'Восстановление')
@section('content')
    <div class="overlay d-flex align-items-center justify-content-center min-height-wrap">
        <div class="bg-white p-4 rounded border">
            <h3 class="login-title text-center text-dark">Восстановление</h3>
            <form action="{{ route('forgot-password') }}" method="POST" class="login-form">
                @csrf
                <div class="form-group mb-3">
                    <label for="login" class="form-label text-dark"><span class="fs-6">Телефон или почта</span></label>
                    <input type="email" id="login" name="login" class="form-control" placeholder="Введите почту" required>
                </div>

                @if (session()->has('fail'))
                    <div class="alert alert-danger">{{ session()->get('fail') }}</div>
                @endif

                @if ($errors->has('login'))
                    <div class="alert alert-danger">{{ $errors->first('login') }}</div>
                @endif

                <button type="submit" class="btn btn-primary w-100 my-3">Далее</button>

                <div class="text-center mb-3">
                    <a href="{{ route('login.index') }}" class="text-dark text-decoration-none">Вернуться обратно</a>
                </div>

                <div class="text-center">
                    <h3 class="text-dark" style="opacity: 0">Зарегистрироваться через сторонние сервисы</h3>
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
    </style>
@endsection

@section('script')
@endsection
