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
        <h2>Обновить иконку</h2>
        <button type="submit" form="faviconForm" class="btn btn-primary w-25">Отправить</button>
    </div>

    <form class="mb-5"
          id="faviconForm"
          action="{{ route('admin.dashboard.update-favicon') }}"
          method="POST"
          enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="favicon" class="form-label">Изображение</label>
            <div class="custom-file">
                <input type="file" class="custom-file-input" id="favicon" name="favicon">
                <label class="custom-file-label form-control" for="favicon">Выберите файл</label>
            </div>

            <small class="form-text text-muted">
                Пожалуйста, загрузите изображение в формате PNG, JPG или ICO. Максимальное разрешение: 1000x1000. Максимальный размер файла: 2MB.
            </small>

            <div id="new-favicon-container" class="mt-3">
            </div>
        </div>
    </form>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('#favicon').on('change', function(e) {
                const fileInput = $(this);
                const fileName = fileInput[0].files[0].name;
                const nextSibling = fileInput.next('.custom-file-label');
                nextSibling.text(fileName);

                const file = fileInput[0].files[0];
                const reader = new FileReader();

                reader.onload = function(e) {
                    const newFaviconContainer = $('#new-favicon-container');
                    newFaviconContainer.html(`
                        <label for="new_favicon_preview">Предварительный просмотр новой иконки</label>
                        <div>
                            <img id="new_favicon_preview" src="${e.target.result}" alt="New favicon" class="img-thumbnail" style="max-width: 200px;">
                        </div>
                    `);
                };

                reader.readAsDataURL(file);
            });
        });
    </script>
@endsection
