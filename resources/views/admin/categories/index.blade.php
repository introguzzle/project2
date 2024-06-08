@extends('admin.layouts.layout')
@section('style')
    <style>
        #categories-table {
            width: 80vw;
            overflow: auto;
        }

        .categories-dialog {
            width: 600px;
            height: 400px;
            border-radius: 15px;
            overflow: hidden;
        }
    </style>
@endsection
@section('content')
    <h2 class="mb-4">Категории</h2>

    <div class="form-group w-25">
        <a class="btn btn-primary mb-2 w-100" href="{{ route('admin.categories.create.index') }}">Добавить</a>
    </div>

    <table id="categories-table" class="table table-bordered">
        <thead>
        <tr>
            <th>ID</th>
            <th>Название</th>

            <th>Родитель</th>
            <th>Количество продуктов</th>
            <th>Продукты</th>

            <th>Действия</th>

            <th>Дата создания</th>
            <th>Дата обновления</th>
        </tr>
        </thead>
        <tbody>
        </tbody>
    </table>

<dialog aria-label="modal-dialog" id="modal-dialog" class="categories-dialog">
    <iframe id="dialogFrame" src="" style="width: 100%; height: 100%; border: none;"></iframe>
</dialog>
@endsection
@section('script')
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
    </script>
    <script type="text/javascript">
        const map = {
            'id': 0,
            'name': 1,
            'parent': 2,
            'products_count': 3,
            'products': 4,
            'action': 5,
            'created_at': 6,
            'updated_at': 7
        };

        const updateUrl = '/admin/categories/update/';
        const table = $('#categories-table').DataTable({
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
                url: "{{ route('admin.categories.index') }}",
                type: 'GET'
            },

            scrollX: true,

            columns: [
                {
                    data: 'category.id',
                    render: data => {
                        return `<a href="${updateUrl + data}"> ${data} </a>`
                    }
                },

                {data: 'category.name'},
                {
                    data: 'category.parent',
                    render: data => {
                        return data
                            ? `<a href="${updateUrl + data['id']}"> ${data['name']}</a>`
                            : '';
                    }
                },
                {
                    data: 'products_count'
                },
                {
                    data: 'products',
                    render: data => {
                        if (!data || data.length === 0) {
                            return '';
                        }

                        let list = '<ul>';
                        data.forEach(product => {
                            list += `<li>${product.name}</li>`;
                        });

                        list += '</ul>';
                        return list;
                    },
                    orderable: false
                },

                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },

                {data: 'created_at'},
                {data: 'updated_at'},

            ],

            columnDefs: [
                {width: '1px', targets: [map['id'], map['products_count']]},
                {width: '120px', targets: [map['name'], map['parent']]},
                {width: '200px', targets: [map['created_at'], map['updated_at']]},
                {width: '250px', targets: [map['products']]}
            ],
        }).on('click', '.delete', function () {
            let id = $(this).data('id');
            sendPostRequest("{{ route('admin.categories.delete') }}", {id: id}, function (response) {
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

        function confirmAndOpenDialog(url, name) {
            if (confirm(`Вы уверены, что хотите удалить категорию ${name}?`)) {
                openDialog(url);
            }
        }
    </script>
@endsection

