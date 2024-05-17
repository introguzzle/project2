<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Profile;
use App\Services\OrderService;
use App\Services\ProfileService;
use App\Services\TelegramService;
use App\Utils\Auth;
use Exception;
use Illuminate\Contracts\Foundation\Application as App;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Facades\DataTables;
use function request;

class AdminController extends Controller
{
    private OrderService $orderService;
    private ProfileService $profileService;
    private TelegramService $telegramService;

    /**
     * @param OrderService $orderService
     * @param ProfileService $profileService
     * @param TelegramService $telegramService
     */
    public function __construct(
        OrderService $orderService,
        ProfileService $profileService,
        TelegramService $telegramService
    )
    {
        $this->orderService = $orderService;
        $this->profileService = $profileService;
        $this->telegramService = $telegramService;
    }

    /**
     * @throws Exception
     */
    public function showOrders(): View|Application|Factory|JsonResponse|App
    {
        if (request()->ajax()) {
            return DataTables::of(
                $this->orderService->acquireLatestAndSerialize()
            )
                ->addIndexColumn()
                ->addColumn('action', function(array $row) {
                    return '<button type="button"
                            data-id="'.$row['order']['id'].'"
                            class="complete btn btn-success btn-sm"
                            style="width: 120px">Завершить
                   </button>
                    <button type="button"
                            onclick="openDialog(\''. route('admin.orders.modal', ['id' => $row['order']['id']]) .'\')"
                            data-id="'.$row['order']['id'].'"
                            class="btn btn-outline-info btn-sm"
                            style="width: 120px">Действия
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

    public function showModal(int $id): View|Application|Factory|App
    {
        return view('admin.modal')->with(['id' => $id]);
    }

    public function showAdmin(): View|Application|Factory|App
    {
        $profile = Auth::getProfile();
        return view('admin.admin', compact('profile'));
    }

    public function getToken(): string
    {
        $token = $this->telegramService->generateToken(Auth::getProfile());
        return $token->getAttribute('token');
    }

    public function showProfile(int $profileId): View|Application|Factory|App
    {
        $profile = Profile::find($profileId);
        return view('admin.associated.profile', compact('profile'));
    }

    public function showOrder(int $orderId): View|Application|Factory|App
    {
        $order = Order::find($orderId);
        $products = $order->products()->get()->all();

        return view('admin.associated.order', compact('order', 'products'));
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
