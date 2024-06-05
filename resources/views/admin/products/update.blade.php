@php
    use App\Models\Category;
    use App\Models\Product;
@endphp
@php
    /** @var Product $product */
    /** @var Category[] $categories */
@endphp
@extends('admin.layouts.layout')
@section('style')
    <style>
        .custom-file-input {
            display: none;
        }

        .custom-file-label {
            cursor: pointer;
        }
    </style>
@endsection
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Редактировать продукт</h1>
        <button type="submit" form="productForm" class="btn btn-primary w-25">Сохранить</button>
    </div>

    <form class="mb-5" id="productForm" action="{{ route('admin.products.update')}}" method="POST"
          enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="name" class="form-label">Название</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $product->name }}" required>
        </div>
        <div class="form-group">
            <label for="price" class="form-label">Цена</label>
            <input type="number" step="0.01" class="form-control" id="price" name="price" value="{{ $product->price }}"
                   required>
        </div>
        <div class="form-group">
            <label for="short_description" class="form-label">Краткое описание</label>
            <textarea class="form-control" id="short_description" name="short_description"
                      required>{{ $product->shortDescription }}</textarea>
        </div>
        <div class="form-group">
            <label for="full_description" class="form-label">Полное описание</label>
            <textarea class="form-control" style="height: 300px" id="full_description" name="full_description"
                      required>{{ $product->fullDescription }}</textarea>
        </div>
        <div class="form-group">
            <label for="weight" class="form-label">Вес</label>
            <input type="number" step="0.01" class="form-control" id="weight" name="weight"
                   value="{{ $product->weight }}"
                   required>
        </div>
        <div class="form-group">
            <label for="availability" class="form-label">Виден клиентам?</label>
            <select class="form-control" id="availability" name="availability">
                <option value="1" {{ $product->availability ? 'selected' : '' }}>Да</option>
                <option value="0" {{ !$product->availability ? 'selected' : '' }}>Нет</option>
            </select>
        </div>
        <div class="form-group">
            <label for="category_id" class="form-label">Категория</label>
            <select class="form-control" id="category_id" name="category_id">
                @foreach($categories as $category)
                    <option
                        value="{{ $category->id }}" {{ $product->category->id === $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                @endforeach
            </select>
        </div>

        @if($image = $product->getPath())
            <div class="form-group">
                <label for="current_image" class="form-label">Текущее изображение</label>
                <div>
                    <img src="{{ $image }}" alt="Current Image" class="img-thumbnail" style="max-width: 200px;">
                </div>
            </div>
        @endif

        <div class="form-group">
            <label for="image" class="form-label">Новое изображение</label>
            <div class="custom-file">
                <input type="file" class="custom-file-input" id="image" name="image">
                <label class="custom-file-label form-control" for="image">Выберите файл</label>
            </div>
            <div id="new-image-container" style="margin-top: 10px;">
            </div>
        </div>
        <input id="product_id" name="product_id" type="hidden" value="{{$product->id}}">
    </form>
@endsection
@section('script')
    <script>
        document.querySelector('.custom-file-input').addEventListener('change', function (e) {
            const fileInput = document.getElementById("image");
            const fileName = fileInput.files[0].name;
            const nextSibling = e.target.nextElementSibling;
            nextSibling.innerText = fileName;

            const file = fileInput.files[0];
            const reader = new FileReader();

            reader.onload = function (e) {
                const newImageContainer = document.getElementById('new-image-container');
                newImageContainer.innerHTML = `
                <label for="new_image_preview" class="form-label">Предварительный просмотр нового изображения</label>
                <div>
                    <img id="new_image_preview" src="${e.target.result}" alt="New Image" class="img-thumbnail" style="max-width: 200px;">
                </div>
            `;
            };

            reader.readAsDataURL(file);

            const mainField = document.createElement('div');
            mainField.className = 'form-group';
            mainField.innerHTML = `
            <label for="main" class="form-label">Основное изображение?</label>
            <select class="form-control" id="main" name="main">
                <option value="1"> Да </option>
                <option value="0"> Нет </option>
            </select>
        `;

            const imageNameField = document.createElement('div');
            imageNameField.className = 'form-group';
            imageNameField.innerHTML = `
             <label for="image_description" class="form-label">Название изображения</label>
             <textarea class="form-control" id="image_description" style="height: 200px" name="image_description" required></textarea>
        `;

            const imageDescriptionField = document.createElement('div');
            imageDescriptionField.className = 'form-group';
            imageDescriptionField.innerHTML = `
             <label for="image_name" class="form-label">Описание изображения</label>
            <textarea class="form-control" id="image_name" style="height: 200px" name="image_name" required></textarea>
        `;

            const form = document.getElementById('productForm');
            form.appendChild(mainField);
            form.appendChild(imageNameField);
            form.appendChild(imageDescriptionField);
        });
    </script>
@endsection
