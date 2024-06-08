@php use App\Models\User\Profile; @endphp
@extends('admin.layouts.layout')
@section('style')
    <style>
        .card {
            border-radius: 15px;
        }

        .list-item {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .list-item div {
            flex: 1;
        }

        .list-item div:first-child {
            font-weight: bold;
            flex: 0 0 150px;
        }
    </style>
@endsection
@php
    /**
     * @var Profile $profile
     */
@endphp
@section('content')
    <h1>Детали профиля</h1>
    <div class="container-fluid m-3 px-5 py-4 card">
        <h3>Информация</h3>
        @csrf
        <div class="list-item mt-1 border-bottom">
            <div>ID</div>
            <div class="fs-6">{{ $profile->id }}</div>
        </div>
        <div class="list-item mt-1 border-bottom">
            <div>Имя</div>
            <div class="fs-6">{{ $profile->name }}</div>
        </div>
        <div class="list-item mt-1 border-bottom">
            <div>Адрес</div>
            <div class="fs-6">{{ $profile->address }}</div>
        </div>
        <div class="list-item mt-1 border-bottom">
            <div>Почта</div>
            <div class="fs-6">{{ $profile->identity->email }}</div>
        </div>
        <div class="list-item mt-1 border-bottom">
            <div>Телефон</div>
            <div class="fs-6">{{ $profile->identity->phone }}</div>
        </div>
        <div class="list-item mt-1 border-bottom">
            <div>День рождения</div>
            <div class="fs-6">{{ $profile->birthday }}</div>
        </div>
        <div class="list-item mt-1 border-bottom">
            <div>Количество заказов</div>
            <div class="fs-6">{{ $profile->orders->count() }}</div>
        </div>
        <div class="list-item mt-1 border-bottom">
            <div>Общая сумма заказов</div>
            <div class="fs-6">{{ $profile->orders->sum('after_amount') }}</div>
        </div>
        <div class="list-item mt-1 border-bottom">
            <div>Дата создания</div>
            <div class="fs-6">{{ formatDate($profile->createdAt, true) }}</div>
        </div>
        <div class="list-item mt-1 border-bottom">
            <div>Дата обновления</div>
            <div class="fs-6">{{ formatDate($profile->updatedAt, true) }}</div>
        </div>
    </div>
@endsection
