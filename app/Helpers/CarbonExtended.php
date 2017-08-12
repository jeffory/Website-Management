<?php

namespace App\Helpers;

use Carbon\Carbon;

class CarbonExtended extends Carbon
{
    /**
     * @param Carbon $date
     * @return string
     */
    function dateDiffForHumans(Carbon $other = null, $absolute = false, $short = false)
    {
        $isNow = $other === null;

        if ($isNow) {
            $other = static::now($this->getTimezone());
        }

        $diffInterval = $this->diff($other);

        switch (true) {
            case $diffInterval->y > 0:
                $unit = $short ? 'y' : 'year';
                $count = $diffInterval->y;
                break;

            case $diffInterval->m > 0:
                $unit = $short ? 'm' : 'month';
                $count = $diffInterval->m;
                break;

            case $diffInterval->d > 0:
                $unit = $short ? 'd' : 'day';
                $count = $diffInterval->d;
                break;

            default:
                $unit = 'Today';
                $count = 0;
                break;
        }

        if ($count === 0) {
            $count = 1;
        }

        $time = static::translator()->transChoice($unit, $count, array(':count' => $count));

        if ($absolute) {
            return $time;
        }

        $isFuture = $diffInterval->invert === 1;

        if ($unit === 'Today') {
            return $unit;
        } else {
            $transId = $isNow ? ($isFuture ? 'from_now' : 'ago') : ($isFuture ? 'after' : 'before');
        }

        // Some langs have special pluralization for past and future tense.
        $tryKeyExists = $unit.'_'.$transId;
        if ($tryKeyExists !== static::translator()->transChoice($tryKeyExists, $count)) {
            $time = static::translator()->transChoice($tryKeyExists, $count, array(':count' => $count));
        }

        return static::translator()->trans($transId, array(':time' => $time));
    }
}