@php use App\Models\Cafe @endphp
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

        .list-item button {
            margin-left: 10px;
        }

        .custom-file-input {
            display: none;
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
    <div class="container-fluid m-3 p-3 card">
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
                    <th>Адреса</th>
                    <td>
                        <ul id="addresses">
                            @foreach($cafe->addresses as $address)
                                <li class="list-item">
                                    <input type="text" class="form-control d-none mb-2" name="addresses[]" value="{{ $address }}" readonly>
                                    <span>{{ $address }}</span>
                                    <button type="button" class="btn btn-danger btn-sm d-none" onclick="removeListItem(this)">Удалить</button>
                                </li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn btn-secondary btn-sm d-none" id="addAddress">Добавить адрес</button>
                    </td>
                </tr>
                <tr>
                    <th>Контактные телефоны</th>
                    <td>
                        <ul id="phones">
                            @foreach($cafe->phones as $phone)
                                <li class="list-item">
                                    <input type="text" class="form-control d-none mb-2" name="phones[]" value="{{ $phone }}" readonly>
                                    <span>{{ $phone }}</span>
                                    <button type="button" class="btn btn-danger btn-sm d-none" onclick="removeListItem(this)">Удалить</button>
                                </li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn btn-secondary btn-sm d-none" id="addPhone">Добавить телефон</button>
                    </td>
                </tr>
                <tr>
                    <th>Электронные почты</th>
                    <td>
                        <ul id="emails">
                            @foreach($cafe->emails as $email)
                                <li class="list-item list-inline-item">
                                    <input type="email" class="form-control d-none mb-2" name="emails[]" value="{{ $email }}" readonly>
                                    <span>{{ $email }}</span>
                                    <button type="button" class="btn btn-danger btn-sm d-none" onclick="removeListItem(this)">Удалить</button>
                                </li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn btn-secondary btn-sm d-none" id="addEmail">Добавить почту</button>
                    </td>
                </tr>
                <tr id="imageRow">
                    <th>Изображение</th>
                    <td>
                        <div class="list-item">
                            @if($cafe->image)
                                <img src="/{{ $cafe->image }}" alt="" class="img-thumbnail" style="max-width: 150px;">
                            @endif
                        </div>
                    </td>
                </tr>
                <tr id="newImageRow" class="d-none">
                    <th>Новое изображение</th>
                    <td>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="image" name="image">
                            <label class="custom-file-label" for="image">Выберите файл</label>
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
            <button type="button" id="editButton" class="btn btn-primary" onclick="toggleEdit()">Редактировать</button>
            <button type="submit" id="saveButton" class="btn btn-primary d-none">Сохранить изменения</button>
        </form>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            const $editButton = $('#editButton');
            const $addAddressButton = $('#addAddress');
            const $addPhoneButton = $('#addPhone');
            const $addEmailButton = $('#addEmail');

            function toggleEdit() {
                const $saveButton = $('#saveButton');
                const $inputs = $('#cafeProfileForm input');
                const $spans = $('#cafeProfileForm span');
                const $removeButtons = $('.list-item .btn-danger');
                const $imageLabel = $('.custom-file-label');
                const $newImageRow = $('#newImageRow');
                const $h2 = $('#h2');

                $h2.text('Редактировать');

                $inputs.each(function() {
                    const $input = $(this);
                    $input.toggleClass('d-none').prop('readonly', !$input.prop('readonly'));
                });

                $spans.toggleClass('d-none');

                $removeButtons.toggleClass('d-none');
                $editButton.toggleClass('d-none');
                $saveButton.toggleClass('d-none');
                $addAddressButton.toggleClass('d-none');
                $addPhoneButton.toggleClass('d-none');
                $addEmailButton.toggleClass('d-none');
                $imageLabel.toggleClass('d-none');
                $newImageRow.toggleClass('d-none');
            }

            $editButton.on('click', toggleEdit);

            $addAddressButton.on('click', function() {
                const $addressesDiv = $('#addresses');
                const newAddressInput = $('<input>', {
                    type: 'text',
                    class: 'form-control mb-2',
                    name: 'addresses[]'
                });

                const newListItem = $('<li>', { class: 'list-item' }).append(newAddressInput).append(
                    $('<button>', {
                        type: 'button',
                        class: 'btn btn-danger btn-sm',
                        text: 'Удалить',
                        click: function() { newListItem.remove(); }
                    })
                );

                $addressesDiv.append(newListItem);
            });

            $addPhoneButton.on('click', function() {
                const $phonesDiv = $('#phones');
                const newPhoneInput = $('<input>', {
                    type: 'text',
                    class: 'form-control mb-2',
                    name: 'phones[]'
                });

                const newListItem = $('<li>', { class: 'list-item' }).append(newPhoneInput).append(
                    $('<button>', {
                        type: 'button',
                        class: 'btn btn-danger btn-sm',
                        text: 'Удалить',
                        click: function() { newListItem.remove(); }
                    })
                );

                $phonesDiv.append(newListItem);
            });

            $addEmailButton.on('click', function() {
                const $emailsDiv = $('#emails');
                const newEmailInput = $('<input>', {
                    type: 'email',
                    class: 'form-control mb-2',
                    name: 'emails[]'
                });

                const newListItem = $('<li>', { class: 'list-item' }).append(newEmailInput).append(
                    $('<button>', {
                        type: 'button',
                        class: 'btn btn-danger btn-sm',
                        text: 'Удалить',
                        click: function() { newListItem.remove(); }
                    })
                );

                $emailsDiv.append(newListItem);
            });

            $('#image, #newImage').on('change', function(e) {
                const fileName = e.target.files[0].name;
                $(this).next('.custom-file-label').text(fileName);
            });
        });
    </script>
@endsection
