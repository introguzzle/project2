<!DOCTYPE html>
<html lang="ru">
<head>
    <title>Заказы</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0 maximum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"/>
    <link href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css" rel="stylesheet">

    <style>
        #this-table {
            width: 80vw;
            overflow: auto;
        }
    </style>

</head>
<body>

<div class="container mt-5">
    <h3 class="mb-2"> Вы зашли как {{\App\Utils\Auth::getProfile()?->getName()}} </h3>
    <h2 class="mb-4">Заказы</h2>
    <table id="this-table" class="table table-bordered">
        <thead>
        <tr>
            <th>ID</th>
            <th>Статус</th>
            <th>Сумма</th>

            <th>Телефон</th>
            <th>Адрес</th>

            <th>Профиль</th>
            <th>Детали</th>

            <th>Дата создания</th>
            <th>Дата обновления</th>

            <th>Действия</th>
        </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>

</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
<script type="text/javascript">
    $(function () {
        const table = $('#this-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.orders') }}",
            scrollX: true,
            columns: [
                {data: 'order.id'},
                {data: 'status.name'},
                {data: 'order.price'},

                {data: 'order.phone'},
                {data: 'order.address'},

                {
                    data: 'profile-link',
                    render: function(data, type, full, meta){
                        return `<a href=${data}> Перейти </a>`;
                    }
                },
                {
                    data: 'details-link',
                    render: function(data, type, full, meta){
                        return `<a href=${data}> Перейти </a>`;
                    }
                },
                {data: 'order.created_at'},
                {data: 'order.updated_at'},
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ],
            columnDefs: [
                { width: '200px', targets: [4, 7, 8]},
                { width: '250px', targets: 9}
            ],
        });
    });

    $(function () {
        const csrfToken = $('meta[name="csrf-token"]').attr('content');

        const sendPostRequest = function(url, data, successCallback) {
            $.ajax({
                url: url,
                type: 'POST',
                data: data,
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: successCallback,
                error: function(response) {
                    console.error('Error:', response);
                }
            });
        };

        $('#this-table').on('click', '.finalize', function() {
            let id = $(this).data('id');
            sendPostRequest("{{ route('admin.orders.complete') }}", {id: id}, function(response) {
                console.log(response);
            });
        });

        $('#this-table').on('click', '.delete', function() {
            let id = $(this).data('id');
            sendPostRequest("{{ route('admin.orders.delete') }}", {id: id}, function(response) {
                console.log(response);
            });
        });
    });
</script>
</html>
