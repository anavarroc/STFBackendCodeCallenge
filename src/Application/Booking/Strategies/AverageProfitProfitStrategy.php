<?php

namespace StayForLong\Application\Booking\Strategies;

use StayForLong\Domain\Booking\BookingCollection;

class AverageProfitProfitStrategy implements BookingProfitStrategyInterface
{
    const NAME = 'avg_night';

    public function execute(BookingCollection $bookings): float
    {
        $totalAverage = 0;
        foreach ($bookings as $booking) {
            $totalAverage += $booking->getProfitPerNight();
        }

        return round($totalAverage / $bookings->count(), 2);
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
