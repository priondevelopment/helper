<?php

namespace PrionDevelopment\Helper\Test\Unit\Actions\Calendar;

use Carbon\Carbon;
use PrionDevelopment\Helper\Actions\Calendar\NextWeekdayAction;
use PrionDevelopment\Helper\Exceptions\DateIsInPastException;
use PrionDevelopment\Helper\tests\HelperBaseTest;

class DateMathTraitTest extends HelperBaseTest
{
    public function test_will_get_tuesday()
    {
        // Setup
        $today = Carbon::now();
        $nextSunday = $today->next(Carbon::MONDAY);

        /** @var NextWeekdayAction $nextWeekdayAction */
        $nextWeekdayAction = app(NextWeekdayAction::class);


        // Run
        $nextWeekday = $nextWeekdayAction->handle($nextSunday);


        // Assert
        $this->assertEquals("Tuesday", $nextWeekday->format("l"));
    }

    public function test_will_get_monday_from_sunday()
    {
        // Setup
        $today = Carbon::now();
        $nextSunday = $today->next(Carbon::SUNDAY);

        /** @var NextWeekdayAction $nextWeekdayAction */
        $nextWeekdayAction = app(NextWeekdayAction::class);


        // Run
        $nextWeekday = $nextWeekdayAction->handle($nextSunday);


        // Assert
        $this->assertEquals("Monday", $nextWeekday->format("l"));
    }

    public function test_will_get_monday_from_friday()
    {
        $today = Carbon::now();
        $nextSunday = $today->next(Carbon::FRIDAY);

        /** @var NextWeekdayAction $nextWeekdayAction */
        $nextWeekdayAction = app(NextWeekdayAction::class);


        // Run
        $nextWeekday = $nextWeekdayAction->handle($nextSunday);


        // Assert
        $this->assertEquals("Monday", $nextWeekday->format("l"));
    }

    public function test_will_throw_exception_date_in_past()
    {
        $today = Carbon::now();
        $nextSunday = $today->next(Carbon::FRIDAY)->subMonth();

        /** @var NextWeekdayAction $nextWeekdayAction */
        $nextWeekdayAction = app(NextWeekdayAction::class);


        // Run
        $nextWeekdayAction->handle($nextSunday);
    }
}