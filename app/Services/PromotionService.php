<?php

namespace App\Services;

use App\Exceptions\ServiceException;
use App\Models\Flow;
use App\Models\Promotion;
use App\Models\PromotionFlow;
use App\Models\PromotionType;
use App\Services\Core\UsesTransaction;
use Illuminate\Support\Facades\Log;
use Throwable;

class PromotionService
{
    use UsesTransaction;

    /**
     * @param array $promotionAttributes
     * @param int $promotionTypeId
     * @param int[] $flowIds
     * @return bool
     * @throws Throwable
     */
    public function create(
        array $promotionAttributes,
        int   $promotionTypeId,
        array $flowIds,
    ): bool
    {
        $this->beginTransaction();

        try {
            $promotion = new Promotion();
            $promotion
                ->promotionType()
                ->associate(PromotionType::find($promotionTypeId));

            $promotion->fill($promotionAttributes);
            $promotion->save();

            foreach ($flowIds as $flowId) {
                $promotionFlow = new PromotionFlow();

                $promotionFlow->promotion()->associate($promotion);
                $promotionFlow->flow()->associate(Flow::find($flowId));

                $promotionFlow->save();
            }

        } catch (Throwable $t) {
            $this->rollbackTransaction();
            throw $t;
        }

        $this->commitTransaction();
        return true;
    }

    /**
     * @param int $promotionId
     * @param int $promotionTypeId
     * @param array $promotionAttributes
     * @param int[] $flowIds
     * @return bool
     * @throws Throwable
     */

    public function update(
        int   $promotionId,
        int   $promotionTypeId,
        array $promotionAttributes,
        array $flowIds
    ): bool
    {
        $this->beginTransaction();

        try {
            $promotion = Promotion::find($promotionId);

            if ($promotion === null) {
                throw new ServiceException();
            }

            $promotion
                ->promotionType()
                ->associate(PromotionType::find($promotionTypeId));

            $promotion->update($promotionAttributes);

            PromotionFlow::query()
                ->where('promotion_id', '=', $promotionId)
                ?->delete();

            foreach ($flowIds as $flowId) {
                $promotionFlow = new PromotionFlow();

                $promotionFlow->promotion()->associate($promotion);
                $promotionFlow->flow()->associate(Flow::find($flowId));

                $promotionFlow->save();
            }

        } catch (Throwable $t) {
            $this->rollbackTransaction();
            Log::error($t);
            throw $t;
        }

        $this->commitTransaction();
        return true;
    }
}
