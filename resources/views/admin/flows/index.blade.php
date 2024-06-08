@php use App\Models\ReceiptMethod; use App\Models\PaymentMethod; @endphp
@php
    /**
     * @var PaymentMethod[] $paymentMethods
     * @var ReceiptMethod[] $receiptMethods
     */
@endphp
@extends('admin.layouts.layout')
@section('content')
    <h1>Настройки способов получения и оплаты</h1>

    <form action="{{ route('admin.flows.update') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="receipt_method_id" class="form-label">Способ получения</label>
            <select class="form-select" id="receipt_method_id" name="receipt_method_id">
                @foreach($receiptMethods as $receiptMethod)
                    <option value="{{ $receiptMethod->id }}">{{ $receiptMethod->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Способы оплаты</label>
            @foreach($paymentMethods as $paymentMethod)
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="payment_method_indices[]"
                           value="{{ $paymentMethod->id }}" id="payment_method_{{ $paymentMethod->id }}">
                    <label class="form-check-label" for="payment_method_{{ $paymentMethod->id }}">
                        {{ $paymentMethod->name }}
                    </label>
                </div>
            @endforeach
        </div>

        <button type="submit" class="btn btn-primary">Сохранить</button>
    </form>

    <h2 class="mt-5">Текущие настройки</h2>
    <div>
        <table class="table table-striped">
            <thead>
            <tr>
                <th>Способ получения</th>
                <th>Способы оплаты</th>
                <th>Действия</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($receiptMethods as $receiptMethod)
                <tr>
                    <td>{{ $receiptMethod->name }}</td>
                    <td>
                        @foreach ($receiptMethod->paymentMethods as $paymentMethod)
                            @if ($paymentMethod)
                                <span class="badge bg-primary" style="font-size: 0.85rem">{{ $paymentMethod->name }}</span>
                            @else
                                Отсутствуют
                            @endif
                        @endforeach
                    </td>
                    <td>
                        <form action="{{ route('admin.flows.receipts.delete') }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="receipt_method_id" value="{{ $receiptMethod->id }}">
                            <button type="submit" class="btn btn-danger btn-sm">Удалить</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="d-flex flex-column flex-grow-1 mt-2 w-25 mb-5 mb-lg-5">
        <a href="{{ route('admin.flows.receipts.create.index') }}" class="btn btn-primary mt-2">Добавить способ получения</a>
        <a href="{{ route('admin.flows.payments.create.index') }}" class="btn btn-primary mt-2">Добавить способ оплаты</a>
        <a href="{{ route('admin.flows.delete') }}" class="btn btn-danger mt-2">Очистить все</a>
    </div>
@endsection
