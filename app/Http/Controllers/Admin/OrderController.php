<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Core\Controller;
use App\Http\Requests\Admin\Order\CompleteRequest;
use App\Http\Requests\Admin\Order\UpdateRequest;
use App\Models\Order;
use App\Models\Status;
use App\Models\User\Profile;
use Closure;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Yajra\DataTables\Facades\DataTables;

class OrderController extends Controller
{
    /**
     * @throws Exception
     */
    public function showActiveOrders(Request $request): View|JsonResponse
    {
        $completed = Status::findByName(Status::COMPLETED);
        $cancelled = Status::findByName(Status::CANCELLED);

        $query = Order::query()
            ->whereNotIn('status_id', [$completed->id, $cancelled->id]);

        if (!$request->ajax()) {
            return view('admin.orders.active');
        }

        if (!$request->has('order')) {
            $query->latest();
        }

        $dataTable = DataTables::eloquent($query);

        $dataTable->editColumn('status_id', static function (Order $order) {
            return $order->status->name;
        });

        $dataTable->editColumn('payment_method_id', static function (Order $order) {
            return $order->paymentMethod->name;
        });

        $dataTable->editColumn('receipt_method_id', static function (Order $order) {
            return $order->receiptMethod->name;
        });

        $dataTable->editColumn('created_at', static function (Order $order) {
            return formatDate($order->createdAt, true);
        });

        $dataTable->editColumn('updated_at', static function (Order $order) {
            return formatDate($order->updatedAt, true);
        });

        $dataTable->addColumn('profile-link', static function (Order $order) {
            return route('admin.orders.profile.index', ['id' => $order->profile->id]);
        });

        $dataTable->addColumn('details-link', static function (Order $order) {
            return route('admin.orders.order.index', ['id' => $order->id]);
        });

        $dataTable->addColumn('action', static function (Order $order) {
            $route = route('admin.orders.update.index', ['id' => $order->id]);

            return '<button type="button" data-id="' . $order->id . '" class="complete btn btn-success btn-sm" style="width: 120px">Завершить</button>
                    <button type="button" onclick="openDialog(\'' . $route . '\')" data-id="' . $order->id . '" class="btn btn-outline-info btn-sm" style="width: 120px">Действия</button>';
        });

        $dataTable->rawColumns(['action', 'profile-link', 'details-link']);
        return $dataTable->toJson();
    }

    /**
     * @throws Exception
     */
    public function showCompletedOrders(Request $request): View|JsonResponse
    {
        $completed = Status::findByName(Status::COMPLETED);
        $cancelled = Status::findByName(Status::CANCELLED);

        $query = Order::query()
            ->where(static function (Builder $query) use ($completed, $cancelled) {
                $query->where('status_id', '=', $completed->id)
                    ->orWhere('status_id', '=', $cancelled->id);
            });

        if (!$request->ajax()) {

            return view('admin.orders.completed');
        }

        if (!$request->has('order')) {
            $query->latest();
        }

        $dataTable = DataTables::eloquent($query);

        $dataTable->editColumn('status_id', static function (Order $order) {
            return $order->status->name;
        });

        $dataTable->editColumn('payment_method_id', static function (Order $order) {
            return $order->paymentMethod->name;
        });

        $dataTable->editColumn('receipt_method_id', static function (Order $order) {
            return $order->receiptMethod->name;
        });

        $dataTable->editColumn('created_at', static function (Order $order) {
            return formatDate($order->createdAt, true);
        });

        $dataTable->editColumn('updated_at', static function (Order $order) {
            return formatDate($order->updatedAt, true);
        });

        $dataTable->addColumn('profile-link', static function (Order $order) {
            return route('admin.orders.profile.index', ['id' => $order->profile->id]);
        });

        $dataTable->addColumn('details-link', static function (Order $order) {
            return route('admin.orders.order.index', ['id' => $order->id]);
        });

        $dataTable->rawColumns(['profile-link', 'details-link']);
        return $dataTable->toJson();
    }

    public function showUpdate(int $orderId): View
    {
        $statuses = Status::all()->all();
        $description = Order::find($orderId)->description;

        $data = compact('orderId', 'statuses', 'description');

        return view('admin.orders.update', $data);
    }

    /**
     * @throws Exception
     */
    public function showProfile(
        Request $request,
        int     $profileId
    ): View|JsonResponse
    {
        $profile = Profile::find($profileId);

        if ($profile === null) {
            $this->abortNotFound();
        }

        if ($request->ajax()) {
            $orders = $profile->orders->map($this->mapper());

            return $this->getOrderDataTables($orders, true, true);
        }

        return view('admin.orders.profile', compact('profile'));
    }

    public function showOrder(int $orderId): View
    {
        $order = Order::find($orderId);

        if ($order === null) {
            $this->abortNotFound();
        }

        $products = $order->products;

        return view('admin.orders.order', compact('order', 'products'));
    }

    public function complete(CompleteRequest $request): JsonResponse
    {
        $order = Order::find($request->id);

        if ($order?->updateStatus(Status::COMPLETED)) {
            return $this->ok();
        }

        return $this->internalServerErrorResponse();
    }

    /**
     * @param Collection $collection
     * @param bool $addButtons
     * @param bool $addLinks
     * @return JsonResponse
     */
    public function getOrderDataTables(
        Collection $collection,
        bool       $addButtons = false,
        bool       $addLinks = false
    ): JsonResponse
    {
        $dataTable = DataTables::collection($collection);
        $dataTable->addIndexColumn();

        if ($addButtons) {
            $dataTable->addColumn('action', static function (array $row) {
                $route = route('admin.orders.update.index', [
                    'id' => $row['order']['id'],
                ]);

                return '<button type="button"
                            data-id="' . $row['order']['id'] . '"
                            class="complete btn btn-success btn-sm"
                            style="width: 120px">Завершить
                   </button>
                    <button type="button"
                            onclick="openDialog(\'' . $route . '\')"
                            data-id="' . $row['order']['id'] . '"
                            class="btn btn-outline-info btn-sm"
                            style="width: 120px">Действия
                   </button>';
            });
        }

        if ($addLinks) {
            $dataTable
                ->rawColumns(['action'])
                ->addColumn('profile-link', static function (array $row) {
                    return route('admin.orders.profile.index', ['id' => $row['order']['profile_id']]);
                })
                ->addColumn('details-link', static function (array $row) {
                    return route('admin.orders.order.index', ['id' => $row['order']['id']]);
                });
        }

        return $dataTable->toJson();
    }

    public function update(UpdateRequest $request): JsonResponse
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
            : $this->internalServerErrorResponse();
    }

    /**
     * @return Closure(Order): array
     */
    public function mapper(): Closure
    {
        return static function (Order $order) {
            return [
                'order'           => $order,
                'profile'         => $order->profile,
                'status'          => $order->status,
                'created_at'      => formatDate($order->createdAt, true),
                'updated_at'      => formatDate($order->updatedAt, true),
                'payment_method'  => $order->paymentMethod,
                'receipt_method'  => $order->receiptMethod,
            ];
        };
    }
}
