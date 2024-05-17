<!DOCTYPE html>
<html lang="ru">
<head>
    <title>Панель администратора</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0 maximum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"/>
</head>
<body>
<div class="container mt-5">
    <h1>Панель администратора</h1>
    @if($profile)
    <h2>Профиль</h2>
    <table class="table">
        <tr>
            <th>ID</th>
            <td>{{ $profile->getAttribute('id') }}</td>
        </tr>
        <tr>
            <th>Имя</th>
            <td>{{ $profile->getAttribute('name') }}</td>
        </tr>
        <tr>
            <th>Адрес</th>
            <td>{{ $profile->getAttribute('address') }}</td>
        </tr>
        <tr>
            <th>Почта</th>
            <td>{{ $profile->identity->email }}</td>
        </tr>
        <tr>
            <th>Телефон</th>
            <td>{{ $profile->identity->phone }}</td>
        </tr>
        <tr>
            <th>День рождения</th>
            <td>{{ $profile->getAttribute('birthday') }}</td>
        </tr>
        <tr>
            <th>Дата создания</th>
            <td>{{ $profile->getAttribute('created_at') }}</td>
        </tr>
        <tr>
            <th>Дата обновления</th>
            <td>{{ $profile->getAttribute('updated_at') }}</td>
        </tr>
    </table>
    @endif

    <div>
        <h2>Действия</h2>
        <div>
            <a href="{{ route('admin.orders') }}" class="btn btn-primary">Просмотр заказов</a>
            <a href="{{ route('admin.token') }}" class="btn btn-primary">Получить токен</a>
        </div>
    </div>
</div>
</body>
</html>
