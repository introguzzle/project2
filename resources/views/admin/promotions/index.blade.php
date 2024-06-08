@php use App\Models\Promotion;use App\Models\PromotionType;use App\Models\Flow; use Illuminate\Database\Eloquent\Collection; @endphp
@extends('admin.layouts.layout')
@php
    /**
     * @var Collection<Promotion> $promotions
     * @var Collection<PromotionType> $promotionTypes
     * @var Collection<Flow> $flows
     */
@endphp
@section('style')
    <style>
        .card {
            border-radius: 15px;
            margin-bottom: 20px;
        }

        .list-item {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .list-item div {
            flex: 1;
            padding-right: 10px; /* Добавим немного отступа справа для красоты */
        }

        .list-item div:first-child {
            font-weight: bold;
            flex: 0 0 200px; /* Увеличим ширину */
        }

        .custom-file-label {
            cursor: pointer;
        }

        .d-none {
            display: none;
        }

        .img-thumbnail {
            max-width: 150px;
        }

        .actions {
            display: flex;
            gap: 10px;
        }

        .flow-item {
            display: flex;
            align-items: center;
        }
    </style>
@endsection

@section('content')
    <h1>Акции</h1>
    <div class="w-100 d-flex flex-column">
        <a href="{{ route('admin.promotions.create.index') }}" class="btn btn-primary w-25 mt-2">Создать</a>
    </div>

    @foreach ($promotions as $promotion)
        <div class="container-fluid m-3 px-5 py-4 card">
            <form action="{{ route('admin.promotions.update')}}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <h3> {{ $promotion->name }}</h3>
                <div class="list-item">
                    <div>ID</div>
                    <div class="fs-6">{{ $promotion->id }}</div>
                </div>
                <div class="list-item">
                    <div>Название</div>
                    <div class="fs-6">
                        <input type="text" class="form-control d-none" name="name" value="{{ $promotion->name }}" readonly>
                        <span>{{ $promotion->name }}</span>
                    </div>
                </div>
                <div class="list-item">
                    <div>Описание</div>
                    <div class="fs-6">
                        <input type="text" class="form-control d-none" name="description" value="{{ $promotion->description }}" readonly>
                        <span>{{ $promotion->description }}</span>
                    </div>
                </div>
                <div class="list-item">
                    <div>Минимальная сумма</div>
                    <div class="fs-6">
                        <input type="number" class="form-control d-none" name="min_sum" value="{{ $promotion->minSum }}" readonly>
                        <span>{{ $promotion->minSum }}</span>
                    </div>
                </div>
                <div class="list-item">
                    <div>Максимальная сумма</div>
                    <div class="fs-6">
                        <input type="number" class="form-control d-none" name="max_sum" value="{{ $promotion->maxSum }}" readonly>
                        <span>{{ $promotion->maxSum }}</span>
                    </div>
                </div>
                <div class="list-item">
                    <div>Тип изменения</div>
                    <div class="fs-6">
                        <select class="form-control d-none" name="promotion_type_id" disabled>
                            @foreach($promotionTypes as $type)
                                <option value="{{ $type->id }}" {{ $promotion->promotionType->id === $type->id ? 'selected' : '' }}>
                                    {{ $type->name }}
                                </option>
                            @endforeach
                        </select>
                        <span>{{ $promotion->promotionType->name }}</span>
                    </div>
                </div>
                <div class="list-item">
                    <div>Значение</div>
                    <div class="fs-6">
                        <input type="number" step="0.01" class="form-control d-none" name="value" value="{{ $promotion->value }}" readonly>
                        <span>{{ $promotion->value }}</span>
                    </div>
                </div>
                <div class="list-item">
                    <div>Дата создания</div>
                    <div class="fs-6">{{ formatDate($promotion->createdAt, true) }}</div>
                </div>
                <div class="list-item">
                    <div>Дата обновления</div>
                    <div class="fs-6">{{ formatDate($promotion->updatedAt, true) }}</div>
                </div>
                <div class="list-item">
                    <div>Потоки</div>
                    <div class="fs-6">
                        <div id="flows-{{$promotion->id}}" class="form-check-group d-none">
                            @foreach($flows as $flow)
                                <div id="flow-{{$flow->id}}" class="form-check">
                                    <input class="form-check-input" type="checkbox" name="flows[]" value="{{ $flow->id }}" @if($promotion->flows->contains($flow->id)) checked @endif>
                                    <label class="form-check-label">
                                        {{ $flow->receiptMethod->name }} - {{ $flow->paymentMethod->name }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        <ul id="flow-list-{{$promotion->id}}">
                            @foreach($promotion->flows as $flow)
                                <li class="flow-item">{{ $flow->receiptMethod->name }} - {{ $flow->paymentMethod->name }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <div class="actions w-100 d-flex flex-column">
                    <button type="button" class="btn btn-primary w-25" onclick="toggleEdit(this, {{$promotion->id}})">Редактировать</button>
                    <button type="submit" class="btn btn-primary d-none w-25">Сохранить</button>
                    <form action="{{ route('admin.promotions.delete') }}" method="POST" class="d-inline">
                        @csrf
                        <input type="hidden" value="{{$promotion->id}}" id="promotion_id" name="promotion_id">
                        <button type="submit" class="btn btn-danger w-25">Удалить</button>
                    </form>
                </div>
            </form>
        </div>
    @endforeach
@endsection

@section('script')
    <script>
        function toggleEdit(button, promotionId) {
            const form = button.closest('form');
            const inputs = form.querySelectorAll('input, select');
            const spans = form.querySelectorAll('span');
            const saveButton = form.querySelector('button[type="submit"]');
            const flowItems = form.querySelectorAll(`#flow-list-${promotionId} .flow-item`);

            inputs.forEach(input => {
                input.classList.toggle('d-none');
                if (input.tagName === 'SELECT' || input.type === 'checkbox') {
                    input.disabled = !input.disabled;
                } else {
                    input.readOnly = !input.readOnly;
                }
            });

            spans.forEach(span => span.classList.toggle('d-none'));

            const formCheckGroup = document.getElementById(`flows-${promotionId}`);
            const children = formCheckGroup.querySelectorAll('.form-check');
            children.forEach(child => {
                child.classList.remove('d-none');
                const input = child.querySelector('input');
                input.disabled = !input.disabled;
                input.classList.remove('d-none');
            });

            formCheckGroup.classList.toggle('d-none');
            flowItems.forEach(item => item.classList.toggle('d-none'));

            button.classList.toggle('d-none');
            saveButton.classList.toggle('d-none');
        }
    </script>
@endsection
