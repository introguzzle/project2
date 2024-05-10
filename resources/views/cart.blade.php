@extends('nav.nav')

@section('content')
    <section id="home" class="cart-section">
        <div class="cart-overlay">
            <header id="main-header" class="cart-header">
                <h2 class="cart-title">Корзина</h2>
                <div class="cart-container">
                    <div class="cart-items">
                        @foreach($productViews as $productView)
                            <div class="cart-item">
                                <div class="item-image-container">
                                    <div class="item-image">
                                        <img src="{{$productView->getPath()}}" alt="Product Image">
                                    </div>
                                </div>
                                <div class="item-details-container">
                                    <div class="item-details">
                                        <h3 class="item-name">{{$productView->getProduct()->getAttribute('name')}}</h3>
                                        <p class="item-price">Цена: {{$productView->getProduct()->getAttribute('price')}}</p>
                                        <div class="item-quantity">
                                            <button class="quantity-btn decrease">-</button>
                                            <span class="quantity">{{$productView->getQuantity()}}</span>
                                            <button class="quantity-btn increase">+</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @if (!empty($productViews))
                        <div class="cart-total">
                            <p>Итого: {{$price}}</p>
                            <form action="{{route('checkout')}}">
                                <input type="submit" class="btn btn-checkout" value="Перейти к оформлению" />
                            </form>
                        </div>
                    @else
                        <div class="cart-total-p">
                            <p>Вы ещё ничего не заказали</p>
                        </div>
                    @endif
                </div>
            </header>
        </div>
    </section>

    <style>
        #home {
            background:
                linear-gradient(
                    rgba(0, 0, 0, 0.7),
                    rgba(0, 0, 0, 0.9)
                ),

                url("https://mebel-blog.ru/wp-content/uploads/2022/08/dizayn-restorana-13-1536x1024.jpg");
            background-size: cover;
        }
    </style>

    <style>
        .cart-total-p {
            text-align: center;
            color: whitesmoke;
            font-size: 1.3em;
        }

        .cart-overlay {
            background-color: rgba(0, 0, 0, 0.8) !important;
        }

        .cart-section {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .cart-header {
            background: rgba(0, 0, 0, 0.7);
            border-top-left-radius: 30px;
            border-top-right-radius: 30px;
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

        .item-image-container {
            flex: 0 0 40%;
            margin: 10px;
            background-color: whitesmoke;
            border-radius: 10px;
            max-width: 150px;
            max-height: 150px;
        }

        .item-image img {
            max-width: 130px;
            max-height: 130px;
            width: auto;
            height: auto;
            margin: 10px;
        }

        .item-details-container {
            flex: 1;
            margin: 10px;
            display: flex;
            background-color: whitesmoke;
            padding: 10px;
            height: 150px;
            max-height: 150px;
            flex-direction: column;
            border-radius: 10px;
            justify-content: center;
            align-items: center;
        }

        .item-name {
            color: black;
            font-size: 1.5em;
            margin-bottom: 5px;
        }

        .item-price {
            margin-top: 10px;
            color: black;
            font-size: 1.2em;
            margin-bottom: 10px;
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
            background-color: #666;
            color: white;
            border: none;
            border-radius: 50%;
            padding: 5px 10px;
            cursor: pointer;
            margin: 0 5px;
            font-size: 1.2em;
        }

        .quantity {
            color: black;
            font-size: 1.2em;
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
            font-size: 1.2em;
        }

        .btn-checkout {
            background-color: #666;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            cursor: pointer;
            font-size: 1.2em;
        }

        .btn-checkout:hover {
            background-color: #333;
        }

        .cart-items {
            width: 100%;
            overflow-y: auto;
            max-height: 60vh;
            scrollbar-width: thin; /* для Firefox */
            scrollbar-color: #666 black; /* для Firefox */
        }

        .cart-items::-webkit-scrollbar {
            width: 10px; /* ширина скроллбара */
        }

        .cart-items::-webkit-scrollbar-thumb {
            background-color: #666; /* цвет скроллбара */
            border-radius: 10px; /* радиус скроллбара */
        }

        .cart-items::-webkit-scrollbar-track {
            background-color: black; /* цвет трека */
        }
    </style>
@endsection
