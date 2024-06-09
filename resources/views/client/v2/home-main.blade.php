@php use App\Models\Product; use App\Models\Category; use App\Other\Authentication;use Illuminate\Database\Eloquent\Collection; @endphp
@php
    /**
     * @var Collection<Category> $categories
     * @var Collection<Product> $products
     */
@endphp

@extends('client.v2.nav.nav')
@section('title', 'Меню')
@section('content')
    <div id="menu" class="container py-3">
        <header class="text-center mb-3">
            <h1 class="subtitle-primary--alt">Наше меню</h1>
            <h3 id="subtitle-category" class="subtitle-secondary--alt">{{$categories[0]->getAttribute('name')}}</h3>
        </header>

        <div class="container mt-4 categories">
            <div class="categories-container mb-4">
                @foreach ($categories as $category)
                    <button
                        data-category-id="{{ $category->getAttribute('id') }}"
                        class="btn btn-menu btn-outline-secondary text-dark category-btn"
                        onclick="loadProducts({{ $category->getAttribute('id') }})">
                        {{ $category->getAttribute('name') }}
                    </button>
                @endforeach
            </div>
        </div>

        <div class="menu-wrapper">
            <div class="row g-4 custom-w-100" id="products-container">
                @if (!isset($categoryQuery))
                @foreach ($products as $product)
                @php
                    $name = $product->name;
                    $id = $product->id;
                @endphp

                @if ($product->category->id === 1)
                <div class="col-12 col-sm-6 col-md-4 col-lg-3 fade-in custom-w-100">
                    <div class="card h-100 custom-w-100">
                        <div class="card-header text-center custom-w-100">
                            <a href="{{ route('product.index', ['id' => $id]) }}">
                                <img
                                    src="{{ $product->getPath() }}"
                                    alt="{{ $name }}"
                                    class="card-img-top img-fluid custom-w-100"
                                >
                            </a>
                        </div>
                        <div class="card-body text-center custom-w-100">
                            <h5 class="card-title text-wrap custom-w-100">{{ $name }}</h5>
                            <p class="card-text custom-w-100">{{ $product->shortDescription }}</p>
                        </div>
                        <div class="card-footer d-flex justify-content-between align-items-center custom-w-100">
                            <p class="mb-0 fs-5">{{ $product->price }}</p>
                            <div class="btn-container d-flex align-items-center">
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
                @endif
                @endforeach
                @endif
            </div>
        </div>
    </div>
@endsection

@section('style')
    <style>
        .menu-wrapper {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
        }

        .fade-in-menu {
            animation: fadeIn 0.5s;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        .categories-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }

        .btn-menu {
            flex: 1 1 calc(33.333% - 5px);
            margin: 5px;
        }

        @media(min-width: 992px) {
            .categories {
                display: none !important;
            }
        }

        @media(max-width: 992px) {
            .categories {
                display: inherit;
            }
        }

        .category-btn-clicked {
            animation: buttonClickAnimation 0.3s forwards;
        }

        @keyframes buttonClickAnimation {
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

        /* New CSS for product fade-in animation */
        .fade-in {
            animation: fadeInProducts 0.5s ease-in-out;
        }

        @keyframes fadeInProducts {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
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

        .animated-quantity {
            animation: animateQuantity 0.5s;
        }

        @keyframes animateQuantity {
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

        @media(max-width: 992px) {
            .custom-w-100 {
                width: 100% !important;
            }
        }

        @media(min-width: 992px) {
            .custom-w-100 {

            }
        }
    </style>
@endsection

@section('script')
    <script>
        @if(isset($categoryQuery))
            console.log('{{$categoryQuery}}');
            $(document).ready(function() {
                loadProducts({{ $categoryQuery }}, false);
            });
        @endif

        function loadProducts(categoryId, buttonAnimation = true) {
            const buttons = document.querySelectorAll('.category-btn');
            buttons.forEach(button => button.classList.remove('category-btn-clicked'));
            const clickedButton = document.querySelector(`button[data-category-id="${categoryId}"]`);

            if (buttonAnimation) {
                clickedButton.classList.add('category-btn-clicked');
            }

            document.getElementById('subtitle-category').textContent = clickedButton.textContent;

            fetch(`/api/products/${categoryId}`)
                .then(response => response.json())
                .then(data => {
                    const productsContainer = document.getElementById('products-container');
                    productsContainer.innerHTML = '';

                    const products = data['data'];

                    products.forEach(product => {
                        const productCard = `
                            <div class="col-12 col-sm-6 col-md-4 col-lg-3 fade-in">
                                <div class="card h-100 custom-w-100">
                                    <div class="card-header text-center custom-w-100">
                                        <a href="/product/${product['id']}">
                                            <img
                                                src="${product['path']}"
                                                alt="${product['name']}"
                                                class="card-img-top img-fluid custom-w-100"
                                            >
                                        </a>
                                    </div>
                                    <div class="card-body text-center custom-w-100">
                                        <h5 class="card-title text-wrap custom-w-100">${product['name']}</h5>
                                        <p class="card-text">${product['short_description']}</p>
                                    </div>
                                    <div class="card-footer d-flex justify-content-between align-items-center">
                                        <p class="mb-0 fs-5">${product['price']}</p>
                                        <div class="btn-container d-flex align-items-center">
                                            <button class="btn btn-group-sm btn-outline-danger m-2"
                                                    data-price="${product['price']}"
                                                    data-product-id="${product['id']}"
                                                    data-change="-1"
                                                    onclick="changeQuantity(this)">-
                                            </button>
                                            <span id="quantity-${product['id']}" class="fs-5 p-1">${product['quantity']}</span>
                                            <button class="btn btn-group-sm btn-outline-success m-2"
                                                    data-price="${product['price']}"
                                                    data-product-id="${product['id']}"
                                                    data-change="1"
                                                    onclick="changeQuantity(this)">+
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                        productsContainer.insertAdjacentHTML('beforeend', productCard);
                    });
                })
                .catch(error => console.error('Error loading products:', error));
        }

        async function changeQuantity(button) {
            const productId = $(button).data('product-id');
            const change = parseInt($(button).data('change'));
            const span = $(button).closest('.d-flex').find('span#quantity-' + productId);
            const totalPriceElement = $('#total-price');

            const newQuantity = parseInt(span.text(), 10) + change;

            if (newQuantity <= 0) {
                return;
            }

            span.text(newQuantity);
            appendItemQuantity(change);

            button.classList.add('animated-button');
            span.addClass('animated-quantity');
            totalPriceElement.addClass('animated-total-price');

            setTimeout(() => {
                button.classList.remove('animated-button');
                span.removeClass('animated-quantity');
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
