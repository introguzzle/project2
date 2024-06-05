@php
/**
 * @var string $token
 * @var string $command
 * @var string $botUsername
 */
@endphp
@extends('admin.layouts.layout')
@section('content')
    <h1>Токен аутентификации</h1>

    <div class="alert alert-info">
        <p class="fs-5">Ваш токен для аутентификации:</p>
        <pre class="fs-6">{{ $token }}</pre>
    </div>

    <div class="alert alert-info">
        <p class="fs-5">Введите эту команду в чат для аутентификации:</p>
        <pre class="fs-6">{{ $command }}</pre>
    </div>

    <div class="alert alert-info">
        <p class="fs-5">Перейдите к Telegram-боту, чтобы завершить аутентификацию:</p>
        <a href="{{ telegramBot() }}" class="btn btn-primary">Перейти к боту</a>
    </div>

    <div class="alert alert-info">
        <a href="{{route('admin.dashboard.token.reset')}}" class="btn btn-primary btn-dark">Сбросить токен</a>
    </div>
@endsection
