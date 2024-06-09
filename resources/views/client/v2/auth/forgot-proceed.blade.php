@php use Illuminate\Support\ViewErrorBag; @endphp
@php
    /**
     * @var ViewErrorBag $errors
     */
@endphp
@extends('client.v2.nav.nav')
@section('title', 'Сброс пароля')
@section('content')
    <div class="d-flex align-items-center justify-content-center min-height-wrap">
        <div class="bg-white p-4 rounded border">
            <h3 class="reset-title text-center text-dark mb-4">Сброс пароля</h3>
            <form action="{{ route('password.reset') }}" method="POST" class="reset-form">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <div class="form-group mb-3">
                    <label for="password" class="form-label text-dark"><span class="fs-6">Новый пароль</span></label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="Введите новый пароль" required>
                </div>

                <div class="form-group mb-3">
                    <label for="password_confirmation" class="form-label text-dark"><span class="fs-6">Подтвердите новый пароль</span></label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="Подтвердите новый пароль" required>
                </div>

                @if (isset($internal))
                    <div class="alert alert-danger mb-3">{{ $internal }}</div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger mb-3">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session()->has('success'))
                    <div class="alert alert-success mb-3">{{ session('success') }}</div>
                @endif

                <button type="submit" class="btn btn-primary w-100 my-3">Отправить</button>

                <div class="text-center">
                    <a href="{{ route('login') }}" class="text-dark text-decoration-none">Вернуться к входу</a>
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
        .reset-title {
            color: black;
            font-size: 2.5em;
        }
    </style>
@endsection

@section('script')
@endsection
