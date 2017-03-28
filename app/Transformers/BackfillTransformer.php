<?php

namespace App\Transformers;

use App\Models\Backfill;

class BackfillTransformer extends Transformer
{
    protected $availableIncludes = [
        'adType',
    ];

    public function transform(Backfill $backfill)
    {
        return [
            'id'            => (int) $backfill->id,
            'website_id'    => $backfill->website_id,
            'ad_type_id'    => $backfill->ad_type_id,
            'advertiser'    => $backfill->advertiser,
            'platform_type' => $backfill->platform_type,
            'embed'         => $backfill->embed,
            'width'         => $backfill->width,
            'ecpm'          => (float) $backfill->ecpm / 100,
            'active'        => (boolean) $backfill->active,
        ];
    }

    /**
     * Include User
     *
     * @param \App\Models\Backfill $backfill
     *
     * @return \League\Fractal\Resource\Item
     */
    public function includeAdType(Backfill $backfill)
    {
        $adType = $backfill->adType;

        return $this->item($adType, new AdTypeTransformer);
    }
}
