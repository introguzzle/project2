@extends('admin.layouts.layout')

@section('title', 'Обновить')
@section('back')
    {{route('admin.dashboard')}}
@endsection
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Обновить иконку</h2>
        <button type="submit" form="faviconForm" class="btn btn-primary w-25">Отправить</button>
    </div>

    <form id="faviconForm" action="{{ route('admin.dashboard.update-favicon') }}" method="POST"
          enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="favicon">Иконка</label>
            <div class="custom-file">
                <input type="file" class="custom-file-input" id="favicon" name="favicon">
                <label class="custom-file-label" for="favicon">Выберите файл</label>
            </div>

            <small class="form-text text-muted">
                Пожалуйста, загрузите изображение в формате PNG, JPG или ICO. Максимальное разрешение: 1000x1000.
                Максимальный размер файла: 2MB.
            </small>

            <div id="new-favicon-container" style="margin-top: 10px;">

            </div>
        </div>
    </form>
    <script>
        document.querySelector('.custom-file-input').addEventListener('change', function (e) {
            const fileInput = document.getElementById('favicon');
            const fileName = fileInput.files[0].name;
            const nextSibling = e.target.nextElementSibling;
            nextSibling.innerText = fileName;

            const file = fileInput.files[0];
            const reader = new FileReader();

            reader.onload = function (e) {
                const newFaviconContainer = document.getElementById('new-favicon-container');
                newFaviconContainer.innerHTML = `
                    <label for="new_favicon_preview">Предварительный просмотр новой иконки</label>
                    <div>
                        <img id="new_favicon_preview" src="${e.target.result}" alt="New favicon" class="img-thumbnail" style="max-width: 200px;">
                    </div>
                `;
            };

            reader.readAsDataURL(file);
        });
    </script>
@endsection
