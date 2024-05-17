<!DOCTYPE html>
<html lang="ru">
<head>
    <title>Профиль</title>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0 maximum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"/>
    <link href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css" rel="stylesheet">

    <style>
        #this-table {
            width: 100%;
        }

        .table-wrapper {
            width: 80vw;
            overflow-x: auto;
        }

        body {
            font-family: Arial, sans-serif;
        }

        .profile-details {
            border-collapse: collapse;
            width: 100%;
        }

        .profile-details th,
        .profile-details td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            white-space: nowrap; /* Текст будет в одну строку */
        }

        .profile-details th {
            background-color: #f2f2f2;
        }

        @media screen and (max-width: 600px) {
            .profile-details th,
            .profile-details td {
                width: auto;
                display: block;
                text-align: center;
            }
        }
    </style>
</head>

<body>
<div class="container mt-5">
    <h3><a href="{{route('admin.orders')}}">Назад</a></h3>
    <h1>Профиль</h1>
    <div class="table-wrapper">
        <table id="this-table" class="profile-details fa-table table table-ordered">
            <thead>
            <tr>
                <th>ID</th>
                <th>Имя</th>
                <th>Адрес</th>
                <th>Почта</th>
                <th>Телефон</th>
                <th>День рождения</th>
                <th>Дата создания</th>
                <th>Дата обновления</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                @php
                    $view = $profileView;
                    $profile = $profileView->getProfile();
                @endphp
                <td>{{ $profile->getAttribute('id') }}</td>
                <td>{{ $profile->getAttribute('name') }}</td>
                <td>{{ $profile->getAttribute('address') }}</td>
                <td>{{ $view->getEmail() }}</td>
                <td>{{ $view->getPhone() }}</td>
                <td>{{ $profile->getAttribute('birthday') }}</td>
                <td>{{ $profile->getAttribute('created_at') }}</td>
                <td>{{ $profile->getAttribute('updated_at') }}</td>
            </tr>
            </tbody>
        </table>
    </div>

    <h2>Заказы</h2>
    <div class="table-wrapper">
        <table id="this-table" class="profile-details fa-table table table-ordered">
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
                @foreach($profileView->getOrders() as $order)
                <tr>
                    <td>{{ $order->getAttribute('id') }}</td>
                    <td>{{ $order->getRelatedStatus()->getAttribute('name') }}</td>
                    <td>{{ $order->getAttribute('price') }}</td>
                    <td>{{ $order->getAttribute('phone') }}</td>
                    <td>{{ $order->getAttribute('address') }}</td>
                    <td>{{ $order->getAttribute('created_at') }}</td>
                    <td>{{ $order->getAttribute('updated_at') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
