<?php

namespace StayForLong\Application\Booking\Strategies;

use StayForLong\Domain\Booking\BookingCollection;

class TotalProfitProfitStrategy implements BookingProfitStrategyInterface
{
    const NAME = 'total_profit';

    public function execute(BookingCollection $bookings): float
    {
        $profit = 0;
        foreach ($bookings as $booking) {
            $profit += $booking->getTotalProfit();
        }

        return round($profit, 2);
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
