@extends('nav.nav')

@section('content')
    <section id="home">
        <div class="overlay">
            <header id="main-header">
                <h3 class="subtitle-script">Cafe & Restaraunt</h3>
                <h1 class="subtitle-primary">Welcome!</h1>
                <h3 class="subtitle-secondary">The Best Coffee Around</h3>
                <a href="#menu" class="btn btn-home btn-cta">Меню</a>
                <a href="#home" class="btn btn-home btn-other">Забронировать</a>
            </header>
        </div>
    </section>

    <section id="about">
        <header class="section-header">
            <h4 class="subtitle-script">Welcome</h4>
            <h1 class="subtitle-primary--alt">About Us</h1>
            <h3 class="subtitle-secondary--alt">Cafe & Restaraunt</h3>
        </header>
        <hr>
        <p class="text-primary">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Stress, for the United States
            element ante. Duis
            cursus, mi quis viverra ornare, eros pain, sometimes none at all, freedom of the living creature was as the
            profit and financial security. Jasmine neck adapter and just running it lorem makeup hairstyle. Now sad
            smile of the television set.</p>
    </section>
    <!-- Services -->
    <section id="services">
        <div class="overlay">
            <header class="section-header">
                <h1 class="subtitle-primary">Did You Know?</h1>
                <h3 class="subtitle-secondary--alt">About Our Restaraunt</h3>
            </header>
            <div class="flex-wrapper">
                <div class="service-container">
                    <div class="service-container--icon">
                        <i class="fas fa-coffee"></i>
                    </div>
                    <h4 class="service-container--text">Famous for <br> Our Coffee</h4>
                </div>
                <div class="service-container">
                    <div class="service-container--icon">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <h4 class="service-container--text">Phone <br> Reservations</h4>
                </div>
                <div class="service-container">
                    <div class="service-container--icon">
                        <i class="far fa-clock"></i>
                    </div>
                    <h4 class="service-container--text">Open Everyday <br> 08:00 - 01:00</h4>
                </div>
                <div class="service-container">
                    <div class="service-container--icon">
                        <i class="fas fa-map-signs"></i>
                    </div>
                    <h4 class="service-container--text">Located in <br> City Center</h4>
                </div>
            </div>
        </div>
    </section>

    <section id="menu">
        <header class="section-header">
            <h1 class="subtitle-primary--alt">Наше меню</h1>
            <h3 id="subtitle-category" class="subtitle-secondary--alt">{{$categories[0]->getAttribute('name')}}</h3>
        </header>

        <div class="flex-wrapper flex-center">
            @foreach ($categories as $category)
                <button
                    category-id="{{$category->getAttribute('id')}}"
                    class="btn btn-menu btn-inactive"
                    onclick=loadProducts({{$category->getAttribute('id')}})>
                    {{$category->getAttribute('name')}}
                </button>
            @endforeach
        </div>

        <div class="menu-wrapper fade-in-menu">
            <div class="grid-container fade-in-menu">
                @foreach ($products as $product)
                    @php
                        $name = $product->name;
                        $id = $product->id;
                    @endphp

                    @if ($product->category_id === 1)
                    <div class="grid-item">
                        <div class="item-header">
                            <a href="{{route("product", ['id' => $id])}}">
                                <img
                                     src="{{$product->getMainImage()->path}}"
                                     alt="{{ $name }}"
                                >
                            </a>
                            <h3>{{ $name }}</h3>
                            <p>{{ $product->short_description }}</p>
                        </div>
                        <div class="item-info">
                            <p class="item-price">{{ $product->price }}</p>
                            <div class="btn-container">
                                <button onclick="updateProductCount(this, {{$id}} , '-1')" class="btn-count">-</button>
                                <span id="item-count-{{ $id }}" class="item-count">
                                    {{$product->getCartQuantity(\App\Utils\Auth::getProfile()?->getRelatedCart())}}
                                </span>
                                <button onclick="updateProductCount(this, {{$id}} ,'1')" class="btn-count">+</button>
                            </div>
                        </div>
                    </div>
                    @endif
                @endforeach
            </div>
        </div>
    </section>

    <section id="gallery">
        <div class="desktop-gallery">
            <header class="section-header">
                <h4 class="subtitle-script">Authentic</h4>
                <h1 class="subtitle-primary--alt">Tasteful</h1>
                <h3 class="subtitle-secondary--alt">Cafe & Restaraunt</h3>
                <p class="text-primary">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse varius enim in
                    eros
                    elementum tristique. Duis cursus, mi quis viverra ornare, eros dolor.</p>
            </header>
            <div class="photo-gallery">
                <img src="https://gas-kvas.com/grafic/uploads/posts/2023-09/1695925240_gas-kvas-com-p-kartinki-burger-11.jpg" alt=""
                     class="gallery-photo">
                <img src="https://foxboar.ru/wp-content/uploads/2021/09/headerImage.jpg"
                     alt="" class="gallery-photo">
                <img src="https://intermountainhealthcare.org/-/media/images/modules/blog/posts/2015/health-benefits-drinking-coffee.jpg?la=en&h=461&w=700&mw=896&hash=24FC7736F38D6ADAC480DCB45239DD5F92CE6679"
                     alt="" class="gallery-photo">
                <img src="https://s3.amazonaws.com/grazecart/naturesrootsfarm/images/1486599107_589bb3c32b4ed.jpg"
                     alt="" class="gallery-photo">
            </div>
        </div>
    </section>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            fetch(`{{route('api.cart.total-quantity')}}`)
                .then(response => response.json())
                .then(data => {
                    setTotalQuantity(data);
                });
        });

        function loadProducts(categoryId) {
            const productContainer = document.querySelector('.grid-container');

            document.getElementById('subtitle-category').classList.remove('fade-in-menu');
            productContainer.classList.remove('fade-in-menu');

            fetch(`/api/category/${categoryId}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('subtitle-category').textContent = data['name'];
                    document.getElementById('subtitle-category').classList.add('fade-in-menu');
                });

            fetch(`/api/products/${categoryId}`)
                .then(response => response.json())
                .then(data => {
                    productContainer.innerHTML = '';

                    const products = data['data'];

                    products.forEach(product => {
                        const div = document.createElement('div');
                        div.classList.add('grid-item');

                        div.innerHTML = `
                            <div class="item-header"
                                <a href="/product/${product['id']}">
                                    <img src="${product['path']}" alt="${product['name']}">
                                </a>
                                <h3>${product['name']}</h3>
                                <p>${product['short_description']}</p>
                            </div>
                            <div class="item-info">
                                <p class="item-price">${product['price']}</p>
                                <div class="btn-container">
                                    <button onclick="updateProductCount(this, ${product['id']} , '-1')" class="btn-count">-</button>
                                    <span id="item-count-${product['id']}" class="item-count">
                                        ${product['quantity']}
                                    </span>
                                    <button onclick="updateProductCount(this, ${product['id']} , '1')" class="btn-count">+</button>
                                </div>
                            </div>
                        `;

                        productContainer.appendChild(div);
                    });

                    productContainer.classList.add('fade-in-menu');
                })
                .catch(error => console.error('Error fetching products:', error));
        }

        function updateProductCount(button, productId, quantityChange) {
            const countSpan = button.parentNode.querySelector('.item-count');

            countSpan.classList.add('item-count-animation', 'item-count-color');
            countSpan.textContent = parseInt(countSpan.textContent) + 1;

            const cartCount = document.getElementById('cart-count-1');
            setTotalQuantity(parseInt(cartCount.textContent) + 1);

            const xhr = new XMLHttpRequest();
            const url = "{{ route('cart.update-quantity') }}";

            const formData = new FormData();
            formData.append('product_id', productId);
            formData.append('quantity_change', quantityChange);

            xhr.open("POST", url, true);
            xhr.setRequestHeader("X-CSRF-TOKEN", "{{ csrf_token() }}");
            xhr.onreadystatechange = () => {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    fetch(`/api/product/${productId}`)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .then(() => {
                            fetch(`{{route('api.cart.total-quantity')}}`)
                                .then(response => {
                                    if (!response.ok) {
                                        throw new Error('Network response was not ok');
                                    }
                                })
                                .catch(error => {
                                    window.location.href = "{{route('login')}}";
                                });
                        })
                        .catch(error => {
                            window.location.href = "{{route('login')}}";
                        });
                }
            };
            xhr.send(formData);
            xhr.onerror = () => window.location.href = "{{route('login')}}";

            countSpan.addEventListener('animationend', () => {
                countSpan.classList.remove('item-count-animation', 'item-count-color');
            }, {once: true});
        }

        function changeCount(spanItemCount, count) {
            spanItemCount.textContent = count;
        }

        function setTotalQuantity(count) {
            const spanItemCount1 = document.getElementById('cart-count-1');
            const spanItemCount2 = document.getElementById('cart-count-2');
            const spanItemCount3 = document.getElementById('cart-count-3');

            changeCount(spanItemCount1, count);
            changeCount(spanItemCount2, count);
            changeCount(spanItemCount3, count);
        }
    </script>
@endsection

<style>
    .fade-in-menu {
        animation: fadeInAnimation 0.5s ease-in forwards;
        opacity: 0;
    }

    @keyframes fadeInAnimation {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }

    .btn-container {
        display: flex;
        align-items: center;
        height: 32px;
    }

    .hamburger {
        background: #fff;
        color: #111;
        padding: 13px;
        font-size: 1.4rem;
        margin-right: 13px;
        border-radius: 5px;
    }

    .item-price,
    .order-btn {
        flex-grow: 1;
        text-align: center;
        margin: 0 5px;
        font-family: 'Fira Sans', sans-serif;
        font-size: 18px;
        width: 120px;
        max-width: 120px;
    }

    .order-btn:hover {
        background: #c70000 !important;
    }

    .order-btn:active {
        background: rgba(100, 0, 0) !important;
    }

    @keyframes pulse {
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

    @keyframes shake {
        10%,
        90% {
            transform: translate3d(-1px, 0, 0);
        }
        20%,
        80% {
            transform: translate3d(2px, 0, 0);
        }
        30%,
        50%,
        70% {
            transform: translate3d(-4px, 0, 0);
        }
        40%,
        60% {
            transform: translate3d(4px, 0, 0);
        }
    }

    .item-count-color {
        background-color: dodgerblue;
        transition: background-color 0.5s;
    }

    .item-count-animation {
        animation: pulse 0.3s;
    }


    .btn-count {
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
    }

    .btn-menu {
        border-radius: 5px;
        border: #cbd5e0;
    }

    .grid-item h3,
    .grid-item p {
        word-wrap: break-word;
    }

    .btn-inactive:hover.btn-menu:hover {
        background: #e6342a linear-gradient(to right, #e6342a, #e88f2a);
        color: #f5f5f5;
    }

    .btn-count:hover {
        background-color: #c70000;
    }

    .item-count {
        display: inline-block;
        background-color: #e6342a;
        color: white;
        border-radius: 50%;
        height: 32px;
        text-align: center;
        line-height: 32px;
        font-size: 1em;
        margin: 0 5px;
        width: 32px;
    }

    .item-info {
        display: flex;
        align-items: center;
    }

    .item-price {
        margin-right: 10px;
        font-size: 16px;
    }

    @media (max-width: 768px) {
        .item-info {
            justify-content: center !important;
        }
    }

    .grid-container {
        display: flex;
        flex-wrap: wrap;
    }

    .grid-item {
        flex: 1 0 auto;
    }

    @media (min-width: 769px) {
        .grid-container {
            align-items: stretch;
        }
    }
</style>

