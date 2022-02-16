<?php

declare(strict_types=1);

namespace App\Helpers;

class Format
{
    public static function numberToString(?float $number, int $decimals = 2): string
    {
        if (is_null($number)) {
            return '';
        }

        return number_format($number, $decimals, ',', '.');
    }

    /**
     * Converts a string with thousands and decimal separators to a number.
     *
     * If there are multiple dots, keep the standard separators.
     * otherwise if there is no comma or the first comma comes before the first dot,
     * reverse the separators.
     */
    public static function stringToNumber(string $string): float
    {
        $decimalSeparator = ',';
        $thousandSeparator = '.';

        $reverseSeparators = self::reverseSeparators($string);

        if ($reverseSeparators) {
            $decimalSeparator = '.';
            $thousandSeparator = ',';
        }

        $number = 0;
        $decimalsSeperated = explode($decimalSeparator, $string);
        $wholePartial = $decimalsSeperated[0];

        $decimalsPartial = 0;
        if (isset($decimalsSeperated[1])) {
            $decimalsPartial = '0.' . $decimalsSeperated[1];
            $decimalsPartial = (float) $decimalsPartial;
        }

        $thousandsSeperated = explode($thousandSeparator, $wholePartial);
        $thousandsPower = count($thousandsSeperated) - 1;

        foreach ($thousandsSeperated as $numberPartial) {
            $number += intval($numberPartial) * pow(1000, $thousandsPower);
            $thousandsPower--;
        }

        return $number + $decimalsPartial;
    }

    public static function numberToEuroString(float $number, int $decimals = 2): string
    {
        return '€ ' . self::numberToString($number, $decimals);
    }

    /** Check whether the comma separator and thousand separator should be reversed. */
    private static function reverseSeparators(string $string): bool
    {
        $multipleDots = (substr_count($string, '.') > 1);
        if ($multipleDots) {
            return false;
        }

        $multipleCommas = (substr_count($string, ',') > 1);
        if ($multipleCommas) {
            return true;
        }

        $commaPosition = strpos($string, ',');
        $dotPosition = strpos($string, '.');
        if ($commaPosition == false || $commaPosition < $dotPosition) {
            return true;
        }

        return false;
    }

    public static function stripTagsAndConvertNewlineToHtml(?string $string): string
    {
        return is_null($string) ? '' : strip_tags(nl2br($string), ['<br>']);
    }
}
