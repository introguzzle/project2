@extends('nav.nav')

@section('content')
    <section id="home" class="cart-section">
        <div class="cart-overlay">
            <header id="main-header" class="cart-header">
                <h2 class="cart-title">Корзина</h2>
                <div class="cart-container">
                    <div class="cart-items">
                        @foreach( $products as $product )
                            @php( $id = $product->id )
                            <div class="cart-item cart-item-{{ $id }}">
                                <div class="item-details-container">
                                    <div class="item-details">
                                        <h3 class="item-name">{{ $product->name }}</h3>
                                        <p class="cart-item-price">Цена: {{ $product->price }}</p>
                                        <div class="item-quantity item-quantity-{{$id}}">
                                            <button class="quantity-btn decrease" onclick="changeQuantity(this, {{$id}}, '-1')">-</button>
                                            <span class="quantity quantity-{{$id}}">{{$product->getCartQuantity(\App\Utils\Auth::getProfile()->getRelatedCart())}}</span>
                                            <button class="quantity-btn increase" onclick="changeQuantity(this, {{$id}}, '1')">+</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @if (!empty($products))
                        <div class="cart-total">
                            <p id="total-price">Итого: {{$price}}</p>
                            <form action="{{route('checkout')}}">
                                <input type="submit" class="btn btn-checkout" value="Перейти к оформлению" />
                            </form>
                        </div>
                    @else
                        <div class="cart-total-p">
                            <p>Корзина пуста</p>
                        </div>
                    @endif
                </div>
            </header>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (window.innerWidth <= 768) {
                const header = document.querySelector('.cart-header');
                const cartSection = document.querySelector('.cart-section');
                if (header && cartSection) {
                    cartSection.style.minHeight = (header.offsetHeight + 200) + 'px';
                    header.style.top = '50%';
                }
            }
        });

        async function changeQuantity(button, productId, quantityChange) {
            const url = '{{route('cart.update-quantity')}}';
            const formData = new FormData();
            formData.append('product_id', productId);
            formData.append('quantity_change', quantityChange);

            try {
                const response = await fetch(url, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });

                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }

                const productData = await fetch(`/api/product/${productId}`);
                const data = await productData.json();

                updateQuantityAndTotalPrice(button, data['quantity']);
                updateNav();
            } catch (error) {
                console.error('Error:', error);
            }
        }

        function updateQuantityAndTotalPrice(button, quantity) {
            if (parseInt(quantity) <= 0) {
                window.location.reload();
            }

            const totalPrice = document.getElementById('total-price');
            const quantityNode = button.parentNode.querySelector('.quantity');

            if (totalPrice && quantityNode) {
                fetch('{{route('api.cart.total-price')}}')
                    .then(response => response.json())
                    .then(data => {
                        totalPrice.textContent = 'Итого: ' + data;
                        quantityNode.textContent = quantity;
                        updateAnimatedElements(totalPrice, quantityNode);
                    });
            }
        }

        function updateAnimatedElements(totalPrice, quantityNode) {
            totalPrice.classList.add('animated-total-price');
            quantityNode.classList.add('animated-button');

            setTimeout(() => {
                totalPrice.classList.remove('animated-total-price');
            }, 500);

            setTimeout(() => {
                quantityNode.classList.remove('animated-button');
            }, 500);
        }

        function updateNav() {
            fetch('{{route('api.cart.total-quantity')}}')
                .then(response => response.json())
                .then(data => {
                    setTotalQuantity(data);
                });
        }

        function setTotalQuantity(totalQuantity) {
            const spanItemCount1 = document.getElementById('cart-count-1');
            const spanItemCount2 = document.getElementById('cart-count-2');
            const spanItemCount3 = document.getElementById('cart-count-3');

            changeCount(spanItemCount1, totalQuantity);
            changeCount(spanItemCount2, totalQuantity);
            changeCount(spanItemCount3, totalQuantity);
        }

        function changeCount(element, totalQuantity) {
            if (element) {
                element.textContent = totalQuantity;
            }
        }
    </script>

    <style>
        .cart-total-p {
            color: whitesmoke;
            font-size: 1.3em;
        }

        @media(max-width: 768px) {
            .cart-total {
                display: block !important;
            }
        }

        #home {
            background:
                linear-gradient(
                    rgba(0, 0, 0, 0.7),
                    rgba(0, 0, 0, 0.9)
                ),

                url("https://mebel-blog.ru/wp-content/uploads/2022/08/dizayn-restorana-13-1536x1024.jpg");
            background-size: cover;
        }

        .cart-section {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .cart-overlay {
            background-color: rgba(0, 0, 0, 0.8) !important;
        }

        .cart-header {
            background: rgba(0, 0, 0, 0.7);
            border-radius: 28px;
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            width: 80%;
            max-width: 800px;
        }

        .cart-title {
            color: white;
            font-size: 2.5em;
            margin-bottom: 20px;
            text-transform: uppercase;
            text-shadow: 2px 2px 4px rgba(200, 200, 200, 0.4);
        }

        .cart-container {
            width: 90%;
            max-width: 600px;
        }

        .cart-items {
            width: 100%;
            overflow-y: auto;
            max-height: 60vh;
            scrollbar-width: thin;
            scrollbar-color: #666 black;
        }

        .cart-item {
            display: flex;
            margin-bottom: 20px;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            overflow: hidden;
            width: 100%;
            align-items: center;
            align-content: center;
            flex-wrap: wrap;
        }

        .item-details-container {
            flex: 1;
            margin: 10px;
            display: flex;
            background-color: whitesmoke;
            padding: 10px;
            border-radius: 10px;
            justify-content: center;
            align-items: center;
            overflow: auto;
        }

        .item-name {
            color: black;
            font-size: 1.5em;
            margin-bottom: 5px;
            border: 2px solid dodgerblue;
            border-radius: 10px;
            padding: 5px;
            text-align: center;
            max-width: 100%;
            word-wrap: break-word;
        }

        .cart-item-price {
            margin-top: 10px;
            color: black;
            font-size: 1.2em;
            max-width: 100%;
            width: 100%;
        }

        .item-quantity {
            display: flex;
            align-items: center;
            margin-top: 10px;
            justify-content: center;
            justify-items: center;
        }

        .quantity-btn {
            padding: 5px 10px;
            background-color: #e6342a;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 30px;
            height: 32px;
            line-height: 0;
            width: 32px;
            margin: 0 8px;
            transition: background-color 0.3s ease;
        }

        .quantity-btn:hover {
            background-color: #d63027;
        }

        .quantity-btn:active {
            color: black;
        }

        .quantity {
            color: black;
            font-size: 1.5em;
            margin: 0 10px;
        }

        .btn-remove {
            background-color: #ff3b3f;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 5px 10px;
            cursor: pointer;
            font-size: 1em;
            transition: background-color 0.3s ease;
        }

        .btn-remove:hover {
            background-color: #f00;
        }

        .cart-total {
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
        }

        .cart-total p {
            color: white;
            font-size: 1.7em;
            transition: color 0.3s ease;
        }

        .btn-checkout {
            background-color: #666;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            cursor: pointer;
            font-size: 1.2em;
            transition: background-color 0.3s ease;
        }

        .btn-checkout:hover {
            background-color: #333;
        }

        .btn:active {
            color: black;
        }

        .cart-items::-webkit-scrollbar {
            width: 10px;
        }

        .cart-items::-webkit-scrollbar-thumb {
            background-color: #666;
            border-radius: 10px;
        }

        .cart-items::-webkit-scrollbar-track {
            background-color: black;
        }

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
