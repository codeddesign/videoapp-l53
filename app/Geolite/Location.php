<?php

namespace App\Geolite;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use Network;

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * Specific table.
     *
     * @var string
     */
    protected $table = 'geolite_locations';

    /**
     * @var array
     */
    protected $fillable = [
        'geoname_id',
        'country',
        'state',
        'city',
    ];

    /**
     * @param string $ip
     * @param array  $keysOnly
     *
     * @return array
     */
    public static function byIp($ip, array $keysOnly = ['country', 'state', 'city'])
    {
        if ($ip == '::1' or $ip == '127.0.0.1') {
            return self::emptyLocation($keysOnly);
        }

        $ipAton = self::ipAton($ip);

        $result = Range::where('ip_start', '<=', $ipAton)
            ->where('ip_end', '>=', $ipAton)
            ->orderBy('ip_start', 'DESC')
            ->limit(1)
            ->get()
            ->first();

        if ($result) {
            return (new self)->byGeonameId($result->geoname_id, $keysOnly);
        } else {
            return self::emptyLocation($keysOnly);
        }
    }

    /**
     * @param int|string $geonameId
     * @param array      $keysOnly
     *
     * @return array
     */
    public function byGeonameId($geonameId, array $keysOnly)
    {
        $result = self::whereGeonameId($geonameId)->first();

        if ($result) {
            return collect($result)->filter(function ($value, $key) use ($keysOnly) {
                return in_array($key, $keysOnly);
            })->toArray();
        }

        return self::emptyLocation($keysOnly);
    }

    protected static function emptyLocation($keys)
    {
        return array_fill_keys($keys, '');
    }
}
