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
     * @param array  $keys_only
     *
     * @return bool|int
     */
    public static function byIp($ip, $keys_only = ['country', 'state', 'city'])
    {
        if ($ip == '::1' or $ip == '127.0.0.1') {
            return 0;
        }

        $ip_aton = self::ipAton($ip);

        $result = Range::where('ip_start', '<=', $ip_aton)
            ->where('ip_end', '>=', $ip_aton)
            ->orderBy('ip_start', 'DESC')
            ->limit(1)
            ->get()
            ->first();

        return (new self)->byGeonameId($result->geoname_id, $keys_only);

        return ! $result ? false : $result;
    }

    /**
     * @param int|string $geonameId
     *
     * @return array|bool
     */
    public function byGeonameId($geonameId, $keys_only)
    {
        $result = self::whereGeonameId($geonameId)->first();
        if ($result) {
            $result = $result->toArray();
            $match_keys = count($keys_only);
            $keys_only = array_flip($keys_only);

            foreach ($result as $key => $value) {
                if ($match_keys && ! isset($keys_only[$key])) {
                    unset($result[$key]);
                }
            }

            return $result;
        }

        return false;
    }
}
