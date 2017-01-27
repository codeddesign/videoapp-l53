<?php

use App\Models\Tag;
use App\Models\Website;

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

/**
 * array_merge_recursive_numeric function.  Merges N arrays into one array AND sums the values of identical keys.
 * WARNING: If keys have values of different types, the latter values replace the previous ones.
 *
 * Source: https://gist.github.com/Nickology/f700e319cbafab5eaedc
 * @param arrays (all parameters must be arrays)
 * @author Nick Jouannem <nick@nickology.com>
 * @return void
 */
function array_merge_recursive_numeric($arrays)
{
    // If there's only one array, it's already merged

    // Remove any items in $arrays that are NOT arrays
    foreach ($arrays as $key => $array) {
        if (! is_array($array)) {
            unset($arrays[$key]);
        }
    }

    // We start by setting the first array as our final array.
    // We will merge all other arrays with this one.
    $final = array_shift($arrays);

    foreach ($arrays as $b) {
        foreach ($final as $key => $value) {

            // If $key does not exist in $b, then it is unique and can be safely merged
            if (! isset($b[$key])) {
                $final[$key] = $value;
            } else {

                // If $key is present in $b, then we need to merge and sum numeric values in both
                if (is_numeric($value) && is_numeric($b[$key])) {
                    // If both values for these keys are numeric, we sum them
                    $final[$key] = $value + $b[$key];
                } elseif (is_array($value) && is_array($b[$key])) {
                    // If both values are arrays, we recursively call ourself
                    $final[$key] = array_merge_recursive_numeric($value, $b[$key]);
                } else {
                    // If both keys exist but differ in type, then we cannot merge them.
                    // In this scenario, we will $b's value for $key is used
                    $final[$key] = $b[$key];
                }
            }
        }

        // Finally, we need to merge any keys that exist only in $b
        foreach ($b as $key => $value) {
            if (! isset($final[$key])) {
                $final[$key] = $value;
            }
        }
    }

    return $final;
}
