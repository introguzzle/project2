@php use App\Models\Product; @endphp
@php /** @var Product $product */ @endphp

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" style="text-align: center">
            Вы действительно хотите удалить продукт <strong>{{$product->name}}</strong>?
        </h5>
    </div>
    <div class="modal-footer d-flex justify-content-center w-100">
        <button onclick="closeDialog()" type="button" class="btn w-50 btn-secondary mx-2 my-2" data-dismiss="modal">Отмена</button>
        <button onclick="confirmDelete()" type="button" class="btn w-50 btn-primary btn-danger mx-2 my-2" id="save_changes">Подтвердить</button>
    </div>
</div>

<script>
    function closeDialog() {
        window.parent.closeDialog();
    }

    function confirmDelete() {
        const url = '{{route('admin.products.delete')}}';
        const data = {
            product_id: '{{$product->id}}'
        };

        const options = {
            method: 'POST',
            headers: {
                'Accept': 'application-json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{csrf_token()}}',
                'Access-Control-Allow-Credentials': true,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(data)
        };

        fetch(url, options)
            .then(response => {
                window.parent.reloadTable();
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
        text-align: center;
        justify-content: center;
        display: flex;
        width: 100%;
    }

    .modal-title {
        font-size: 1.25rem;
        text-align: center;
    }

    .modal-body {
        padding: 20px;
    }

    .modal-footer {
        border-top: none;
        display: flex;
        justify-content: center;
    }
</style>
