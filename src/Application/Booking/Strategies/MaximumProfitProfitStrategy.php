<?php

namespace StayForLong\Application\Booking\Strategies;

use StayForLong\Domain\Booking\BookingCollection;

class MaximumProfitProfitStrategy implements BookingProfitStrategyInterface
{
    const NAME = 'max_night';

    public function execute(BookingCollection $bookings): float
    {
        $maximumProfit = null;
        foreach ($bookings as $booking) {
            $profit = $booking->getProfitPerNight();
            if (null === $maximumProfit || $maximumProfit < $profit) {
                $maximumProfit = $profit;
            }
        }

        return round($maximumProfit, 2);
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
