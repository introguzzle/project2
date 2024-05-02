@extends('nav.nav')

@section('content')
    <section id="product">
        <div class="overlay">
            <header class="section-header">
                <h1 class="subtitle-primary">Наше меню</h1>
                <h3 id="subtitle-category" class="subtitle-secondary">{{$productView->getProduct()->getAttribute('name')}}</h3>
            </header>

            <div class="menu-wrapper fade-in-menu">
                <div class="grid-container fade-in-menu">
                    <div class="grid-item">
                        <img src="{{$productView->getPath()}}" alt="{{$productView->getProduct()->getAttribute('name')}}">
                        <h3>{{$productView->getProduct()->getAttribute('name')}}</h3>
                        <p>{{$productView->getProduct()->getAttribute('description')}}</p>
                        <div class="item-info">
                            <p class="item-price">{{$productView->getProduct()->getAttribute('price')}}</p>
                            <div class="btn-container">
                                <button onclick="decreaseItemCount(this)" class="btn-count">-</button>
                                <span id="item-count-{{$productView->getProduct()->getAttribute('name')}}" class="item-count">
                                    {{$productView->getQuantity()}}
                                </span>
                                <button onclick="increaseItemCount(this)" class="btn-count">+</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

<style>
    /* Ваши стили продукта */

    #product {
        /* Стилизуйте вашу страницу продукта здесь */
    }
</style>
