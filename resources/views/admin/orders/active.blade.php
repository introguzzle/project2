@extends('admin.layouts.layout')

@section('style')
    <style>
        #this-table {
            width: 80vw;
            overflow: auto;
        }

        .orders-dialog {
            width: 600px;
            height: 400px;
            border-radius: 15px;
            overflow: hidden;
        }
    </style>
@endsection

@section('content')
    <dialog aria-label="modal-dialog" id="modal-dialog" class="orders-dialog">
        <iframe id="dialogFrame" src="" style="width: 100%; height: 100%; border: none;"></iframe>
    </dialog>

    <h2>Активные заказы</h2>

    <div class="form-group mb-3 w-25">
        <label for="auto-refresh-interval">Интервал автообновления (в секундах):</label>
        <select id="auto-refresh-interval" class="form-control w-100" style="width: 200px;">
            <option value="0">Выключено</option>
            <option value="-1">В реальном времени</option>
            <option value="5">5 секунд</option>
            <option value="20">20 секунд</option>
            <option value="60">1 минута</option>
            <option value="300">5 минут</option>
        </select>
        <button id="manual-refresh" class="btn btn-info my-3 w-100">Обновить</button>
    </div>

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

            <th>Действия</th>
        </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
@endsection
@section('script')
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
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
                url: '{{ route('admin.orders.active.index') }}',
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
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ],

            order: [],
            scrollX: true,
            sScrollX: true,

            columnDefs: [
                {width: '80px', targets: map['total_quantity']},
                {width: '150px', targets: getIndices(['created_at', 'updated_at'])},
                {width: '200px', targets: getIndices(['description', 'address'])}
            ],

        }).on('click', '.complete',  function() {
            let id = $(this).data('id');
            sendPostRequest("{{ route('admin.orders.complete') }}", {id: id}, response => {
                console.log(response);
            });
        });

        const csrfToken = $('meta[name="csrf-token"]').attr('content');

        const sendPostRequest = (url, data, onSuccess) => {
            $.ajax({
                url: url,
                type: 'POST',
                data: data,
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: response => {
                    onSuccess(response);
                    reloadTable();
                },
                error: response => {
                    console.error('Error:', response);
                }
            });
        };

        function reloadTable() {
            table.ajax.reload();
        }

        let autoRefreshIntervalId = null;
        let pusher = null;
        let channel = null;

        function setupPusher() {
            Pusher.logToConsole = true;

            pusher = new Pusher('{{ env('PUSHER_APP_KEY') }}', {
                cluster: '{{ env('PUSHER_APP_CLUSTER') }}'
            });

            channel = pusher.subscribe('orders');
            channel.bind('order.created', () => {
                reloadTable();
            });
        }

        function teardownPusher() {
            if (channel) {
                channel.unbind('order.created');
                pusher.unsubscribe('orders');
                channel = null;
                pusher = null;
            }
        }

        $('#manual-refresh').click(() => {
            reloadTable();
        });

        $('#auto-refresh-interval').change(function() {
            const interval = parseInt($(this).val(), 10);
            clearInterval(autoRefreshIntervalId);
            teardownPusher();

            if (interval === -1) {
                setupPusher();
            } else if (interval > 0) {
                autoRefreshIntervalId = setInterval(() => {
                    reloadTable();
                }, interval * 1000);
            }
        });

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
    </script>
@endsection
