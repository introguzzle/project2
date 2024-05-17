<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title">Действия с заказом {{$id}}<span id="order_id"></span></h5>
    </div>
    <div class="modal-body">
        <div class="form-group">
            <label for="status">Статус:</label>
            <select class="form-control" id="status">
                <option value="pending">В ожидании</option>
                <option value="processing">Обрабатывается</option>
                <option value="completed">Завершен</option>
                <option value="cancelled">Отменен</option>
            </select>
        </div>
        <div class="form-group">
            <label for="note">Примечание:</label>
            <textarea class="form-control" id="note" rows="3"></textarea>
        </div>
    </div>
    <div class="modal-footer">
        <button onclick="closeDialog()" type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
        <button onclick="save()" type="button" class="btn btn-primary" id="save_changes">Сохранить изменения</button>
    </div>
</div>

<script>
    function closeDialog() {
        window.parent.closeDialog();
    }

    function save() {

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
