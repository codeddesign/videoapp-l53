<?php

namespace App\Models;

use App\Models\Traits\SaveMany;
use App\Sessions\Parsers\BackfillSessionParser;
use App\Sessions\Parsers\ImpressionSessionParser;
use App\Sessions\Parsers\VisitSessionParser;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Redis\RedisManager;
use Illuminate\Support\Collection;

/**
 * Database Columns
 *
 * @property int      $id
 * @property int      $campaign_id
 * @property int      $website_id
 * @property string   $platform
 * @property int      $ecpm
 * @property int      $sessions
 * @property Carbon   $created_at
 * @property Carbon   $updated_at
 *
 * Relationships
 *
 * @property Campaign $campaign
 */
class SessionEvent extends Model
{
    use SaveMany;

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function website()
    {
        return $this->belongsTo(Website::class);
    }

    public static function persist()
    {
        $redis = app(RedisManager::class)->connection();

        $data = collect($redis->hgetall('sessions'));

        $sessions = new Collection;

        $parsers = [
            'impression' => new ImpressionSessionParser,
            'visit'      => new VisitSessionParser,
            'backfill'   => new BackfillSessionParser,
        ];

        foreach ($data as $key => $value) {
            $params = explode(':', $key);

            $sessions->push($parsers[$params[0]]->parse($params, $value));
        }

        $collections = [
            'impression' => Tag::whereIn('id', $sessions->pluck('tag_id')->filter()->unique())->get(),
            'backfill'   => Backfill::whereIn('id', $sessions->pluck('backfill_id')->filter()->unique())->get(),
            'visit'      => new EloquentCollection,
        ];

        $impressionsByCampaign = $sessions->map(function ($item) use ($parsers, $collections) {
            $type = $item['type'];

            return $parsers[$type]->addInfo($item, $collections[$type]);
        })->groupBy(function ($item) {
            return $item['website_id'].$item['campaign_id'].$item['platform'];
        })->map(function ($item) {
            return [
                'website_id'    => $item[0]['website_id'],
                'campaign_id'   => $item[0]['campaign_id'],
                'platform_type' => $item[0]['platform'],
                'rpm'           => $item->sum(function ($item) {
                    return $item['ecpm'] * $item['count'];
                }),
                'sessions'      => $item->pluck('session_id')->unique()->count(),
            ];
        });

        $timestamp = Carbon::now()->second(0)->subSecond();

        $redis->del(['sessions']);

        return static::saveMany($impressionsByCampaign, $timestamp);
    }
}
