<?php

namespace App\Transformers;

use App\Models\Backfill;

class BackfillTransformer extends Transformer
{
    public function transform(Backfill $backfill)
    {
        return [
            'id'            => (int) $backfill->id,
            'website_id'    => $backfill->website_id,
            'advertiser'    => $backfill->advertiser,
            'ad_type'       => $backfill->ad_type,
            'platform_type' => $backfill->platform_type,
            'embed'         => $backfill->embed,
            'width'         => $backfill->width,
            'ecpm'          => (float) $backfill->ecpm / 100,
            'active'        => (boolean) $backfill->active,
        ];
    }
}
