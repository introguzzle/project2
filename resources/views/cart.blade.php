@extends('nav.nav')

@section('content')
    <section id="home" class="cart-section">
        <div class="overlay">
            <header id="main-header" class="cart-header">
                <h2 class="cart-title">Корзина</h2>
                <div class="cart-container">
                    <div class="cart-items">
                        @foreach($productViews as $productView)
                            <div class="cart-item">
                                <div class="item-image">
                                    <img src="{{$productView->getPath()}}" alt="Product Image">
                                </div>
                                <div class="item-details">
                                    <h3 class="item-name">{{$productView->getProduct()->getAttribute('name')}}</h3>
                                    <p class="item-price">Цена: {{$productView->getProduct()->getAttribute('price')}}</p>
                                    <div class="item-quantity">
                                        <button class="quantity-btn decrease">-</button>
                                        <span class="quantity">{{$productView->getQuantity()}}</span>
                                        <button class="quantity-btn increase">+</button>
                                    </div>
                                    <button class="btn btn-remove">Удалить</button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="cart-total">
                        <p>Итого: $20.00</p>
                        <button class="btn btn-checkout">Оформить заказ</button>
                    </div>
                </div>
            </header>
        </div>
    </section>
@endsection

<style>
    .overlay {
        height: 100%;
        width: 100%;
        position: fixed;
        top: 0;
        left: 0;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 1;
        overflow-y: auto; /* Добавляем прокрутку по вертикали */
    }

    .cart-section {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100%;
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
    }

    .cart-header::after {
        content: '';
        position: absolute;
        bottom: -5px;
        left: 0;
        width: 100%;
        height: 5px;
        background-color: white;
    }

    .cart-title {
        color: white;
        font-size: 2.5em;
        margin-bottom: 20px;
        text-transform: uppercase;
        text-shadow: 2px 2px 4px rgba(200, 200, 200, 0.4);
    }

    .cart-container {
        width: 80%;
        max-width: 800px;
        margin-bottom: 20px;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .cart-items {
        width: 100%;
        overflow-y: auto; /* Добавляем прокрутку по вертикали */
        max-height: 60vh; /* Ограничиваем высоту, чтобы контент помещался на странице */
    }

    .cart-item {
        display: flex;
        margin-bottom: 20px;
        background-color: rgba(255, 255, 255, 0.1);
        border-radius: 10px;
        overflow: hidden;
        width: 100%;
    }

    .item-image {
        flex: 0 0 30%;
    }

    .item-image img {
        width: 100%;
        height: auto;
    }

    .item-details {
        flex: 1;
        padding: 10px;
    }

    .item-name {
        color: white;
        font-size: 1.5em;
        margin-bottom: 5px;
    }

    .item-price {
        color: white;
        font-size: 1.2em;
        margin-bottom: 10px;
    }

    .item-quantity {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
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
        color: white;
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
</style>
