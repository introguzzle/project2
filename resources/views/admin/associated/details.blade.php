<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Детали заказа</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"/>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .order-details {
            border-collapse: collapse;
            width: 100%;
        }

        .order-details th,
        .order-details td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .order-details th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <h3><a href="{{route('admin.orders')}}">Обратно</a></h3>
    <h1>Детали заказа</h1>
    <table class="order-details table table-bordered">
        <thead>
        <tr>
            <th>ID</th>
            <th>Статус</th>
            <th>Сумма</th>
            <th>Телефон</th>
            <th>Адрес</th>
            <th>Дата создания</th>
            <th>Дата обновления</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>{{ $orderView->getOrder()->id }}</td>
            <td>{{ $orderView->getOrder()->status->name }}</td>
            <td>{{ $orderView->getOrder()->price }}</td>
            <td>{{ $orderView->getOrder()->phone }}</td>
            <td>{{ $orderView->getOrder()->address }}</td>
            <td>{{ $orderView->getOrder()->created_at }}</td>
            <td>{{ $orderView->getOrder()->updated_at }}</td>
        </tr>
        </tbody>
    </table>

    <h2>Продукты в заказе</h2>
    <table class="order-details table table-bordered">
        <thead>
        <tr>
            <th>ID</th>
            <th>Название</th>
            <th>Категория</th>
            <th>Цена</th>
            <th>Количество</th>
        </tr>
        </thead>
        <tbody>
        @foreach($orderView->getProductViews() as $productView)
            <tr>
                <td>{{ $productView->getProduct()->getId() }}</td>
                <td>{{ $productView->getProduct()->getName() }}</td>
                <td>{{ $productView->getProduct()->category()->first()['name'] }}</td>
                <td>{{ $productView->getProduct()->getPrice() }}</td>
                <td>{{ $productView->getQuantity() }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
</body>
</html>
