<?php

namespace StayForLong\Application\Booking\Strategies;

use StayForLong\Domain\Booking\BookingCollection;

class MinimumProfitProfitStrategy implements BookingProfitStrategyInterface
{
    const NAME = 'min_night';

    public function execute(BookingCollection $bookings): float
    {
        $minimumProfit = null;
        foreach ($bookings as $booking) {
            $profit = $booking->getProfitPerNight();
            if (null === $minimumProfit || $minimumProfit > $profit) {
                $minimumProfit = $profit;
            }
        }

        return round($minimumProfit, 2);
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
