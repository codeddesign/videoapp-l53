<?php

namespace App\Sessions;

use App\Models\Backfill;
use App\Models\Campaign;
use App\Models\Tag;
use App\Models\Website;
use App\Sessions\Parsers\BackfillSessionParser;
use App\Sessions\Parsers\ImpressionSessionParser;
use App\Sessions\Parsers\VisitSessionParser;
use Illuminate\Redis\RedisManager;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

class RedisSessions
{
    /**
     * @var \Illuminate\Redis\Connections\Connection
     */
    protected $redis;

    public function __construct()
    {
        $this->redis = app(RedisManager::class)->connection();
    }

    public function fetch()
    {
        $data = collect($this->redis->hgetall('sessions'));

        $sessions = new SessionsCollection;

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

        $validIds = [
            'campaigns' => Campaign::all()->pluck('id'),
            'websites'  => Website::all()->pluck('id'),
        ];

        $sessions = $sessions
            ->map(function ($item) use ($parsers, $collections) {
                $type = $item['type'];

                return $parsers[$type]->addInfo($item, $collections[$type]);
            })
            ->filter(function ($item) use ($validIds) {
                if ($item === null) {
                    return false;
                }

                if (! $validIds['campaigns']->contains($item['campaign_id']) ||
                    ! $validIds['websites']->contains($item['website_id'])) {
                    return false;
                }

                return true;
            })
            ->groupBy(function ($item) {
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

        return $sessions;
    }

    public function delete()
    {
        $this->redis->del(['sessions']);
    }
}
