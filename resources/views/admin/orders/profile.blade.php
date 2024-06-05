@php use App\Models\User\Profile; @endphp
    <!DOCTYPE html>
<html lang="ru">
<head>
    <title>Профиль</title>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0 maximum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"/>
    <link href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css" rel="stylesheet">

    <style>
        #profile-table {
            width: 100%;
        }

        #order-table {
            width: 80vw;
            overflow: auto;
        }

        .table-wrapper {
            width: 80vw;
            overflow-x: auto;
        }

        body {
            font-family: Arial, sans-serif;
        }

        .profile-details {
            border-collapse: collapse;
            width: 100%;
        }

        .profile-details th,
        .profile-details td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            white-space: nowrap; /* Текст будет в одну строку */
        }

        .profile-details th {
            background-color: #f2f2f2;
        }

        @media screen and (max-width: 600px) {
            .profile-details th,
            .profile-details td {
                width: auto;
                display: block;
                text-align: center;
            }
        }
    </style>
</head>
@php /** @var Profile $profile */ @endphp
<body>

<div class="container mt-5">
    <h2><a href="{{route('admin.dashboard')}}"> На главную </a></h2>
    <h3 class="mb-2"> Вы зашли как {{\App\Other\Auth::getProfile()?->name}} </h3>
    <h2 class="mb-4">Заказы</h2>

    <h1>Профиль</h1>
    <div class="table-wrapper">
        <table id="profile-table" class="profile-details fa-table table table-ordered">
            <thead>
            <tr>
                <th>Имя</th>
                <th>Адрес</th>
                <th>Почта</th>
                <th>Телефон</th>
                <th>День рождения</th>
                <th>Дата создания</th>
                <th>Дата обновления</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>{{ $profile->name }}</td>
                <td>{{ $profile->address }}</td>
                <td>{{ $profile->identity->email }}</td>
                <td>{{ $profile->identity->phone }}</td>
                <td>{{ $profile->birthday }}</td>
                <td>{{ formatDate($profile->createdAt, true) }}</td>
                <td>{{ formatDate($profile->updatedAt, true) }}</td>
            </tr>
            </tbody>
        </table>
    </div>

    <table id="order-table" class="table table-bordered">
        <thead>
        <tr>
            <th>ID</th>
            <th>Статус</th>

            <th>Сумма</th>
            <th>Кол-во</th>

            <th>Телефон</th>
            <th>Адрес</th>
            <th>Описание</th>

            <th>Способ оплаты</th>
            <th>Способ получения</th>

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
    const table = $('#order-table').DataTable({
        fnInitComplete: function() {
            $('.dataTables_scrollHead').css('overflow', 'auto');

            $('.dataTables_scrollHead').on('scroll', function () {
                $('.dataTables_scrollBody').scrollLeft($(this).scrollLeft());
            });
        },
        processing: true,
        serverSide: true,
        ajax: "{{ route('admin.orders.profile.index', ['id' => $profile->id]) }}",
        scrollX: true,
        columns: [
            {data: 'order.id'},
            {data: 'status.name'},
            {data: 'order.total_amount'},
            {data: 'order.total_quantity'},

            {data: 'order.phone'},
            {data: 'order.address'},
            {data: 'order.description'},

            {data: 'payment_method.name'},
            {data: 'receipt_method.name'},
            {
                data: 'profile-link',
                render: function (data, type, full, meta) {
                    return `<a href=${data}>Перейти</a>`;
                }
            },
            {
                data: 'details-link',
                render: function (data, type, full, meta) {
                    return `<a href=${data}>Перейти</a>`;
                }
            },
            {data: 'created_at'},
            {data: 'updated_at'},
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false
            },
        ],
        columnDefs: [
            {width: '80px', targets: 3},
            {width: '200px', targets: [5, 11, 12]},
            {width: '250px', targets: 13}
        ],
    });
</script>

<dialog aria-label="modal-dialog" id="modal-dialog" class="orders-dialog">
    <iframe id="dialogFrame" src="" style="width: 100%; height: 100%; border: none;"></iframe>
</dialog>

<style>
    .orders-dialog {
        width: 600px;
        height: 400px;
        border-radius: 15px;
        overflow: hidden;
    }
</style>

<script>
    function openDialog(url) {
        const dialog = document.getElementById('modal-dialog');
        const dialogFrame = document.getElementById('dialogFrame');
        dialogFrame.src = url;
        dialog.showModal();

        dialog.addEventListener('click', function (event) {
            const rect = dialog.getBoundingClientRect();
            const isInDialog = (rect.top <= event.clientY && event.clientY <= rect.top + rect.height &&
                rect.left <= event.clientX && event.clientX <= rect.left + rect.width);
            if (!isInDialog) {
                dialog.close();
            }
        });
    }

    function closeDialog() {
        const dialog = document.getElementById('modal-dialog');
        dialog.close();
    }

    function reloadTable() {
        table.ajax.reload();
    }
</script>

</html>
