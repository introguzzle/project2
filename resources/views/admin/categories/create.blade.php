@php use App\Models\Category; @endphp
@extends('admin.layouts.layout')

@php
    /**
     * @var Category[] $categories
     */
@endphp

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Создать новую категорию</h2>
        <button type="submit" form="categoriesForm" class="btn btn-primary w-25">Создать</button>
    </div>

    <form class="mb-5" id="categoriesForm" action="{{ route('admin.categories.create') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Название</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
        </div>
        <div class="mb-3">
            <label for="parent_id" class="form-label">Родительская категория</label>
            <select class="form-select" id="parent_id" name="parent_id">
                <option value="0">Отсутствует</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ (int) old('parent_id') === $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
    </form>
@endsection
