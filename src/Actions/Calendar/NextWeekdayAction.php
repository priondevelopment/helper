<?php

namespace PrionDevelopment\Helper\Actions\Calendar;

use Carbon\Carbon;
use PrionDevelopment\Helper\Exceptions\DateIsInPastException;

class NextWeekdayAction
{
    /**
     * Pull the Next Weekday
     *
     * @param Carbon|null $start
     *
     * @return Carbon
     * @throws DateIsInPastException
     */
    public function __invoke(null|Carbon $start = null): Carbon
    {
        if (null === $start) {
            $start = Carbon::now();
        }

        $return = $start->copy()->addWeekday();

        if ($return->isPast()) {
            throw new DateIsInPastException("Date is in the past: {$return->timestamp}");
        }

        return $return;
    }
}

