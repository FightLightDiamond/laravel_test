<?php

namespace App\Transformers;

use App\Models\Order;

/**
 * Class OrderTransformer.
 *
 * @package namespace App\Transformers;
 */
class OrderTransformer
{
    /**
     * Transform the Order entity.
     *
     * @param \App\Models\Order $model
     *
     * @return array
     */
    public function transform(Order $model): array
    {
        return [
            'id'         => (int) $model->id,

            /* place your other model properties here */

            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at
        ];
    }
}
