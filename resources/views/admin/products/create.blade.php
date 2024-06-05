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
        <h2>Создать новый продукт</h2>
        <button type="submit" form="productForm" class="btn btn-primary w-25">Создать</button>
    </div>

    <form class="mb-5" id="productForm" action="{{ route('admin.products.create') }}" method="POST"
          enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="name" class="form-label">Название</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
        </div>
        <div class="form-group">
            <label for="price" class="form-label">Цена</label>
            <input type="number" step="0.01" class="form-control" id="price" name="price" value="{{ old('price') }}"
                   required>
        </div>
        <div class="form-group">
            <label for="short_description" class="form-label">Краткое описание</label>
            <textarea class="form-control" id="short_description" name="short_description"
                      required>{{ old('short_description') }}</textarea>
        </div>
        <div class="form-group">
            <label for="full_description" class="form-label">Полное описание</label>
            <textarea class="form-control" id="full_description" style="height: 200px" name="full_description"
                      required>{{ old('full_description') }}</textarea>
        </div>
        <div class="form-group">
            <label for="weight" class="form-label">Вес</label>
            <input type="number" step="0.01" class="form-control" id="weight" name="weight" value="{{ old('weight') }}"
                   required>
        </div>
        <div class="form-group">
            <label for="availability" class="form-label">Виден клиентам?</label>
            <select class="form-control" id="availability" name="availability">
                <option value="1" {{ old('availability') == '1' ? 'selected' : '' }}>Да</option>
                <option value="0" {{ old('availability') == '0' ? 'selected' : '' }}>Нет</option>
            </select>
        </div>
        <div class="form-group">
            <label for="category_id" class="form-label">Категория</label>
            <select class="form-control" id="category_id" name="category_id">
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="image" class="form-label">Изображение</label>
            <div class="custom-file">
                <input type="file" class="custom-file-input" id="image" name="image">
                <label class="custom-file-label form-control" for="image">Выберите файл</label>
            </div>
            <div id="new-image-container" style="margin-top: 10px;">
            </div>
        </div>
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
