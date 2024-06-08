@php use App\Models\Order; @endphp
@extends('admin.layouts.layout')
@section('style')
    <style>
        .card {
            border-radius: 15px;
        }

        .list-item {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .list-item div {
            flex: 1;
        }

        .list-item div:first-child {
            font-weight: bold;
            flex: 0 0 150px;
        }

        .custom-file-label {
            cursor: pointer;
        }

        .d-none {
            display: none;
        }

        .img-thumbnail {
            max-width: 150px;
        }

        .status-pending {
            color: darkorange;
        }

        .status-confirmed {
            color: #17a2b8;
        }

        .status-getting-ready {
            color: #28a745;
        }

        .status-shipped {
            color: #007bff;
        }


        .status-delivered {
            color: #6c757d;
        }

        .status-cancelled {
            color: #dc3545;
        }

        .status-returned {
            color: #fd7e14;
        }

        .status-refunded {
            color: #6610f2;
        }

        .status-completed {
            color: #20c997;
        }

        .status-failed-delivery {
            color: #343a40;
        }

        .promotions-list a {
            text-decoration: none; /* Убирает подчеркивание у ссылок */
            color: inherit; /* Наследует цвет текста */
        }

        .product-list-item {
            font-size: 1.2rem;
        }

        .orders-dialog {
            width: 600px;
            height: 400px;
            border-radius: 15px;
            overflow: hidden;
        }
    </style>
@endsection

@php
    /**
     * @var Order $order
     */
@endphp

@section('content')
    <h1>Детали заказа</h1>
    <div class="container-fluid m-3 px-5 py-4 card">
        <h3>Информация</h3>
        @csrf
        <div class="list-item mt-1 border-bottom">
            <div>ID</div>
            <div class="fs-6">{{ $order->id }}</div>
        </div>
        <div class="list-item mt-1 border-bottom">
            <div>Статус</div>
            <div class="fs-6">
                <ul>
                    @php
                        $statusClass = '';
                        switch ($order->status->name) {
                            case 'Ожидание':
                                $statusClass = 'status-pending';
                                break;
                            case 'Подтвержден':
                                $statusClass = 'status-confirmed';
                                break;
                            case 'Готовится':
                                $statusClass = 'status-getting-ready';
                                break;
                            case 'Отправлен':
                                $statusClass = 'status-shipped';
                                break;
                            case 'Доставлен':
                                $statusClass = 'status-delivered';
                                break;
                            case 'Отменен':
                                $statusClass = 'status-cancelled';
                                break;
                            case 'Возвращен':
                                $statusClass = 'status-returned';
                                break;
                            case 'Возврат средств':
                                $statusClass = 'status-refunded';
                                break;
                            case 'Завершен':
                                $statusClass = 'status-completed';
                                break;
                            case 'Доставка отменена':
                                $statusClass = 'status-failed-delivery';
                                break;
                        }
                    @endphp
                    <li class="{{$statusClass}}"> {{$order->status->name}} </li>
                </ul>
            </div>
        </div>
        <div class="list-item mt-1 border-bottom">
            <div>Сумма</div>
            <div class="fs-6">{{ $order->totalAmount }}</div>
        </div>
        <div class="list-item mt-1 border-bottom">
            <div>Сумма после скидок</div>
            <div class="fs-6">{{ $order->afterAmount }}</div>
        </div>
        <div class="list-item mt-1 border-bottom">
            <div>Примененные скидки</div>
            <div class="fs-6">
                <ul class="promotions-list">
                    @foreach ($order->promotions as $promotion)
                        <li><a class="fst-normal text-decoration-none" href="{{ route('admin.promotions.index')}}">{{ $promotion->name }}</a></li>
                    @endforeach
                </ul>
            </div>
        </div>
        <div class="list-item mt-1 border-bottom">
            <div>Получение</div>
            <div class="fs-6">{{ $order->receiptMethod->name }}</div>
        </div>
        <div class="list-item mt-1 border-bottom">
            <div>Оплата</div>
            <div class="fs-6">{{ $order->paymentMethod->name }}</div>
        </div>
        <div class="list-item mt-1 border-bottom">
            <div>Количество</div>
            <div class="fs-6">{{ $order->totalQuantity }}</div>
        </div>
        <div class="list-item mt-1 border-bottom">
            <div>Телефон</div>
            <div class="fs-6">{{ $order->phone }}</div>
        </div>
        <div class="list-item mt-1 border-bottom">
            <div>Адрес</div>
            <div class="fs-6">{{ $order->address }}</div>
        </div>
        <div class="list-item mt-1 border-bottom">
            <div>Описание</div>
            <div class="fs-6">{{ $order->description }}</div>
        </div>
        <div class="list-item mt-1 border-bottom">
            <div>Дата создания</div>
            <div class="fs-6">{{ formatDate($order->createdAt, true) }}</div>
        </div>
        <div class="list-item mt-1 border-bottom">
            <div>Дата обновления</div>
            <div class="fs-6">{{ formatDate($order->updatedAt, true) }}</div>
        </div>
        <div class="w-100 d-flex flex-column">
            <a href="{{ route('admin.orders.complete', ['id' => $order->id]) }}" class="btn btn-outline-primary w-25 mt-2">Завершить</a>
            <button type="button" onclick="openDialog('{{route('admin.orders.update.index', ['id' => $order->id])}} ')" class="mt-2 btn btn-primary w-25">Действия</button>
        </div>
    </div>

    <h2 class="mt-4">Продукты в заказе</h2>
    @foreach($order->products as $product)
        @php
            $quantity = $product->getOrderQuantity($order);
        @endphp
        <div class="container-fluid m-3 px-5 py-4 card">
            <h3>{{ $product->name }}<span class="fs-4"> — {{$quantity}} единиц</span></h3>
            <div class="list-item product-list-item fs-6">
                <div>Категория</div>
                <div>{{ $product->category->name }}</div>
            </div>
            <div class="list-item product-list-item fs-6">
                <div>Цена (ед.)</div>
                <div>{{ $product->price }}</div>
            </div>
        </div>
    @endforeach

    <dialog aria-label="modal-dialog" id="modal-dialog" class="orders-dialog">
        <iframe id="dialogFrame" src="" style="width: 100%; height: 100%; border: none;"></iframe>
    </dialog>

@endsection
@section('script')
    <script>
        function openDialog(url) {
            const dialog = document.getElementById('modal-dialog');
            const dialogFrame = document.getElementById('dialogFrame');
            dialogFrame.src = url;
            dialog.showModal();

            dialog.addEventListener('click', function (event) {
                const rect = dialog.getBoundingClientRect();
                const isInDialog = (rect.top <= event.clientY && event.clientY <= rect.top + rect.height &&
                    rect.left <= event.clientX && event.clientX <= rect.left + rect.width);
                if (!isInDialog) {
                    dialog.close();
                }
            });
        }

        function closeDialog() {
            const dialog = document.getElementById('modal-dialog');
            dialog.close();
        }

        $('#modal-dialog').on('close', function() {
            window.location.reload();
        });
    </script>
@endsection
