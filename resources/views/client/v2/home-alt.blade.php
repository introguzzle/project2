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
    <div id="menu" class="container py-5">
        <header class="text-center mb-4">
            <h1 class="subtitle-primary--alt">Наше меню</h1>
            <h3 id="subtitle-category" class="subtitle-secondary--alt">{{$categories[0]->getAttribute('name')}}</h3>
        </header>

        <div class="categories container mt-4">
            <div class="categories-grid mb-4">
                @foreach ($categories as $category)
                    <button
                        data-category-id="{{ $category->getAttribute('id') }}"
                        class="btn btn-menu btn-secondary"
                        onclick="loadProducts({{ $category->getAttribute('id') }})">
                        {{ $category->getAttribute('name') }}
                    </button>
                @endforeach
            </div>
        </div>

        <div class="menu-wrapper">
            <div class="row g-4">
                @foreach ($products as $product)
                    @php
                        $name = $product->name;
                        $id = $product->id;
                    @endphp

                    @if ($product->category->id === 1)
                        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                            <div class="card h-100">
                                <div class="card-header text-center">
                                    <a href="{{ route('product.index', ['id' => $id]) }}">
                                        <img
                                            src="{{ $product->getPath() }}"
                                            alt="{{ $name }}"
                                            class="card-img-top img-fluid"
                                        >
                                    </a>
                                </div>
                                <div class="card-body text-center">
                                    <h5 class="card-title">{{ $name }}</h5>
                                    <p class="card-text">{{ $product->shortDescription }}</p>
                                </div>
                                <div class="card-footer d-flex justify-content-between align-items-center">
                                    <p class="mb-0">{{ $product->price }}</p>
                                    <div class="btn-container d-flex align-items-center">
                                        <button onclick="updateProductCount(this, {{$id}}, '-1')"
                                                class="btn btn-outline-danger btn-sm">-
                                        </button>
                                        <span id="item-count-{{ $id }}"
                                              class="mx-2">{{ $product->getCartQuantity(Authentication::profile()?->cart) }}</span>
                                        <button onclick="updateProductCount(this, {{$id}}, '1')"
                                                class="btn btn-outline-success btn-sm">+
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
@endsection

@section('style')
    <style>
        .btn-menu {
            min-width: 100px;
        }

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
    </style>
@endsection

@section('script')
    <script>
        function loadProducts(categoryId) {
            // Implement AJAX call to load products based on the selected category
        }

        async function updateProductCount(button, productId, quantityChange) {
            const span = document.getElementById('item-count-' + productId);
            const currentCount = parseInt(span.textContent, 10);
            const newCount = currentCount + parseInt(quantityChange, 10);

            if (newCount < 0) return;

            span.textContent = newCount;

            const url = '{{ route('cart.update-quantity') }}';
            const formData = new FormData();
            formData.append('product_id', productId);
            formData.append('quantity_change', quantityChange);

            try {
                await fetch(url, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });
            } catch (error) {
                console.error('Error:', error);
            }
        }
    </script>
@endsection
