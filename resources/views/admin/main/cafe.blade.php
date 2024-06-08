@php use App\Models\Cafe @endphp
@php
    /**
     * @var Cafe $cafe
     */
@endphp
@extends('admin.layouts.layout')
@section('style')
    <style>
        .card {
            border-radius: 15px;
        }

        .list-item {
            display: flex;
            align-items: center;
        }

        .list-item input, .list-item span {
            flex: 1;
        }

        .custom-file-label {
            cursor: pointer;
        }

        .d-none {
            display: none;
        }

        .table tr {
            margin-bottom: 15px;
        }

        .table td, .table th {
            padding: 15px 0;
        }
    </style>
@endsection
@section('content')
    <h1>Профиль кафе</h1>
    @php
        /**
        * @var Cafe $cafe
        */
    @endphp
    <div class="container-fluid m-3 p-4 card">
        <h3 id="h2">Информация</h3>
        <form id="cafeProfileForm" action="{{ route('admin.cafe.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <table class="table">
                <tr>
                    <th>Название</th>
                    <td>
                        <div class="list-item">
                            <input type="text" class="form-control d-none" name="name" value="{{ $cafe->name }}" readonly>
                            <span>{{ $cafe->name }}</span>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th>Описание</th>
                    <td>
                        <div class="list-item">
                            <input type="text" class="form-control d-none" name="description" value="{{ $cafe->description }}" readonly>
                            <span>{{ $cafe->description }}</span>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th>Минимальная сумма заказа</th>
                    <td>
                        <div class="list-item">
                            <input type="number" class="form-control d-none" name="required_order_sum" value="{{ $cafe->requiredOrderSum }}" readonly>
                            <span>{{ $cafe->requiredOrderSum }}</span>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th>Адрес</th>
                    <td>
                        <div class="list-item">
                            <input type="text" class="form-control d-none" name="address" value="{{ $cafe->address }}" readonly>
                            <span>{{ $cafe->address }}</span>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th>Контактный телефон</th>
                    <td>
                        <div class="list-item">
                            <input type="text" class="form-control d-none" name="phone" value="{{ $cafe->phone }}" readonly>
                            <span>{{ $cafe->phone }}</span>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th>Электронная почта</th>
                    <td>
                        <div class="list-item">
                            <input type="email" class="form-control d-none" name="email" value="{{ $cafe->email }}" readonly>
                            <span>{{ $cafe->email }}</span>
                        </div>
                    </td>
                </tr>
                <tr id="imageRow">
                    <th>Изображение</th>
                    <td>
                        <div class="list-item">
                            @if($cafe->image)
                                <img src="{{ $cafe->getImage() }}" alt="" class="img-thumbnail" style="max-width: 150px;">
                            @endif
                        </div>
                    </td>
                </tr>
                <tr id="newImageRow" class="d-none">
                    <th>Новое изображение</th>
                    <td>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="image" name="image">
                            <label class="custom-file-label form-control" for="image">Выберите файл</label>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th>Дата создания</th>
                    <td>{{ formatDate($cafe->createdAt, true) }}</td>
                </tr>
                <tr>
                    <th>Дата обновления</th>
                    <td>{{ formatDate($cafe->updatedAt, true) }}</td>
                </tr>
            </table>
            <div class="w-100">
                <button type="button" id="editButton" class="btn btn-primary w-25" onclick="toggleEdit()">Редактировать</button>
                <button type="submit" id="saveButton" class="btn btn-primary d-none w-25">Сохранить изменения</button>
            </div>
        </form>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            const $editButton = $('#editButton');

            function toggleEdit() {
                const $saveButton = $('#saveButton');
                const $inputs = $('#cafeProfileForm input');
                const $spans = $('#cafeProfileForm span');
                const $imageLabel = $('.custom-file-label');
                const $newImageRow = $('#newImageRow');
                const $h2 = $('#h2');

                $h2.text('Редактировать');

                $inputs.each(function() {
                    const $input = $(this);
                    $input.toggleClass('d-none').prop('readonly', !$input.prop('readonly'));
                });

                $spans.toggleClass('d-none');

                $editButton.toggleClass('d-none');
                $saveButton.toggleClass('d-none');
                $newImageRow.toggleClass('d-none');
            }

            $editButton.on('click', toggleEdit);

            $('#image').on('change', function(e) {
                const fileName = e.target.files[0].name;
                $(this).next('.custom-file-label').text(fileName);
            });
        });
    </script>
@endsection
