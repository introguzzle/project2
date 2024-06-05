@php use App\Models\Category; @endphp
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
@php
    /** @var Category $category */
@endphp
<div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title text-center">
                Удаление этой категории повлечет за собой удаление всех продуктов, связанных с ней.
                <br>
                Вы действительно хотите удалить категорию <span><br><strong>{{$category->name}}?</strong></span>
            </h5>
        </div>
        <div class="modal-footer d-flex justify-content-center w-100">
            <button onclick="closeDialog()" type="button" class="btn w-50 btn-secondary mx-2 my-2" data-dismiss="modal">Отмена</button>
            <button onclick="confirmDelete()" type="button" class="btn w-50 btn-primary btn-danger mx-2 my-2" id="save_changes">Подтвердить</button>
        </div>
    </div>
</div>

<script>
    function closeDialog() {
        window.parent.closeDialog();
    }

    function confirmDelete() {
        const url = '{{ route('admin.categories.delete') }}';
        const data = {
            category_id: '{{ $category->id }}'
        };

        const options = {
            method: 'POST',
            headers: {
                'Accept': 'application-json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
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
    }

    .modal-content {
        border-radius: 10px;
    }

    .modal-header {
        border-bottom: none;
        text-align: center;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .modal-title {
        font-size: 1.25rem;
    }

    .modal-footer {
        border-top: none;
        display: flex;
        justify-content: center;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
