<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\Promotion\CreateRequest;
use App\Http\Requests\Admin\Promotion\DeleteRequest;
use App\Http\Requests\Admin\Promotion\UpdateRequest;
use App\Models\Flow;
use App\Models\Promotion;
use App\Models\PromotionType;
use App\Services\PromotionService;
use App\Http\Controllers\Core\Controller;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

use Throwable;

class PromotionController extends Controller
{
    private PromotionService $promotionService;

    /**
     * @param PromotionService $promotionService
     */
    public function __construct(
        PromotionService $promotionService
    )
    {
        $this->promotionService = $promotionService;
    }


    public function showPromotions(): View
    {
        $promotions = Promotion::ordered('id');
        $promotionTypes = PromotionType::ordered('id');
        $flows = Flow::ordered('id');

        $data = compact('promotions', 'promotionTypes', 'flows');

        return view('admin.promotions.index', $data);
    }

    public function showCreate(): View
    {
        $promotionTypes = PromotionType::ordered('id');
        $flows = Flow::ordered('id');

        $data = compact('promotionTypes', 'flows');
        return view('admin.promotions.create', $data);
    }

    public function create(
        CreateRequest $request
    ): RedirectResponse
    {
        $attributes = $request->getModelAttributes();

        try {
            $this->promotionService->create(
                $attributes,
                $request->promotionTypeId,
                $request->flows
            );
        } catch (Throwable) {
            return $this->back()->with($this->internal());
        }

        return $this->back()->with($this->success('Акция была успешно создана'));
    }

    public function update(
        UpdateRequest $request
    ): RedirectResponse
    {
        $attributes = $request->getModelAttributes();

        try {
            $this->promotionService->update(
                $request->promotionId,
                $request->promotionTypeId,
                $attributes,
                $request->flows
            );
        } catch (Throwable) {
            return $this->back()->with($this->internal());
        }

        return $this->back()->with($this->success('Акция была успешно обновлена'));
    }

    public function delete(
        DeleteRequest $request
    ): RedirectResponse
    {
        Promotion::find($request->promotionId)?->delete();
        return $this->back()->with($this->success('Акция была успешно удалена'));
    }
}
