@extends('admin.layouts.layout')
@section('title', 'Создать')
@section('back')
    {{route('admin.statuses.index')}}
@endsection
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Создать новый статус</h2>
        <button type="submit" form="categoriesForm" class="btn btn-primary w-25">Создать</button>
    </div>

    <form action="{{ route('admin.statuses.create') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="name">Название</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Сохранить</button>
    </form>
@endsection
