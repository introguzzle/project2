<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Профиль</title>
</head>
<body>
<h1>Профиль</h1>
<h2>Информация о профиле</h2>
<p><strong>Имя:</strong> {{ $profileView->getProfile()->getName() }}</p>
<p><strong>День рождения:</strong> {{ $profileView->getProfile()->getBirthday() }}</p>
<p><strong>Адрес:</strong> {{ $profileView->getProfile()->getAddress() }}</p>

<h2>Доп. информация</h2>
<p><strong>Логин:</strong> {{ $profileView->getIdentity()->getLogin() }}</p>

<h2>Заказы</h2>
<table border="1">
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
            <td>{{ $order->id }}</td>
            <td>{{ $order->status->name }}</td>
            <td>{{ $order->price }}</td>
            <td>{{ $order->phone }}</td>
            <td>{{ $order->address }}</td>
            <td>{{ $order->created_at }}</td>
            <td>{{ $order->updated_at }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>
