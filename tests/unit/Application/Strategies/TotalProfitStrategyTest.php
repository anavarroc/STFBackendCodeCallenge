<?php

namespace Tests\unit\Application\Strategies;

use PHPUnit\Framework\TestCase;
use StayForLong\Application\Booking\Strategies\TotalProfitProfitStrategy;
use StayForLong\Domain\Booking\Booking;
use StayForLong\Domain\Booking\BookingCollection;
use Tests\Iterators\JsonDataProviderIterator;

class TotalProfitStrategyTest extends TestCase
{
    /**
     * @dataProvider bookingCollectionData
     */
    public function testTotalProfitPerNight($data)
    {
        $bookings = $this->collectionFromData($data);
        $strategy = new TotalProfitProfitStrategy();
        $this->assertEquals($data['expected_profit'], $strategy->execute($bookings));
    }

    public function bookingCollectionData()
    {
        return new JsonDataProviderIterator(__FILE__, 'BookingCollections');
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
