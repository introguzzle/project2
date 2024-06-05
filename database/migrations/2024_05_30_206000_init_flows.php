<?php

use App\Models\Flow;
use App\Models\PaymentMethod;
use App\Models\ReceiptMethod;
use App\Other\Populate;

return new class extends Populate
{
    public function up(): void
    {
        foreach (ReceiptMethod::all() as $receipt) {
            foreach (PaymentMethod::all() as $paymentMethod) {
                $flow = new Flow();

                $flow->receiptMethod()->associate($receipt);
                $flow->paymentMethod()->associate($paymentMethod);

                $flow->save();
            }
        }
    }
};
