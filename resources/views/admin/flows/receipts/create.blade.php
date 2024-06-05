@extends('admin.layouts.layout')
@section('title', 'Создать')
@section('back')
    {{route('admin.flows.index')}}
@endsection
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Создать способ получения</h2>
        <button type="submit" form="receiptForm" class="btn btn-primary w-25">Создать</button>
    </div>

    <form id="receiptForm" action="{{ route('admin.flows.receipts.create') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="name">Название</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                   value="{{ old('name') }}" required>
            @error('name')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
    </form>
@endsection
