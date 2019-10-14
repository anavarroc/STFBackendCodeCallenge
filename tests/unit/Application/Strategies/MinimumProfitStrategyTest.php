<?php

namespace Tests\unit\Application\Strategies;

use PHPUnit\Framework\TestCase;
use StayForLong\Application\Booking\Strategies\MinimumProfitProfitStrategy;
use StayForLong\Domain\Booking\Booking;
use StayForLong\Domain\Booking\BookingCollection;
use Tests\Iterators\JsonDataProviderIterator;

class MinimumProfitStrategyTest extends TestCase
{
    /**
     * @dataProvider bookingCollectionData
     */
    public function testMaximumProfitPerNight($data)
    {
        $bookings = $this->collectionFromData($data);
        $strategy = new MinimumProfitProfitStrategy();
        $this->assertEquals($data['expected_min'], $strategy->execute($bookings));
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
