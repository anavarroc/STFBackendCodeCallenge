<?php

namespace StayForLong\Application\Booking\Strategies;

use StayForLong\Domain\Booking\BookingCollection;

interface BookingProfitStrategyInterface
{
    public function execute(BookingCollection $bookings): float;

    public function getName(): string;
}
