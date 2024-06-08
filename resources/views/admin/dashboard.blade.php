@php use App\Models\User\Profile; @endphp
@extends('admin.layouts.layout')
@section('style')
    <style>
        .card {
            border-radius: 15px;
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
    <h1>Профиль администратора</h1>
    @php
        /**
        * @var Profile $profile
        */
    @endphp
    @if($profile)
        <div class="container-fluid m-3 p-4 card">
            <h3 id="h2">Информация</h3>
            <form id="profileForm" action="{{ route('admin.dashboard.update') }}" method="POST">
                @csrf
                @method('PUT')
                <table class="table m-3">
                    <tr>
                        <th>ID</th>
                        <td>{{ $profile->id }}</td>
                    </tr>
                    <tr>
                        <th>Имя</th>
                        <td><input type="text" class="form-control d-none" name="name" value="{{ $profile->name }}"
                                   readonly><span>{{ $profile->name }}</span></td>
                    </tr>
                    <tr>
                        <th>Адрес</th>
                        <td><input type="text" class="form-control d-none" name="address"
                                   value="{{ $profile->address }}" readonly><span>{{ $profile->address }}</span></td>
                    </tr>
                    <tr>
                        <th>Почта</th>
                        <td><input type="email" class="form-control d-none" name="email"
                                   value="{{ $profile->identity->email }}"
                                   readonly><span>{{ $profile->identity->email }}</span></td>
                    </tr>
                    <tr>
                        <th>Телефон</th>
                        <td><input type="text" class="form-control d-none" name="phone"
                                   value="{{ $profile->identity->phone }}"
                                   readonly><span>{{ $profile->identity->phone }}</span></td>
                    </tr>
                    <tr>
                        <th>День рождения</th>
                        <td><input type="date" class="form-control d-none" name="birthday"
                                   value="@if ($profile->birthday) {{ formatDate($profile->birthday) }} @endif" readonly><span>@if ($profile->birthday) {{ formatDate($profile->birthday) }} @endif</span></td>
                    </tr>
                    <tr>
                        <th>Дата создания</th>
                        <td>{{ formatDate($profile->createdAt, true) }}</td>
                    </tr>
                    <tr>
                        <th>Дата обновления</th>
                        <td>{{ formatDate($profile->updatedAt, true) }}</td>
                    </tr>
                </table>
                <div class="w-100 d-flex flex-column">
                    <button type="button" id="editButton" class="btn btn-primary w-25 mt-2" onclick="toggleEdit()">Редактировать</button>
                    <button type="submit" id="saveButton" class="btn btn-primary d-none w-25 mt-2">Сохранить изменения</button>
                    <a id="update-password" class="btn btn-primary w-25 mt-2" href="{{route('admin.dashboard.update-password.index')}}">Обновить пароль</a>
                </div>
            </form>
        </div>
    @endif
@endsection
@section('script')
    <script>
        function toggleEdit() {
            const editButton = document.getElementById('editButton');
            const saveButton = document.getElementById('saveButton');
            const inputs = document.querySelectorAll('#profileForm input');
            const spans = document.querySelectorAll('#profileForm span');

            inputs.forEach(input => {
                if (input.classList.contains('d-none')) {
                    input.classList.remove('d-none');
                    input.removeAttribute('readonly');
                } else {
                    input.classList.add('d-none');
                    input.setAttribute('readonly', 'readonly');
                }
            });

            spans.forEach(span => {
                if (span.classList.contains('d-none')) {
                    span.classList.remove('d-none');
                } else {
                    span.classList.add('d-none');
                }
            });

            if (editButton.classList.contains('d-none')) {
                editButton.classList.remove('d-none');
                saveButton.classList.add('d-none');
            } else {
                editButton.classList.add('d-none');
                saveButton.classList.remove('d-none');
            }
        }
    </script>
@endsection
