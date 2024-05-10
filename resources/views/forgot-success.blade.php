@extends('nav.nav')

@section('content')
    <section id="home">
        <div class="overlay">
            <header id="main-header" class="forgot-header">
                <h2 class="forgot-title">Восстановление</h2>
                <div class="success">
                    <p>Письмо было отправлено на вашу почту</p>
                </div>
                <div class="form-group">
                    <a href="{{ route('login') }}">Вернуться обратно</a>
                </div>
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
    </style>
@endsection

<style>
    .forgot-header {
        background: rgba(0, 0, 0, 0.7);
        border-radius: 30px;
        padding: 50px;
        align-items: center;
        display: flex;
        flex-direction: column;
        justify-content: center;
        margin: 0 auto;
    }

    .forgot-title {
        color: white;
        font-size: 2.5em;
        margin-bottom: 20px;
        text-transform: uppercase;
        text-shadow: 2px 2px 4px rgba(200, 200, 200, 0.4);
        position: relative;
        display: inline-block;
    }

    .success {
        color: white;
        font-size: 1.2em;
        text-align: center;
        margin-bottom: 20px;
    }

    .form-group a {
        color: #666666;
        font-size: 1em;
    }

    .form-group a:hover {
        color: #FFFFFF;
    }

    .form-group a:active {
        color: dodgerblue;
    }

    @media (max-width: 768px) {
        .forgot-header {
            max-width: 80vw;
        }
    }

    @media (min-width: 769px) {
        .forgot-header {
            max-width: 70vw; /* Увеличение размера хедера для десктопных устройств */
        }
    }

    @media (min-width: 1200px) {
        .forgot-header {
            max-width: 50vw; /* Ещё большее увеличение размера хедера для крупных экранов */
        }
    }
</style>
