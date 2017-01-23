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

    public static function decimals($number, $decimals = 2)
    {
        return floatval(number_format($number, $decimals, '.', ''));
    }
}
