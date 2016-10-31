<?php

/**
 *  Echo's the provided css $class if the current route is matched.
 *
 * @param string $route
 * @param string $class
 */
function setActiveNav($route, $class)
{
    if ($route != '/') {
        $route = trim(trim($route), '/');
    }

    if (request()->is($route)) {
        echo $class;
    }
}

/**
 * $data coresponds to a model that gets filtered and
 * it returns only the specified keys.
 *
 * They keys apply to relational models too (in-depth).
 *
 * @param array $data
 * @param array $keys
 *
 * @return array
 */
function filterModelKeys(array $data, array $keys)
{
    $only = array_flip($keys);

    foreach ($data as $key => $value) {
        if (is_array($value)) {
            $data[$key] = filterModelKeys($value, $keys);
            continue;
        }

        if (! isset($only[$key])) {
            unset($data[$key]);
        }
    }

    return $data;
}

/**
 * @return string
 */
function refererUtil()
{
    return isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'n/a';
}

/**
 * @return string
 */
function ipUtil()
{
    return (isset($_SERVER['REMOTE_ADDR'])) ? $_SERVER['REMOTE_ADDR'] : 'n/a';
}

/**
 * Return the ad tags.
 * currently storred in the .env file.
 *
 * @return array
 */
function env_adTags()
{
    $tags = [];
    foreach ($_ENV as $key => $value) {
        if (preg_match('/TAG_(.*?)_(.*?)[_(.*?)]?$/', $key, $matched)) {
            $type = strtolower($matched[1]);
            $mode = strtolower($matched[2]);

            if (preg_match('/(.*?)_(.*?)$/', $mode, $matched)) {
                $mode = $matched[1];
            }

            if (! isset($tags[$type])) {
                $tags[$type] = [];
            }

            if (! isset($tags[$type][$mode])) {
                $tags[$type][$mode] = [];
            }

            $tags[$type][$mode][] = $value;
        }
    }

    return $tags;
}

/**
 * Compute a range between two dates, and generate
 * a plain array of Carbon objects of each day in it.
 *
 * @param  \Carbon\Carbon  $from
 * @param  \Carbon\Carbon  $to
 * @param  bool  $inclusive
 * @return array|null
 */
function date_range(Carbon\Carbon $from, Carbon\Carbon $to, $inclusive = true)
{
    if ($from->gt($to)) {
        return;
    }

    // Clone the date objects to avoid issues, then reset their time
    $from = $from->copy();
    $to = $to->copy();

    // Include the end date in the range
    if ($inclusive) {
        $to->addDay();
    }

    $step = Carbon\CarbonInterval::day();
    $period = new DatePeriod($from, $step, $to);

    // Convert the DatePeriod into a plain array of Carbon objects
    $range = [];

    foreach ($period as $day) {
        $range[] = new Carbon\Carbon($day);
    }

    return ! empty($range) ? $range : null;
}

function sendTestEvents($min = 100, $max = 200, $failPercent = 10, $seconds = 10)
{
    $campaignEvents = new \App\Services\CampaignEvents();
    $time = 0;

    do {
        $repeat = mt_rand($min, $max);

        for ($i = 0;$i < $repeat;$i++) {
            $rate = mt_rand(0, 100);

            $campaignEvents->handle([
                'campaign' => 1,
                'source' => 'app',
                'status' => 200,
                'tag' => null,
            ]);

            if ($rate > $failPercent) {
                //success
                $campaignEvents->handle([
                    'campaign' => 1,
                    'source' => 'tag',
                    'status' => 0,
                    'tag' => 'http://example-tag.com',
                ]);

                $campaignEvents->handle([
                    'campaign' => 1,
                    'source' => 'ad',
                    'status' => 0,
                    'tag' => null,
                ]);
            } else {
                if (mt_rand(0, 100) > 50) {
                    //tag error
                    $campaignEvents->handle([
                        'campaign' => 1,
                        'source' => 'tag',
                        'status' => 400,
                        'tag' => 'http://example-tag.com',
                    ]);
                } else {
                    //ad error
                    $campaignEvents->handle([
                        'campaign' => 1,
                        'source' => 'tag',
                        'status' => 0,
                        'tag' => 'http://example-tag.com',
                    ]);

                    $campaignEvents->handle([
                        'campaign' => 1,
                        'source' => 'ad',
                        'status' => 300,
                        'tag' => null,
                    ]);
                }
            }
        }

        $time++;

        sleep(1);
    } while ($time < $seconds);
}
