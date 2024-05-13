<?php

namespace App\Http\Controllers;

use App\Services\OrderService;
use App\Services\ProfileService;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Contracts\Foundation\Application as App;
use function request;

class AdminOrderController extends Controller
{
    private OrderService $orderService;
    private ProfileService $profileService;

    /**
     * @param OrderService $orderService
     * @param ProfileService $profileService
     */
    public function __construct(
        OrderService $orderService,
        ProfileService $profileService
    )
    {
        $this->orderService = $orderService;
        $this->profileService = $profileService;
    }

    /**
     * @throws Exception
     */
    public function showOrders(): View|Application|Factory|JsonResponse|App
    {
        if (request()->ajax()) {
            return DataTables::of(
                $this->orderService->createLatestOrderViewsAndSerialize()
            )
                ->addIndexColumn()
                ->addColumn('action', function(array $row) {
                    return '<button type="button"
                                    data-id="'.$row['order']['id'].'"
                                    class="finalize btn btn-success btn-sm"
                                    style="width: 120px">Завершить
                           </button>
                            <button type="button"
                                    data-id="'.$row['order']['id'].'"
                                    class="delete btn btn-danger btn-sm"
                                    style="width: 120px">Удалить
                           </button>';
                })
                ->rawColumns(['action'])
                ->addColumn('profile-link', function(array $row) {
                    return route('admin.associated.profile', ['id' => $row['order']['profile_id']]);
                })
                ->addColumn('details-link', function(array $row) {
                    return route('admin.associated.order', ['id' => $row['order']['id']]);
                })
                ->toJson();
        }

        return view('admin.orders');
    }

    public function showProfile(int $profileId): View|Application|Factory|App
    {
        $profileView = $this->profileService->createProfileViewById($profileId);
        return view('admin.associated.profile', compact('profileView'));
    }

    public function showOrder(int $orderId): View|Application|Factory|App
    {
        $orderView = $this->orderService->createOrderViewById($orderId);
        return view('admin.associated.order', compact('orderView'));
    }

    public function complete(): JsonResponse
    {
        // TODO
        return response()->json()->setData(['success' => 'Success']);
    }

    public function delete(): JsonResponse
    {
        // TODO
        return response()->json()->setData(['success' => 'Success']);
    }
}
