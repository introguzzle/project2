@extends('admin.layouts.layout')

@section('content')
    <h2>Завершенные заказы</h2>
    <table id="this-table" class="table table-bordered table-responsive rounded">
        <thead>
        <tr>
            <th>ID</th>
            <th>Статус</th>

            <th>Сумма</th>
            <th>Сумма после скидок</th>
            <th>Примененные скидки</th>
            <th>Кол-во</th>

            <th>Телефон</th>
            <th>Адрес</th>
            <th>Описание</th>

            <th>Получение</th>
            <th>Оплата</th>

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
        const keys = [
            'id',
            'status',

            'total_amount',
            'after_amount',
            'applied_promotions',
            'total_quantity',

            'phone',
            'address',
            'description',

            'receipt_method',
            'payment_method',

            'profile-link',
            'details-link',
            'created_at',
            'updated_at',
            'action'
        ];

        const map = {};

        for (let i = 0; i < keys.length; i++) {
            map[keys[i]] = i;
        }

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

                $('label[for="dt-length-0"]').text('записей на странице');
                $('label[for="dt-search-0"]').text('Поиск');
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
                {data: 'status'},

                {data: 'total_amount'},
                {data: 'after_amount'},
                {data: 'applied_promotions'},
                {data: 'total_quantity'},

                {data: 'phone'},
                {data: 'address'},
                {data: 'description'},

                {data: 'receipt_method'},
                {data: 'payment_method'},

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

