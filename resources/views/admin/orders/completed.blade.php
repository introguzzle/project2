@extends('admin.layouts.layout')

@section('content')
    <h2 class="mb-4">Завершенные заказы</h2>
    <table id="this-table" class="table table-bordered table-responsive rounded">
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
        </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
@endsection
@section('script')
    <script type="text/javascript">
        const map = {
            'id': 0,
            'status_id': 1,
            'total_amount': 2,
            'total_quantity': 3,
            'phone': 4,
            'address': 5,
            'description': 6,
            'payment_method_id': 7,
            'receipt_method_id': 8,
            'created_at': 9,
            'updated_at': 10
        };

        function getIndices(columns) {
            return columns.map(column => map[column]);
        }

        const table = $('#this-table').DataTable({
            fnInitComplete: () => {
                $('.dt-scroll-head')
                    .css('overflow', 'auto')
                    .on('scroll', function () {
                        $('.dt-scroll-body').scrollLeft($(this).scrollLeft());
                    });
            },

            processing: true,
            serverSide: true,

            ajax: {
                url: '{{ route('admin.orders.completed.index') }}',
                type: 'GET'
            },

            columns: [
                {
                    data: 'id',
                    render: data => {
                        return `<a href=/admin/orders/${data}>${data}</a>`
                    }
                },
                {data: 'status_id'},
                {data: 'total_amount'},
                {data: 'total_quantity'},

                {data: 'phone'},
                {data: 'address'},
                {data: 'description'},

                {data: 'payment_method_id'},
                {data: 'receipt_method_id'},
                {
                    data: 'profile-link',
                    render: data => {
                        return `<a href=${data}>Перейти</a>`;
                    }
                },
                {
                    data: 'details-link',
                    render: data => {
                        return `<a href=${data}>Перейти</a>`;
                    }
                },
                {data: 'created_at'},
                {data: 'updated_at'},
            ],

            order: [],
            scrollX: true,

            columnDefs: [
                {width: '80px', targets: map['total_quantity']},
                {width: '150px', targets: getIndices(['created_at', 'updated_at'])},
                {width: '200px', targets: getIndices(['description', 'address'])}
            ],
        }).on('click', '.complete', function () {
            let id = $(this).data('id');
            sendPostRequest("{{ route('admin.orders.complete') }}", {id: id}, function (response) {
                console.log(response);
            });
        });

        const csrfToken = $('meta[name="csrf-token"]').attr('content');

        const sendPostRequest = function (url, data, onSuccess) {
            $.ajax({
                url: url,
                type: 'POST',
                data: data,
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function (response) {
                    onSuccess(response);
                    reloadTable();
                },
                error: function (response) {
                    console.error('Error:', response);
                }
            });
        };

        function reloadTable() {
            table.ajax.reload();
        }
    </script>
@endsection

