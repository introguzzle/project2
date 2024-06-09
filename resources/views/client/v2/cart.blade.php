@php use App\Models\Product;use App\Other\Authentication;use Illuminate\Database\Eloquent\Collection; @endphp
@php
    /**
     * @var Collection<Product> $products
     */
@endphp
@extends('client.v2.nav.nav')
@section('title', 'Корзина')
@section('content')
    <div class="min-height-wrap">
        <h2 class="py-2 my-2 cart-title">Корзина</h2>
        <div class="cart-container container">
            <div class="cart-items">
                @foreach($products as $product)
                @php($id = $product->id)
                <div id="product-{{$id}}" class="card mb-3 w-100">
                    <div class="card-body d-flex justify-content-between align-items-center w-100">
                        <div class="w-100">
                            <h4><a href="{{ route('product.index', ['id' => $product->id]) }}" class="card-title product-title m-2 text-wrap w-100 text-decoration-none">{{ $product->name }}</a></h4>
                            <p class="card-text m-2 w-50"> {{ $product->shortDescription }} </p>
                            <p class="card-text m-2">Цена: {{ $product->price }}</p>
                            <div class="d-flex align-items-center">
                                <button class="btn btn-group-sm btn-outline-danger m-2"
                                        data-price="{{ $product->price }}"
                                        data-product-id="{{ $id }}"
                                        data-change="-1"
                                        onclick="changeQuantity(this)">-
                                </button>
                                <span id="quantity-{{$id}}" class="fs-5 p-1">{{ $product->getCartQuantity(Authentication::profile()->cart) }}</span>
                                <button class="btn btn-group-sm btn-outline-success m-2"
                                        data-price="{{ $product->price }}"
                                        data-product-id="{{ $id }}"
                                        data-change="1"
                                        onclick="changeQuantity(this)">+
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @if ($products->isNotEmpty())
                <div class="card-body my-3">
                    <p id="total-price" class="h5">Итого: {{ $price }}</p>
                    <form action="{{ route('order.index') }}" method="GET">
                        <button type="submit" class="btn btn-primary">Перейти к оформлению</button>
                    </form>
                </div>
            @else
                <div class="card px-2 pt-1 border" style="padding-bottom: 6rem">
                    <h3 class="m-3">Корзина пуста</h3>
                </div>
            @endif
        </div>
    </div>
@endsection
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
    </style>
@endsection

@section('script')
    <script>
        async function changeQuantity(button) {
            const productId = $(button).data('product-id');
            const change = parseInt($(button).data('change'));
            const price = $(button).data('price');
            const span = $(button).closest('.d-flex').find('span#quantity-' + productId);
            const totalPriceElement = $('#total-price');
            const currentPrice = parseFloat(totalPriceElement
                .text()
                .replace('Итого: ', '')
            );

            const newQuantity = parseInt(span.text(), 10) + change;

            span.text(newQuantity);
            appendItemQuantity(change);

            if (change < 0) {
                totalPriceElement.text('Итого: ' + (currentPrice - parseFloat(price)));
            } else {
                totalPriceElement.text('Итого: ' + (currentPrice + parseFloat(price)));
            }

            button.classList.add('animated-button');
            totalPriceElement.addClass('animated-total-price');

            setTimeout(() => {
                button.classList.remove('animated-button');
                totalPriceElement.removeClass('animated-total-price');
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
