@php use App\Models\Image; @endphp
@php
    /**
     * @var Image $image
     */
@endphp

<!DOCTYPE html>
<html lang="ru">
<head>
    <title>Галерея изображений</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"/>
</head>
<body>
<div class="container mt-5">
    <h4><a href="{{route('admin.dashboard')}}"> На главную </a></h4>
    <h1 class="mb-4">Галерея изображений</h1>
    <div class="row">
        @foreach($images as $image)
            <div class="col-md-4 mb-4">
                <div class="card">
                    <img src="{{ $image->url() }}" class="card-img-top" alt="{{ $image->name }}">
                    <div class="card-body">
                        <h5 class="card-title">{{ $image->name }}</h5>
                        <p class="card-text">{{ $image->description }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
</body>
</html>
