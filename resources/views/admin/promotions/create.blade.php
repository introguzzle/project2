@php use App\Models\PromotionType; use App\Models\Flow;use Illuminate\Database\Eloquent\Collection; @endphp
@extends('admin.layouts.layout')

@php
    /**
     * @var Collection<PromotionType> $promotionTypes
     * @var Collection<Flow> $flows
     */
@endphp

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Создать новую акцию</h2>
        <button type="submit" form="promotionForm" class="btn btn-primary w-25">Создать</button>
    </div>

    <form class="mb-5" id="promotionForm" action="{{ route('admin.promotions.create') }}" method="POST"
          enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Название</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Описание</label>
            <textarea class="form-control" id="description" name="description"
                      rows="3">{{ old('description') }}</textarea>
        </div>
        <div class="mb-3">
            <label for="min_sum" class="form-label">Минимальная сумма</label>
            <input type="number" class="form-control" id="min_sum" name="min_sum" value="{{ old('min_sum') }}" required>
        </div>
        <div class="mb-3">
            <label for="max_sum" class="form-label">Максимальная сумма</label>
            <input type="number" class="form-control" id="max_sum" name="max_sum" value="{{ old('max_sum') }}" required>
        </div>
        <div class="mb-3">
            <label for="promotion_type_id" class="form-label">Тип изменения</label>
            <select class="form-select" id="promotion_type_id" name="promotion_type_id" required>
                @foreach($promotionTypes as $type)
                    <option
                        value="{{ $type->id }}" {{ (int) old('promotion_type_id') === $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="value" class="form-label">Значение</label>
            <input type="number" step="0.01" class="form-control" id="value" name="value" value="{{ old('value') }}"
                   required>
        </div>
        <div class="mb-3">
            <label class="form-label">Связки получения и оплаты</label>
            <div class="form-check-group">
                @foreach($flows as $flow)
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="flow-{{$flow->id}}" name="flows[]"
                               value="{{ $flow->id }}" @if(in_array($flow->id, old('flows', []), false)) checked @endif>
                        <label class="form-check-label" for="flow-{{$flow->id}}">
                            {{ $flow->receiptMethod->name }} - {{ $flow->paymentMethod->name }}
                        </label>
                    </div>
                @endforeach
            </div>
        </div>
    </form>
@endsection
