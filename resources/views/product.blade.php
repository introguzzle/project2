@php use App\Models\Product; @endphp
@extends('nav.nav')

@section('content')
    @php /** @var Product $product */ @endphp
    <section id="home" class="product">
        <div class="overlay">
            <header id="main-header">
                <div class="product-container">
                    <div class="product-details">
                        <div class="product-image">
                            <img src="{{$product->getPath()}}" alt="{{$product->name}}">
                        </div>
                        <h3 class="product-title">{{$product->name}}</h3>
                        <div class="item-info">
                            <button class="btn-count">-</button>
                            <span class="item-count">0</span>
                            <button class="btn-count">+</button>
                        </div>
                        <button class="btn-back">НАЗАД</button>
                    </div>
                    <div class="product-description">
                        <div class="product-description-text">
                            {{$product->fullDescription}}
                        </div>
                    </div>
                </div>
            </header>
        </div>
    </section>

    <style>
        .product-container {
            display: flex;
            flex-direction: row;
            background-color: white;
            border: 1px solid #e0e0e0;
            padding: 20px;
            border-radius: 10px;
            box-sizing: border-box;
            max-width: 800px;
            width: 100%;
            margin: 0 auto;
            overflow: hidden;
        }

        .product-details {
            display: flex;
            flex-direction: column;
            align-items: center;
            flex: 1;
            padding: 20px;
            box-sizing: border-box;
        }

        .product-image {
            width: 150px;
            height: 150px;
            background-color: #ddd;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 20px;
        }

        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .product-title {
            font-size: 24px;
            margin-bottom: 10px;
            text-align: center; /* Center the title text */
        }

        .item-info {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 10px;
        }

        .btn-count {
            padding: 5px 10px;
            background-color: #e6342a;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 30px;
            height: 40px;
            line-height: 0;
            width: 40px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .item-count {
            display: inline-block;
            background-color: #e6342a;
            color: white;
            border-radius: 50%;
            height: 40px;
            text-align: center;
            line-height: 40px;
            font-size: 1em;
            margin: 0 10px;
            width: 40px;
        }

        .btn-back {
            padding: 10px 20px;
            background-color: white;
            border: 1px solid #e6342a;
            color: #e6342a;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
        }

        .product-description {
            flex: 1;
            padding: 20px;
            box-sizing: border-box;
            border-left: 1px solid #e0e0e0;
            overflow: auto;
            max-height: 100%;
        }

        .product-description-text {
            font-size: 1.2em;
            word-wrap: break-word;
            overflow-wrap: break-word;
            max-width: 100%;
            padding-left: 20px;
        }

        /* Media Query for Smartphones */
        @media (max-width: 600px) {
            .product-container {
                flex-direction: column;
                padding: 10px;
            }

            .product-details, .product-description {
                padding: 10px;
            }

            .product-image {
                width: 100px;
                height: 100px;
                margin-bottom: 10px;
            }

            .product-title {
                font-size: 20px;
                margin-bottom: 5px;
            }

            .btn-count {
                font-size: 20px;
                height: 30px;
                width: 30px;
            }

            .item-count {
                height: 30px;
                line-height: 30px;
                font-size: 0.9em;
                margin: 0 5px;
                width: 30px;
            }

            .btn-back {
                margin-top: 10px;
            }

            .product-description-text {
                font-size: 1em;
                padding-left: 10px;
            }
        }
    </style>
@endsection
