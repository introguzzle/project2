@extends('nav.nav')

@section('content')
    <section id="home" class="profile-section">
        <div class="overlay">
            <header id="main-header" class="profile-header">
                <h2 class="profile-title">Профиль</h2>
                <div class="profile-info">
                    <div class="info-item">
                        <label for="name" class="info-label">Имя:</label>
                        <span class="info-value">{{$profileView->getProfile()->getAttribute('name')}}</span>
                    </div>
                    <div class="info-item">
                        <label for="birthday" class="info-label">Дата:</label>
                        <span class="info-value">{{$profileView->getProfile()->getSerializedBirthday()}}</span>
                    </div>
                    <div class="info-item">
                        <label for="address" class="info-label">Адрес:</label>
                        <span class="info-value">{{$profileView->getProfile()->getAttribute('address')}}</span>
                    </div>
                </div>
                <button type="submit" class="btn btn-edit-profile">Редактировать профиль</button>
                <h2 class="credentials-title">Учётные данные</h2>
                <form action="{{route('identity.password.update')}}" method="POST">
                    @csrf
                    <div class="credentials-info">
                        <div class="credential-item">
                            <label for="phone" class="credential-label">Телефон:</label>
                            <span class="credential-value">+7 (999) 999-99-99</span>
                        </div>
                        <div class="credential-item">
                            <label for="email" class="credential-label">Почта:</label>
                            <span class="credential-value">{{$profileView->getIdentity()->getAuthIdentifier()}}</span>
                        </div>
                        <div class="credential-item">
                            <label for="current-password" class="credential-label">Текущий пароль:</label>
                            <input type="password"
                                   id="current-password"
                                   name="current_password"
                                   class="credential-value credential-input"
                                   placeholder="" required>
                        </div>
                        <div class="credential-item">
                            <label for="new-password" class="credential-label">Новый пароль:</label>
                            <input type="password"
                                   id="new-password"
                                   name="new_password"
                                   class="credential-value credential-input"
                                   placeholder="" required>
                        </div>
                        <div class="credential-item">
                            <label for="new-password-confirmation" class="credential-label">Повторите пароль:</label>
                            <input type="password"
                                   id="new-password-confirmation"
                                   name="new_password_confirmation"
                                   class="credential-value credential-input"
                                   placeholder="" required>
                        </div>

                        @if (session()->has('fail') || session()->has('success'))
                        <div class="credential-item" style="justify-content: center; align-items: center" >
                            <span class="password-error">{{session()->get('fail')}}</span>
                            <span class="password-success">{{session()->get('success')}}</span>
                        </div>
                        @endif

                        <button type="submit" class="btn btn-new-password">Сохранить</button>
                    </div>
                </form>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-logout">Выйти</button>
                </form>
            </header>
        </div>
    </section>

    <style>
        #home {
            background:
                linear-gradient(
                    rgba(0, 0, 0, 0.7),
                    rgba(0, 0, 0, 0.9)
                ),

                url("https://mebel-blog.ru/wp-content/uploads/2022/08/dizayn-restorana-13-1536x1024.jpg");
            background-size: cover;
        }

        .password-error {
            color: red;
            font-size: 1.3em;
        }

        .password-success {
            color: green;
            font-size: 1.3em;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (window.innerWidth <= 768) {
                const header = document.querySelector('.profile-header');
                document.querySelector('.profile-section')
                    .style
                    .minHeight = (header.offsetHeight + 200) + 'px';

                header.style.top = '50%';
            }
        });

        document.addEventListener('DOMContentLoaded', function () {
            const editProfileBtn = document.querySelector('.btn-edit-profile');

            editProfileBtn.addEventListener('click', function () {
                const infoValues = document.querySelectorAll('.info-value');

                infoValues.forEach(function (infoValue) {
                    const value = infoValue.textContent;
                    const input = document.createElement('input');
                    const labelFor = infoValue.previousElementSibling.getAttribute('for');

                    input.setAttribute('type', labelFor === 'birthday' ? 'date' : 'text');
                    input.setAttribute('value', value);
                    input.setAttribute('id', labelFor.toLowerCase());
                    input.classList.add('info-input');

                    infoValue.parentNode.replaceChild(input, infoValue);
                });

                const saveBtn = document.createElement('button');

                saveBtn.textContent = 'Сохранить';
                saveBtn.classList.add('btn', 'btn-edit-profile', 'btn-save-profile');

                saveBtn.addEventListener('click', function() {
                    const inputValues = document.querySelectorAll('.info-input');
                    post(inputValues);

                    inputValues.forEach(function (inputValue) {
                        const value = inputValue.value;
                        const span = document.createElement('span');

                        span.textContent = value;
                        span.classList.add('info-value');

                        inputValue.parentNode.replaceChild(span, inputValue);
                    });

                    saveBtn.parentNode.removeChild(saveBtn);
                    editProfileBtn.style.display = 'block';
                });

                editProfileBtn.parentNode.insertBefore(saveBtn, editProfileBtn);
                editProfileBtn.style.display = 'none';
            });
        });

        function post(inputValues) {
            const formData = new FormData();

            inputValues.forEach(function(input) {
                formData.append(input.id, input.value);
            });

            fetch('{{ route('profile.update') }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }

                    return response.json();
                })
                .then(data => {
                    console.log(data);
                    window.location.reload();
                })
                .catch(error => {
                    console.error(error);
                });
        }
    </script>

    <style>
        .info-input, .credential-input {
            width: calc(100% - 300px);
            margin-right: 10px;
            font-size: 1.2em;
            background-color: transparent;
            border: 0;
            color: white;
        }

        input[type="date"]::-webkit-calendar-picker-indicator {
            filter: invert(1);
        }

        @media (max-width: 768px) {
            .info-input, .credential-input {
                width: 100%;
                white-space: normal;
            }

            .info-label, .credential-label {
                white-space: normal;
                max-width: 200px;
            }
        }

        .profile-section {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
        }

        .profile-header {
            background: rgba(0, 0, 0, 0.7);
            border-top-left-radius: 30px;
            border-top-right-radius: 30px;
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            width: 80%;
            max-width: 600px;
        }

        .profile-header::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 100%;
            height: 5px;
            background-color: white;
        }

        .profile-title, .credentials-title {
            color: white;
            font-size: 2.5em;
            margin-bottom: 20px;
            text-transform: uppercase;
            text-shadow: 2px 2px 4px rgba(200, 200, 200, 0.4);
        }

        .profile-info, .credentials-info {
            width: 100%;
            max-width: 800px;
        }

        .info-item, .credential-item {
            display: flex;
            margin-bottom: 10px;
        }

        .info-label, .credential-label {
            color: white;
            font-size: 1.2em;
            flex: 0 0 30%;
            text-align: right;
            margin-right: 10px;
            white-space: normal;
            width: 400px;
        }

        .info-value, .credential-value {
            color: white;
            font-size: 1.2em;
            flex: 1;
            overflow: hidden; /
        text-overflow: ellipsis;
            text-align: left;
        }

        .btn-edit-profile, .btn-logout, .btn-new-password {
            background-color: #666;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            cursor: pointer;
            font-size: 1.2em;
            margin-top: 10px;
        }

        .btn-edit-profile:hover, .btn-logout:hover {
            background-color: #333;
        }
    </style>

@endsection
