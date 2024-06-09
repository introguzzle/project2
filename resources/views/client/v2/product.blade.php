@php use App\Models\Product;use App\Other\Authentication; @endphp
@extends('client.v2.nav.nav')
@php
    /**
     * @var Product $product
     */
@endphp
@section('style')
    <style>
        .animated-button {
            animation: animateButton 0.5s;
        }

        .animated-total-price {
            animation: animateTotalPrice 0.5s;
        }

        @keyframes animateButton {
            0% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.2);
            }
            100% {
                transform: scale(1);
            }
        }

        @keyframes animateTotalPrice {
            0% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.1);
            }
            100% {
                transform: scale(1);
            }
        }

        .animated-quantity {
            animation: animateQuantity 0.5s;
            -moz-animation: animateQuantity 0.5s;
            -webkit-animation: animateQuantity 0.5s;
        }

        @keyframes animateQuantity {
            0% {
                font-size: 1em;
            }
            50% {
                font-size: 1.5em;
            }
            100% {
                font-size: 1em;
            }
        }
    </style>
@endsection
@section('title', $product->name)

@section('content')
    <div class="container py-3">
        <div class="row">
            <div class="col-md-5">
                <img src="{{ $product->getPath() }}" alt="{{ $product->name }}" class="img-fluid" style="max-width: 80%; max-height: 80%">
            </div>
            <div class="col-md-6">
                <h1>{{ $product->name }}</h1>
                <p>{{ $product->shortDescription }}</p>
                <div class="mt-3">
                    <h3>Цена</h3>
                    <p>{{ $product->price }}</p>
                </div>
                <div class="mt-3">
                    <h3>Полное описание</h3>
                    <p>{{ $product->fullDescription }}</p>
                </div>
                <div class="mt-3">
                    <h3>Вес</h3>
                    <p>{{ $product->weight }}</p>
                </div>
                <div class="mt-3">
                    <h3>В корзине</h3>
                    <span id="quantity" class="fs-4">{{ $product->getCartQuantity(Authentication::profile()?->cart) }}</span>
                </div>
                <div class="btn-container d-flex align-items-start flex-column w-100">
                    <button class="btn btn-outline-danger m-2 w-50"
                            data-price="{{ $product->price }}"
                            data-product-id="{{ $product->id }}"
                            data-change="-1"
                            onclick="changeQuantity(this)">Удалить
                    </button>
                    <button class="btn btn-outline-success m-2 w-50"
                            data-price="{{ $product->price }}"
                            data-product-id="{{ $product->id }}"
                            data-change="1"
                            onclick="changeQuantity(this)">Добавить
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        async function changeQuantity(button) {
            const productId = $(button).data('product-id');
            const change = parseInt($(button).data('change'));
            const span = $(`#quantity`);

            const newQuantity = parseInt(span.text(), 10) + change;

            if (newQuantity < 0) {
                return;
            }

            span.text(newQuantity);
            appendItemQuantity(change);

            button.classList.add('animated-button');
            span.addClass('animated-quantity');

            setTimeout(() => {
                button.classList.remove('animated-button');
                span.removeClass('animated-quantity');
            }, 500);

            const url = '{{ route('cart.update-quantity') }}';
            const formData = new FormData();
            formData.append('product_id', productId);
            formData.append('quantity_change', change.toString(10));

            try {
                const response = await fetch(url, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });

                if (!response.ok) {
                    console.log('Ошибка');
                }

            } catch (error) {
                console.error('Ошибка:', error);
            }

            if (newQuantity <= 0) {
                $(`#product-${productId}`).remove();
            }
        }
    </script>
@endsection
