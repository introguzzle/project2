@php use App\Models\ReceiptMethod; use App\Models\Flow; use App\Models\PaymentMethod; @endphp
@php
    /**
     * @var PaymentMethod[] $paymentMethods
     * @var ReceiptMethod[] $receiptMethods
     */
@endphp

    <!DOCTYPE html>
<html lang="ru">
<head>
    <title>Настройки</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"/>
</head>
<body>
<div class="container mt-5">
    <h4><a href="{{ route('admin.dashboard') }}">На главную</a></h4>
    <h1>Настройки способов получения и оплаты</h1>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('internal'))
        <div class="alert alert-danger">
            {{ session('internal') }}
        </div>
    @endif

    @foreach($errors->all() as $error)
        <div class="alert alert-danger">
            {{ $error }}
        </div>
    @endforeach

    <form action="{{ route('admin.flows.update') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="receipt_method_id">Способ получения</label>
            <select class="form-control" id="receipt_method_id" name="receipt_method_id">
                @foreach($receiptMethods as $receiptMethod)
                    <option value="{{ $receiptMethod->id }}">{{ $receiptMethod->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Способы оплаты</label>
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
                                <span class="badge badge-primary"
                                      style="font-size: 0.85rem">{{ $paymentMethod->name }}</span>
                            @else
                                Отсутствуют
                            @endif
                        @endforeach
                    </td>
                    <td>
                        <form action="{{ route('admin.flows.receipts.delete') }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="receipt_method_id" value="{{$receiptMethod->id}}">
                            <button type="submit" class="btn btn-danger btn-sm">Удалить</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="d-flex flex-column flex-grow-1 mt-2 w-25 mb-5 mb-lg-5">
        <a href="{{route('admin.flows.receipts.create.index')}}" class="btn btn-primary mt-2">Добавить способ получения</a>
        <a href="{{route('admin.flows.payments.create.index')}}" class="btn btn-primary mt-2">Добавить способ оплаты</a>
        <a href="{{route('admin.flows.delete')}}" class="btn btn-danger mt-2">Очистить все</a>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
</body>
</html>
