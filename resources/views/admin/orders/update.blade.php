@php use App\Models\Order;use App\Models\Status; @endphp
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
      integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

@php
    /**
    * @var Order $order
    * @var Status[] $statuses
    */
@endphp

<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title">Действия с заказом {{$order->id}}<span id="order_id"></span></h5>
    </div>
    <div class="modal-body">
        <div class="mb-3">
            <label for="status" class="form-label">Статус:</label>
            <select class="form-select" id="status">
                @foreach($statuses as $status)
                    <option value="{{$status->id}}" {{ $order->status->id === $status->id ? 'selected' : ''}}>{{$status->name}}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="note" class="form-label">Примечание:</label>
            <textarea class="form-control" id="note" rows="3">{{ $order->description }}</textarea>
        </div>
        <div class="modal-footer">
            <button onclick="closeDialog()" type="button" class="btn btn-secondary mx-1">Закрыть</button>
            <button onclick="save()" type="button" class="btn btn-primary mx-1" id="save_changes">Сохранить изменения</button>
        </div>
    </div>
</div>

<script>
    function closeDialog() {
        window.parent.closeDialog();
    }

    function save() {
        const url = '{{route('admin.orders.update')}}';
        const data = {
            status: document.getElementById('status').value,
            order: '{{$order->id}}',
            description: document.getElementById('note').value
        };

        const options = {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{csrf_token()}}',
                'Access-Control-Allow-Credentials': true,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(data)
        };

        fetch(url, options)
            .then(response => {
                try {
                    window.parent.reloadTable();
                } catch (e) {

                }

                closeDialog();
            });
    }
</script>

<style>
    .modal-dialog {
        max-width: 600px;
        max-height: 400px;
    }

    .modal-content {
        border-radius: 10px;
    }

    .modal-header {
        border-bottom: none;
    }

    .modal-title {
        font-size: 1.25rem;
    }

    .modal-body {
        padding: 20px;
    }

    .modal-footer {
        border-top: none;
    }
</style>
