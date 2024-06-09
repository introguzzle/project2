@php use App\Models\User\Profile;use Illuminate\Support\ViewErrorBag; @endphp
@php
    /**
     * @var Profile $profile
     * @var ViewErrorBag $errors
     */
@endphp
@extends('client.v2.nav.nav')
@section('title', 'Профиль')
@section('content')
    <div class="min-height-wrap d-flex flex-column align-items-start justify-content-center py-3 w-100">
        <h2 class="profile-title">Профиль</h2>

        <div class="row profile-info w-100 px-3">
            <div class="info-item row mb-1">
                <label for="name" class="col-sm-3 col-form-label">Имя:</label>
                <div class="col-sm-9">
                    <span class="info-value">{{ $profile->name }}</span>
                </div>
            </div>
            <div class="info-item row mb-1">
                <label for="birthday" class="col-sm-3 col-form-label">День рождения:</label>
                <div class="col-sm-9">
                    <span class="info-value">{{ formatDate($profile->birthday) }}</span>
                </div>
            </div>
            <div class="info-item row mb-1">
                <label for="address" class="col-sm-3 col-form-label">Адрес:</label>
                <div class="col-sm-9">
                    <span class="info-value">{{ $profile->address }}</span>
                </div>
            </div>
            <div class="d-flex w-100">
                <button type="button" class="custom-w-25 btn btn-primary btn-edit-profile mb-4">Редактировать профиль
                </button>
            </div>
        </div>

        <h2 class="credentials-title">Учётные данные</h2>
        <form action="{{ route('identity.update') }}" method="POST" class="password-form w-100 px-3">
            @csrf

            @if (session()->has('fail'))
                <div class="alert alert-danger">
                    {{ session()->get('fail') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    {{ $errors->first() }}
                </div>
            @endif

            @if (session()->has('success'))
                <div class="alert alert-success">
                    {{ session()->get('success') }}
                </div>
            @endif

            <div class="credentials-info">
                <div class="credential-item row mb-3">
                    <label for="phone" class="col-sm-3 col-form-label">Телефон:</label>
                    <div class="col-sm-9">
                        <input class="form-control"
                               type="number"
                               id="phone"
                               name="phone"
                               value="{{ $profile->identity->phone }}"
                        >
                    </div>
                </div>
                <div class="credential-item row mb-3">
                    <label for="email" class="col-sm-3 col-form-label">Почта:</label>
                    <div class="col-sm-9">
                        <input class="form-control"
                               type="email"
                               id="email"
                               name="email"
                               value="{{ $profile->identity->email }}"
                        >
                    </div>
                </div>
                <div class="credential-item row mb-3">
                    <label for="current-password" class="col-sm-3 col-form-label">Текущий пароль:</label>
                    <div class="col-sm-9">
                        <input type="password" id="current-password" name="current_password" class="form-control"
                               placeholder="" required>
                    </div>
                </div>
                <div class="credential-item row mb-3">
                    <label for="new-password" class="col-sm-3 col-form-label">Новый пароль:</label>
                    <div class="col-sm-9">
                        <input type="password" id="new-password" name="new_password" class="form-control" placeholder=""
                               required>
                    </div>
                </div>
                <div class="credential-item row mb-3">
                    <label for="new-password-confirmation" class="col-sm-3 col-form-label">Повторите пароль:</label>
                    <div class="col-sm-9">
                        <input type="password" id="new-password-confirmation" name="new_password_confirmation"
                               class="form-control" placeholder="" required>
                    </div>
                </div>

                <div class="w-100">
                    <button type="submit" class="btn btn-success mb-1 custom-w-25">Сохранить</button>
                </div>
            </div>
        </form>

        <form method="POST" action="{{ route('logout') }}" class="w-100 px-3">
            @csrf
            <button type="submit" class="btn btn-danger mt-1 custom-w-25">Выйти</button>
        </form>
    </div>
@endsection

@section('style')
    <style>
        @media (min-width: 992px) {
            .custom-w-25 {
                width: 25% !important;
            }
        }

        @media (max-width: 991px) {
            .custom-w-25 {
                width: 100% !important;
            }
        }
    </style>
@endsection

@section('script')
    <script>
        $(document).ready(function () {
            const editProfileBtn = $('.btn-edit-profile');

            editProfileBtn.on('click', function () {
                const infoValues = $('.info-value');

                infoValues.each(function () {
                    const value = $(this).text();
                    const input = $('<input>');
                    const labelFor = $(this).closest('.info-item').find('label').attr('for');

                    input.attr('type', labelFor === 'birthday' ? 'date' : 'text');
                    input.attr('value', value);
                    input.attr('id', labelFor.toLowerCase());
                    input.addClass('form-control mb-2');

                    $(this).replaceWith(input);
                });

                const saveBtn = $('<button>');

                saveBtn.text('Сохранить');
                saveBtn.addClass('btn btn-save-profile btn-success mt-3 custom-w-25');

                saveBtn.on('click', function () {
                    const inputValues = $('.form-control.mb-2');
                    post(inputValues);

                    inputValues.each(function () {
                        const value = $(this).val();
                        const span = $('<span>');

                        span.text(value);
                        span.addClass('info-value');

                        $(this).replaceWith(span);
                    });

                    saveBtn.remove();
                    editProfileBtn.show();
                });

                editProfileBtn.before(saveBtn);
                editProfileBtn.hide();
            });
        });

        function post(inputValues) {
            const formData = new FormData();

            inputValues.each(function () {
                formData.append($(this).attr('id'), $(this).val());
            });

            fetch('{{ route('profile.update') }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            }).then(response => {
                return response.json();
            }).then(() => {
                window.location.reload();
            }).catch(error => {
                console.error(error);
            });
        }
    </script>

@endsection
