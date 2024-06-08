@extends('admin.layouts.layout')

@section('style')
    <style>
        #products-table {
            width: 80vw;
            overflow: auto;
        }

        .products-dialog {
            width: 600px;
            height: 400px;
            border-radius: 15px;
            overflow: hidden;
        }

        .compact-img {
            max-width: 80px;
            max-height: 80px;
        }

        .full-description {
            display: none;
        }
    </style>
@endsection

@section('content')
    <h2 class="mb-4">Продукты</h2>

    <div class="form-group w-25">
        <a class="btn btn-primary mb-2 w-100" href="{{ route('admin.products.create.index') }}">Добавить</a>
    </div>

    <table id="products-table" class="table table-bordered table-responsive rounded">
        <thead>
        <tr>
            <th>ID</th>
            <th>Название</th>
            <th>Категория</th>
            <th>Изображение</th>
            <th>Краткое описание</th>
            <th>Полное описание</th>
            <th>Цена</th>
            <th>Вес</th>
            <th>Доступность</th>
            <th>Действия</th>
            <th>Дата создания</th>
            <th>Дата обновления</th>
        </tr>
        </thead>
        <tbody>
        </tbody>
    </table>

    <dialog aria-label="modal-dialog" id="modal-dialog" class="products-dialog">
        <iframe id="dialogFrame" src="" style="width: 100%; height: 100%; border: none;"></iframe>
    </dialog>
@endsection

@section('script')
    <script type="text/javascript">
        const uploadUrl = '/admin/products/update/';
        const table = $('#products-table').DataTable({
            fnInitComplete: () => {
                $('.dt-scroll-head')
                    .css('overflow', 'auto')
                    .on('scroll', function () {
                        $('.dt-scroll-body').scrollLeft($(this).scrollLeft());
                    });

                $('label[for="dt-length-0"]').text('записей на странице');
                $('label[for="dt-search-0"]').text('Поиск');

                $('li.dt-paging-button.page-item.disabled')
                    .find('a.page-link.ellipsis')
                    .removeClass('ellipsis');
            },

            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('admin.products.index') }}",
                type: 'GET'
            },

            columns: [
                {
                    data: 'product.id',
                    render: data => {
                        return `<a href="${uploadUrl + data}"> ${data} </a>`;
                    }
                },
                {data: 'product.name'},
                {data: 'category'},
                {
                    data: 'image',
                    render: data => {
                        return data
                            ? `<img class="compact-img" src="${data}" alt="Изображение">`
                            : `Отсутствует`;
                    }
                },
                {data: 'product.short_description'},
                {
                    data: 'product.full_description',
                    render: data => {
                        return `
                            <div class="short-description">${data.substring(0, 50)}...</div>
                            <div class="full-description">${data}</div>
                            <button class="btn btn-link show-more">Показать больше</button>
                        `;
                    }
                },
                {data: 'product.price'},
                {data: 'product.weight'},
                {
                    data: 'product.availability',
                    render: data => {
                        return (!!data) ? 'Да' : 'Нет';
                    }
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

            scrollX: true,

            columnDefs: [
                {width: '80px', targets: [1, 9, 10]},
                {width: '250px', targets: 5}
            ],
        }).on('click', '.delete', function () {
            let id = $(this).data('id');
            sendPostRequest("{{ route('admin.products.delete') }}", {id: id}, function (response) {
                console.log(response);
            });
        }).on('click', '.upload', function () {
            let id = $(this).data('id');

        }).on('click', '.show-more', function () {
            const button = $(this);
            const fullDescription = button.siblings('.full-description');
            const shortDescription = button.siblings('.short-description');

            if (fullDescription.is(':visible')) {
                fullDescription.hide();
                shortDescription.show();
                button.text('Показать больше');
            } else {
                fullDescription.show();
                shortDescription.hide();
                button.text('Скрыть');
            }
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

        function closeDialog() {
            const dialog = document.getElementById('modal-dialog');
            dialog.close();
        }

        function confirmAndOpenDialog(url, name) {
            if (confirm(`Вы уверены, что хотите удалить продукт ${name}?`)) {
                openDialog(url);
            }
        }

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
    </script>
@endsection
