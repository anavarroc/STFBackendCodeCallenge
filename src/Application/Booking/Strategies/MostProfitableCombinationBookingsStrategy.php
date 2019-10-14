<?php

namespace StayForLong\Application\Booking\Strategies;

use StayForLong\Domain\Booking\BookingCollection;

class MostProfitableCombinationBookingsStrategy implements BookingsStrategyInterface
{
    private $totalProfitStrategy;

    public function __construct(TotalProfitProfitStrategy $totalProfitStrategy)
    {
        $this->totalProfitStrategy = $totalProfitStrategy;
    }

    public function execute(BookingCollection $bookings): BookingCollection
    {
        $combinations = $this->getBookingCombinations($bookings);

        return $this->getMostProfitableCombination($combinations);
    }

    private function getBookingCombinations(BookingCollection $bookings): array
    {
        $combinations = array(new BookingCollection());
        foreach ($bookings as $booking) {
            $combinations = $this->addBookingToCombinations($booking, $combinations);
        }

        return $combinations;
    }

    private function addBookingToCombinations($booking, $combinations): array
    {
        $newCombinations = array();
        foreach ($combinations as $combination) {
            if (!$combination->overlapBy($booking)) {
                $newCombination = clone $combination;
                $newCombination->add($booking);
                $newCombinations[] = $newCombination;
            }
        }

        return array_merge($newCombinations, $combinations);
    }

    private function getMostProfitableCombination(array $combinations): BookingCollection
    {
        $mostProfitableCombination = null;
        $mostProfitableAmount = null;
        foreach ($combinations as $combination) {
            $actualCombinationProfit = $this->totalProfitStrategy->execute($combination);
            if (
                null === $mostProfitableCombination ||
                $mostProfitableAmount < $actualCombinationProfit
            ) {
                $mostProfitableCombination = $combination;
                $mostProfitableAmount = $actualCombinationProfit;
            }
        }

        return $mostProfitableCombination;
    }
}
