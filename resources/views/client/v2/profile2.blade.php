@php use App\Models\User\Profile; use App\Other\Authentication;use Illuminate\Support\ViewErrorBag; @endphp
@php
    /**
    * @var Profile $profile
    * @var ViewErrorBag $errors
    */
@endphp
@extends('client.v2.nav.nav')
@section('title', 'Профиль')
@section('content')
    <div class="min-height-wrap container py-3 border rounded w-100">
        <h2 class="profile-title text-center">Профиль</h2>

        <form action="{{ route('profile.update') }}" method="POST" class="profile-form w-100 px-3">
            @csrf

            <div class="profile-info w-100">
                <div class="info-item row mb-3">
                    <label for="name" class="col-sm-3 col-form-label">Имя:</label>
                    <div class="col-sm-9">
                        <span class="info-value">{{ $profile->name }}</span>
                    </div>
                </div>
                <div class="info-item row mb-3">
                    <label for="birthday" class="col-sm-3 col-form-label">День рождения:</label>
                    <div class="col-sm-9">
                        <span class="info-value">{{ formatDate($profile->birthday) }}</span>
                    </div>
                </div>
                <div class="info-item row mb-3">
                    <label for="address" class="col-sm-3 col-form-label">Адрес:</label>
                    <div class="col-sm-9">
                        <span class="info-value">{{ $profile->address }}</span>
                    </div>
                </div>
                <div class="d-flex w-100 justify-content-center">
                    <button type="button" class="btn btn-primary btn-edit-profile mb-4 rounded-pill custom-w-25">
                        Редактировать профиль
                    </button>
                </div>
            </div>
        </form>

        <h2 class="credentials-title text-center">Учётные данные</h2>
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
                        <input class="form-control rounded"
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
                        <input class="form-control rounded"
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
                        <input type="password" id="current-password" name="current_password"
                               class="form-control rounded"
                               placeholder="" required>
                    </div>
                </div>
                <div class="credential-item row mb-3">
                    <label for="new-password" class="col-sm-3 col-form-label">Новый пароль:</label>
                    <div class="col-sm-9">
                        <input type="password" id="new-password" name="new_password" class="form-control rounded"
                               placeholder=""
                               required>
                    </div>
                </div>
                <div class="credential-item row mb-3">
                    <label for="new-password-confirmation" class="col-sm-3 col-form-label">Повторите пароль:</label>
                    <div class="col-sm-9">
                        <input type="password" id="new-password-confirmation" name="new_password_confirmation"
                               class="form-control rounded" placeholder="" required>
                    </div>
                </div>

                <div class="w-100 d-flex justify-content-center">
                    <button type="submit" class="btn btn-success mb-2 rounded-pill custom-w-25">Сохранить</button>
                </div>

                @if(Authentication::isAdmin())
                    <div class="w-100 d-flex justify-content-center">
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-primary mb-2 rounded-pill custom-w-25">Панель администратора</a>
                    </div>
                @endif
            </div>
        </form>

        <form method="POST" action="{{ route('logout') }}" class="w-100 px-3">
            @csrf
            <div class="w-100 d-flex justify-content-center">
                <button type="submit" class="btn btn-danger mb-2 rounded-pill custom-w-25">Выйти</button>
            </div>
        </form>

        <h2 class="order-title text-center">Мои заказы</h2>
        <div class="order-info w-100 px-3">
            @foreach ($profile->getLatestOrders() as $order)
                <div class="order-item border rounded mb-3 p-3">
                    <p class="fw-bold">Заказ: {{ $order->id }}</p>
                    <p>Статус: {{ $order->status->name }}</p>
                    <p>Дата заказа: {{ $order->createdAt }}</p>
                    <p>Сумма заказа: {{ $order->totalAmount }}</p>
                    <a href="" class="btn btn-outline-primary">Просмотреть детали</a>
                </div>
            @endforeach
        </div>
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

        .order-item {
            background-color: #f8f9fa;
        }
    </style>
@endsection

@section('script')
    <script>
        $(document).ready(function () {
            const editProfileBtn = $('.btn-edit-profile');

            editProfileBtn.on('click', function () {
                const infoValues = $('.info-value');
                const isEditMode = $(this).hasClass('editing');

                if (isEditMode) {
                    // Save mode
                    const inputValues = $('.form-control');
                    post(inputValues);

                    inputValues.each(function () {
                        const value = $(this).val();
                        const span = $('<span>');

                        span.text(value);
                        span.addClass('info-value');

                        $(this).replaceWith(span);
                    });

                    $(this).text('Редактировать профиль');
                    $(this).removeClass('editing');
                } else {
                    // Edit mode
                    infoValues.each(function () {
                        const value = $(this).text();
                        const input = $('<input>');
                        const labelFor = $(this).closest('.info-item').find('label').attr('for');

                        input.attr('type', labelFor === 'birthday' ? 'date' : 'text');
                        input.attr('value', value);
                        input.attr('id', labelFor.toLowerCase());
                        input.addClass('form-control mb-2 rounded');

                        $(this).replaceWith(input);
                    });

                    $(this).text('Сохранить');
                    $(this).addClass('editing');
                }
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
