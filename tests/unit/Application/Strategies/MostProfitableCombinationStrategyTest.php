<?php

namespace Tests\unit\Application\Strategies;

use PHPUnit\Framework\TestCase;
use StayForLong\Application\Booking\Strategies\MostProfitableCombinationBookingsStrategy;
use StayForLong\Application\Booking\Strategies\TotalProfitProfitStrategy;
use StayForLong\Domain\Booking\Booking;
use StayForLong\Domain\Booking\BookingCollection;
use Tests\Iterators\JsonDataProviderIterator;

class MostProfitableCombinationStrategyTest extends TestCase
{
    private $totalProfitStrategy;

    public function setUp(): void
    {
        parent::setUp();
        $this->totalProfitStrategy = new TotalProfitProfitStrategy();
    }

    /**
     * @dataProvider expectedProfitData
     */
    public function testExpectProfitToBeEqual($data)
    {
        $bookings = $this->collectionFromData($data);
        $strategy = new MostProfitableCombinationBookingsStrategy($this->totalProfitStrategy);
        $mostProfitableCombination = $strategy->execute($bookings);

        $this->assertResults($data, $mostProfitableCombination);
    }

    private function assertResults($data, $bookings): void
    {
        $this->assertEquals(
            $data['expected_profit'],
            $this->totalProfitStrategy->execute($bookings)
        );
    }

    public function expectedProfitData()
    {
        return new JsonDataProviderIterator(__FILE__, 'ExpectedProfit');
    }

    private function collectionFromData($data): BookingCollection
    {
        $bookingCollection = new BookingCollection();
        foreach ($data['bookings'] as $bookingArray) {
            $bookingCollection->add(Booking::fromArray($bookingArray));
        }

        return $bookingCollection;
    }
}
