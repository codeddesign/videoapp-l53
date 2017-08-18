<?php

namespace App\Stats;

class Calculator
{
    public static function fillRate($fills, $requests)
    {
        $fillRate = 0;

        if ($requests !== 0) {
            $fillRate = (($fills / $requests) * 100);
        }

        return self::decimals($fillRate);
    }

    public static function errorRate($errors, $requests)
    {
        $errorRate = 0;

        if ($requests !== 0) {
            $errorRate = (($errors / $requests) * 100);
        }

        return self::decimals($errorRate);
    }

    public static function useRate($impressions, $fills)
    {
        $useRate = 0;

        if ($fills !== 0) {
            $useRate = (($impressions / $fills) * 100);
        }

        return self::decimals($useRate);
    }

    public static function revenue($impressions, $tag)
    {
        if (! $tag) {
            return 0;
        }

        return self::decimals(($impressions / 1000) * ($tag->ecpm / 100));
    }

    public static function ecpm($revenue, $impressions)
    {
        if ($impressions === 0) {
            return 0;
        }

        return self::decimals($revenue / ($impressions / 1000));
    }

    public static function percentage($dividend, $divisor)
    {
        if ($divisor === 0) {
            return '0.00';
        }

        return number_format(($dividend / $divisor * 100), 2);
    }

    public static function decimals($number, $decimals = 2)
    {
        return floatval(number_format($number, $decimals, '.', ''));
    }
}
