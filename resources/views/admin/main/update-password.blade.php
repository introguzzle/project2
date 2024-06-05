<!DOCTYPE html>
<html lang="ru">
<head>
    <title>Создание</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0 maximum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"/>
    <style>
        .custom-file-input {
            display: none;
        }

        .custom-file-label {
            cursor: pointer;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <h2><a href="{{ route('admin.dashboard') }}">На главную</a></h2>


    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Обновить пароль</h2>
        <button type="submit" form="passwordForm" class="btn btn-primary w-25">Отправить</button>
    </div>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('internal'))
        <div class="alert alert-danger">
            {{ session('internal') }}
        </div>
    @endif

    @foreach($errors->all() as $error)
        <div class="alert alert-danger">
            {{ $error }}
        </div>
    @endforeach

    <form class="mb-5" id="passwordForm" action="{{ route('admin.dashboard.update-password') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="current_password">Текущий пароль</label>
            <input type="password" class="form-control" id="current_password" name="current_password" required>
        </div>
        <div class="form-group">
            <label for="new_password">Новый пароль</label>
            <input type="password" class="form-control" id="new_password" name="new_password" required>
        </div>
        <div class="form-group">
            <label for="new_password_confirmation">Повторите пароль</label>
            <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" required>
        </div>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
</body>
</html>

