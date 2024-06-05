@php use App\Models\Order; @endphp
@extends('admin.layouts.layout')

@section('style')

@endsection

@php
    /**
     * @var Order $order
     */
@endphp

@section('content')
    <h2 class="my-3">Детали заказа</h2>
    <table id="this-table" class="table table-bordered table-responsive rounded">
        <thead>
        <tr>
            <th>ID</th>
            <th>Статус</th>
            <th>Сумма</th>
            <th>Кол-во</th>

            <th>Телефон</th>
            <th>Адрес</th>

            <th>Дата создания</th>
            <th>Дата обновления</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>{{ $order->id }}</td>
            <td>{{ $order->status->name }}</td>
            <td>{{ $order->totalAmount }}</td>
            <td>{{ $order->totalQuantity }}</td>

            <td>{{ $order->phone }}</td>
            <td>{{ $order->address }}</td>

            <td>{{ $order->createdAt }}</td>
            <td>{{ $order->updatedAt }}</td>
        </tr>
        </tbody>
    </table>

    <h2 class="my-3">Продукты в заказе</h2>
    <table class="order-details table table-bordered">
        <thead>
        <tr>
            <th>ID</th>
            <th>Название</th>
            <th>Категория</th>
            <th>Цена</th>
            <th>Количество</th>
        </tr>
        </thead>
        <tbody>
        @foreach($order->products as $product)
            <tr>
                <td>{{ $product->id }}</td>
                <td>{{ $product->name }}</td>
                <td>{{ $product->category->name }}</td>
                <td>{{ $product->price }}</td>
                <td>{{ $product->getOrderQuantity($order) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection

@section('script')
@endsection
