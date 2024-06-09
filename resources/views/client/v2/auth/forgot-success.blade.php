@php use Illuminate\Support\ViewErrorBag; @endphp
@php
    /**
     * @var ViewErrorBag $errors
     */
@endphp
@extends('client.v2.nav.nav')
@section('title', 'Восстановление')
@section('content')
    <div class="d-flex align-items-center justify-content-center min-height-wrap">
        <div class="bg-white p-4 rounded border text-center">
            <h2 class="forgot-title text-dark mb-4">Восстановление</h2>
            <div class="alert alert-success">
                Письмо было отправлено на вашу почту
            </div>
            <div class="form-group">
                <a href="{{ route('login') }}" class="btn btn-link">Вернуться обратно</a>
            </div>

            <div class="text-center">
                <h3 class="text-dark" style="opacity: 0">Зарегистрироваться через сторонние сервисы</h3>
            </div>
        </div>
    </div>
@endsection

@section('style')
    <style>
    </style>
@endsection
@section('script')
@endsection
