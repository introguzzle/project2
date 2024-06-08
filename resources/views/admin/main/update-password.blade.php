@extends('admin.layouts.layout')
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Обновить пароль</h2>
        <button type="submit" form="passwordForm" class="btn btn-primary w-25">Отправить</button>
    </div>

    <form class="mb-5" id="passwordForm" action="{{ route('admin.dashboard.update-password') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="current_password">Текущий пароль</label>
            <input type="password" class="form-control" id="current_password" name="current_password" required>
        </div>
        <div class="form-group">
            <label for="new_password">Новый пароль</label>
            <input type="password" class="form-control" id="new_password" name="new_password" required>
        </div>
        <div class="form-group">
            <label for="new_password_confirmation">Повторите пароль</label>
            <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" required>
        </div>
    </form>
@endsection

