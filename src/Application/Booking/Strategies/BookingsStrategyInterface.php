<?php

namespace StayForLong\Application\Booking\Strategies;

use StayForLong\Domain\Booking\BookingCollection;

interface BookingsStrategyInterface
{
    public function execute(BookingCollection $bookings): BookingCollection;
}
