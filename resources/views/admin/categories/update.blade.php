@php use App\Models\Category; @endphp
@php
    /**
     * @var Category[] $categories
     */
@endphp
@extends('admin.layouts.layout')
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Редактировать категорию</h2>
        <button type="submit" form="categoriesForm" class="btn btn-primary w-25">Обновить</button>
    </div>

    <form class="mb-5" id="categoriesForm" action="{{ route('admin.categories.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Название</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $category->name }}" required>
        </div>
        <div class="mb-3">
            <label for="parent_id" class="form-label">Родительская категория</label>
            <select class="form-select" id="parent_id" name="parent_id">
                <option value="0">Отсутствует</option>
                @foreach($categories as $c)
                    <option value="{{ $c->id }}" {{ $c->id === $category?->parent?->id ? 'selected' : '' }}>{{ $c->name }}</option>
                @endforeach
            </select>
        </div>
        <input type="hidden" name="category_id" value="{{ $category->id }}" id="category_id">
    </form>
@endsection
