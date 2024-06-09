@php use App\Models\PaymentMethod;use App\Models\ReceiptMethod;use App\Models\User\Profile; @endphp
@php
    /**
     * @var Profile $profile
     * @var ReceiptMethod[] $receiptMethods
     */
@endphp

@extends('client.v2.nav.nav')
@section('title', 'Оформление заказа')
@section('content')
    <div class="min-height-wrap container py-3 border rounded">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <header class="checkout-header text-center">
                    <h2 class="checkout-title">Оформление заказа</h2>
                </header>
                <form action="{{ route('order') }}" method="POST" class="checkout-form">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Имя</label>
                        <input type="text" id="name" name="name" class="form-control" placeholder="Введите имя" required value="{{ $profile->name }}">
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label">Телефон</label>
                        <input type="text" id="phone" name="phone" class="form-control" placeholder="Ваш номер телефона" required value="{{ $profile->identity->phone }}">
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">Адрес</label>
                        <input type="text" id="address" name="address" class="form-control" placeholder="Ваш адрес" required value="{{ $profile->address }}">
                    </div>

                    <div class="mb-3">
                        <label for="receipt_method" class="form-label">Способ получения</label>
                        <select id="receipt_method" name="receipt_method_id" class="form-select" required>
                            @foreach($receiptMethods as $receiptMethod)
                                <option value="{{ $receiptMethod->id }}">{{ $receiptMethod->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="payment_method" class="form-label">Способ оплаты</label>
                        <select id="payment_method" name="payment_method_id" class="form-select" required>
                        </select>
                    </div>

                    <input type="hidden" name="price" value="{{$price}}">
                    <div class="mb-3">
                        @if (session('internal'))
                            <span class="text-danger">{{ session('internal') }}</span>
                        @endif
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Оформить заказ</button>

                    <div class="mt-3 text-center">
                        <a href="{{ route('home.index') }}" class="text-decoration-none text-dark">Вернуться на главную</a>
                    </div>

                    <div class="mt-3 text-center">
                        <a href="{{ route('cart.index') }}" class="text-decoration-none text-dark">Вернуться в корзину</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            const $rm = $('#receipt_method');

            function loadPaymentMethods() {
                const receiptMethodId = $rm.val();

                $.ajax({
                    url: '{{ route('api.payment-methods') }}',
                    type: 'GET',
                    data: {
                        receipt_method_id: receiptMethodId,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        const paymentMethodSelect = $('#payment_method');
                        paymentMethodSelect.empty();
                        $.each(response, function (key, value) {
                            paymentMethodSelect.append('<option value="' + value.id + '">' + value.name + '</option>');
                        });
                    },
                    error: function (xhr) {
                        console.error(xhr);
                    }
                });
            }

            loadPaymentMethods();

            $rm.on('change', loadPaymentMethods);
        });
    </script>
@endsection

@section('style')
    <style>

    </style>
@endsection
