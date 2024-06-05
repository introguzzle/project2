<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Core\Controller;

use App\Http\Requests\Admin\Flow\Receipt\CreateRequest as CreateReceiptRequest;
use App\Http\Requests\Admin\Flow\Payment\CreateRequest as CreatePaymentRequest;

use App\Http\Requests\Admin\Flow\Receipt\DeleteRequest as DeleteReceiptRequest;
use App\Http\Requests\Admin\Flow\Payment\DeleteRequest as DeletePaymentRequest;

use App\Http\Requests\Admin\Flow\UpdateRequest;

use App\Models\Flow;
use App\Models\ReceiptMethod;
use App\Models\PaymentMethod;

use App\Services\FlowService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class FlowController extends Controller
{
    private FlowService $flowService;

    /**
     * @param FlowService $flowService
     */
    public function __construct(FlowService $flowService)
    {
        $this->flowService = $flowService;
    }

    public function show(): View
    {
        $receiptMethods = ReceiptMethod::ordered('id');
        $paymentMethods = PaymentMethod::ordered('id');

        $data = compact('receiptMethods', 'paymentMethods');
        return view('admin.flows.index')->with($data);
    }

    public function delete(): RedirectResponse
    {
        Flow::all()->each(static function (Flow $flow) {
            $flow->delete();
        });

        ReceiptMethod::all()->each(static function (ReceiptMethod $receiptMethod) {
            $receiptMethod->delete();
        });

        PaymentMethod::all()->each(static function (PaymentMethod $paymentMethod) {
            $paymentMethod->delete();
        });

        return redirect()
            ->back()
            ->withInput()
            ->with($this->success('Настройки были успешно сброшены'));
    }

    public function showReceiptMethodCreate(): View
    {
        return view('admin.flows.receipts.create');
    }

    public function showPaymentMethodCreate(): View
    {
        return view('admin.flows.payments.create');
    }

    public function createReceiptMethod(CreateReceiptRequest $request): RedirectResponse
    {
        $receiptMethod = new ReceiptMethod();

        $receiptMethod->name = $request->name;
        $receiptMethod->save();

        return redirect()
            ->back()
            ->withInput()
            ->with($this->success('Способ получения был успешно создан'));
    }

    public function createPaymentMethod(CreatePaymentRequest $request): RedirectResponse
    {
        $paymentMethod = new PaymentMethod();

        $paymentMethod->name = $request->name;
        $paymentMethod->save();

        return redirect()
            ->back()
            ->withInput()
            ->with($this->success('Способ оплаты был успешно создан'));
    }

    public function deleteReceiptMethod(DeleteReceiptRequest $request): RedirectResponse
    {
        ReceiptMethod::find($request->receiptMethodId)?->delete();

        return redirect()
            ->back()
            ->withInput()
            ->with($this->success('Способ получения был успешно удален'));
    }

    public function deletePaymentMethod(DeletePaymentRequest $request): RedirectResponse
    {
        PaymentMethod::find($request->paymentMethodId)?->delete();

        return redirect()
            ->back()
            ->withInput()
            ->with($this->success('Способ оплаты был успешно удален'));
    }

    public function update(UpdateRequest $request): RedirectResponse
    {
        $redirect = redirect()->back();

        $result = $this->flowService->applySettings(
            $request->receiptMethodId,
            $request->paymentMethodIndices
        );

        if (!$result) {
            return $redirect->with($this->internal());
        }

        return $redirect->with($this->success('Настройки были успешно обновлены'));
    }
}
