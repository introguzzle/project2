<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CompleteOrderRequest;
use App\Http\Requests\Admin\UpdateOrderRequest;
use App\Models\Order;
use App\Models\Status;
use App\Services\OrderService;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class AdminOrderController extends Controller
{
    private OrderService $orderService;

    /**
     * @param OrderService $orderService
     */
    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * @throws Exception
     */
    public function showOrders(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            return DataTables::of(
                $this->orderService->acquireLatestAndSerialize()
            )
                ->addIndexColumn()
                ->addColumn('action', static function (array $row) {
                    return '<button type="button"
                            data-id="' . $row['order']['id'] . '"
                            class="complete btn btn-success btn-sm"
                            style="width: 120px">Завершить
                   </button>
                    <button type="button"
                            onclick="openDialog(\'' . route('admin.orders.modal', ['id' => $row['order']['id']]) . '\')"
                            data-id="' . $row['order']['id'] . '"
                            class="btn btn-outline-info btn-sm"
                            style="width: 120px">Действия
                   </button>';
                })
                ->rawColumns(['action'])
                ->addColumn('profile-link', static function (array $row) {
                    return route('admin.associated.profile', ['id' => $row['order']['profile_id']]);
                })
                ->addColumn('details-link', static function (array $row) {
                    return route('admin.associated.order', ['id' => $row['order']['id']]);
                })
                ->toJson();
        }

        return view('admin.orders');
    }

    public function showModal(int $orderId): View
    {
        $statuses = Status::all()->all();

        return view('admin.modal', compact('statuses'))
            ->with(['id' => $orderId]);
    }

    public function showOrder(int $orderId): View
    {
        $order = Order::find($orderId);
        $products = $order?->products()->get()->all();

        return view('admin.associated.order', compact('order', 'products'));
    }

    public function complete(CompleteOrderRequest $request): JsonResponse
    {
        $order = Order::find($request->getIdInput());

        if ($order?->updateStatus(Status::COMPLETED)) {
            return $this->ok();
        }

        return $this->internalServerError();
    }

    public function update(UpdateOrderRequest $request): JsonResponse
    {
        $order = Order::find($request->getOrderIdInput());
        $status = Status::find($request->getStatusIdInput());

        $result = true;

        $result &= $order?->updateStatus($status) ?? false;

        if ($description = $request->getDescriptionInput()) {
            $result &= $order?->updateDescription($description) ?? false;
        }

        return $result
            ? $this->ok()
            : $this->internalServerError();
    }
}
