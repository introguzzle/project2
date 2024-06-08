<?php

namespace App\Services;

use App\Models\Flow;
use App\Services\Core\UsesTransaction;
use Illuminate\Support\Facades\Log;
use Throwable;

class FlowService
{
    use UsesTransaction;

    public function applySettings(
        int   $receiptMethodId,
        array $paymentMethodIndices
    ): bool
    {
        $this->beginTransaction();

        try {
            Flow::whereEquals('receipt_method_id', $receiptMethodId)
                ->delete();

            foreach ($paymentMethodIndices as $paymentMethodIndex) {
                $flow = new Flow();

                $flow->receiptMethodId = $receiptMethodId;
                $flow->paymentMethodId = $paymentMethodIndex;

                $flow->save();
            }
        } catch (Throwable $t) {
            Log::error('Не получилось обновить настройки, потому что транзакция провалилась');
            Log::error($t);
            $this->rollbackTransaction();
            return false;
        }

        $this->commitTransaction();
        return true;
    }
}
