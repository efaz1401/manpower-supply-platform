<?php

namespace App\Support;

use Carbon\CarbonInterface;
use IntlDateFormatter;

/**
 * Localised date + currency formatting helpers.
 *
 * Hijri dates are produced with the intl extension's Umm al-Qura calendar
 * (the official calendar of Saudi Arabia) — no external service required.
 */
class DateFormatter
{
    /** Format a date using the Gregorian calendar for the active locale. */
    public static function gregorian(?CarbonInterface $date, string $pattern = 'dd MMM yyyy'): string
    {
        if (! $date) {
            return '—';
        }

        $fmt = new IntlDateFormatter(
            app()->getLocale() === 'ar' ? 'ar_SA' : 'en',
            IntlDateFormatter::NONE,
            IntlDateFormatter::NONE,
            $date->getTimezone()->getName(),
            IntlDateFormatter::GREGORIAN,
            $pattern
        );

        return $fmt->format($date);
    }

    /** Format a date using the Umm al-Qura (Hijri) calendar. */
    public static function hijri(?CarbonInterface $date, string $pattern = 'dd MMMM yyyy'): string
    {
        if (! $date) {
            return '—';
        }

        $locale = (app()->getLocale() === 'ar' ? 'ar_SA' : 'en')
            .'@calendar=islamic-umalqura';

        $fmt = new IntlDateFormatter(
            $locale,
            IntlDateFormatter::NONE,
            IntlDateFormatter::NONE,
            $date->getTimezone()->getName(),
            IntlDateFormatter::TRADITIONAL,
            $pattern
        );

        $suffix = app()->getLocale() === 'ar' ? ' هـ' : ' AH';

        return $fmt->format($date).$suffix;
    }

    /** Both calendars side by side, e.g. "22 Jul 2026 (07 Muharram 1448 AH)". */
    public static function both(?CarbonInterface $date): string
    {
        if (! $date) {
            return '—';
        }

        return self::gregorian($date).' ('.self::hijri($date).')';
    }

    /** Format an amount as SAR currency for the active locale. */
    public static function money(float|int|string|null $amount, string $currency = 'SAR'): string
    {
        $amount = (float) $amount;
        $locale = app()->getLocale() === 'ar' ? 'ar_SA' : 'en';
        $formatted = number_format($amount, 2);

        return app()->getLocale() === 'ar'
            ? $formatted.' '.__('ر.س')
            : $currency.' '.$formatted;
    }
}
